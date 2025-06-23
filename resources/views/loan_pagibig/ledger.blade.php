@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h1 class="h2 text-primary mb-0">Pag-IBIG Loan Ledger: {{ $loan->employee->last_name }} {{ $loan->employee->first_name }}, {{ $loan->employee->middle_name ?? ' ' }} {{ $loan->employee->suffix ?? ' ' }}</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('loan_pagibig.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Pag-IBIG Loans
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h2 class="h5 mb-0"><i class="fas fa-info-circle mr-2"></i>Loan Details</h2>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-6 text-muted">Employee ID:</dt>
                        <dd class="col-sm-6">{{ $loan->employee->company_id }}</dd>
                        <dt class="col-sm-6 text-muted">Loan Amount:</dt>
                        <dd class="col-sm-6">₱{{ number_format($loan->loan_amount, 2) }}</dd>
                        <dt class="col-sm-6 text-muted">Loan Type:</dt>
                        <dd class="col-sm-6">{{ $loan->loan_type }}</dd>
                        <dt class="col-sm-6 text-muted">Interest Rate:</dt>
                        <dd class="col-sm-6">{{ $loan->interest_rate }}%</dd>
                        <dt class="col-sm-6 text-muted">Loan Term:</dt>
                        <dd class="col-sm-6">{{ $loan->loan_term_months }} months</dd>
                        <dt class="col-sm-6 text-muted">Monthly Amortization:</dt>
                        <dd class="col-sm-6">₱{{ number_format($loan->monthly_amortization, 2) }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h2 class="h5 mb-0"><i class="fas fa-chart-pie mr-2"></i>Repayment Summary</h2>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-6 text-muted">Total Paid:</dt>
                        <dd class="col-sm-6">₱{{ number_format($totalPaid, 2) }}</dd>
                        <dt class="col-sm-6 text-muted">Remaining Balance:</dt>
                        <dd class="col-sm-6">₱{{ number_format($remainingBalance, 2) }}</dd>
                        <dt class="col-sm-6 text-muted">Repayment Progress:</dt>
                        <dd class="col-sm-6">
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($totalPaid / $loan->loan_amount) * 100 }}%"></div>
                            </div>
                            <small class="text-muted">{{ number_format(($totalPaid / $loan->loan_amount) * 100, 1) }}% complete</small>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h2 class="h5 mb-0"><i class="fas fa-history mr-2"></i>Payment History</h2>
                    <button class="btn btn-sm btn-outline-light" id="exportBtn">
                        <i class="fas fa-download mr-2"></i>Export to Excel
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="paymentsTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>Payment Date</th>
                                    <th>Amount</th>
                                    <th>Running Balance</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $runningBalance = $loan->loan_amount; @endphp
                                @forelse ($loan->payments->sortBy('payment_date') as $payment)
                                    @php $runningBalance -= $payment->amount; @endphp
                                    <tr>
                                        <td>{{ $payment->payment_date->format('F d, Y') }}</td>
                                        <td>₱{{ number_format($payment->amount, 2) }}</td>
                                        <td>₱{{ number_format($runningBalance, 2) }}</td>
                                        <td>{{ $payment->notes ?: 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            <i class="fas fa-info-circle mr-2"></i>No payments recorded yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/tableExport.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/libs/jsPDF/jspdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
<script>
    $(document).ready(function() {
        $('#exportBtn').on('click', function() {
            $('#paymentsTable').tableExport({
                formats: ['xlsx'],
                fileName: 'Pag-IBIG_Loan_Ledger_{{ $loan->employee->last_name }}_{{ $loan->employee->first_name }}',
                exportOptions: {
                    columns: [0, 1, 2, 3]
                }
            });
        });
    });
</script>
@endpush
