@extends('layouts.app')

@section('content')
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="card-title">User Details</h2>
                    <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm ml-auto">Back</a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3 text-center">
                            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image" class="profile-img img-fluid rounded-circle mb-3" style="height: 150px; width: 150px;">
                        </div>
                        <div class="col-md-9">
                            <h4 class="text-primary">{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }} {{ $user->suffix }}</h4>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Roles:</strong>
                                @forelse($user->getRoleNames() as $role)
                                    <span class="badge badge-secondary">{{ $role }}</span>
                                @empty
                                    <span class="badge badge-secondary">No roles assigned</span>
                                @endforelse
                            </p>
                            <p><strong>Bio:</strong> {{ $user->bio }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-secondary mb-3">
                                <div class="card-header bg-secondary text-white">
                                    Personal Information
                                </div>
                                <div class="card-body">
                                    <p><strong>First Name:</strong> {{ $user->first_name }}</p>
                                    <p><strong>Middle Name:</strong> {{ $user->middle_name ?? 'N/A' }}</p>
                                    <p><strong>Last Name:</strong> {{ $user->last_name }}</p>
                                    <p><strong>Suffix:</strong> {{ $user->suffix ?: 'N/A'}}</p>
                                    <p><strong>Email:</strong> {{ $user->email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-secondary mb-3">
                                <div class="card-header bg-secondary text-white">
                                    Additional Details
                                </div>
                                <div class="card-body">
                                    <p><strong>Bio:</strong> {{ $user->bio }}</p>
                                    <p><strong>Department:</strong> {{ $user->department->name ?? 'N/A' }}</p>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
