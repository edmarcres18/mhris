<?php

namespace App\Http\Controllers;

use App\Models\PagibigLoan;
use App\Models\Employee;
use App\Enums\LoanType;
use App\Models\Loan;
use Illuminate\Http\Request;
use App\Models\PagibigLoanPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

class PagibigLoanController extends Controller
{
    public function __construct()
    {
        $loanTypes = LoanType::cases();
        View::share('loanTypes', $loanTypes);
    }

    public function index()
    {
        $employees = Employee::where('employee_status', 'active')
                             ->whereNotNull('pagibig_no')
                             ->get();
        $loans = PagibigLoan::all();
        return view('loan_pagibig.index', compact('loans', 'employees'));
    }

    public function create()
    {
        $employees = Employee::all();
        $loanTypes = LoanType::cases();

        return view('loan_pagibig.create', compact('employees', 'loanTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'loan_type' => 'required|in:' . implode(',', array_column(LoanType::cases(), 'value')),
            'loan_amount' => 'required|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'loan_term_months' => 'required|integer|min:1',
            'total_accumulated_value' => 'required_if:loan_type,calamity|numeric|min:0',
        ]);

        // Check if the employee already has an active loan
        $existingActiveLoan = PagibigLoan::where('employee_id', $validated['employee_id'])
            ->where('status', 'active')
            ->exists();

        if ($existingActiveLoan) {
            return redirect()->route('loan_pagibig.index')
                ->with('error', 'This employee already has an active PAGIBIG loan.')
                ->withInput();
        }

        $loan = new PagibigLoan($validated);
        $loan->loan_type = LoanType::from($validated['loan_type']);
        $loan->monthly_amortization = $loan->calculateMonthlyAmortization();
        $loan->status = 'active'; // Ensure the new loan is set to active
        $loan->save();

        return redirect()->route('loan_pagibig.index')->with('success', 'PAGIBIG loan created successfully.');
    }

    public function show(PagibigLoan $pagibigLoan)
    {
        return view('loan_pagibig.show', compact('pagibigLoan'));
    }

    public function edit($id)
    {
        $loan = PagibigLoan::findOrFail($id);
        return view('loan_pagibig.update_status', compact('loan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,complete',
        ]);

        $pagibigLoan = PagibigLoan::findOrFail($id);
        $pagibigLoan->status = $validated['status'];
        $pagibigLoan->save();

        return redirect()->route('loan_pagibig.index')->with('success', 'PAGIBIG loan status updated successfully.');
    }

    public function destroy(PagibigLoan $loanPagibig)
    {
        try {
            $loanPagibig->delete();
            return redirect()->route('loan_pagibig.index')->with('success', 'Pag-IBIG loan has been successfully deleted.');
        } catch (\Exception $e) {
            return redirect()->route('loan_pagibig.index')->with('error', 'An error occurred while deleting the Pag-IBIG loan: ' . $e->getMessage());
        }
    }

    public function showLedger($id)
    {
        $loan = PagibigLoan::with(['employee', 'payments'])->findOrFail($id);

        // Calculate total paid amount
        $totalPaid = $loan->payments->sum('amount');

        // Calculate remaining balance
        $remainingBalance = max(0, $loan->loan_amount - $totalPaid);

        return view('loan_pagibig.ledger', compact('loan', 'totalPaid', 'remainingBalance'));
    }

    public function generatePayments()
    {
        try {
            $loans = PagibigLoan::all();
            $paymentsGenerated = 0;
            $currentMonth = Carbon::now()->startOfMonth();

            foreach ($loans as $loan) {
                // Check if a payment for the current month already exists
                $existingPayment = PagibigLoanPayment::where('pagibig_loan_id', $loan->id)
                    ->whereYear('payment_date', $currentMonth->year)
                    ->whereMonth('payment_date', $currentMonth->month)
                    ->exists();

                if (!$existingPayment) {
                    $payment = PagibigLoanPayment::create([
                        'pagibig_loan_id' => $loan->id,
                        'amount' => $loan->monthly_amortization,
                        'payment_date' => Carbon::now(),
                        'notes' => 'Auto-generated payment',
                    ]);
                    $paymentsGenerated++;

                    // Calculate half of the payment amount
                    $halfAmount = $payment->amount / 2;

                    // Get the payment month and year
                    $paymentDate = Carbon::parse($payment->payment_date);
                    $paymentYear = $paymentDate->year;
                    $paymentMonth = $paymentDate->month;

                    // Create two Loan entries for the 10th and 25th of the payment month
                    foreach ([10, 25] as $day) {
                        Loan::create([
                            'employee_id' => $loan->employee_id,
                            'pagibig_loan' => $halfAmount,
                            'date' => Carbon::create($paymentYear, $paymentMonth, $day),
                        ]);
                    }
                }
            }

            return redirect()->route('loan_pagibig.index')->with('success', "Successfully generated {$paymentsGenerated} payments.");
        } catch (\Exception $e) {
            return redirect()->route('loan_pagibig.index')->with('error', 'Error generating payments: ' . $e->getMessage());
        }
    }
}
