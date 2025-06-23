@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Controller Analysis Report</h3>
                    <div class="card-tools">
                        <div class="btn-group">
                            <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-download"></i> Download
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ route('controller.analysis.pdf') }}" class="dropdown-item">
                                    <i class="fas fa-file-pdf text-danger"></i> PDF
                                </a>
                                <a href="{{ route('controller.analysis.excel') }}" class="dropdown-item">
                                    <i class="fas fa-file-excel text-success"></i> Excel
                                </a>
                                <a href="{{ route('controller.analysis.word') }}" class="dropdown-item">
                                    <i class="fas fa-file-word text-primary"></i> Word
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Summary Cards -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ count($controllerData) }}</h3>
                                    <p>Total Controllers</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-code"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ collect($controllerData)->sum(function($controller) {
                                        return $controller['metrics']['methods_count'];
                                    }) }}</h3>
                                    <p>Total Methods</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-functions"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ collect($controllerData)->sum(function($controller) {
                                        return $controller['metrics']['complexity'];
                                    }) }}</h3>
                                    <p>Total Complexity</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ collect($controllerData)->sum(function($controller) {
                                        return $controller['metrics']['code_lines'];
                                    }) }}</h3>
                                    <p>Total Lines of Code</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-file-code"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Controllers Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Controller</th>
                                    <th>Methods</th>
                                    <th>Complexity</th>
                                    <th>Lines</th>
                                    <th>Size</th>
                                    <th>Last Modified</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($controllerData as $controller)
                                <tr>
                                    <td>{{ $controller['name'] }}</td>
                                    <td>{{ $controller['metrics']['methods_count'] }}</td>
                                    <td>
                                        <span class="badge badge-{{ $controller['metrics']['complexity'] > 20 ? 'danger' : ($controller['metrics']['complexity'] > 10 ? 'warning' : 'success') }}">
                                            {{ $controller['metrics']['complexity'] }}
                                        </span>
                                    </td>
                                    <td>{{ $controller['metrics']['total_lines'] }}</td>
                                    <td>{{ $controller['file_size'] }} KB</td>
                                    <td>{{ $controller['last_modified'] }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#controllerModal{{ $loop->index }}">
                                            Details
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Controller Detail Modals -->
@foreach($controllerData as $index => $controller)
<div class="modal fade" id="controllerModal{{ $index }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $controller['name'] }} Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Methods Analysis -->
                <h6>Methods Analysis</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Method</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($controller['methods'] as $method)
                            <tr>
                                <td>{{ $method['name'] }}</td>
                                <td>{{ $method['type'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Permissions -->
                <h6>Permissions</h6>
                <div class="mb-3">
                    @forelse($controller['permissions'] as $permission)
                        <span class="badge badge-info">{{ $permission }}</span>
                    @empty
                        <span class="text-muted">No permissions specified</span>
                    @endforelse
                </div>

                <!-- Dependencies -->
                <h6>Dependencies</h6>
                <div class="mb-3">
                    @forelse($controller['dependencies'] as $dependency)
                        <div class="badge badge-secondary">{{ $dependency }}</div>
                    @empty
                        <span class="text-muted">No dependencies found</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.table').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true
    });
});
</script>
@endpush
