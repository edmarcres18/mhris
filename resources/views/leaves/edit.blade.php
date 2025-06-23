@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 48px;
        padding: 0.5rem 1rem;
        font-size: 1rem;
        border-radius: 0.375rem;
    }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        padding: 0.25rem 0;
        line-height: 1.5;
    }
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #344767;
    }
    .card {
        border: 0;
        box-shadow: 0 20px 27px 0 rgb(0 0 0 / 5%);
        border-radius: 1rem;
    }
    .card-header {
        background: linear-gradient(87deg, #11cdef 0, #1171ef 100%);
        border-radius: 1rem 1rem 0 0 !important;
        padding: 1.5rem 2rem;
    }
    .input-group-text {
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-right: 0;
        color: #8898aa;
    }
    .form-control, .form-select {
        border: 1px solid #dee2e6;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        border-radius: 0.5rem;
        box-shadow: 0 3px 2px rgb(233 236 239 / 05%);
        transition: all .2s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #11cdef;
        box-shadow: 0 0 0 2px rgb(17 205 239 / 25%);
    }
    .input-group .form-control {
        border-left: 0;
    }
    .input-group-text i {
        color: #677788;
    }
    .btn-lg {
        padding: 0.875rem 1.5rem;
        font-weight: 600;
        letter-spacing: 0.025em;
        text-transform: uppercase;
        font-size: 0.875rem;
        margin: 0 0.25rem;
    }
    .btn-primary {
        background: linear-gradient(87deg, #11cdef 0, #1171ef 100%);
        border: 0;
        box-shadow: 0 4px 6px rgb(50 50 93 / 11%), 0 1px 3px rgb(0 0 0 / 8%);
    }
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 7px 14px rgb(50 50 93 / 10%), 0 3px 6px rgb(0 0 0 / 8%);
    }
    .btn-light {
        background: #fff;
        border: 1px solid #dee2e6;
        color: #8898aa;
    }
    .btn-light:hover {
        background: #f6f9fc;
        color: #677788;
    }
    .card-body {
        padding: 2rem;
    }
    .row {
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header py-3">
            <h4 class="mb-0 text-white">Edit Leave Request</h4>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('leaves.update', $leave->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="employee_id" class="form-label">Employee Name</label>
                        <select name="employee_id" id="employee_id" class="form-select select2 @error('employee_id') is-invalid @enderror" required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ $leave->employee_id == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="leave_type" class="form-label">Leave Type</label>
                        <select name="leave_type" id="leave_type" class="form-select select2 @error('leave_type') is-invalid @enderror" required>
                            <option value="Leave" {{ $leave->leave_type == 'Leave' ? 'selected' : '' }}>Leave</option>
                            <option value="Undertime" {{ $leave->leave_type == 'Undertime' ? 'selected' : '' }}>Undertime</option>
                        </select>
                        @error('leave_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="date_from" class="form-label">Date From</label>
                        <div class="input-group">
                            <span class="input-group-text rounded-start"><i class="fas fa-calendar"></i></span>
                            <input type="date" class="form-control @error('date_from') is-invalid @enderror"
                                   id="date_from" name="date_from" value="{{ old('date_from', $leave->date_from) }}" required>
                            @error('date_from')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="date_to" class="form-label">Date To</label>
                        <div class="input-group">
                            <span class="input-group-text rounded-start"><i class="fas fa-calendar"></i></span>
                            <input type="date" class="form-control @error('date_to') is-invalid @enderror"
                                   id="date_to" name="date_to" value="{{ old('date_to', $leave->date_to) }}" required>
                            @error('date_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="type_id" class="form-label">Leave Category</label>
                        <select name="type_id" id="type_id" class="form-select select2 @error('type_id') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" {{ $leave->type_id == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="reason_to_leave" class="form-label">Reason for Leave</label>
                    <textarea class="form-control @error('reason_to_leave') is-invalid @enderror"
                              id="reason_to_leave" name="reason_to_leave" rows="4" required
                              placeholder="Please provide detailed reason for your leave request">{{ old('reason_to_leave', $leave->reason_to_leave) }}</textarea>
                    @error('reason_to_leave')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('leaves.index') }}" class="btn btn-light btn-lg me-3">
                        <i class="fas fa-times me-2"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i> Update Leave Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 with Bootstrap 5 theme
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: $(this).data('placeholder'),
        });

        // Date validation
        document.getElementById('date_to').addEventListener('change', function() {
            const dateFrom = document.getElementById('date_from').value;
            const dateTo = this.value;

            if (dateFrom && dateTo && dateFrom > dateTo) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Date Range',
                    text: 'End date cannot be before start date',
                });
                this.value = dateFrom;
            }
        });
    });
</script>
@endpush
