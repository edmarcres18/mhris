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
                    <h2 class="card-title">Role Management</h2>
                    <div class="card-tools">
                        @can('role-create')
                        <a href="{{ route('roles.create') }}" class="btn btn-success btn-sm rounded-pill">
                            Add Roles <i class="fas fa-plus-circle"></i>
                        </a>
                        @endcan
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">{{ $message }}</div>
                    @endif
                    <table id="roles-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('roles.show',$role->id) }}"><i class="fas fa-eye"></i>&nbsp;Preview</a>
                                                @can('role-edit')
                                                    <a class="dropdown-item" href="{{ route('roles.edit',$role->id) }}"><i class="fas fa-edit"></i>&nbsp;Edit</a>
                                                @endcan
                                                @can('role-delete')
                                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this role?')"><i class="fas fa-trash"></i>&nbsp;Delete</button>
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
        $('#roles-table').DataTable();
    });
</script>
@endsection
