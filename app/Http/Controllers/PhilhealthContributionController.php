<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhilhealthContribution;
use App\Models\Employee;
use Carbon\Carbon;

class PhilhealthContributionController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware(['permission:philhealthcontribution-list|philhealthcontribution-create|philhealthcontribution-edit|philhealthcontribution-delete'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:philhealthcontribution-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:philhealthcontribution-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:philhealthcontribution-delete'], ['only' => ['destroy']]);
    }
 /**
     * Display a listing of the philhealthcontributions.
     */
    public function index(Request $request)
    {
        $query = PhilhealthContribution::with('employee');

        // Filter by employee if employee_id is provided
        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $philhealthcontributions = $query->orderBy('date', 'desc')->get();

        // Calculate totals
        $totals = [
            'philhealth' => $philhealthcontributions->sum('philhealth_contribution'),
        ];

        $employees = Employee::all(); // For the employee dropdown

        return view('philhealthcontributions.index', compact('philhealthcontributions', 'totals', 'employees'));
    }
    /**
     * Show the form for creating a new contribution.
     */
    public function create()
    {
        $employees = Employee::where('employee_status', 'Active')->get();
        return view('philhealthcontributions.create',compact('employees'));
    }

    /**
     * Store a newly created contribution in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'philhealth_contribution' => 'nullable|numeric',
        ]);

        $contribution = PhilhealthContribution::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Philhealth Contribution created successfully.',
            'data' => $contribution
        ], 201);
    }

    /**
     * Display the specified contribution.
     */
    public function show(PhilhealthContribution $philhealthcontribution)
    {
        return view('philhealthcontributions.show', compact('philhealthcontribution'));
    }

    /**
     * Show the form for editing the specified contribution.
     */
    public function edit($id)
    {
        $philhealthcontribution = PhilhealthContribution::findOrFail($id);

        // Convert the date to Y-m-d format for the HTML input
        if ($philhealthcontribution->date) {
            $philhealthcontribution->date = Carbon::parse($philhealthcontribution->date)->format('Y-m-d');
        }

        $employees = Employee::where('employee_status', 'Active')->get();
        return view('philhealthcontributions.edit', compact('philhealthcontribution', 'employees'));
    }
    /**
     * Update the specified contribution in storage.
     */
    public function update(Request $request, PhilhealthContribution $philhealthcontribution)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'philhealth_contribution' => 'nullable|numeric',
        ]);

        $philhealthcontribution->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Philhealth Contribution updated successfully.',
            'data' => $philhealthcontribution
        ]);
    }

    /**
     * Remove the specified contribution from storage.
     */
    public function destroy(PhilhealthContribution $philhealthcontribution)
    {
        $philhealthcontribution->delete();

        return response()->json([
            'success' => true,
            'message' => 'Philhealth Contribution deleted successfully.'
        ]);
    }
}
