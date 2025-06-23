@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <form action="{{ route('employees.update', $employee->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card card-outline card-primary shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title"><i class="fas fa-user-edit mr-2"></i>Employee Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="company_id">Company ID</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary text-white"><i class="fas fa-id-card"></i></span>
                                    </div>
                                    <input type="text" class="form-control @error('company_id') is-invalid @enderror" id="company_id" name="company_id" value="{{ old('company_id', $employee->company_id) }}" required placeholder="Enter Company ID">
                                </div>
                                @error('company_id')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var companyIdInput = document.getElementById('company_id');

                                companyIdInput.addEventListener('input', function() {
                                    // Remove any non-alphanumeric characters
                                    var currentValue = this.value.replace(/[^a-zA-Z0-9]/g, '');

                                    // Ensure the first 3-5 characters are letters
                                    var letters = currentValue.substr(0, 5).replace(/[^a-zA-Z]/g, '').toUpperCase();
                                    var numbers = currentValue.substr(letters.length).replace(/\D/g, '');

                                    // Limit letters to 5 and numbers to 10 digits
                                    letters = letters.substr(0, 5);
                                    numbers = numbers.substr(0, 11);

                                    // Combine letters and numbers
                                    var formattedValue = letters + (numbers.length > 0 ? '-' + numbers : '');

                                    // Update the input value
                                    this.value = formattedValue;

                                    // Validate the input
                                    if (letters.length >= 3 && letters.length <= 5 && numbers.length >= 8 && numbers.length <= 11) {
                                        this.setCustomValidity('');
                                    } else {
                                        this.setCustomValidity('Company ID must have 3 to 5 letters followed by 8 to 11 digits');
                                    }
                                });
                            });
                        </script>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="profile">Profile Picture</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('profile') is-invalid @enderror" id="profile" name="profile" placeholder="Upload Profile Picture">
                                    <label class="custom-file-label" for="profile">Choose file</label>
                                </div>
                                @error('profile')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="current_profile">Current Profile Picture</label>
                                <div class="text-center">
                                    @if($employee->profile)
                                        <img src="{{ asset('storage/' . $employee->profile) }}" alt="Profile Image" class="profile-img img-fluid rounded-circle mb-3" style="height: 150px; width: 150px; object-fit: cover; border: 3px solid #007bff;">
                                    @else
                                        <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Image" class="profile-img img-fluid rounded-circle mb-3" style="height: 150px; width: 150px; object-fit: cover; border: 3px solid #6c757d;">
                                    @endif
                                </div>
                                <div class="text-center">
                                    @if($employee->profile)
                                        <p class="mb-1"><small>Current: {{ basename($employee->profile) }}</small></p>
                                    @else
                                        <p class="text-muted"><small>No custom profile picture set</small></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-info shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title"><i class="fas fa-user mr-2"></i>Personal Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $employee->first_name) }}" required placeholder="Enter First Name">
                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="middle_name">Middle Name</label>
                                <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name', $employee->middle_name) }}" placeholder="Enter Middle Name">
                                @error('middle_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $employee->last_name) }}" required placeholder="Enter Last Name">
                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="suffix">Suffix</label>
                                <input type="text" class="form-control @error('suffix') is-invalid @enderror" id="suffix" name="suffix" value="{{ old('suffix', $employee->suffix) }}" placeholder="Enter Suffix">
                                @error('suffix')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email_address">Email Address</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-info text-white"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" class="form-control @error('email_address') is-invalid @enderror" id="email_address" name="email_address" value="{{ old('email_address', $employee->email_address) }}" required placeholder="Enter Email Address">
                                </div>
                                @error('email_address')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="contact_no">Contact Number</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-info text-white"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control @error('contact_no') is-invalid @enderror" id="contact_no" name="contact_no" value="{{ old('contact_no', $employee->contact_no) }}" required placeholder="Enter Contact Number">
                                </div>
                                @error('contact_no')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        var contactInput = document.getElementById('contact_no');

                                        contactInput.addEventListener('input', function() {
                                            // Remove any non-digit characters
                                            var cleanedValue = this.value.replace(/\D/g, '');

                                            // Limit to 11 digits
                                            cleanedValue = cleanedValue.slice(0, 11);

                                            // Update the input value
                                            this.value = cleanedValue;
                                        });
                                    });
                                    </script>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="birth_date">Birth Date</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-info text-white"><i class="fas fa-calendar"></i></span>
                                    </div>
                                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date', $employee->birth_date) }}" required>
                                </div>
                                @error('birth_date')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gender_id">Gender</label>
                                <select class="form-control select2 @error('gender_id') is-invalid @enderror" id="gender_id" name="gender_id" required>
                                    <option value="">Select Gender</option>
                                    @foreach($genders as $gender)
                                        <option value="{{ $gender->id }}" {{ old('gender_id', $employee->gender_id) == $gender->id ? 'selected' : '' }}>{{ $gender->name }}</option>
                                    @endforeach
                                </select>
                                @error('gender_id')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="emergency_name">Emergency Contact Name</label>
                                <input type="text" class="form-control @error('emergency_name') is-invalid @enderror" id="emergency_name" name="emergency_name" value="{{ old('emergency_name', $employee->emergency_name) }}" required placeholder="Enter Last Name">
                                @error('emergency_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="emergency_no">Contact Number</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-info text-white"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control @error('emergency_no') is-invalid @enderror" id="emergency_no" name="emergency_no" value="{{ old('emergency_no', $employee->emergency_no) }}" required placeholder="Enter Contact Number">
                                </div>
                                @error('emergency_no')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        var emergencyInput = document.getElementById('emergency_no');

                                        emergencyInput.addEventListener('input', function() {
                                            // Remove any non-digit characters
                                            var cleanedValue = this.value.replace(/\D/g, '');

                                            // Limit to 11 digits
                                            cleanedValue = cleanedValue.slice(0, 11);

                                            // Update the input value
                                            this.value = cleanedValue;
                                        });
                                    });
                                    </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-success shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title"><i class="fas fa-map-marker-alt mr-2"></i>Address Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="province_id">Province</label>
                                <select class="form-control select2 @error('province_id') is-invalid @enderror"
                                        id="province_id"
                                        name="province_id"
                                        required
                                        {{ Auth::user()->hasRole('Super Admin') ? '' : 'disabled' }}>
                                    <option value="">Select Province</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->id }}" {{ old('province_id', $employee->province_id) == $province->id ? 'selected' : '' }}>{{ $province->name }}</option>
                                    @endforeach
                                </select>
                                @error('province_id')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="city_id">City</label>
                                <select class="form-control select2 @error('city_id') is-invalid @enderror"
                                        id="city_id"
                                        name="city_id"
                                        required
                                        {{ Auth::user()->hasRole('Super Admin') ? '' : 'disabled' }}>
                                    <option value="">Select City</option>
                                    @if(old('city_id', $employee->city_id))
                                        <option value="{{ old('city_id', $employee->city_id) }}" selected>{{ $employee->city->name }}</option>
                                    @endif
                                </select>
                                @error('city_id')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="barangay_id">Barangay</label>
                                <select class="form-control select2 @error('barangay_id') is-invalid @enderror"
                                        id="barangay_id"
                                        name="barangay_id"
                                        required
                                        {{ Auth::user()->hasRole('Super Admin') ? '' : 'disabled' }}>
                                    <option value="">Select Barangay</option>
                                    @if(old('barangay_id', $employee->barangay_id))
                                        <option value="{{ old('barangay_id', $employee->barangay_id) }}" selected>{{ $employee->barangay->name }}</option>
                                    @endif
                                </select>
                                @error('barangay_id')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="zip_code">Zip Code</label>
                                <input type="text"
                                       class="form-control @error('zip_code') is-invalid @enderror"
                                       id="zip_code"
                                       name="zip_code"
                                       value="{{ old('zip_code', $employee->zip_code) }}"
                                       required
                                       {{ Auth::user()->hasRole('Super Admin') ? '' : 'disabled' }}
                                       placeholder="Enter Zip Code">
                                @error('zip_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-warning shadow-sm mb-4">
                <div class="card-header bg-warning text-white">
                    <h3 class="card-title"><i class="fas fa-briefcase mr-2"></i>Employment Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="department_id">Department</label>
                                <select class="form-control select2 @error('department_id') is-invalid @enderror"
                                        id="department_id"
                                        name="department_id"
                                        required
                                        {{ Auth::user()->hasRole('Super Admin') ? '' : 'disabled' }}>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" 
                                                {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="position_id">Position</label>
                                <select class="form-control select2 @error('position_id') is-invalid @enderror"
                                        id="position_id"
                                        name="position_id"
                                        required
                                        {{ Auth::user()->hasRole('Super Admin') ? '' : 'disabled' }}>
                                    <option value="">Select Position</option>
                                    @if(old('position_id', $employee->position_id))
                                        <option value="{{ old('position_id', $employee->position_id) }}" selected>
                                            {{ $employee->position->name }}
                                        </option>
                                    @endif
                                </select>
                                @error('position_id')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="salary">Salary</label>
                                @if(Auth::user()->hasRole(['Super Admin', 'Admin', 'Finance']) || $employee->rank != 'Managerial')
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-warning text-white">Php</span>
                                        </div>
                                        <input type="number" step="0.01" class="form-control @error('salary') is-invalid @enderror" id="salary" name="salary" value="{{ old('salary', $employee->salary) }}" required>
                                    </div>
                                    @error('salary')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @else
                                    <div class="form-control bg-light text-muted">
                                        <i class="fas fa-lock mr-2"></i>This information is confidential
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sss_no">SSS No</label>
                                <input type="text" class="form-control @error('sss_no') is-invalid @enderror" id="sss_no" name="sss_no" value="{{ old('sss_no', $employee->sss_no) }}" placeholder="Enter SSS No">
                                @error('sss_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <script>
                            document.getElementById('sss_no').addEventListener('input', function (e) {
                                let sssNumber = e.target.value.replace(/\D/g, ''); // Remove non-digit characters
                                if (sssNumber.length > 10) {
                                    sssNumber = sssNumber.substr(0, 10); // Limit to 10 digits
                                }
                                // Format: XX-XXXXXXXXX-X
                                let formattedSSS = '';
                                if (sssNumber.length > 2) {
                                    formattedSSS = sssNumber.substr(0, 2) + '-';
                                    if (sssNumber.length > 3) {
                                        formattedSSS += sssNumber.substr(2, sssNumber.length - 3) + '-';
                                    }
                                    formattedSSS += sssNumber.substr(sssNumber.length - 1);
                                } else {
                                    formattedSSS = sssNumber;
                                }
                                e.target.value = formattedSSS;
                            });
                        </script>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tin_no">TIN No</label>
                                <input type="text" class="form-control @error('tin_no') is-invalid @enderror" id="tin_no" name="tin_no" value="{{ old('tin_no', $employee->tin_no) }}" placeholder="Enter TIN No">
                                @error('tin_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="philhealth_no">PhilHealth No</label>
                                <input type="text" class="form-control @error('philhealth_no') is-invalid @enderror" id="philhealth_no" name="philhealth_no" value="{{ old('philhealth_no', $employee->philhealth_no) }}" placeholder="Enter PhilHealth No">
                                @error('philhealth_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <script>
                            document.getElementById('philhealth_no').addEventListener('input', function (e) {
                                // Remove non-numeric characters
                                let input = e.target.value.replace(/\D/g, '');

                                // Format as XXXX-XXXXXX-X (12 digits)
                                if (input.length > 12) {
                                    input = input.slice(0, 12);
                                }

                                let formatted = '';
                                for (let i = 0; i < input.length; i++) {
                                    if (i === 2 || i === 11) {
                                        formatted += '-';
                                    }
                                    formatted += input[i];
                                }

                                e.target.value = formatted;
                            });
                        </script>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="pagibig_no">Pag-IBIG No</label>
                                <input type="text" class="form-control @error('pagibig_no') is-invalid @enderror" id="pagibig_no" name="pagibig_no" value="{{ old('pagibig_no', $employee->pagibig_no) }}" placeholder="Enter Pag-IBIG No">
                                @error('pagibig_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <script>
                            document.getElementById('pagibig_no').addEventListener('input', function (e) {
                                // Remove non-numeric characters
                                let input = e.target.value.replace(/\D/g, '');

                                // Format as XXXX-XXXX-XXXX (12 digits)
                                if (input.length > 12) {
                                    input = input.slice(0, 12);
                                }

                                let formatted = '';
                                for (let i = 0; i < input.length; i++) {
                                    if (i === 4 || i === 8) {
                                        formatted += '-';
                                    }
                                    formatted += input[i];
                                }

                                e.target.value = formatted;
                            });
                        </script>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_hired">Date Hired</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-warning text-white"><i class="fas fa-calendar"></i></span>
                                    </div>
                                    <input type="date" class="form-control @error('date_hired') is-invalid @enderror" id="date_hired" name="date_hired" value="{{ old('date_hired', $employee->date_hired) }}" required>
                                </div>
                                @error('date_hired')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="employee_status">Employment Status</label>
                                <select class="form-control select2 @error('employee_status') is-invalid @enderror" id="employee_status" name="employee_status" required>
                                    <option value="Active" {{ old('employee_status', $employee->employment_status) == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Resigned" {{ old('employee_status', $employee->employment_status) == 'Resigned' ? 'selected' : '' }}>Resigned</option>
                                    <option value="Terminated" {{ old('employee_status', $employee->employment_status) == 'Terminated' ? 'selected' : '' }}>Terminated</option>
                                </select>
                                @error('employee_status')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="rank">Rank</label>
                            @if(Auth::user()->hasRole(['Super Admin', 'Admin', 'Finance']) || $employee->rank != 'Managerial')
                                <select class="form-control select2 @error('rank') is-invalid @enderror" id="rank" name="rank" required>
                                    <option value="Rank File" {{ old('rank', $employee->rank) == 'Rank File' ? 'selected' : '' }}>Rank and File</option>
                                    <option value="Managerial" {{ old('rank', $employee->rank) == 'Managerial' ? 'selected' : '' }}>Managerial</option>
                                </select>
                                @error('rank')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @else
                                <div class="form-control bg-light text-muted">
                                    <i class="fas fa-lock mr-2"></i>Only Admin, Super Admin, and Finance can change this
                                </div>
                                <input type="hidden" name="rank" value="Managerial">
                            @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 text-left">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <span class="btn-text">Update Employee</span>
                            </button>
                            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
</div>
@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
<style>
/* Enhanced Card Styling */
.card {
    transition: all 0.3s ease;
    border: none;
    margin-bottom: 1.5rem;
}

.card-outline {
    border-top: 3px solid;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
}

.card-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.card-header .card-title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.card-header .card-title i {
    margin-right: 0.75rem;
    font-size: 1.2rem;
}

.card-body {
    padding: 1.5rem;
}

/* Form Elements Enhancement */
.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.form-control {
    border-radius: 0.375rem;
    border: 1px solid #dce4ec;
    padding: 0.625rem 0.875rem;
    transition: all 0.2s ease;
    height: calc(2.5rem + 2px);
}

.form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52,152,219,0.25);
}

.input-group-text {
    min-width: 45px;
    justify-content: center;
    background-color: #f8f9fa;
    border: 1px solid #dce4ec;
}

/* Select2 Customization */
.select2-container--bootstrap4 .select2-selection {
    height: calc(2.5rem + 2px) !important;
    border: 1px solid #dce4ec;
    border-radius: 0.375rem;
}

.select2-container--bootstrap4 .select2-selection__rendered {
    line-height: calc(2.5rem + 2px) !important;
    padding-left: 0.875rem;
}

.select2-container--bootstrap4 .select2-selection__arrow {
    height: calc(2.5rem + 2px) !important;
}

/* Profile Image Section */
.profile-img-container {
    position: relative;
    width: 150px;
    height: 150px;
    margin: 0 auto;
}

.profile-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #3498db;
    transition: all 0.3s ease;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }

    .row {
        margin-left: -0.5rem;
        margin-right: -0.5rem;
    }

    [class*="col-"] {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }
}

/* Button Styling */
.btn {
    padding: 0.625rem 1.25rem;
    font-weight: 500;
    border-radius: 0.375rem;
    transition: all 0.2s ease;
}

.btn-primary {
    background-color: #3498db;
    border-color: #3498db;
}

.btn-primary:hover {
    background-color: #2980b9;
    border-color: #2980b9;
    transform: translateY(-1px);
}

/* Error States */
.is-invalid {
    border-color: #e74c3c !important;
}

.invalid-feedback {
    color: #e74c3c;
    font-size: 0.85rem;
    margin-top: 0.25rem;
}

/* Custom File Input */
.custom-file-label {
    height: calc(2.5rem + 2px);
    padding: 0.625rem 0.875rem;
    border-radius: 0.375rem;
}

.custom-file-label::after {
    height: calc(2.5rem + 2px);
    padding: 0.625rem 0.875rem;
    background-color: #f8f9fa;
    border-left: 1px solid #dce4ec;
}

/* Grid System Improvements */
.row + .row {
    margin-top: 1rem;
}

/* Typography */
input:not([type="file"]) {
    text-transform: capitalize;
}

/* Accessibility */
.form-control:focus,
.btn:focus,
.custom-file-input:focus ~ .custom-file-label {
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(52,152,219,0.25);
}
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 for all select elements
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

        // Province change event
        $('#province_id').on('change', function() {
            var provinceId = this.value;
            var cityDropdown = $('#city_id');
            cityDropdown.empty().append('<option value="">Select city</option>');

            @foreach($city as $city)
                if ("{{ $city->province_id }}" == provinceId) {
                    cityDropdown.append('<option value="{{ $city->id }}">{{ $city->name }}</option>');
                }
            @endforeach

            cityDropdown.trigger('change'); // Refresh Select2
        });

        // City change event
        $('#city_id').on('change', function() {
            var cityId = this.value;
            var barangayDropdown = $('#barangay_id');
            barangayDropdown.empty().append('<option value="">Select barangay</option>');

            @foreach($barangay as $barangay)
                if ("{{ $barangay->city_id }}" == cityId) {
                    barangayDropdown.append('<option value="{{ $barangay->id }}">{{ $barangay->name }}</option>');
                }
            @endforeach

            barangayDropdown.trigger('change'); // Refresh Select2
        });

        // Department change event
        $('#department_id').on('change', function() {
            var departmentId = this.value;
            var positionDropdown = $('#position_id');
            positionDropdown.empty().append('<option value="">Select Position</option>');

            @foreach($positions as $position)
                if ("{{ $position->department_id }}" == departmentId) {
                    positionDropdown.append('<option value="{{ $position->id }}">{{ $position->name }}</option>');
                }
            @endforeach

            positionDropdown.trigger('change');
        });

        // Ensure department and position are properly set on page load
        var savedDepartmentId = '{{ old('department_id', $employee->department_id) }}';
        var savedPositionId = '{{ old('position_id', $employee->position_id) }}';

        // Set department and trigger change event
        if (savedDepartmentId) {
            $('#department_id')
                .val(savedDepartmentId)
                .trigger('change');

            // Wait for positions to load, then set the saved position
            setTimeout(function() {
                $('#position_id')
                    .val(savedPositionId)
                    .trigger('change');
            }, 100);
        }

        // If fields are disabled, ensure the hidden input fields exist
        if ($('#department_id').is(':disabled')) {
            $('<input>')
                .attr({
                    type: 'hidden',
                    name: 'department_id',
                    value: savedDepartmentId
                })
                .appendTo('form');
        }

        if ($('#position_id').is(':disabled')) {
            $('<input>')
                .attr({
                    type: 'hidden',
                    name: 'position_id',
                    value: savedPositionId
                })
                .appendTo('form');
        }

        // Form submission handler
        $('form').on('submit', function() {
            const submitBtn = $('#submitBtn');
            const spinner = submitBtn.find('.spinner-border');
            const btnText = submitBtn.find('.btn-text');
            
            // Disable the button and show spinner
            submitBtn.prop('disabled', true);
            spinner.removeClass('d-none');
            btnText.text('Updating...');
        });
    });
</script>
@stop

@push('js')
<script>
    $(document).ready(function() {
        // Preview new profile image
        $('#profile').on('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.profile-img').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        // Remove profile image
        $('#remove_profile_btn').on('click', function() {
            if (confirm('Are you sure you want to remove the profile picture?')) {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'remove_profile',
                    value: '1'
                }).appendTo('form');
                $('.profile-img').attr('src', '{{ asset('images/default-profile.png') }}');
                $(this).parent().html('<p class="text-muted"><small>Profile picture will be removed upon saving</small></p>');
            }
        });
    });
</script>
@endpush