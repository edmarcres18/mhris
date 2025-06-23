<?php

namespace App\Http\Controllers;

use App\Models\CashAdvance;
use App\Models\CashAdvancePayment;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Loan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
class CashAdvanceController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        $cashAdvances = CashAdvance::with('employee', 'payments')->get();
        return view('cash_advances.index', compact('cashAdvances', 'employees'));
    }

    public function create()
    {
        $oneYearAgo = now()->subYear();

        // Check if the authenticated user has the role of 'employee'
        if (auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Supervisor')) {
            // Get the employee with the same email address as the authenticated user
            $employees = Employee::where('email_address', auth()->user()->email)
                ->where('date_hired', '<=', $oneYearAgo)
                ->get();
        }
        // Check if user is Super Admin - they can create for anyone without date restriction
        elseif (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Admin')) {
            $employees = Employee::all();
        }
        else {
            // For other roles, get all employees who are eligible
            $employees = Employee::where('date_hired', '<=', $oneYearAgo)->get();
        }

        return view('cash_advances.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'cash_advance_amount' => 'required|numeric|min:0',
            'repayment_term' => 'required|integer|min:1|max:24',
            'signature' => 'required|string',
            'reference_number' => 'nullable|string|unique:cash_advances,reference_number'
        ]);

        try {
            $employee = Employee::findOrFail($request->input('employee_id'));

            // Check if employee has an active cash advance
            $hasActiveCashAdvance = CashAdvance::where('employee_id', $employee->id)
                ->whereIn('status', ['pending', 'active'])
                ->exists();

            if ($hasActiveCashAdvance) {
                return redirect()->route('cash_advances.create')
                    ->with('error', 'Employee already has an active or pending cash advance.')
                    ->withInput();
            }

            if (!$this->isEmployeeEligibleForCashAdvance($employee)) {
                return redirect()->route('cash_advances.create')
                    ->with('error', 'Employee must be hired for at least one year to be eligible for a cash advance.')
                    ->withInput();
            }

            // Calculate monthly amortization and total repayment
            $cashAdvanceAmount = $request->input('cash_advance_amount');
            $repaymentTerm = $request->input('repayment_term');
            $monthlyAmortization = $cashAdvanceAmount / $repaymentTerm;
            $totalRepayment = $cashAdvanceAmount;

            // Process and store signature
            $signatureData = $request->input('signature');
            $signatureFileName = null;

            if ($signatureData) {
                // Remove data:image/png;base64, from the beginning of the string
                $signatureImage = substr($signatureData, strpos($signatureData, ',') + 1);

                // Generate unique filename
                $signatureFileName = 'signatures/cash-advance-' . uniqid() . '-' . time() . '.png';

                // Ensure the signatures directory exists
                Storage::disk('public')->makeDirectory('signatures');

                // Store the signature
                Storage::disk('public')->put($signatureFileName, base64_decode($signatureImage));
            }

            // Generate reference number
            $referenceNumber = $this->generateUniqueReferenceNumber();

            // Create cash advance record
            $cashAdvance = CashAdvance::create([
                'reference_number' => $referenceNumber,
                'employee_id' => $request->input('employee_id'),
                'cash_advance_amount' => $cashAdvanceAmount,
                'repayment_term' => $repaymentTerm,
                'monthly_amortization' => $monthlyAmortization,
                'total_repayment' => $totalRepayment,
                'status' => 'pending',
                'signature' => $signatureFileName
            ]);

            if (auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Supervisor')) {
                return redirect()->route('cash_advances.create')
                    ->with('success', 'Cash advance application submitted successfully.');
            } else {
                return redirect()->route('cash_advances.index')
                    ->with('success', 'Cash advance application submitted successfully.');
            }

        } catch (\Exception $e) {
            // Log the error
            Log::error('Cash Advance Creation Error: ' . $e->getMessage());

            return redirect()->route('cash_advances.create')
                ->with('error', 'An error occurred while processing your request. Please try again.')
                ->withInput();
        }
    }

    public function show($id)
    {
        $cashAdvance = CashAdvance::findOrFail($id);
        if (!Auth::user()->hasRole('Employee') || !Auth::user()->hasRole('Supervisor')) {
            $this->markAsRead($cashAdvance);
        } else {
            $this->markAsViewed($cashAdvance);
        }
        return view('cash_advances.show', compact('cashAdvance'));
    }

    private function markAsRead(CashAdvance $cashAdvance)
    {
        if (!$cashAdvance->is_read) {
            $cashAdvance->is_read = true;
            $cashAdvance->save();
        }
    }
    private function markAsViewed(CashAdvance $cashAdvance)
    {
        if (!$cashAdvance->is_view) {
            $cashAdvance->is_view = true;
            $cashAdvance->save();
        }
    }

    public function edit(CashAdvance $cashAdvance)
    {
        return view('cash_advances.edit', compact('cashAdvance'));
    }

    public function update(Request $request, CashAdvance $cashAdvance)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:active,declined,complete',
            'reason' => 'nullable|string|max:255',
        ]);

        try {
            $user = Auth::user();
            
            if ($validatedData['status'] === 'active') {
                // Approve the cash advance
                $cashAdvance->update([
                    'status' => 'active',
                    'approved_by' => $user->id,
                    'rejected_by' => null, // Clear any previous rejection
                    'is_read' => false,    // Reset read status for notifications
                    'is_view' => false     // Reset view status for notifications
                ]);

                $message = 'Cash Advance has been approved successfully.';
            } 
            elseif ($validatedData['status'] === 'declined') {
                // Reject the cash advance
                $cashAdvance->update([
                    'status' => 'declined',
                    'rejected_by' => $user->id,
                    'approved_by' => null, // Clear any previous approval
                    'is_read' => false,    // Reset read status for notifications
                    'is_view' => false     // Reset view status for notifications
                ]);

                $message = 'Cash Advance has been declined.';
            }
            else {
                // Handle complete status
                $cashAdvance->update([
                    'status' => 'complete'
                ]);

                $message = 'Cash Advance has been marked as complete.';
            }

            return redirect()->route('cash_advances.index')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Cash Advance Update Error: ' . $e->getMessage());
            return redirect()->route('cash_advances.index')
                ->with('error', 'An error occurred while updating the cash advance. Please try again.');
        }
    }

    public function destroy(CashAdvance $cashAdvance)
    {
        $cashAdvance->delete();
        return redirect()->route('cash_advances.index')->with('success', 'Cash Advance deleted successfully.');
    }

    public function ledger($id)
    {
        $cashAdvance = CashAdvance::with(['employee', 'payments'])->findOrFail($id);

        // Check if user has Employee role and if the cash advance belongs to them
        if (auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Supervisor')) {
            $employee = Employee::where('email_address', auth()->user()->email)->first();

            if (!$employee || $cashAdvance->employee_id !== $employee->id) {
                abort(403, 'Unauthorized access to this cash advance ledger.');
            }
        }

        return view('cash_advances.ledger', compact('cashAdvance'));
    }

    public function generatePayments()
    {
        try {
            $cashAdvances = CashAdvance::where('status', 'active')->get();
            $paymentsGenerated = 0;
            $currentMonth = Carbon::now()->startOfMonth();

            foreach ($cashAdvances as $cashAdvance) {
                // Check if a payment for the current month already exists
                $existingPayment = CashAdvancePayment::where('cash_advance_id', $cashAdvance->id)
                    ->whereYear('payment_date', $currentMonth->year)
                    ->whereMonth('payment_date', $currentMonth->month)
                    ->exists();

                if (!$existingPayment) {
                    // Calculate covered periods
                    $month = $currentMonth->month;
                    $year = $currentMonth->year;
                    
                    // For the current month, get the previous month's 26th to current month's 10th
                    $prevMonth = Carbon::create($year, $month)->subMonth();
                    $firstHalfStart = Carbon::create($prevMonth->year, $prevMonth->month, 26)->startOfDay();
                    $firstHalfEnd = Carbon::create($year, $month, 10)->endOfDay();
                    
                    // For the current month's 11th to 25th
                    $secondHalfStart = Carbon::create($year, $month, 11)->startOfDay();
                    $secondHalfEnd = Carbon::create($year, $month, 25)->endOfDay();
                    
                    // Create the main payment record
                    $payment = CashAdvancePayment::create([
                        'cash_advance_id' => $cashAdvance->id,
                        'amount' => $cashAdvance->monthly_amortization,
                        'payment_date' => Carbon::now(),
                        'notes' => 'Auto-generated payment',
                        'covered_period_start' => $firstHalfStart,
                        'covered_period_end' => $secondHalfEnd,
                    ]);
                    $paymentsGenerated++;

                    // Calculate half of the payment amount
                    $halfAmount = $payment->amount / 2;

                    // Create two payment details with covered periods
                    $paymentDetails = [
                        [
                            'day' => 10,
                            'period_start' => $firstHalfStart,
                            'period_end' => $firstHalfEnd,
                            'note' => "First half ({$firstHalfStart->format('M d')} - {$firstHalfEnd->format('M d')})",
                            'period' => 'first_half'
                        ],
                        [
                            'day' => 25,
                            'period_start' => $secondHalfStart,
                            'period_end' => $secondHalfEnd,
                            'note' => "Second half ({$secondHalfStart->format('M d')} - {$secondHalfEnd->format('M d')})",
                            'period' => 'second_half'
                        ]
                    ];

                    foreach ($paymentDetails as $paymentInfo) {
                        // Create the loan record first
                        $loan = Loan::create([
                            'employee_id' => $cashAdvance->employee_id,
                            'cash_advance' => $halfAmount,
                            'date' => Carbon::create($year, $month, $paymentInfo['day']),
                            'notes' => $paymentInfo['note'],
                        ]);

                        // Create the payment detail and link it to the loan
                        $detail = $payment->paymentDetails()->create([
                            'amount' => $halfAmount,
                            'payment_date' => Carbon::create($year, $month, $paymentInfo['day']),
                            'covered_period_start' => $paymentInfo['period_start'],
                            'covered_period_end' => $paymentInfo['period_end'],
                            'notes' => $paymentInfo['note'],
                            'payment_period' => $paymentInfo['period'],
                            'loan_id' => $loan->id,
                        ]);
                    }

                    // Check if all payments are made to mark the cash advance as complete
                    $totalPaid = $cashAdvance->payments->sum('amount');
                    if ($totalPaid >= $cashAdvance->total_repayment) {
                        $cashAdvance->update(['status' => 'complete']);
                    }
                }
            }

            return redirect()->route('cash_advances.index')->with('success', "Successfully generated {$paymentsGenerated} payments.");
        } catch (\Exception $e) {
            return redirect()->route('cash_advances.index')->with('error', 'Error generating payments: ' . $e->getMessage());
        }
    }

    public function generatePaymentForEmployee(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'employee_id' => 'required|exists:employees,id',
            ]);

            $employee_id = $validatedData['employee_id'];
            $cashAdvances = CashAdvance::where('status', 'active')
                ->where('employee_id', $employee_id)
                ->get();
            $paymentsGenerated = 0;
            $currentMonth = Carbon::now()->startOfMonth();

            foreach ($cashAdvances as $cashAdvance) {
                // Check if a payment for the current month already exists
                $existingPayment = CashAdvancePayment::where('cash_advance_id', $cashAdvance->id)
                    ->whereYear('payment_date', $currentMonth->year)
                    ->whereMonth('payment_date', $currentMonth->month)
                    ->exists();

                if (!$existingPayment) {
                    // Calculate covered periods
                    $month = $currentMonth->month;
                    $year = $currentMonth->year;
                    
                    // For the current month, get the previous month's 26th to current month's 10th
                    $prevMonth = Carbon::create($year, $month)->subMonth();
                    $firstHalfStart = Carbon::create($prevMonth->year, $prevMonth->month, 26)->startOfDay();
                    $firstHalfEnd = Carbon::create($year, $month, 10)->endOfDay();
                    
                    // For the current month's 11th to 25th
                    $secondHalfStart = Carbon::create($year, $month, 11)->startOfDay();
                    $secondHalfEnd = Carbon::create($year, $month, 25)->endOfDay();
                    
                    // Create the main payment record
                    $payment = CashAdvancePayment::create([
                        'cash_advance_id' => $cashAdvance->id,
                        'amount' => $cashAdvance->monthly_amortization,
                        'payment_date' => Carbon::now(),
                        'notes' => 'Auto-generated payment for specific employee',
                        'covered_period_start' => $firstHalfStart,
                        'covered_period_end' => $secondHalfEnd,
                    ]);
                    $paymentsGenerated++;

                    // Calculate half of the payment amount
                    $halfAmount = $payment->amount / 2;

                    // Create two payment details with covered periods
                    $paymentDetails = [
                        [
                            'day' => 10,
                            'period_start' => $firstHalfStart,
                            'period_end' => $firstHalfEnd,
                            'note' => "First half ({$firstHalfStart->format('M d')} - {$firstHalfEnd->format('M d')})",
                            'period' => 'first_half'
                        ],
                        [
                            'day' => 25,
                            'period_start' => $secondHalfStart,
                            'period_end' => $secondHalfEnd,
                            'note' => "Second half ({$secondHalfStart->format('M d')} - {$secondHalfEnd->format('M d')})",
                            'period' => 'second_half'
                        ]
                    ];

                    foreach ($paymentDetails as $paymentInfo) {
                        // Create the loan record first
                        $loan = Loan::create([
                            'employee_id' => $cashAdvance->employee_id,
                            'cash_advance' => $halfAmount,
                            'date' => Carbon::create($year, $month, $paymentInfo['day']),
                            'notes' => $paymentInfo['note'],
                        ]);

                        // Create the payment detail and link it to the loan
                        $detail = $payment->paymentDetails()->create([
                            'amount' => $halfAmount,
                            'payment_date' => Carbon::create($year, $month, $paymentInfo['day']),
                            'covered_period_start' => $paymentInfo['period_start'],
                            'covered_period_end' => $paymentInfo['period_end'],
                            'notes' => $paymentInfo['note'],
                            'payment_period' => $paymentInfo['period'],
                            'loan_id' => $loan->id,
                        ]);
                    }

                    // Check if all payments are made to mark the cash advance as complete
                    $totalPaid = $cashAdvance->payments->sum('amount');
                    if ($totalPaid >= $cashAdvance->total_repayment) {
                        $cashAdvance->update(['status' => 'complete']);
                    }
                }
            }

            return redirect()->route('cash_advances.index')->with('success', "Successfully generated {$paymentsGenerated} payments for the selected employee.");
        } catch (\Exception $e) {
            return redirect()->route('cash_advances.index')->with('error', 'Error generating payments: ' . $e->getMessage());
        }
    }

    private function isEmployeeEligibleForCashAdvance(Employee $employee)
    {
        $hireDate = Carbon::parse($employee->date_hired);
        $today = Carbon::now();

        return $hireDate->diffInDays($today) >= 365;
    }

    private function generateUniqueReferenceNumber()
    {
        do {
            // Format: CA-YYYYMMDD-XXXX (e.g., CA-20240318-1234)
            $referenceNumber = 'CA-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (CashAdvance::where('reference_number', $referenceNumber)->exists());

        return $referenceNumber;
    }
}
