@extends('layouts.app')

@section('content')
<br>
<div class="container-fluid">
    <!-- Enhanced professional-looking link buttons -->
    <div class="mb-4">
        <div class="contribution-nav" role="navigation" aria-label="Contribution Types">
            @can('super-admin')
            <a href="{{ route('provinces.index') }}" class="contribution-link {{ request()->routeIs('provinces.index') ? 'active' : '' }}">
                <div class="icon-wrapper">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="text-wrapper">
                    <span class="title">Provinces</span>
                    <small class="description">Province List</small>
                </div>
            </a>
            @endcan
            @can('super-admin')
            <a href="{{ route('city.index') }}" class="contribution-link {{ request()->routeIs('city.index') ? 'active' : '' }}">
                <div class="icon-wrapper">
                    <i class="fas fa-city"></i> 
                </div>
                <div class="text-wrapper">
                    <span class="title">Cities</span>
                    <small class="description">City List</small>
                </div>
            </a>
            @endcan
            @can('super-admin')
            <a href="{{ route('barangay.index') }}" class="contribution-link {{ request()->routeIs('barangay.index') ? 'active' : '' }}">
                <div class="icon-wrapper">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="text-wrapper">
                    <span class="title">Barangays</span>
                    <small class="description">Barangay List</small>
                </div>
            </a>
            @endcan
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Province List</h3>
                    <div class="card-tools">
                        @can('province-create')
                        <a href="{{ route('provinces.create') }}" class="btn btn-success btn-sm rounded-pill">
                            Add Province <i class="fas fa-plus-circle"></i>
                        </a>
                        @endcan
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">{{ $message }}</div>
                    @endif
                    <table id="provinces-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($provinces as $province)
                                <tr>
                                    <td>{{ $province->id }}</td>
                                    <td>{{ $province->name }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                @can('province-edit')
                                                    <a class="dropdown-item" href="{{ route('provinces.edit',$province->id) }}"><i class="fas fa-edit"></i>&nbsp;Edit</a>
                                                @endcan
                                                @can('province-delete')
                                                    <form action="{{ route('provinces.destroy', $province->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this province?')"><i class="fas fa-trash"></i>&nbsp;Delete</button>
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
        $('#provinces-table').DataTable();
    });
</script>
@endsection
