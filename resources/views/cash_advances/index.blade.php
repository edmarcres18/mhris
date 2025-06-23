@extends('layouts.app')

@section('content')
<br>

<!-- Enhanced professional-looking link buttons -->
<div class="mb-4">
    <div class="contribution-nav" role="navigation" aria-label="Contribution Types">
        <a href="{{ route('loan_sss.index') }}" class="contribution-link {{ request()->routeIs('loan_sss.index') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Sss Loan</span>
                <small class="description">Social Security System</small>
            </div>
        </a>
        <a href="{{ route('loan_pagibig.index') }}" class="contribution-link {{ request()->routeIs('loan_pagibig.index') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-home"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Pag-IBIG Loan</span>
                <small class="description">Home Development Mutual Fund</small>
            </div>
        </a>
        <a href="{{ route('cash_advances.index') }}" class="contribution-link {{ request()->routeIs('cash_advances.index') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Cash Advance</span>
                <small class="description">Company Cash Advance</small>
            </div>
        </a>
        <a href="{{ route('loans.employees-list') }}" class="contribution-link {{ request()->routeIs('loans.employees-list') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-users"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Borrower</span>
                <small class="description">Loan Applicant List</small>
            </div>
        </a>
    </div>
</div>

<!-- Add success and error message display -->
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: "{{ session('success') }}",
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "{{ session('error') }}",
        });
    </script>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Cash Advance List</h3>
        <div class="card-tools">
            {{-- <a href="{{ route('cash_advances.create') }}" class="btn btn-success btn-sm rounded-pill">
                Apply for Cash Advance
            </a> --}}
            <button id="export-excel" class="btn btn-primary btn-sm rounded-pill mr-2">
                Export to Excel <i class="fas fa-file-excel"></i>
            </button>
            <form action="{{ route('cash_advances.generate_payments') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-info btn-sm rounded-pill mr-2">
                    Generate Payments <i class="fas fa-money-bill-wave"></i>
                </button>
            </form>
            <!-- New button for generating payment for specific employee -->
            <button type="button" class="btn btn-warning btn-sm rounded-pill" data-toggle="modal" data-target="#generatePaymentModal">
                Generate Payment for Employee <i class="fas fa-user-cog"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <table id="cash_advances" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Reference Number</th>
                <th>Employee Name</th>
                <th>Loan Amount</th>
                <th>Repayment Term</th>
                <th>Monthly Amortization</th>
                <th>Total Repayment</th>
                <th>Remaining Balance</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cashAdvances as $loan)
                <tr>
                    <td>{{ $loan->reference_number }}</td>
                    <td>{{ $loan->employee->last_name }} {{ $loan->employee->first_name }}, {{ $loan->employee->middle_name ?? ' ' }} {{ $loan->employee->suffix ?? ' ' }}</td>
                    <td>₱{{ number_format($loan->cash_advance_amount, 2) }}</td>
                    <td>{{ $loan->repayment_term }} {{ $loan->repayment_term <= 1 ? 'Month' : 'Months' }}</td>
                    <td>₱{{ number_format($loan->monthly_amortization, 2) }}</td>
                    <td>₱{{ number_format($loan->total_repayment, 2) }}</td>
                    <td>₱{{ number_format($loan->remainingBalance(), 2) }}</td>
                    <td>
                        @if($loan->status == 'active')
                            <span class="badge badge-success">{{ $loan->status }}</span>
                        @elseif($loan->status == 'pending')
                            <span class="badge badge-warning">{{ $loan->status }}</span>
                            @else
                            <span class="badge badge-primary">{{ $loan->status }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group">
                            <div class="dropdown">
                                <button class="btn btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Super Admin'))
                                    <a href="{{ route('cash_advances.show', $loan->id) }}" class="dropdown-item">
                                        <i class="fas fa-eye"></i>&nbsp;View Details
                                    </a>
                                    @endif
                                    <a href="{{ route('cash_advances.ledger', $loan->id) }}" class="dropdown-item">
                                        <i class="fas fa-book"></i>&nbsp;Ledger
                                    </a>
                                    @if((auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Super Admin')) && $loan->status === 'active')
                                        <a href="{{ route('cash_advances.edit', $loan->id) }}" class="dropdown-item">
                                            <i class="fas fa-edit"></i>&nbsp;Update Status
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
<!-- Modal for generating payment for specific employee -->
<div class="modal fade" id="generatePaymentModal" tabindex="-1" role="dialog" aria-labelledby="generatePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generatePaymentModalLabel">Generate Payment for Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('cash_advances.generate_payment_for_employee') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="employee_id">Select Employee</label>
                        <select name="employee_id" id="employee_id" class="form-control" required>
                            <option value="" selected disabled>Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->company_id }} - {{ $employee->last_name }}, {{ $employee->first_name }} {{ $employee->middle_name ?? '' }} {{ $employee->suffix ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Generate Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('css')
<style>
    .contribution-nav {
        display: flex;
        gap: 15px;
        justify-content: flex-start;
        flex-wrap: wrap;
    }
    .contribution-link {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        border-radius: 8px;
        text-decoration: none;
        color: #333;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
        border: 1px solid #dee2e6;
    }
    .contribution-link:hover {
        background-color: #e9ecef;
        text-decoration: none;
        color: #333;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .contribution-link.active {
        background-color: #007bff;
        color: #fff;
        border-color: #007bff;
    }
    .contribution-link .icon-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: rgba(0,0,0,0.1);
        margin-right: 10px;
    }
    .contribution-link.active .icon-wrapper {
        background-color: rgba(255,255,255,0.2);
    }
    .contribution-link .icon-wrapper i {
        font-size: 1.2rem;
    }
    .contribution-link .text-wrapper {
        display: flex;
        flex-direction: column;
    }
    .contribution-link .title {
        font-weight: bold;
        font-size: 1rem;
    }
    .contribution-link .description {
        font-size: 0.75rem;
        opacity: 0.8;
    }
    .contribution-link.active .description {
        opacity: 0.9;
    }
    .colored-toast.swal2-icon-success {
        box-shadow: 0 0 12px rgba(40, 167, 69, 0.4) !important;
    }
    .colored-toast.swal2-icon-error {
        box-shadow: 0 0 12px rgba(220, 53, 69, 0.4) !important;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('js')
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        // Enhanced Select2 initialization with better configuration
        $('#employee_id').select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: 'Select Employee',
            allowClear: true,
            dropdownParent: $('#generatePaymentModal'),
            escapeMarkup: function(markup) {
                return markup;
            }
        });

        // Initialize DataTable
        var table = $('#cash_advances').DataTable({
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "order": [[1, "desc"]],
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "Showing 0 to 0 of 0 entries",
                "infoFiltered": "(filtered from _MAX_ total entries)",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                }
            }
        });

        // Export to Excel functionality
        $('#export-excel').on('click', function() {
            // Get table data properly by mapping through visible rows
            var data = table.rows().data().toArray();
            var header = ['Reference Number', 'Employee Name', 'Loan Amount', 'Repayment Term', 'Monthly Amortization', 'Total Repayment', 'Remaining Balance', 'Status'];

            // Prepare data with proper text extraction
            var exportData = data.map(function(row) {
                return [
                    row[0], // Reference Number
                    row[1], // Employee Name
                    row[2].replace('₱', '').replace(/,/g, ''), // Loan Amount
                    row[3].replace(' Months', '').replace(' Month', ''), // Repayment Term
                    row[4].replace('₱', '').replace(/,/g, ''), // Monthly Amortization
                    row[5].replace('₱', '').replace(/,/g, ''), // Total Repayment
                    row[6].replace('₱', '').replace(/,/g, ''), // Remaining Balance
                    $(row[7]).text().trim() // Status (properly extract text from badge)
                ];
            });

            var wb = XLSX.utils.book_new();
            var ws = XLSX.utils.aoa_to_sheet([header].concat(exportData));

            // Set column widths
            ws['!cols'] = [
                {wch: 20}, // Reference Number
                {wch: 30}, // Employee Name
                {wch: 15}, // Loan Amount
                {wch: 15}, // Repayment Term
                {wch: 20}, // Monthly Amortization
                {wch: 18}, // Total Repayment
                {wch: 18}, // Remaining Balance
                {wch: 12}  // Status
            ];

            // Style configurations
            var headerStyle = {
                font: { bold: true, color: { rgb: "FFFFFF" } },
                fill: { fgColor: { rgb: "4472C4" } },
                alignment: { horizontal: "center", vertical: "center", wrapText: true },
                border: {
                    top: {style: "thin"},
                    bottom: {style: "thin"},
                    left: {style: "thin"},
                    right: {style: "thin"}
                }
            };

            var dataStyle = {
                alignment: { vertical: "center" },
                border: {
                    top: {style: "thin"},
                    bottom: {style: "thin"},
                    left: {style: "thin"},
                    right: {style: "thin"}
                }
            };

            // Apply styles to all cells
            var range = XLSX.utils.decode_range(ws['!ref']);
            for (var R = range.s.r; R <= range.e.r; ++R) {
                for (var C = range.s.c; C <= range.e.c; ++C) {
                    var cellRef = XLSX.utils.encode_cell({r: R, c: C});
                    if (!ws[cellRef]) continue;
                    
                    if (R === 0) {
                        // Header row
                        ws[cellRef].s = headerStyle;
                    } else {
                        // Data rows
                        var style = {...dataStyle};
                        
                        // Specific column alignments and formats
                        if (C === 0 || C === 1) {
                            // Reference Number and Employee Name
                            style.alignment = {...style.alignment, horizontal: "left"};
                        } else if (C === 3) {
                            // Repayment Term
                            style.alignment = {...style.alignment, horizontal: "center"};
                        } else if (C === 2 || C === 4 || C === 5 || C === 6) {
                            // Amount columns
                            style.alignment = {...style.alignment, horizontal: "right"};
                            style.numFmt = '"₱"#,##0.00';
                        } else {
                            // Status
                            style.alignment = {...style.alignment, horizontal: "center"};
                        }
                        
                        ws[cellRef].s = style;
                    }
                }
            }

            // Generate and download the file
            XLSX.utils.book_append_sheet(wb, ws, 'Cash Advances');
            XLSX.writeFile(wb, 'cash_advances_' + new Date().toISOString().split('T')[0] + '.xlsx');
        });

        // Common toast configuration
        const toastConfig = {
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            background: '#fff',
            color: '#424242',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast'
            }
        };

        @if(Session::has('success'))
            Swal.fire({
                ...toastConfig,
                icon: 'success',
                title: 'Success',
                text: "{{ Session::get('success') }}",
                background: '#28a745',
                color: '#fff'
            });
        @endif

        @if(Session::has('error'))
            Swal.fire({
                ...toastConfig,
                icon: 'error',
                title: 'Error',
                text: "{{ Session::get('error') }}",
                background: '#dc3545',
                color: '#fff'
            });
        @endif
    });
</script>
@endsection
