@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <!-- Payslip Header -->
            <div class="row g-0 p-4 bg-light border-bottom">
                <div class="col-md-4 mb-3 mb-md-0">
                    <img src="{{ asset('vendor/adminlte/dist/img/LOGO4.png') }}" alt="Company Logo" class="img-fluid mb-3" style="max-height: 60px;">
                    <h5 class="text-uppercase text-muted mb-2">MHR Property Conglomerates, Inc.</h5>
                    <address class="small mb-0">
                        MHR Building: Jose L. Briones St.,<br>
                        North Reclamation Area, Cebu City,<br>
                        Cebu, Philippines 6000<br>
                        Phone: (032) 238-1887<br>
                        Email: info@mhrpci.ph
                    </address>
                </div>
                <div class="col-md-4 text-center mb-3 mb-md-0">
                    <h2 class="text-primary mb-0">Payslip</h2>
                    <p class="text-muted small mb-1">For the period</p>
                    <h5 class="mb-0">{{ $payroll->start_date }} - {{ $payroll->end_date }}</h5>
                </div>
                <div class="col-md-4 text-md-end">
                    <h5 class="mb-2">Employee Details</h5>
                    <p class="mb-1"><strong>ID #:</strong> {{ $payroll->employee->company_id }}</p>
                    <p class="mb-1"><strong>Name:</strong> {{ $payroll->employee->last_name }} {{ $payroll->employee->first_name }}, {{ $payroll->employee->middle_name ?? ' ' }} {{ $payroll->employee->suffix ?? ' ' }}</p>
                    <p class="mb-1"><strong>Department:</strong> {{ $payroll->employee->department->name }}</p>
                    <p class="mb-0"><strong>Position:</strong> {{ $payroll->employee->position->name }}</p>
                </div>
            </div>

            <!-- Payslip Body -->
            <div class="row g-0 p-4">
                <div class="col-md-6 pe-md-3 mb-4 mb-md-0">
                    <h5 class="text-primary mb-3">Earnings</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Description</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Basic Salary</td>
                                    <td class="text-end">₱{{ number_format($payroll->gross_salary, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Overtime Pay</td>
                                    <td class="text-end">₱{{ number_format($payroll->overtime_pay, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Night Premium Pay</td>
                                    <td class="text-end">₱{{ number_format($payroll->night_premium_pay, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Adjustments</td>
                                    <td class="text-end">₱{{ number_format($payroll->adjustments, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Allowances</td>
                                    <td class="text-end">₱{{ number_format($payroll->allowances, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Other Adjustments</td>
                                    <td class="text-end">₱{{ number_format($payroll->other_adjustments, 2) }}</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td>Total Earnings</td>
                                    <td class="text-end">₱{{ number_format($payroll->total_earnings, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6 ps-md-3">
                    <h5 class="text-primary mb-3">Deductions</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Description</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $deductions = [
                                        'Late Deduction' => $payroll->late_deduction,
                                        'Undertime Deduction' => $payroll->undertime_deduction,
                                        'SSS Contribution' => $payroll->sss_contribution,
                                        'Pag-IBIG Contribution' => $payroll->pagibig_contribution,
                                        'PhilHealth Contribution' => $payroll->philhealth_contribution,
                                        'TIN Contribution' => $payroll->tin_contribution,
                                        'SSS Loan' => $payroll->sss_loan,
                                        'Pag-IBIG Loan' => $payroll->pagibig_loan,
                                        'Cash Advance' => $payroll->cash_advance,
                                    ];
                                    $totalDeductions = array_sum($deductions);
                                @endphp
                                @foreach($deductions as $description => $amount)
                                    <tr>
                                        <td>{{ $description }}</td>
                                        <td class="text-end">₱{{ number_format($amount, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>Cash Bond</td>
                                    <td class="text-end">₱{{ number_format($payroll->cash_bond, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Other Deductions</td>
                                    <td class="text-end">₱{{ number_format($payroll->other_deduction, 2) }} - {{ $payroll->other_deduction_description }}</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td>Total Deductions</td>
                                    <td class="text-end">₱{{ number_format($totalDeductions, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Net Pay -->
            <div class="row g-0 p-4 bg-light border-top">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h5 class="text-uppercase mb-1">Net Pay</h5>
                    <h2 class="text-primary">₱{{ number_format($payroll->net_salary, 2) }}</h2>
                </div>
                <div class="col-md-6 text-md-end">
                    @php
                        $endDate = \Carbon\Carbon::parse($payroll->end_date);
                        $payoutDate = $endDate->day <= 10 ? $endDate->copy()->day(15) : $endDate->copy()->lastOfMonth();
                    @endphp
                    <p class="mb-1"><strong>Pay Date:</strong> {{ $payoutDate->format('F d, Y') }}</p>
                    <p class="mb-0"><strong>Payment Method:</strong> Direct Deposit</p>
                </div>
            </div>
        </div>

        <!-- Payslip Footer -->
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('HR ComBen'))
                    <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary mb-2 mb-md-0">
                        <i class="fas fa-arrow-left me-2"></i>Back to Payroll List
                    </a>
                @else
                    <a href="{{ url('/my-payrolls') }}" class="btn btn-outline-secondary mb-2 mb-md-0">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                @endif
                <a href="{{ route('payroll.payslip', $payroll->id) }}" class="btn btn-primary">
                    <i class="fas fa-download me-2"></i>Download Payslip
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .card-footer, .main-header, .main-sidebar { display: none !important; }
        .content-wrapper { margin-left: 0 !important; }
        .card { border: none !important; box-shadow: none !important; }
    }
</style>
@endsection
