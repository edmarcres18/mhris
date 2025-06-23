@extends('layouts.app')

@section('content')
    <br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h3 class="card-title mb-2 mb-md-0">Leave Management</h3>
                            <div class="card-tools">
                                @can('leave-create')
                                <a href="{{ route('leaves.create') }}" class="btn btn-success btn-sm rounded-pill">
                                    <i class="fas fa-plus-circle"></i>
                                    <span class="d-none d-sm-inline-block ml-1">Add Leave</span>
                                </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-12 col-md-3 mb-2 mb-md-0">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text text-primary">
                                            <i class="fas fa-filter"></i>
                                        </span>
                                    </div>
                                    <select class="form-control" id="status-filter">
                                        <option value="">All Status</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                        <option value="pending">Pending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text text-primary">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                    <select class="form-control" id="read-filter">
                                        <option value="">All Leaves</option>
                                        <option value="read">Read Leaves</option>
                                        <option value="new">Unread Leaves</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="leave-table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="min-w-200">Employee Name</th>
                                        <th class="min-w-150">Date</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Read</th>
                                        <th class="no-sort">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leaves as $leave)
                                        <tr>
                                            <td>{{ $leave->employee->company_id }} {{ $leave->employee->last_name }} {{ $leave->employee->first_name }}, {{ $leave->employee->middle_name }} {{ $leave->employee->suffix }}</td>
                                            <td><strong>From:</strong>{{ \Carbon\Carbon::parse($leave->date_from)->format('F j, Y') }}<br>
                                                <strong>To:</strong> {{ \Carbon\Carbon::parse($leave->date_to)->format('F j, Y') }}
                                            </td>
                                            <td>{{ $leave->type->name }}</td>
                                            <td style="text-align: center;">
                                                @if($leave->status == 'approved')
                                                    <i class="fas fa-check-circle" style="color: green;"></i> Approved
                                                @elseif($leave->status == 'rejected')
                                                    <i class="fas fa-times-circle" style="color: red;"></i> Rejected
                                                @else
                                                    <i class="fas fa-hourglass-half" style="color: orange;"></i> Pending
                                                @endif
                                            </td>
                                            <td>
                                                @if($leave->is_read)
                                                    <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Read</span>
                                                @else
                                                    <span class="badge badge-info"><i class="fas fa-bell mr-1"></i> New</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a class="dropdown-item" href="{{ route('leaves.show',$leave->id) }}"><i class="fas fa-eye"></i>&nbsp;Preview</a>
                                                        @canany(['HR ComBen', 'Admin', 'Super Admin'])
                                                        <a class="dropdown-item" href="{{ route('leaves.edit', $leave->id) }}">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        @endcanany
                                                        @can('leave-delete')
                                                            <form action="{{ route('leaves.destroy', $leave->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="fas fa-trash"></i>&nbsp;Delete
                                                                </button>
                                                            </form>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col-md-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
@endsection

