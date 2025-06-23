@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">System Updates</h1>
                <a href="{{ route('system-updates.create') }}" class="d-none d-sm-inline-block btn btn-success shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Create New Update
                </a>
            </div>
            
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-light p-2 rounded">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">System Updates</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle mr-1"></i> {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-sync-alt me-2"></i>
                        <h6 class="m-0 font-weight-bold">System Updates Management</h6>
                        <a class="btn btn-light btn-sm ms-auto d-block d-sm-none" href="{{ route('system-updates.create') }}">
                            <i class="fas fa-plus"></i> Create
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="table-light">
                                <tr>
                                    <th width="30%">Title</th>
                                    <th>Description</th>
                                    <th width="15%">Published At</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Author</th>
                                    <th width="12%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($updates as $update)
                                <tr>
                                    <td class="fw-semibold">{{ $update->title }}</td>
                                    <td>{{ Str::limit($update->description, 50) }}</td>
                                    <td><span class="text-muted"><i class="far fa-clock me-1"></i> {{ $update->published_at->format('M d, Y h:i A') }}</span></td>
                                    <td class="text-center">
                                        @if($update->is_active)
                                            <span class="badge bg-success rounded-pill"><i class="fas fa-check-circle me-1"></i> Active</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill"><i class="fas fa-times-circle me-1"></i> Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-circle me-1 text-primary"></i>
                                            <span>{{ $update->author->first_name ?? 'Unknown' }} {{ $update->author->last_name ?? 'Unknown' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-info btn-sm" href="{{ route('system-updates.show', $update->id) }}" data-bs-toggle="tooltip" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a class="btn btn-primary btn-sm" href="{{ route('system-updates.edit', $update->id) }}" data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('system-updates.destroy', $update->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this update?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle me-1"></i> No system updates found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $updates->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            responsive: true,
            "order": [[ 2, "desc" ]],
            "pageLength": 25,
            "searching": true,
            "dom": '<"top"f>rt<"bottom"ip><"clear">',
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search updates...",
                "emptyTable": "No system updates available",
                "zeroRecords": "No matching records found"
            },
            "initComplete": function() {
                $('.dataTables_filter input').addClass('form-control form-control-sm');
            }
        });
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endsection 