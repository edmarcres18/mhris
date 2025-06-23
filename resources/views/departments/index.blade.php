@extends('layouts.app')

@section('content')
<br>
<div class="container-fluid">
    <!-- Enhanced professional-looking link buttons -->
    <div class="mb-4">
        <div class="contribution-nav" role="navigation" aria-label="Contribution Types">
            @can('super-admin', 'admin')
            <a href="{{ route('users.index') }}" class="contribution-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                <div class="icon-wrapper">
                    <i class="fas fa-users"></i>
                </div>
                <div class="text-wrapper">
                    <span class="title">Users</span>
                    <small class="description">Users List</small>
                </div>
            </a>
            @endcan
            @can('super-admin')
            <a href="{{ route('roles.index') }}" class="contribution-link {{ request()->routeIs('roles.index') ? 'active' : '' }}">
                <div class="icon-wrapper">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="text-wrapper">
                    <span class="title">Roles and Permissions</span>
                    <small class="description">Enhance Security Permissions</small>
                </div>
            </a>
            @endcan
            @can('super-admin', 'admin')
            <a href="{{ route('departments.index') }}" class="contribution-link {{ request()->routeIs('departments.index') ? 'active' : '' }}">
                <div class="icon-wrapper">
                    <i class="fas fa-sitemap"></i>
                </div>
                <div class="text-wrapper">
                    <span class="title">Departments</span>
                    <small class="description">Department List</small>
                </div>
            </a>
            @endcan
            @can('super-admin','admin')
            <a href="{{ route('positions.index') }}" class="contribution-link {{ request()->routeIs('positions.index') ? 'active' : '' }}">
                <div class="icon-wrapper">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="text-wrapper">
                    <span class="title">Positions</span>
                    <small class="description">Position List</small>
                </div>
            </a>
            @endcan
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Department List</h3>
                    <div class="card-tools">
                        @can('department-create')
                        <a href="{{ route('departments.create') }}" class="btn btn-success btn-sm rounded-pill">
                            Add Department <i class="fas fa-plus-circle"></i>
                        </a>
                        @endcan
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="departments-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Head Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $department)
                                <tr>
                                    <td>{{ $department->id }}</td>
                                    <td>{{ $department->name }}</td>
                                    <td>{{ $department->head_name }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                @can('department-edit')
                                                    <a class="dropdown-item" href="{{ route('departments.edit',$department->id) }}"><i class="fas fa-edit"></i>&nbsp;Edit</a>
                                                @endcan
                                                @can('department-delete')
                                                    <form action="{{ route('departments.destroy', $department->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item delete-department" data-department-name="{{ $department->name }}">
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

@section('js')
<script>
    $(document).ready(function () {
        $('#departments-table').DataTable();

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

        // Handle delete confirmation
        $(document).on('click', '.delete-department', function(e) {
            e.preventDefault();
            let form = $(this).closest('form');
            let departmentName = $(this).data('department-name');

            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to delete "${departmentName}"? This action cannot be undone!`,
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
    });
</script>

<style>
    /* Toast styles */
    .colored-toast.swal2-icon-success {
        box-shadow: 0 0 12px rgba(40, 167, 69, 0.4) !important;
    }
    .colored-toast.swal2-icon-error {
        box-shadow: 0 0 12px rgba(220, 53, 69, 0.4) !important;
    }

    /* Your existing contribution-nav styles can remain unchanged */
</style>
@endsection
