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
                <span class="title">SSS Loan</span>
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

<div class="card">
    <div class="card-header">
        <h3 class="card-title">PAGIBIG Loan List</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-success btn-sm rounded-pill" data-toggle="modal" data-target="#loanModal">
                Apply for PAGIBIG Loan
            </button>
            <button id="export-excel" class="btn btn-primary btn-sm rounded-pill mr-2">
                Export to Excel <i class="fas fa-file-excel"></i>
            </button>
            <form action="{{ route('loan_pagibig.generate_payments') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-info btn-sm rounded-pill mr-2">
                    Generate Payments <i class="fas fa-money-bill-wave"></i>
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <table id="loan_pagibig" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Pagibig No.</th>
                <th>Employee Name</th>
                <th>Loan Amount</th>
                <th>Loan Term</th>
                <th>Monthly Amortization</th>
                <th>Interest Rate</th>
                <th>Loan Type</th>
                <th>Status</th>
                <th>Remaining Balance</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $loan)
                <tr>
                    <td>{{ $loan->employee->pagibig_no }}</td>
                    <td>{{ $loan->employee->last_name }} {{ $loan->employee->first_name }}, {{ $loan->employee->middle_name ?? ' ' }} {{ $loan->employee->suffix ?? ' ' }}</td>
                    <td>₱{{ number_format($loan->loan_amount, 2) }}</td>
                    <td>{{ $loan->loan_term_months }} {{ $loan->loan_term_months <= 1 ? 'Month' : 'Months' }}</td>
                    <td>₱{{ number_format($loan->monthly_amortization, 2) }}</td>
                    <td>{{ $loan->interest_rate }}%</td>
                    <td>{{ $loan->loan_type->value }}</td>
                    <td>
                        @if($loan->status == 'active')
                            <span class="badge badge-success">{{ $loan->status }}</span>
                        @else
                            <span class="badge badge-primary">{{ $loan->status }}</span>
                        @endif
                    </td>
                    <td>₱{{ number_format($loan->calculateRemainingBalance(), 2) }}</td>
                    <td>
                        <div class="btn-group">
                            <div class="dropdown">
                                <button class="btn btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a href="{{ route('loan_pagibig.ledger', $loan->id) }}" class="dropdown-item">
                                        <i class="fas fa-book"></i>&nbsp;Ledger
                                    </a>
                                    @if($loan->calculateRemainingBalance() == 0)
                                    <a href="{{ route('loan_pagibig.edit', $loan->id) }}" class="dropdown-item">
                                        <i class="fas fa-edit"></i>&nbsp;Update Status
                                    </a>
                                    @endif
                                    @can('super-admin')
                                    <form action="{{ route('loan_pagibig.destroy', $loan->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="dropdown-item delete-button">
                                            <i class="fas fa-trash"></i>&nbsp;Delete
                                        </button>
                                    </form>
                                    @endcan
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

@include('loan_pagibig.create')
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
    /* Toast styles */
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
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        // Add SweetAlert toast configuration
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

        // Success toast
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

        // Error toast
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

        // Initialize Select2 for all select elements
        $('select').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

        var table = $('#loan_pagibig').DataTable({
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "order": [[1, "desc"]],
            "columnDefs": [
                { "orderable": false, "targets": 9 }
            ]
        });

        $('#export-excel').on('click', function() {
            var data = table.rows().data().toArray();
            var header = ['Pagibig No.', 'Employee Name', 'Loan Amount', 'Loan Term', 'Monthly Amortization', 'Interest Rate', 'Loan Type', 'Status', 'Remaining Balance'];

            var wb = XLSX.utils.book_new();
            var ws = XLSX.utils.aoa_to_sheet([header].concat(data.map(row => [
                row[0], // Pagibig No.
                row[1], // Employee Name
                parseFloat(row[2].replace(/[₱,]/g, '')).toFixed(2), // Loan Amount
                row[3], // Loan Term
                parseFloat(row[4].replace(/[₱,]/g, '')).toFixed(2), // Monthly Amortization
                row[5], // Interest Rate
                row[6], // Loan Type
                row[7], // Status
                parseFloat(row[8].replace(/[₱,]/g, '')).toFixed(2) // Remaining Balance
            ])));

            // Set column widths
            ws['!cols'] = [
                {wch: 15}, // Pagibig No.
                {wch: 30}, // Employee Name
                {wch: 15}, // Loan Amount
                {wch: 15}, // Loan Term
                {wch: 20}, // Monthly Amortization
                {wch: 15}, // Interest Rate
                {wch: 15}, // Loan Type
                {wch: 15}, // Status
                {wch: 20}  // Remaining Balance
            ];

            // Style the header row
            var headerStyle = {
                font: { bold: true, color: { rgb: "FFFFFF" } },
                fill: { fgColor: { rgb: "4472C4" } },
                alignment: { horizontal: "center", vertical: "center" },
                border: {
                    top: {style: "thin", color: {auto: 1}},
                    bottom: {style: "thin", color: {auto: 1}},
                    left: {style: "thin", color: {auto: 1}},
                    right: {style: "thin", color: {auto: 1}}
                }
            };
            for (var i = 0; i < header.length; i++) {
                var cellRef = XLSX.utils.encode_cell({r: 0, c: i});
                ws[cellRef].s = headerStyle;
            }

            // Style the data cells
            var dataStyle = {
                alignment: { horizontal: "right", vertical: "center" },
                border: {
                    top: {style: "thin", color: {auto: 1}},
                    bottom: {style: "thin", color: {auto: 1}},
                    left: {style: "thin", color: {auto: 1}},
                    right: {style: "thin", color: {auto: 1}}
                }
            };
            var currencyStyle = Object.assign({}, dataStyle, { numFmt: '"₱"#,##0.00' });
            var monthStyle = Object.assign({}, dataStyle, {
                alignment: { horizontal: "center" }
            });

            var range = XLSX.utils.decode_range(ws['!ref']);
            for (var R = range.s.r; R <= range.e.r; ++R) {
                for (var C = range.s.c; C <= range.e.c; ++C) {
                    var cellRef = XLSX.utils.encode_cell({r: R, c: C});
                    if (!ws[cellRef]) continue;
                    if (!ws[cellRef].s) ws[cellRef].s = {};
                    if (R === 0) {
                        ws[cellRef].s = headerStyle;
                    } else {
                        if (C === 3) {
                            ws[cellRef].s = monthStyle;
                        } else if (C === 2 || C === 4) {
                            ws[cellRef].s = currencyStyle;
                        } else {
                            ws[cellRef].s = dataStyle;
                        }
                    }
                }
            }

            XLSX.utils.book_append_sheet(wb, ws, 'PAGIBIG Loans');
            XLSX.writeFile(wb, 'pagibig_loans.xlsx');
        });

        // Remove the AJAX call for generate payments
        // The form submission will handle this now
    });

    $(document).on('click', '.delete-button', function() {
        const form = $(this).closest('.delete-form');
        const loanId = form.attr('action').split('/').pop(); // Extract loan ID from the action URL

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Submit the form if confirmed
            }
        });
    });
</script>
@endsection
