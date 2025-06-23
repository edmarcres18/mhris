@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-primary text-white">
                    <h2 class="mb-0"><i class="fas fa-edit mr-2"></i>Edit Hiring Position</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('hirings.update', $hiring->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="position"><i class="fas fa-briefcase mr-2"></i>Position</label>
                                    <input type="text" class="form-control" id="position" name="position" value="{{ $hiring->position }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="location"><i class="fas fa-map-marker-alt mr-2"></i>Location</label>
                                    <input type="text" class="form-control" id="location" name="location" value="{{ $hiring->location }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="department_id"><i class="fas fa-building mr-2"></i>Department</label>
                                    <select class="form-control" id="department_id" name="department_id" required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ $hiring->department_id == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employment_type"><i class="fas fa-user-clock mr-2"></i>Employment Type</label>
                                    <select class="form-control" id="employment_type" name="employment_type" required>
                                        <option value="">Select Employment Type</option>
                                        <option value="Full-time" {{ $hiring->employment_type == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                                        <option value="Part-time" {{ $hiring->employment_type == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                                        <option value="Contract" {{ $hiring->employment_type == 'Contract' ? 'selected' : '' }}>Contract</option>
                                        <option value="Temporary" {{ $hiring->employment_type == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                                        <option value="Internship" {{ $hiring->employment_type == 'Internship' ? 'selected' : '' }}>Internship</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description"><i class="fas fa-align-left mr-2"></i>Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required>{{ $hiring->description }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="responsibilities"><i class="fas fa-tasks mr-2"></i>Responsibilities</label>
                            <textarea class="form-control" id="responsibilities" name="responsibilities" rows="4" required>{{ $hiring->responsibilities }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="requirements"><i class="fas fa-list-ul mr-2"></i>Requirements</label>
                            <textarea class="form-control" id="requirements" name="requirements" rows="4" required>{{ $hiring->requirements }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="benefits"><i class="fas fa-gift mr-2"></i>Benefits</label>
                            <textarea class="form-control" id="benefits" name="benefits" rows="4" required>{{ $hiring->benefits }}</textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-save mr-2"></i>Update Position
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 15px;
        overflow: hidden;
    }
    .card-header {
        border-bottom: 0;
    }
    .form-control {
        border-radius: 10px;
    }
    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection
