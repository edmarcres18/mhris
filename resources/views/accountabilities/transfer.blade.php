@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0 fs-4">Transfer Accountability</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('accountabilities.process-transfer', $accountability) }}" method="POST">
                        @csrf
                        
                        <!-- Current Employee Section -->
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Current Employee</label>
                            <input type="text" class="form-control bg-light" 
                                value="{{ $accountability->employee->first_name }} {{ $accountability->employee->last_name }}" 
                                readonly>
                        </div>
                        
                        <!-- New Employee Selection -->
                        <div class="form-group mb-3">
                            <label for="new_employee_id" class="form-label fw-bold">Transfer To</label>
                            <select name="new_employee_id" id="new_employee_id" class="form-control select2 @error('new_employee_id') is-invalid @enderror" required>
                                <option value="">Select new employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('new_employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->last_name }}, {{ $employee->first_name }} {{ $employee->middle_name ?? '' }} {{ $employee->suffix ?? '' }}
                                        ({{ $employee->company_id ?? 'No ID' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('new_employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Items to Transfer Section -->
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Items to be Transferred</label>
                            <div class="card bg-light">
                                <div class="card-body p-3">
                                    @forelse($accountability->itInventories as $inventory)
                                        <div class="mb-2 d-flex align-items-center">
                                            <i class="fas fa-laptop me-2 text-primary"></i>
                                            <span>{{ $inventory->name }} - {{ $inventory->serial_number }}</span>
                                        </div>
                                    @empty
                                        <div class="text-muted">No items to transfer</div>
                                    @endforelse
                                </div>
                            </div>
                            <small class="text-muted mt-1 d-block">
                                Note: All listed items will be transferred to the new employee.
                            </small>
                        </div>

                        <!-- Transfer Notes -->
                        <div class="form-group mb-4">
                            <label for="transfer_notes" class="form-label fw-bold">Transfer Notes</label>
                            <textarea name="transfer_notes" id="transfer_notes" 
                                class="form-control @error('transfer_notes') is-invalid @enderror" 
                                rows="3" 
                                placeholder="Enter any additional notes about this transfer">{{ old('transfer_notes') }}</textarea>
                            @error('transfer_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="form-group mb-0 d-flex gap-2">
                            <a href="{{ route('accountabilities.show', $accountability) }}" class="btn btn-secondary btn-lg flex-grow-1">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                                Process Transfer
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .card {
        border: none;
        border-radius: 0.5rem;
    }
    .card-header {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
    .select2-container--bootstrap-5 .select2-selection {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }
    .form-control {
        border-radius: 0.25rem;
    }
    .btn-lg {
        border-radius: 0.3rem;
    }
    .form-label {
        margin-bottom: 0.5rem;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select an employee',
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush 