@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Overtime Application</h5>
                </div>
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('overtime.apply') }}" id="overtimeForm">
                        @csrf

                        <!-- Employee Information -->
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="employee_name" class="form-label">Employee Name</label>
                                <input type="text" class="form-control bg-light" id="employee_name" value="{{ $employee->first_name }} {{ $employee->last_name }}" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="employee_number" class="form-label">Employee ID</label>
                                <input type="text" class="form-control bg-light" id="employee_number" value="{{ $employee->company_id }}" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="department" class="form-label">Department</label>
                                <input type="text" class="form-control bg-light" id="department" value="{{ $employee->department->name }}" readonly>
                            </div>
                        </div>

                        <!-- Overtime Date -->
                        <div class="mb-3">
                            <label for="date" class="form-label">Overtime Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date') ?? date('Y-m-d') }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Time In/Out -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="time_in" class="form-label">Time In <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('time_in') is-invalid @enderror" id="time_in" name="time_in" value="{{ old('time_in') }}" required>
                                @error('time_in')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Start time</small>
                            </div>

                            <div class="col-md-6">
                                <label for="time_out" class="form-label">Time Out <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('time_out') is-invalid @enderror" id="time_out" name="time_out" value="{{ old('time_out') }}" required>
                                @error('time_out')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">End time</small>
                            </div>
                            <div class="col-12 mt-2">
                                <div id="timeErrorMessage" class="text-danger d-none"></div>
                            </div>
                        </div>

                        <!-- Reason -->
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for Overtime <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" rows="3" placeholder="Why do you need overtime? (10-500 characters)" minlength="10" maxlength="500" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="d-flex justify-content-end mt-1">
                                <small id="reason_counter" class="text-muted">0/500</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Your request will be reviewed by your supervisor and the finance department</small>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('overtime.history') }}" class="btn btn-light me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                Submit Request
                                <span class="spinner-border spinner-border-sm d-none ms-1" id="submitSpinner" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media (max-width: 768px) {
        input[type="date"], 
        input[type="datetime-local"] {
            font-size: 16px; /* Prevent zoom on iOS */
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form elements
        const form = document.getElementById('overtimeForm');
        const dateInput = document.getElementById('date');
        const timeInInput = document.getElementById('time_in');
        const timeOutInput = document.getElementById('time_out');
        const reasonTextarea = document.getElementById('reason');
        const reasonCounter = document.getElementById('reason_counter');
        const submitBtn = document.getElementById('submitBtn');
        const submitSpinner = document.getElementById('submitSpinner');
        const timeErrorMessage = document.getElementById('timeErrorMessage');
        
        // Initialize counter
        updateCharacterCount();
        
        // Set min date for overtime date to today
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
        
        // Synchronize date field with time_in and time_out
        dateInput.addEventListener('change', updateTimeFields);
        
        // Character counter
        reasonTextarea.addEventListener('input', updateCharacterCount);
        
        // Time validation
        timeOutInput.addEventListener('change', validateTimes);
        timeInInput.addEventListener('change', function() {
            validateTimes();
            
            // Update date field to match time_in date
            if (timeInInput.value) {
                const timeInDate = new Date(timeInInput.value).toISOString().split('T')[0];
                dateInput.value = timeInDate;
            }
        });
        
        // Form submission
        form.addEventListener('submit', function(e) {
            submitBtn.setAttribute('disabled', 'disabled');
            submitSpinner.classList.remove('d-none');
            
            if (!validateTimes()) {
                e.preventDefault();
                submitBtn.removeAttribute('disabled');
                submitSpinner.classList.add('d-none');
                return false;
            }
            
            return true;
        });
        
        // Helper functions
        function updateTimeFields() {
            const selectedDate = dateInput.value;
            
            if (timeInInput.value) {
                let timeInValue = new Date(timeInInput.value);
                let newTimeIn = new Date(selectedDate);
                newTimeIn.setHours(timeInValue.getHours(), timeInValue.getMinutes());
                timeInInput.value = newTimeIn.toISOString().slice(0, 16);
            }
            
            if (timeOutInput.value) {
                let timeOutValue = new Date(timeOutInput.value);
                let newTimeOut = new Date(selectedDate);
                newTimeOut.setHours(timeOutValue.getHours(), timeOutValue.getMinutes());
                timeOutInput.value = newTimeOut.toISOString().slice(0, 16);
            }
        }
        
        function updateCharacterCount() {
            const characterCount = reasonTextarea.value.length;
            reasonCounter.textContent = characterCount + '/500';
            
            if (characterCount >= 490) {
                reasonCounter.classList.add('text-danger');
                reasonCounter.classList.remove('text-muted');
            } else {
                reasonCounter.classList.remove('text-danger');
                reasonCounter.classList.add('text-muted');
            }
        }
        
        function validateTimes() {
            if (timeInInput.value && timeOutInput.value) {
                const timeIn = new Date(timeInInput.value);
                const timeOut = new Date(timeOutInput.value);
                
                if (timeOut <= timeIn) {
                    timeErrorMessage.textContent = 'Time out must be after time in';
                    timeErrorMessage.classList.remove('d-none');
                    timeOutInput.value = '';
                    return false;
                } else {
                    timeErrorMessage.classList.add('d-none');
                    return true;
                }
            }
            return true;
        }
    });
</script>
@endpush


