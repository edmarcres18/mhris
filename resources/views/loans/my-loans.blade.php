@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Loans for {{ $employee->company_id }} {{ $employee->last_name }} {{ $employee->first_name }}, {{ $employee->middle_name ?? ' ' }} {{ $employee->suffix ?? ' ' }}</h1>
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="mb-2 mb-md-0">
                    <button id="printButton" class="btn btn-info btn-sm ml-2">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
                <div class="d-flex align-items-center">
                    <div class="input-group input-group-sm mr-2">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="yearSelect">Year</label>
                        </div>
                        <select id="yearSelect" class="custom-select">
                            <option value="">All</option>
                            @foreach(range(date('Y'), 1950) as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="monthSelect">Month</label>
                        </div>
                        <select id="monthSelect" class="custom-select">
                            <option value="">All</option>
                            @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <!-- Info Boxes -->
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="info-box bg-primary h-100 shadow-sm">
                        <span class="info-box-icon d-flex align-items-center justify-content-center">
                            <i class="fas fa-money-bill-wave fa-lg"></i>
                        </span>
                        <div class="info-box-content p-3">
                            <h5 class="info-box-text mb-2">Total SSS Loan</h5>
                            <h4 class="info-box-number mb-0">₱{{ number_format($totals['sss_loan'], 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="info-box bg-success h-100 shadow-sm">
                        <span class="info-box-icon d-flex align-items-center justify-content-center">
                            <i class="fas fa-home fa-lg"></i>
                        </span>
                        <div class="info-box-content p-3">
                            <h5 class="info-box-text mb-2">Total Pag-IBIG Loan</h5>
                            <h4 class="info-box-number mb-0">₱{{ number_format($totals['pagibig_loan'], 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="info-box bg-info h-100 shadow-sm position-relative">
                        <span class="info-box-icon d-flex align-items-center justify-content-center">
                            <i class="fas fa-hand-holding-usd fa-lg"></i>
                        </span>
                        <div class="info-box-content p-3 d-flex flex-column">
                            <div>
                                <h5 class="info-box-text mb-2">Total Cash Advance</h5>
                                <h4 class="info-box-number mb-0">₱{{ number_format($totals['cash_advance'], 2) }}</h4>
                            </div>
                            @if($employee->cashAdvances->isNotEmpty())
                                <a href="{{ route('cash_advances.ledger', $employee->cashAdvances->first()->id) }}"
                                   class="btn btn-light btn-sm mt-3 w-100 text-info">
                                    <i class="fas fa-book"></i> View Ledger
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Date</th>
                            <th>SSS Loan</th>
                            <th>Pag-IBIG Loan</th>
                            <th>Cash Advance</th>
                        </tr>
                    </thead>
                    <tbody id="loanTableBody">
                        @forelse($loans as $loan)
                        <tr data-date="{{ $loan->date }}">
                            <td>{{ $loan->date }}</td>
                            <td>₱{{ number_format($loan->sss_loan, 2) }}</td>
                            <td>₱{{ number_format($loan->pagibig_loan, 2) }}</td>
                            <td>₱{{ number_format($loan->cash_advance, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No Data Available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div id="no-data-message" class="text-center" style="display: none;">No data available for the selected month.</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('printButton').addEventListener('click', function() {
            const printWindow = window.open('', '_blank');

            const printContent = `
                <html>
                <head>
                    <title>Loan Report</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            line-height: 1.6;
                            color: #333;
                            max-width: 800px;
                            margin: 0 auto;
                            padding: 20px;
                        }
                        h1 {
                            color: #2c3e50;
                            border-bottom: 2px solid #3498db;
                            padding-bottom: 10px;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-bottom: 20px;
                        }
                        th, td {
                            border: 1px solid #ddd;
                            padding: 12px;
                            text-align: left;
                        }
                        th {
                            background-color: #3498db;
                            color: white;
                        }
                        tr:nth-child(even) {
                            background-color: #f2f2f2;
                        }
                        .total-row {
                            font-weight: bold;
                            background-color: #ecf0f1;
                        }
                        .summary {
                            display: flex;
                            justify-content: space-between;
                            margin-bottom: 20px;
                        }
                        .summary-item {
                            text-align: center;
                            padding: 10px;
                            background-color: #f8f9fa;
                            border-radius: 5px;
                        }
                        .summary-label {
                            font-weight: bold;
                            color: #2c3e50;
                        }
                        @media print {
                            body {
                                print-color-adjust: exact;
                                -webkit-print-color-adjust: exact;
                            }
                        }
                    </style>
                </head>
                <body>
                    <h1>Loan Report</h1>
                    <p><strong>Employee:</strong> {{ $employee->company_id }} {{ $employee->last_name }}, {{ $employee->first_name }} {{ $employee->middle_name ?? '' }} {{ $employee->suffix ?? '' }}</p>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>SSS Loan</th>
                                <th>Pag-IBIG Loan</th>
                                <th>Cash Advance</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${document.getElementById('loanTableBody').innerHTML}
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td>Total</td>
                                <td>₱{{ number_format($totals['sss_loan'], 2) }}</td>
                                <td>₱{{ number_format($totals['pagibig_loan'], 2) }}</td>
                                <td>₱{{ number_format($totals['cash_advance'], 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                    <p><small>Generated on ${new Date().toLocaleString()}</small></p>
                </body>
                </html>
            `;

            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.focus();

            // Delay printing to ensure styles are applied
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        });

        const yearSelect = document.getElementById('yearSelect');
        const monthSelect = document.getElementById('monthSelect');

        yearSelect.addEventListener('change', filterByDate);
        monthSelect.addEventListener('change', filterByDate);

        function filterByDate() {
            const year = yearSelect.value;
            const month = monthSelect.value;
            const rows = document.querySelectorAll('#loanTableBody tr');

            rows.forEach(row => {
                const date = new Date(row.getAttribute('data-date'));
                const rowYear = date.getFullYear();
                const rowMonth = date.getMonth() + 1; // Months are zero-indexed

                if ((year === "" || rowYear == year) && (month === "" || rowMonth == month)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            checkNoData();
        }

        function checkNoData() {
            const rows = document.querySelectorAll('#loanTableBody tr');
            const noDataMessage = document.getElementById('no-data-message');
            const hasData = Array.from(rows).some(row => row.style.display !== 'none');

            if (!hasData) {
                const year = yearSelect.value;
                const month = monthSelect.value;
                let message = 'No data available';

                if (year && month) {
                    const monthName = new Date(year, month - 1, 1).toLocaleString('default', { month: 'long' });
                    message += ` for ${monthName} ${year}`;
                } else if (year) {
                    message += ` for the year ${year}`;
                } else if (month) {
                    const monthName = new Date(2000, month - 1, 1).toLocaleString('default', { month: 'long' });
                    message += ` for ${monthName}`;
                }

                noDataMessage.textContent = message + '.';
                noDataMessage.style.display = 'block';
            } else {
                noDataMessage.style.display = 'none';
            }
        }

        // Initial check for no data
        checkNoData();
    });
</script>
@endpush
