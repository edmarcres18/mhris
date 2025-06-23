@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h3 class="card-title m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line mr-2"></i>Shared Credential Link Tracking
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('credentials.shareable-links') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Shared Links
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info bg-light border-left border-info mb-4">
                        <div class="d-flex">
                            <div class="mr-3">
                                <i class="fas fa-info-circle text-info fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="alert-heading">Link Information</h5>
                                <p class="mb-1"><strong>Description:</strong> {{ $shareableLink->description ?: 'No description' }}</p>
                                <p class="mb-1"><strong>Created:</strong> {{ $shareableLink->created_at->format('M d, Y h:i A') }}</p>
                                <p class="mb-1"><strong>Expires:</strong> {{ $shareableLink->expires_at->format('M d, Y h:i A') }}</p>
                                <p class="mb-1"><strong>Status:</strong> 
                                    @if($shareableLink->isActive())
                                        <span class="badge badge-success px-2 py-1">Active</span>
                                        <small class="text-muted ml-1">({{ $shareableLink->remainingTimeInMinutes() }} minutes remaining)</small>
                                    @else
                                        <span class="badge badge-danger px-2 py-1">Expired</span>
                                    @endif
                                </p>
                                <p class="mb-0"><strong>Shared Credentials:</strong> {{ $shareableLink->credentials->count() }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <h5 class="font-weight-bold mb-3">
                        <i class="fas fa-eye mr-2"></i>View History
                        <span class="badge badge-primary ml-2">{{ $shareableLink->views->count() }} Views</span>
                    </h5>
                    
                    <div class="table-responsive">
                        <table id="tracking-table" class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Timestamp</th>
                                    <th>Viewer</th>
                                    <th>Authentication</th>
                                    <th>IP Address</th>
                                    <th>Device Info</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shareableLink->views->sortByDesc('created_at') as $view)
                                    <tr>
                                        <td>
                                            <div class="font-weight-medium">{{ $view->created_at->format('M d, Y h:i:s A') }}</div>
                                            <small class="text-muted">{{ $view->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            @if($view->email)
                                                <div class="d-flex align-items-center">
                                                    <div class="mr-2">
                                                        <i class="fas fa-user-circle fa-lg text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div>{{ $view->email }}</div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted"><i class="fas fa-user-secret mr-1"></i> Anonymous</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($view->is_authenticated)
                                                <span class="badge badge-success px-2 py-1">
                                                    <i class="fas fa-user-shield mr-1"></i> Authenticated
                                                </span>
                                                <small class="d-block mt-1">via {{ ucfirst($view->auth_provider) }}</small>
                                            @else
                                                <span class="badge badge-warning px-2 py-1">
                                                    <i class="fas fa-eye-slash mr-1"></i> Limited View
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <code>{{ $view->ip_address }}</code>
                                        </td>
                                        <td>
                                            <small class="text-muted device-info">{{ $view->user_agent }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-chart-pie fa-2x mb-2 d-block"></i>
                                            No view history available for this shared link yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($shareableLink->views->isNotEmpty())
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-white">
                                        <h5 class="card-title m-0"><i class="fas fa-chart-pie mr-2"></i>Authentication Stats</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-around">
                                            <div class="text-center">
                                                <div class="h4 font-weight-bold text-success">
                                                    {{ $shareableLink->views->where('is_authenticated', true)->count() }}
                                                </div>
                                                <div>Authenticated</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="h4 font-weight-bold text-warning">
                                                    {{ $shareableLink->views->where('is_authenticated', false)->count() }}
                                                </div>
                                                <div>Limited View</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="h4 font-weight-bold text-info">
                                                    {{ $shareableLink->views->count() }}
                                                </div>
                                                <div>Total Views</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-white">
                                        <h5 class="card-title m-0"><i class="fas fa-users mr-2"></i>Unique Viewers</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="unique-viewers">
                                            @php
                                                $uniqueEmails = $shareableLink->views->whereNotNull('email')->pluck('email')->unique();
                                                $anonymousCount = $shareableLink->views->whereNull('email')->count();
                                            @endphp
                                            
                                            @forelse($uniqueEmails as $email)
                                                <div class="badge badge-light p-2 m-1">
                                                    <i class="fas fa-user text-primary mr-1"></i> {{ $email }}
                                                </div>
                                            @empty
                                                @if($anonymousCount == 0)
                                                    <div class="text-muted text-center py-2">
                                                        No viewers yet
                                                    </div>
                                                @endif
                                            @endforelse
                                            
                                            @if($anonymousCount > 0)
                                                <div class="badge badge-secondary p-2 m-1">
                                                    <i class="fas fa-user-secret mr-1"></i> {{ $anonymousCount }} anonymous view(s)
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .border-left {
        border-left: 4px solid;
    }
    
    .border-info {
        border-left-color: #17a2b8 !important;
    }
    
    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    
    .device-info {
        display: block;
        max-width: 250px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .table-responsive {
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0,0,0,0.02);
    }
    
    .unique-viewers {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#tracking-table').DataTable({
            responsive: true,
            order: [[0, 'desc']], // Sort by timestamp desc
            pageLength: 10,
            language: {
                emptyTable: "No view history available for this shared link yet."
            }
        });
    });
</script>
@endsection 