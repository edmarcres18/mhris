@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="fas fa-briefcase mr-2"></i>{{ $hiring->position }}</h2>
                    <div>
                        <a href="{{ route('hirings.edit', $hiring->id) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('hirings.destroy', $hiring->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this position?')">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4><i class="fas fa-map-marker-alt mr-2 text-primary"></i>Location</h4>
                            <p class="lead">{{ $hiring->location }}</p>
                        </div>
                        <div class="col-md-6">
                            <h4><i class="fas fa-building mr-2 text-primary"></i>Department</h4>
                            <p class="lead">{{ $hiring->department->name ?? 'Not Assigned' }}</p>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4><i class="fas fa-user-clock mr-2 text-primary"></i>Employment Type</h4>
                            <p class="lead">{{ $hiring->employment_type ?? 'Not Specified' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h4><i class="fas fa-link mr-2 text-primary"></i>Job URL</h4>
                            <p class="lead">
                                <a href="{{ url('careers/jobs/' . $hiring->slug) }}" target="_blank">
                                    {{ url('careers/jobs/' . $hiring->slug) }}
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="section mb-4">
                        <h4><i class="fas fa-align-left mr-2 text-primary"></i>Description</h4>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($hiring->description)) !!}
                        </div>
                    </div>
                    <div class="section mb-4">
                        <h4><i class="fas fa-tasks mr-2 text-primary"></i>Responsibilities</h4>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($hiring->responsibilities)) !!}
                        </div>
                    </div>
                    <div class="section mb-4">
                        <h4><i class="fas fa-list-ul mr-2 text-primary"></i>Requirements</h4>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($hiring->requirements)) !!}
                        </div>
                    </div>
                    <div class="section">
                        <h4><i class="fas fa-gift mr-2 text-primary"></i>Benefits</h4>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($hiring->benefits)) !!}
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('hirings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Positions
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card-header {
        border-bottom: 0;
    }
    .bg-light {
        background-color: #f8f9fc !important;
    }
    .text-primary {
        color: #4e73df !important;
    }
    h4 {
        font-size: 1.1rem;
        font-weight: 600;
    }
    .lead {
        font-size: 1.1rem;
    }
    .section {
        margin-bottom: 1.5rem;
    }
    .btn-light {
        background-color: white;
        border-color: #d1d3e2;
    }
    .btn-light:hover {
        background-color: #f8f9fc;
        border-color: #d1d3e2;
    }
</style>
@endsection
