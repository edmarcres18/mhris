@extends('layouts.app')

@section('content')
<br>

<!-- Enhanced professional-looking link buttons -->
<div class="mb-4">
    <div class="contribution-nav" role="navigation" aria-label="Contribution Types">
        <a href="{{ route('sss.index') }}" class="contribution-link {{ request()->routeIs('sss.index') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">SSS</span>
                <small class="description">Social Security System</small>
            </div>
        </a>
        <a href="{{ route('pagibig.index') }}" class="contribution-link {{ request()->routeIs('pagibig.index') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-home"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Pag-IBIG</span>
                <small class="description">Home Development Mutual Fund</small>
            </div>
        </a>
        <a href="{{ route('philhealth.index') }}" class="contribution-link {{ request()->routeIs('philhealth.index') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-heartbeat"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">PhilHealth</span>
                <small class="description">Philippine Health Insurance</small>
            </div>
        </a>
        <a href="{{ route('contributions.employees-list') }}" class="contribution-link {{ request()->routeIs('contributions.employees-list') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-users"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Contributor</span>
                <small class="description">Employee Contributor List</small>
            </div>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Philhealth Contributions List</h3>
        <div class="card-tools">
            <a href="{{ route('philhealth.create') }}" class="btn btn-success btn-sm rounded-pill">
                Add Philhealth Contribution <i class="fas fa-plus-circle"></i>
            </a>
            <button id="export-excel" class="btn btn-primary btn-sm rounded-pill mr-2">
                Export to Excel <i class="fas fa-file-excel"></i>
            </button>
            <!-- Add the new button here -->
           <button type="button" class="btn btn-info btn-sm rounded-pill" data-toggle="modal" data-target="#createAllModal">
               Create for All Active ({{ $activeEmployeesCount }}) <i class="fas fa-users"></i>
           </button>
           <button type="button" class="btn btn-warning btn-sm rounded-pill mr-2" data-toggle="modal" data-target="#notifyModal">
               <i class="fas fa-bell"></i> Notify Employees
           </button>
       </div>
   </div>
   <div class="card-body">
       <table id="philhealth-table" class="table table-bordered table-striped">
           <thead>
           <tr>
               <th>PHILHEALTH NO.</th>
               <th>Employee</th>
               <th>Contribution Date</th>
               <th>Employee Share</th>
               <th>Employer Share</th>
               <th>Total Contribution</th>
               <th>Actions</th>
           </tr>
       </thead>
       <tbody>
           @foreach($contributions as $contribution)
               <tr>
                   <td>{{ $contribution->employee->philhealth_no}}</td>
                   <td>{{ $contribution->employee->last_name }} {{ $contribution->employee->first_name }}, {{ $contribution->employee->middle_name ?? ' ' }} {{ $contribution->employee->suffix ?? ' ' }}</td>
                   <td>{{ $contribution->contribution_date->format('F Y') }}</td>
                   <td>{{ number_format($contribution->employee_contribution, 2) }}</td>
                   <td>{{ number_format($contribution->employer_contribution, 2) }}</td>
                   <td>{{ number_format($contribution->total_contribution, 2) }}</td>
                   <td>
                       <div class="btn-group">
                           <div class="dropdown">
                               <button class="btn btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <i class="fas fa-ellipsis-v"></i>
                               </button>
                               <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                   <a class="dropdown-item" href="{{ route('philhealth.show', $contribution) }}"><i class="fas fa-eye"></i>&nbsp;Preview</a>
                                   @if(auth()->user()->hasRole('Super Admin'))
                                       <form action="{{ route('philhealth.destroy', $contribution->id) }}" method="POST">
                                           @csrf
                                           @method('DELETE')
                                           <button type="submit" class="dropdown-item"><i class="fas fa-trash"></i>&nbsp;Delete</button>
                                       </form>
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
<!-- Modal -->
<div class="modal fade" id="createAllModal" tabindex="-1" role="dialog" aria-labelledby="createAllModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="createAllModalLabel">Create Contributions for All Active Employees</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
               </button>
           </div>
           <form action="{{ route('philhealth.store-all-active') }}" method="POST">
               @csrf
               <div class="modal-body">
                   <div class="form-group">
                       <label for="contribution_date">Contribution Date</label>
                       <input type="month" class="form-control" id="contribution_date" name="contribution_date" required>
                   </div>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                   <button type="submit" class="btn btn-primary">Create Contributions</button>
               </div>
           </form>
       </div>
   </div>