@section('css')
<style>
    /* Responsive styles */
    .min-w-150 {
        min-width: 150px;
    }

    .min-w-200 {
        min-width: 200px;
    }

    /* Table responsive styles */
    @media (max-width: 768px) {
        .table-responsive {
            border: 0;
            margin-bottom: 0;
        }

        #leave-table {
            font-size: 0.9rem;
        }

        .dropdown-menu {
            position: fixed !important;
            top: auto !important;
            right: 0 !important;
            left: auto !important;
            transform: none !important;
            width: auto !important;
        }
    }

    /* Card and layout responsive adjustments */
    @media (max-width: 576px) {
        .card-body {
            padding: 0.75rem;
        }

        .table td, .table th {
            padding: 0.5rem;
        }

        .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    }

    /* Updated toast styles with dark mode support */
    .colored-toast {
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
    }

    /* Light mode styles */
    .colored-toast.swal2-success {
        background: rgba(40, 167, 69, 0.9) !important;
        color: #fff !important;
        box-shadow: 0 0 12px rgba(40, 167, 69, 0.4) !important;
    }

    .colored-toast.swal2-error {
        background: rgba(220, 53, 69, 0.9) !important;
        color: #fff !important;
        box-shadow: 0 0 12px rgba(220, 53, 69, 0.4) !important;
    }

    .colored-toast.swal2-info {
        background: rgba(23, 162, 184, 0.9) !important;
        color: #fff !important;
        box-shadow: 0 0 12px rgba(23, 162, 184, 0.4) !important;
    }

    /* Dark mode styles */
    @media (prefers-color-scheme: dark) {
        .swal2-modal-custom {
            background-color: #1a1a1a !important;
            color: #fff !important;
        }

        .swal2-modal-custom .swal2-title,
        .swal2-modal-custom .swal2-content {
            color: #fff !important;
        }

        .colored-toast {
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .colored-toast.swal2-success {
            background: rgba(40, 167, 69, 0.8) !important;
        }

        .colored-toast.swal2-error {
            background: rgba(220, 53, 69, 0.8) !important;
        }

        .colored-toast.swal2-info {
            background: rgba(23, 162, 184, 0.8) !important;
        }
    }
</style>
@endsection

@section('js')
    <!-- Include SweetAlert library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            // Updated toast configuration with dark mode support
            const toastConfig = {
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-right',
                iconColor: 'green',
                customClass: {
                    popup: 'colored-toast'
                }
            };

            // Success toast with dark mode support
            @if(Session::has('success'))
                Swal.fire({
                    ...toastConfig,
                    icon: 'success',
                    title: 'Success',
                    text: "{{ Session::get('success') }}",
                    customClass: {
                        popup: 'colored-toast swal2-success'
                    }
                });
            @endif

            // Error toast with dark mode support
            @if(Session::has('error'))
                Swal.fire({
                    ...toastConfig,
                    icon: 'error',
                    title: 'Error',
                    text: "{{ Session::get('error') }}",
                    customClass: {
                        popup: 'colored-toast swal2-error'
                    }
                });
            @endif

            // Initialize DataTable with responsive configuration
            var table = $('#leave-table').DataTable({
                responsive: true,
                language: {
                    emptyTable: "No leave requests available at the moment."
                },
                columnDefs: [
                    { targets: 'no-sort', orderable: false },
                    { responsivePriority: 1, targets: [0, 3, 5] }, // Priority columns
                    { responsivePriority: 2, targets: [1, 2] },
                    { responsivePriority: 3, targets: 4 }
                ],
                dom: '<"d-flex flex-column flex-md-row justify-content-between align-items-center"<"mb-2 mb-md-0"l><"d-flex"f>>rtip',
                pageLength: 10,
                ordering: true,
                autoWidth: false
            });

            // Make table responsive to window resize
            $(window).on('resize', function () {
                table.columns.adjust().responsive.recalc();
            });

            // Updated filter notifications
            $('#status-filter').on('change', function() {
                const value = this.value;
                table.column(3).search(value).draw();

                if (value) {
                    Swal.fire({
                        ...toastConfig,
                        icon: 'info',
                        title: 'Filter Applied',
                        text: `Showing ${value.charAt(0).toUpperCase() + value.slice(1)} leaves`,
                        customClass: {
                            popup: 'colored-toast swal2-info'
                        }
                    });
                }
            });

            // Updated read filter notifications
            $('#read-filter').on('change', function() {
                const value = this.value;
                const searchTerm = value === 'read' ? 'Read' : (value === 'new' ? 'New' : '');
                table.column(4).search(searchTerm).draw();

                if (value) {
                    Swal.fire({
                        ...toastConfig,
                        icon: 'info',
                        title: 'Filter Applied',
                        text: `Showing ${value === 'read' ? 'read' : 'unread'} leaves`,
                        customClass: {
                            popup: 'colored-toast swal2-info'
                        }
                    });
                }
            });

            // Updated delete confirmation with dark mode support
            $(document).on('click', '.dropdown-item[type="submit"]', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        popup: 'swal2-modal-custom'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
