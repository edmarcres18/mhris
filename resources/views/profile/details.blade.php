{{-- resources/views/profile/details.blade.php --}}
@extends('layouts.profile')

@push('styles')
<style>
    .profile-header {
        background: linear-gradient(to right, var(--primary-light), rgba(255,255,255,0.5));
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .profile-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .profile-placeholder {
        width: 120px;
        height: 120px;
        margin: 0 auto;
        background: linear-gradient(145deg, var(--primary-color) 0%, #3a0ca3 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        font-weight: 600;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .info-card {
        height: 100%;
        transition: all var(--transition-speed) ease;
    }

    .info-card:hover {
        transform: translateY(-5px);
    }

    .info-label {
        font-size: 0.875rem;
        color: var(--text-muted);
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 1rem;
        color: var(--text-color);
        font-weight: 500;
    }

    .stat-card {
        background: white;
        border-radius: 0.5rem;
        padding: 1rem;
        text-align: center;
        transition: all var(--transition-speed) ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .signature-img {
        max-width: 300px;
        border: 1px solid var(--border-color);
        padding: 1rem;
        border-radius: 0.5rem;
        background: white;
    }

    .badge-role {
        background: var(--primary-light);
        color: var(--primary-color);
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid fade-in">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="row align-items-center">
            <div class="col-auto">
                @if($user->profile_image)
                    <img src="{{ Storage::url($user->profile_image) }}"
                         alt="Profile"
                         class="profile-img rounded-circle">
                @else
                    <div class="profile-placeholder">
                        {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="col">
                <h1 class="h3 mb-2">{{ $user->first_name }} {{ $user->last_name }}</h1>
                <p class="text-muted mb-0">{{ $user->email }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-clock fa-2x text-primary opacity-75"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted small">Member Since</div>
                        <div class="fw-medium">{{ date('F j, Y', strtotime($user->date_hired)) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-shield-alt fa-2x text-success opacity-75"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted small">Status</div>
                        <div class="fw-medium text-success">Active</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-tag fa-2x text-primary opacity-75"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted small">Role</div>
                        <div>
                            @forelse($user->getRoleNames() as $role)
                                <span class="badge-role">{{ $role }}</span>
                            @empty
                                <span class="badge-role">No roles assigned</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Information -->
    <div class="row g-4">
        <!-- Personal Information -->
        <div class="col-md-6">
            <div class="card info-card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-user me-2 text-primary opacity-75"></i>
                        Personal Information
                    </h5>
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="info-label">First Name</div>
                            <div class="info-value">{{ $user->first_name }}</div>
                        </div>
                        @if($user->middle_name)
                        <div class="col-sm-6">
                            <div class="info-label">Middle Name</div>
                            <div class="info-value">{{ $user->middle_name }}</div>
                        </div>
                        @endif
                        <div class="col-sm-6">
                            <div class="info-label">Last Name</div>
                            <div class="info-value">{{ $user->last_name }}</div>
                        </div>
                        @if($user->suffix)
                        <div class="col-sm-6">
                            <div class="info-label">Suffix</div>
                            <div class="info-value">{{ $user->suffix }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="col-md-6">
            <div class="card info-card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-address-card me-2 text-primary opacity-75"></i>
                        Contact Information
                    </h5>
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="info-label">Email Address</div>
                            <div class="info-value">{{ $user->email }}</div>
                        </div>
                        @if($user->phone)
                        <div class="col-sm-6">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value">{{ $user->phone }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Biography -->
        <div class="col-12">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-book-open me-2 text-primary opacity-75"></i>
                        Bio
                    </h5>
                    <p class="mb-0">{{ $user->bio ?: 'No biography provided.' }}</p>
                </div>
            </div>
        </div>

        <!-- Digital Signature -->
        @if($user->signature)
        <div class="col-12">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-signature me-2 text-primary opacity-75"></i>
                        Digital Signature
                    </h5>
                    <div class="text-center">
                        <img src="{{ Storage::url($user->signature) }}"
                             alt="Signature"
                             class="signature-img">
                        <div class="text-muted small mt-2">
                            Last updated: {{ \Carbon\Carbon::parse($user->updated_at)->format('F d, Y h:i A') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
