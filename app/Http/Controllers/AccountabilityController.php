<?php

namespace App\Http\Controllers;

use App\Models\Accountability;
use App\Models\ItInventory;
use App\Models\Employee;
use Illuminate\Http\Request;

class AccountabilityController extends Controller
{
    public function index()
    {
        $accountabilities = Accountability::with(['employee', 'itInventories'])->paginate(10);
        return view('accountabilities.index', compact('accountabilities'));
    }

    public function create()
    {
        $employees = Employee::all();
        $itInventories = ItInventory::whereDoesntHave('accountabilities', function ($query) {
            $query->whereNull('returned_at');
        })->get();
        return view('accountabilities.create', compact('employees', 'itInventories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'it_inventories' => 'required|array',
            'it_inventories.*' => 'exists:it_inventories,id',
            'documents' => 'nullable|array|max:10',
            'documents.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string',
        ]);

        $accountability = Accountability::create([
            'employee_id' => $validated['employee_id'],
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($request->hasFile('documents')) {
            $documents = [];
            foreach ($request->file('documents') as $document) {
                $path = $document->store('accountability_documents', 'public');
                $documents[] = $path;
            }
            $accountability->documents = $documents;
            $accountability->save();
        }

        foreach ($validated['it_inventories'] as $inventoryId) {
            $accountability->assignInventory(ItInventory::find($inventoryId));
        }

        return redirect()->route('accountabilities.index')->with('success', 'Accountability created successfully.');
    }

    public function show(Accountability $accountability)
    {
        $accountability->load(['employee', 'itInventories']);
        $documents = $accountability->documents ?? [];
        return view('accountabilities.show', compact('accountability', 'documents'));
    }

    public function edit(Accountability $accountability)
    {
        $employees = Employee::all();
        $itInventories = ItInventory::all();
        return view('accountabilities.edit', compact('accountability', 'employees', 'itInventories'));
    }

    public function update(Request $request, Accountability $accountability)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'it_inventories' => 'required|array',
            'it_inventories.*' => 'exists:it_inventories,id',
            'documents' => 'nullable|array|max:10',
            'documents.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string',
        ]);

        $accountability->update([
            'employee_id' => $validated['employee_id'],
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($request->hasFile('documents')) {
            $documents = $accountability->documents ?? [];
            foreach ($request->file('documents') as $document) {
                $path = $document->store('accountability_documents', 'public');
                $documents[] = $path;
            }
            $accountability->documents = array_slice($documents, 0, 10);
            $accountability->save();
        }

        $currentInventories = $accountability->itInventories->pluck('id')->toArray();
        $newInventories = $validated['it_inventories'];

        $toAttach = array_diff($newInventories, $currentInventories);
        $toDetach = array_diff($currentInventories, $newInventories);

        foreach ($toAttach as $inventoryId) {
            $accountability->assignInventory(ItInventory::find($inventoryId));
        }

        foreach ($toDetach as $inventoryId) {
            $accountability->returnInventory(ItInventory::find($inventoryId));
        }

        return redirect()->route('accountabilities.show', $accountability)->with('success', 'Accountability updated successfully.');
    }

    public function destroy(Accountability $accountability)
    {
        $accountability->delete();
        return redirect()->route('accountabilities.index')->with('success', 'Accountability deleted successfully.');
    }

    public function transfer(Accountability $accountability)
    {
        $employees = Employee::where('id', '!=', $accountability->employee_id)->get();
        return view('accountabilities.transfer', compact('accountability', 'employees'));
    }

    public function processTransfer(Request $request, Accountability $accountability)
    {
        $validated = $request->validate([
            'new_employee_id' => 'required|exists:employees,id',
            'transfer_notes' => 'nullable|string',
        ]);

        // Create new accountability for the new employee
        $newAccountability = Accountability::create([
            'employee_id' => $validated['new_employee_id'],
            'notes' => "Transferred from {$accountability->employee->full_name}. " . ($validated['transfer_notes'] ?? ''),
        ]);

        // Transfer all IT inventories to the new accountability
        foreach ($accountability->itInventories as $inventory) {
            $accountability->returnInventory($inventory);
            $newAccountability->assignInventory($inventory);
        }

        // Copy documents if they exist
        if ($accountability->documents) {
            $newAccountability->documents = $accountability->documents;
            $newAccountability->save();
        }

        // Check if the old accountability has any remaining inventories
        if ($accountability->itInventories()->count() === 0) {
            // If no inventories remain, delete the old accountability
            $accountability->delete();
            $message = 'Accountability successfully transferred and old record deleted.';
        } else {
            // If some inventories remain, just mark as inactive
            $accountability->update([
                'status' => 'inactive',
                'notes' => ($accountability->notes ? $accountability->notes . "\n" : '') . 
                          "Transferred to {$newAccountability->employee->full_name} on " . now()->format('Y-m-d H:i:s'),
            ]);
            $message = 'Accountability successfully transferred to new employee.';
        }

        return redirect()
            ->route('accountabilities.show', $newAccountability)
            ->with('success', $message);
    }
}

