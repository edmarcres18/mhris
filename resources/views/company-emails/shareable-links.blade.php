@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">{{ __('Shareable Email Links') }}</h5>
                    <a href="{{ route('company-emails.share-form') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> {{ __('Create New Link') }}
                    </a>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center">
                            <i class="fas fa-check-circle me-2 fs-5"></i> 
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger d-flex align-items-center">
                            <i class="fas fa-exclamation-circle me-2 fs-5"></i> 
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%" class="text-center">#</th>
                                    <th width="20%">Created</th>
                                    <th width="20%">Expires</th>
                                    <th>Description</th>
                                    <th width="10%" class="text-center">Emails</th>
                                    <th width="10%" class="text-center">Status</th>
                                    <th width="15%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shareableLinks as $index => $link)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <i class="fas fa-calendar-alt me-1 text-muted"></i>
                                            {{ $link->created_at->format('M d, Y h:i A') }}
                                        </td>
                                        <td>
                                            <i class="fas fa-clock me-1 text-muted"></i>
                                            {{ $link->expires_at->format('M d, Y h:i A') }}
                                        </td>
                                        <td>{{ $link->description ?: 'No description' }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-info text-white">
                                                {{ $link->companyEmails->count() }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($link->isValid())
                                                <span class="badge bg-success">Active</span>
                                                <small class="d-block text-muted">{{ $link->remainingTimeInMinutes() }} mins left</small>
                                            @else
                                                <span class="badge bg-danger">Expired</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('company-emails.share-link', $link->token) }}" 
                                                   class="btn btn-sm btn-info text-white" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($link->isValid())
                                                    <a href="{{ route('public.shared-emails', $link->token) }}" 
                                                       class="btn btn-sm btn-success" title="Public Link" target="_blank">
                                                        <i class="fas fa-link"></i>
                                                    </a>
                                                @endif
                                                <form action="{{ route('company-emails.delete-share', $link->id) }}" method="POST" 
                                                      class="d-inline" onsubmit="return confirm('Are you sure you want to delete this link?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="fas fa-link fa-2x mb-3 d-block"></i>
                                            <p>No shareable links found</p>
                                            <a href="{{ route('company-emails.share-form') }}" class="btn btn-primary btn-sm mt-2">
                                                <i class="fas fa-plus me-1"></i> Create Your First Link
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-white py-3">
                    <a href="{{ route('company-emails.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> {{ __('Back to Company Emails') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@section('styles')
<style>
    .gap-1 {
        gap: 0.25rem;
    }
</style>
@endsection
@endsection 