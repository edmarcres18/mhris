<?php

namespace App\Http\Controllers;

use App\Models\CashAdvance;
use App\Models\Loan;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\SssLoan;
use App\Models\PagibigLoan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoanController extends Controller
{

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware(['permission:loan-list|loan-create|loan-edit|loan-delete'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:loan-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:loan-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:loan-delete'], ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the loans.
     */
    public function index()
    {
        $loans = loan::all();
        return view('loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new loan.
     */
    public function create()
    {
        $employees = Employee::where('employee_status', 'Active')->get();
        return view('loans.create',compact('employees'));
    }

    /**
     * Store a newly created loan in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'sss_loan' => 'nullable|numeric',
            'pagibig_loan' => 'nullable|numeric',
            'cash_advance' => 'nullable|numeric',
        ]);

        loan::create($validatedData);

        return redirect()->route('loans.index')->with('success', 'loan created successfully.');
    }

    /**
     * Display the specified loan.
     */
    public function show(loan $loan)
    {
        return view('loans.show', compact('loan'));
    }

    /**
     * Show the form for editing the specified loan.
     */
    public function edit($id)
    {
        $loan = loan::findOrFail($id);

        // Convert the date to Y-m-d format for the HTML input
        if ($loan->date) {
            $loan->date = Carbon::parse($loan->date)->format('Y-m-d');
        }

        $employees = Employee::where('employee_status', 'Active')->get();
        return view('loans.edit', compact('loan', 'employees'));
    }
    /**
     * Update the specified loan in storage.
     */
    public function update(Request $request, loan $loan)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'sss_loan' => 'nullable|numeric',
            'philhealth_loan' => 'nullable|numeric',
            'pagibig_loan' => 'nullable|numeric',
            'cash_advance' => 'nullable|numeric',
        ]);

        $loan->update($validatedData);

        return redirect()->route('loans.index')->with('success', 'loan updated successfully.');
    }

    /**
     * Remove the specified loan from storage.
     */
    public function destroy(loan $loan)
    {
        $loan->delete();

        return redirect()->route('loans.index')->with('success', 'loan deleted successfully.');
    }

    /**
     * Display a listing of all employees with their time sheet data.
     */
    public function allEmployeesLoan()
    {
        // Get authenticated user
        $user = Auth::user();
        
        // Retrieve employees based on user role
        $employees = $user->hasRole('Super Admin')
            ? Employee::all()
            : Employee::where('employee_status', 'Active')->get();

        return view('loans.employees-list', compact('employees'));
    }

    /**
     * Display loans for the authenticated employee.
     */
    public function myLoans()
    {
        $user = Auth::user();

        // Ensure the user is authenticated and has the role 'Employee'
        if ($user && ($user->hasRole('Employee') || $user->hasRole('Supervisor'))) {
            $employee = Employee::where('email_address', $user->email)->firstOrFail();

            $loans = Loan::where('employee_id', $employee->id)
                ->orderBy('date', 'desc')
                ->get();

            // Calculate totals
            $totals = [
                'sss_loan' => $loans->sum('sss_loan'),
                'pagibig_loan' => $loans->sum('pagibig_loan'),
                'cash_advance' => $loans->sum('cash_advance'),
            ];

            return view('loans.my-loans', compact('loans', 'totals', 'employee'));
        }

        return redirect()->route('loans.index')->with('error', 'Unauthorized access.');
    }

    /**
     * Display loans for a specific employee.
     */
    public function employeeLoans($employee_id)
    {
        $employee = Employee::findOrFail($employee_id);

        $loans = Loan::where('employee_id', $employee_id)
            ->orderBy('date', 'desc')
            ->get();

        // Group loans by date and calculate totals
        $loanTotals = $loans->groupBy(function ($loan) {
            return Carbon::parse($loan->date)->format('Y-m-d');
        })->map(function ($groupedLoans) {
            return [
                'sss' => $groupedLoans->sum('sss_loan'),
                'pagibig' => $groupedLoans->sum('pagibig_loan'),
                'cash_advance' => $groupedLoans->sum('cash_advance'),
            ];
        });

        // Calculate overall totals
        $totals = [
            'sss' => $loans->sum('sss_loan'),
            'pagibig' => $loans->sum('pagibig_loan'),
            'cash_advance' => $loans->sum('cash_advance'),
        ];

        return view('loans.employee-loans', compact('employee', 'loanTotals', 'totals'));
    }
}
