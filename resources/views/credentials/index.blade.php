@extends('layouts.app')

@section('content')
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Credentials Accountability List</h3>
                    <div class="card-tools">
                        @can('credential-create')
                        <a href="{{ route('credentials.create') }}" class="btn btn-success btn-sm rounded-pill">
                            Add credential <i class="fas fa-plus-circle"></i>
                        </a>
                        @endcan
                        @can('credential-list')
                        <a href="{{ route('credentials.share-form') }}" class="btn btn-primary btn-sm rounded-pill ml-2">
                            Share credentials <i class="fas fa-share-alt"></i>
                        </a>
                        <a href="{{ route('credentials.shareable-links') }}" class="btn btn-info btn-sm rounded-pill ml-2">
                            My shared links <i class="fas fa-link"></i>
                        </a>
                        @endcan
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @if(Session::has('success'))
                        <input type="hidden" id="success-message" value="{{ Session::get('success') }}">
                    @endif
                    @if(Session::has('error'))
                        <input type="hidden" id="error-message" value="{{ Session::get('error') }}">
                    @endif
                    <div class="table-responsive">
                        <table id="credentials-table" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Company Number</th>
                                    <th>Company Email</th>
                                    <th>Email Password</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($credentials as $credential)
                                    <tr>
                                        <td>{{ $credential->employee->company_id }} {{ $credential->employee->last_name }} {{ $credential->employee->first_name }}, {{ $credential->employee->middle_name ?? ' ' }} {{ $credential->employee->suffix ?? ' ' }}</td>
                                        <td>{{ $credential->company_number ?? ' '}}</td>
                                        <td>{{ $credential->company_email ?? ' '}}</td>
                                        <td>{{ $credential->email_password ?? ' '}}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    @can('credential-edit')
                                                        <a class="dropdown-item" href="{{ route('credentials.edit',$credential->id) }}"><i class="fas fa-edit"></i>&nbsp;Edit</a>
                                                    @endcan
                                                    @if(Auth::user()->role == 'Super Admin')
                                                        <form action="{{ route('credentials.destroy', $credential->id) }}" method="POST" class="delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item"><i class="fas fa-trash"></i>&nbsp;Delete</button>
                                                        </form>
                                                    @endif
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
    /* Update card styling for better desktop view */
    .card {
        margin: 20px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
    }

    /* Table improvements for desktop */
    .table-responsive {
        overflow-x: auto;
        min-height: 400px;
    }

    #credentials-table {
        width: 100% !important;
    }

    #credentials-table th,
    #credentials-table td {
        white-space: nowrap;
        padding: 12px 15px;
    }

    /* Keep existing mobile styles but improve breakpoints */
    @media (max-width: 768px) {
        .card {
            margin: 10px;
        }

        .card-header {
            flex-direction: column;
            align-items: start !important;
        }

        .card-tools {
            margin-top: 10px;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .btn-success, .btn-primary, .btn-info {
            width: 100%;
            margin-top: 5px;
            margin-left: 0 !important;
        }
    }

    /* Add desktop-specific enhancements */
    @media (min-width: 769px) {
        .btn-success {
            padding: 8px 20px;
        }

        .card-tools {
            min-width: 200px;
            text-align: right;
        }

        .table-responsive {
            padding: 10px;
        }
    }

    /* Add toast styles */
    .colored-toast.swal2-icon-success {
        box-shadow: 0 0 12px rgba(40, 167, 69, 0.4) !important;
    }
    .colored-toast.swal2-icon-error {
        box-shadow: 0 0 12px rgba(220, 53, 69, 0.4) !important;
    }
</style>
@endsection

@section('js')
<script>
    $(document).ready(function () {
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

        // Success message toast
        const successMessage = $('#success-message').val();
        if (successMessage) {
            Swal.fire({
                ...toastConfig,
                icon: 'success',
                title: 'Success',
                text: successMessage,
                background: '#28a745',
                color: '#fff'
            });
        }

        // Delete confirmation
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to delete this credential. This action cannot be undone!",
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

        /* Update DataTable configuration for better desktop experience */
        $('#credentials-table').DataTable({
            responsive: true,
            scrollX: false,  // Changed to false for desktop
            autoWidth: true, // Changed to true for desktop
            pageLength: 15,  // Show more rows on desktop
            columnDefs: [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: -1 },
                { responsivePriority: 3, targets: 2 },
                { responsivePriority: 4, targets: '_all' }
            ],
            language: {
                emptyTable: "No credentials available at the moment."
            },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
        });
    });
</script>
@endsection
