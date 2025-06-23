<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\PagibigContribution;
use Carbon\Carbon;

class PagibigContributionController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware(['permission:pagibigcontribution-list|pagibigcontribution-create|pagibigcontribution-edit|pagibigcontribution-delete'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:pagibigcontribution-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:pagibigcontribution-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:pagibigcontribution-delete'], ['only' => ['destroy']]);
    }
 /**
     * Display a listing of the pagibigcontributions.
     */
    public function index(Request $request)
    {
        $query = PagibigContribution::with('employee');

        // Filter by employee if employee_id is provided
        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $pagibigcontributions = $query->orderBy('date', 'desc')->get();

        // Calculate totals
        $totals = [
            'pagibig' => $pagibigcontributions->sum('pagibig_contribution'),
        ];

        $employees = Employee::all(); // For the employee dropdown

        return view('pagibigcontributions.index', compact('pagibigcontributions', 'totals', 'employees'));
    }
    /**
     * Show the form for creating a new contribution.
     */
    public function create()
    {
        $employees = Employee::where('employee_status', 'Active')->get();
        return view('pagibigcontributions.create',compact('employees'));
    }

    /**
     * Store a newly created contribution in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'pagibig_contribution' => 'nullable|numeric',
        ]);

        $contribution = PagibigContribution::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Pagibig Contribution created successfully.',
            'data' => $contribution
        ], 201);
    }

    /**
     * Display the specified contribution.
     */
    public function show(PagibigContribution $pagibigcontribution)
    {
        return view('pagibigcontributions.show', compact('pagibigcontribution'));
    }

    /**
     * Show the form for editing the specified contribution.
     */
    public function edit($id)
    {
        $pagibigcontribution = PagibigContribution::findOrFail($id);

        // Convert the date to Y-m-d format for the HTML input
        if ($pagibigcontribution->date) {
            $pagibigcontribution->date = Carbon::parse($pagibigcontribution->date)->format('Y-m-d');
        }

        $employees = Employee::where('employee_status', 'Active')->get();
        return view('pagibigcontributions.edit', compact('pagibigcontribution', 'employees'));
    }
    /**
     * Update the specified contribution in storage.
     */
    public function update(Request $request, PagibigContribution $pagibigcontribution)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'pagibig_contribution' => 'nullable|numeric',
        ]);

        $pagibigcontribution->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Pagibig Contribution updated successfully.',
            'data' => $pagibigcontribution
        ]);
    }

    /**
     * Remove the specified contribution from storage.
     */
    public function destroy(PagibigContribution $pagibigcontribution)
    {
        $pagibigcontribution->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pagibig Contribution deleted successfully.'
        ]);
    }
}
