@extends('layouts.app')

@section('content')
<br>
<div class="container-fluid">
    <!-- Enhanced professional-looking link buttons -->
    <div class="mb-4">
        <div class="contribution-nav" role="navigation" aria-label="Contribution Types">
            <a href="{{ route('accountabilities.index') }}" class="contribution-link {{ request()->routeIs('accountabilities.index') ? 'active' : '' }}">
                <div class="icon-wrapper">
                    <i class="fas fa-list"></i>
                </div>
                <div class="text-wrapper">
                    <span class="title">Accountabilities</span>
                    <small class="description">Accountability List</small>
                </div>
            </a>
            @canany(['hrcompliance', 'hrpolicy', 'admin', 'super-admin'])
            <a href="{{ route('accountabilities.create') }}" class="contribution-link {{ request()->routeIs('accountabilities.create') ? 'active' : '' }}">
                <div class="icon-wrapper">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="text-wrapper">
                    <span class="title">Create</span>
                    <small class="description">New Accountability</small>
                </div>
            </a>
            @endcanany
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Accountability Management</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">{{ $message }}</div>
                    @endif

                    <table id="accountabilities-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Employee</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($accountabilities as $accountability)
                            <tr>
                                <td>{{ $accountability->id }}</td>
                                <td>{{ $accountability->employee->last_name }}, {{ $accountability->employee->first_name }} {{ $accountability->employee->middle_name ?? '' }} {{ $accountability->employee->suffix ?? '' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('accountabilities.show', $accountability) }}"><i class="fas fa-eye"></i>&nbsp;Preview</a>
                                            @can('accountability-edit')
                                                <a class="dropdown-item" href="{{ route('accountabilities.edit', $accountability) }}"><i class="fas fa-edit"></i>&nbsp;Edit</a>
                                            @endcan
                                            @can('accountability-delete')
                                                <form action="{{ route('accountabilities.destroy', $accountability) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this accountability?')"><i class="fas fa-trash"></i>&nbsp;Delete</button>
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

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
<style>
    #accountabilities-table {
        width: 100% !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#accountabilities-table').DataTable({
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "autoWidth": true,
            "columnDefs": [
                { "width": "auto", "targets": "_all" }
            ],
            "order": [[0, "desc"]] // Sort by ID column (index 0) in descending order
        });
    });
</script>
@endpush