</div>

<!-- Notify Modal -->
<div class="modal fade" id="notifyModal" tabindex="-1" role="dialog" aria-labelledby="notifyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notifyModalLabel">Send PhilHealth Contribution Notifications</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('philhealth.notify') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="notification_date">Select Month and Year</label>
                        <input type="month" class="form-control" id="notification_date" name="notification_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning" id="notify-submit-btn">
                        <span class="normal-state">Send Notifications</span>
                        <span class="loading-state d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Processing...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

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
    /* Spinner button styles */
    .btn .loading-state {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn.is-loading .normal-state {
        display: none;
    }
    
    .btn.is-loading .loading-state {
        display: inline-flex !important;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script>
    $(document).ready(function () {
        // SweetAlert toast configuration
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

        // Delete confirmation
        $(document).on('click', '.dropdown-item[type="submit"]', function(e) {
            e.preventDefault();
            let form = $(this).closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Handle notify form submission with loading state
        $('#notifyModal form').on('submit', function(e) {
            // Get the submit button
            const submitBtn = $('#notify-submit-btn');
            
            // Disable the button and show loading state
            submitBtn.prop('disabled', true)
                    .addClass('is-loading');
        });
        
        // Add this to ensure the modal works correctly for the notify modal
        $('#notifyModal').on('shown.bs.modal', function () {
            $('#notification_date').trigger('focus');
        });
        
        // Reset button state if modal is closed
        $('#notifyModal').on('hidden.bs.modal', function () {
            $('#notify-submit-btn').prop('disabled', false).removeClass('is-loading');
        });

        var table = $('#philhealth-table').DataTable({
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "order": [[2, "desc"]],
            "columnDefs": [
                {
                    targets: [3, 4, 5],
                    render: function(data, type, row) {
                        if (type === 'sort' || type === 'type') {
                            return parseFloat(data.replace(/[^\d.-]/g, ''));
                        }
                        return data;
                    }
                }
            ]
        });

        $('#export-excel').on('click', function() {
            var data = table.rows().data().toArray();
            var header = ['PHILHEALTH NO.', 'Employee', 'Contribution Month', 'Employee Share', 'Employer Share', 'Total Contribution'];

            var wb = XLSX.utils.book_new();
            var ws = XLSX.utils.aoa_to_sheet([header].concat(data.map(row => [
                row[0], // PHILHEALTH NO.
                row[1], // Employee
                row[2], // Contribution Month (already in correct format)
                parseFloat(row[3].replace(/[^\d.-]/g, '')).toFixed(2), // Employee Share
                parseFloat(row[4].replace(/[^\d.-]/g, '')).toFixed(2), // Employer Share
                parseFloat(row[5].replace(/[^\d.-]/g, '')).toFixed(2)  // Total Contribution
            ])));

            // Set column widths
            ws['!cols'] = [
                {wch: 15}, // PHILHEALTH NO.
                {wch: 30}, // Employee
                {wch: 20}, // Contribution Month
                {wch: 15}, // Employee Share
                {wch: 15}, // Employer Share
                {wch: 18}  // Total Contribution
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
            var currencyStyle = Object.assign({}, dataStyle, { numFmt: "#,##0.00" });
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
                        ws[cellRef].s = C === 2 ? monthStyle : currencyStyle;
                    }
                }
            }

            XLSX.utils.book_append_sheet(wb, ws, 'philhealth Contributions');
            XLSX.writeFile(wb, 'philhealth_contributions.xlsx');
        });
    });
</script>
@endsection
