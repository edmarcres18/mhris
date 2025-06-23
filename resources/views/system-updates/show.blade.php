@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">System Update Details</h1>
                <div>
                    <a href="{{ route('system-updates.edit', $systemUpdate->id) }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm me-2">
                        <i class="fas fa-edit fa-sm text-white-50 me-1"></i> Edit
                    </a>
                    <a href="{{ route('system-updates.index') }}" class="d-none d-sm-inline-block btn btn-secondary shadow-sm">
                        <i class="fas fa-arrow-left fa-sm text-white-50 me-1"></i> Back to List
                    </a>
                </div>
            </div>
            
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-light p-2 rounded">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('system-updates.index') }}" class="text-decoration-none">System Updates</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file-alt me-2"></i>
                        <h6 class="m-0 font-weight-bold">Update Content</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h3 mb-0 text-primary">{{ $systemUpdate->title }}</h2>
                        <span class="badge {{ $systemUpdate->is_active ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                            <i class="fas {{ $systemUpdate->is_active ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                            {{ $systemUpdate->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <div class="mb-4 small text-muted">
                        <i class="fas fa-calendar-alt me-1"></i> Published on {{ $systemUpdate->published_at->format('F d, Y \a\t h:i A') }}
                    </div>
                    
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body bg-light rounded">
                            <div class="content fs-6">
                                {!! nl2br(e($systemUpdate->description)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-1"></i> Information
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-user-edit me-1 text-primary"></i> Author</span>
                            <span class="fw-bold">{{ $systemUpdate->author->name ?? 'Unknown' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-clock me-1 text-primary"></i> Created</span>
                            <span>{{ $systemUpdate->created_at->format('M d, Y h:i A') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-edit me-1 text-primary"></i> Last Updated</span>
                            <span>{{ $systemUpdate->updated_at->format('M d, Y h:i A') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-eye me-1 text-primary"></i> Status</span>
                            <span>
                                @if($systemUpdate->is_active)
                                    <span class="badge bg-success rounded-pill">Active</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">Inactive</span>
                                @endif
                            </span>
                        </li>
                    </ul>
                    
                    <div class="d-flex flex-column">
                        <a href="{{ route('system-updates.edit', $systemUpdate->id) }}" class="btn btn-primary btn-sm shadow-sm mb-2">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <button type="button" class="btn btn-danger btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Actions -->
            <div class="d-block d-sm-none mb-4">
                <div class="d-grid gap-2">
                    <a href="{{ route('system-updates.edit', $systemUpdate->id) }}" class="btn btn-primary shadow-sm">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('system-updates.index') }}" class="btn btn-secondary shadow-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-exclamation-triangle me-1"></i> Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this system update?</p>
                <p class="fw-bold">{{ $systemUpdate->title }}</p>
                <p class="text-danger small">
                    <i class="fas fa-exclamation-circle me-1"></i> This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('system-updates.destroy', $systemUpdate->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 