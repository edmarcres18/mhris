@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Loans for {{ $employee->company_id }} {{ $employee->last_name }} {{ $employee->first_name }}, {{ $employee->middle_name ?? ' ' }} {{ $employee->suffix ?? ' ' }}</h1>
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="mb-2 mb-md-0">
                    <a href="{{ route('loans.employees-list') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
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
                <div class="col-sm-6 col-lg-4 mb-4">
                    <div class="info-box bg-primary">
                        <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total SSS Loan</span>
                            <span class="info-box-number">{{ number_format($totals['sss'], 2) }}</span>
                        </div>
                        <div class="info-box-overlay">
                            <img src="{{ asset('vendor/adminlte/dist/img/sss.png') }}" alt="SSS Logo">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4 mb-4">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-home"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Pagibig Loan</span>
                            <span class="info-box-number">{{ number_format($totals['pagibig'], 2) }}</span>
                        </div>
                        <div class="info-box-overlay">
                            <img src="{{ asset('vendor/adminlte/dist/img/pagibig.png') }}" alt="Pagibig Logo">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4 mb-4">
                    <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-coins"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Cash Advance</span>
                            <span class="info-box-number">{{ number_format($totals['cash_advance'], 2) }}</span>
                        </div>
                        <div class="info-box-overlay">
                            <img src="{{ asset('vendor/adminlte/dist/img/cashadvance.png') }}" alt="Cash Advance Logo">
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Date</th>
                            <th>SSS</th>
                            <th>Pagibig</th>
                            <th>Cash Advance</th>
                        </tr>
                    </thead>
                    <tbody id="loanTableBody">
                        @forelse($loanTotals as $date => $totals)
                        <tr data-date="{{ $date }}">
                            <td>{{ $date }}</td>
                            <td>{{ number_format($totals['sss'], 2) }}</td>
                            <td>{{ number_format($totals['pagibig'], 2) }}</td>
                            <td>{{ number_format($totals['cash_advance'], 2) }}</td>
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
@push('css')
<style>
    .container-fluid {
        padding: 20px;
    }

    .info-box {
    position: relative;
    overflow: hidden;
    height: 120px;
    border-radius: .25rem;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    padding: 1rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
    background-color: #f4f6f9;
}

.info-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.info-box-icon {
    font-size: 2.5rem;
    color: #fff;
    margin-right: 1rem;
}

.info-box-content {
    position: relative; /* Added to ensure content overlays on top of the background */
    z-index: 1; /* Ensure content is above the overlay */
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.info-box-text {
    font-size: 1rem;
    font-weight: bold;
}

.info-box-number {
    font-size: 1.5rem;
    font-weight: bold;
}

.info-box-overlay {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 100px; /* Adjusted size for better visibility */
    height: auto; /* Auto height to maintain aspect ratio */
    opacity: 0.15; /* Slightly increased opacity for subtle effect */
    z-index: 0; /* Positioned behind the content */
}

.info-box-overlay img {
    width: 100%; /* Ensure the image takes the full width of the container */
    height: auto; /* Maintain aspect ratio */
}

</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('printButton').addEventListener('click', function() {
            const printWindow = window.open('', '_blank');

            const printContent = `
                <html>
                <head>
                    <title>Loan Report - {{ $employee->last_name }}, {{ $employee->first_name }}</title>
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
                                <td>{{ number_format($totals['sss'], 2) }}</td>
                                <td>{{ number_format($totals['pagibig'], 2) }}</td>
                                <td>{{ number_format($totals['cash_advance'], 2) }}</td>
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

        const monthFilterLinks = document.querySelectorAll('.dropdown-menu .dropdown-item[data-month]');
        monthFilterLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const month = this.getAttribute('data-month');
                filterByMonth(month);
            });
        });

        function filterByMonth(month) {
            const rows = document.querySelectorAll('#loanTableBody tr');
            rows.forEach(row => {
                const date = new Date(row.getAttribute('data-date'));
                const rowMonth = date.getMonth() + 1; // Months are zero-indexed
                if (month === "" || rowMonth == month) {
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

        // Initial check for no data
        checkNoData();
    });
</script>
@endpush
