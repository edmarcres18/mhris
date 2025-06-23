@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-success text-white">
                    <h2 class="mb-0"><i class="fas fa-plus-circle mr-2"></i>Create New Hiring Position</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('hirings.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="position"><i class="fas fa-briefcase mr-2"></i>Position</label>
                                    <input type="text" class="form-control" id="position" name="position" 
                                        placeholder="e.g. Senior Software Engineer" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="location"><i class="fas fa-map-marker-alt mr-2"></i>Location</label>
                                    <input type="text" class="form-control" id="location" name="location" 
                                        placeholder="e.g. New York, NY or Remote" required>
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
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employment_type"><i class="fas fa-user-clock mr-2"></i>Employment Type</label>
                                    <select class="form-control" id="employment_type" name="employment_type" required>
                                        <option value="">Select Employment Type</option>
                                        <option value="Full-time">Full-time</option>
                                        <option value="Part-time">Part-time</option>
                                        <option value="Contract">Contract</option>
                                        <option value="Temporary">Temporary</option>
                                        <option value="Internship">Internship</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description"><i class="fas fa-align-left mr-2"></i>Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" 
                                placeholder="Describe the role and what a typical day looks like..." required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="responsibilities"><i class="fas fa-tasks mr-2"></i>Responsibilities</label>
                            <textarea class="form-control" id="responsibilities" name="responsibilities" rows="4" 
                                placeholder="List the key responsibilities for this position..." required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="requirements"><i class="fas fa-list-ul mr-2"></i>Requirements</label>
                            <textarea class="form-control" id="requirements" name="requirements" rows="4" 
                                placeholder="List required skills, experience, education, and qualifications..." required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="benefits"><i class="fas fa-gift mr-2"></i>Benefits</label>
                            <textarea class="form-control" id="benefits" name="benefits" rows="4" 
                                placeholder="List company benefits, perks, and compensation details..." required></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="fas fa-plus mr-2"></i>Create Position
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
    .card-header {
        border-bottom: 0;
    }
    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }
    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
        transition: all 0.3s ease;
    }
    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection
