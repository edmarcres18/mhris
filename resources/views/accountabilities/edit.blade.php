@extends('layouts.app')

@section('title', 'Edit Accountability')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Edit Accountability</h1>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('accountabilities.update', $accountability) }}" method="POST" enctype="multipart/form-data" id="editAccountabilityForm">
                @csrf
                @method('PUT')

                <div class="section">
                    <h2 class="section-title">Employee Information</h2>
                    <div class="info-block">
                        <div class="form-group mb-3">
                            <label for="employee_id" class="info-label">Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" id="employee_id" class="form-control select2" required>
                                <option value="">Select an employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id', $accountability->employee_id) == $employee->id ? 'selected' : '' }}>
                                        ({{ $employee->company_id ?? 'No ID' }}) {{ $employee->first_name }} {{ $employee->last_name }}, {{ $employee->middle_name ?? ' ' }} {{ $employee->suffix ?? ' ' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <h2 class="section-title">Documents</h2>
                    <div class="info-block">
                        <div class="form-group mb-3">
                            <div class="custom-file">
                                <input type="file" name="documents[]" id="documents" class="custom-file-input" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <label class="custom-file-label" for="documents">Choose files</label>
                            </div>
                            <small class="form-text text-muted">Allowed file types: PDF, DOC, DOCX, JPG, JPEG, PNG. Max file size: 5MB each.</small>

                            @if(is_object($accountability->documents) && $accountability->documents->isNotEmpty())
                                <div class="mt-3">
                                    <h6 class="mb-2">Current Documents:</h6>
                                    <ul class="document-list">
                                        @foreach($accountability->documents as $document)
                                            <li class="document-item d-flex justify-content-between align-items-center">
                                                <a href="{{ $document->url }}" target="_blank" class="document-link">
                                                    <i class="fas fa-file-alt document-icon"></i>
                                                    {{ $document->original_name }}
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm delete-document" data-document-id="{{ $document->id }}">Delete</button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="section">
                    <h2 class="section-title">IT Inventories</h2>
                    <div class="info-block">
                        <div class="form-group mb-3">
                            <select name="it_inventories[]" id="it_inventories" class="form-control select2" multiple>
                                @foreach($itInventories as $inventory)
                                    <option value="{{ $inventory->id }}" {{ in_array($inventory->id, old('it_inventories', $accountability->itInventories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $inventory->name }} ({{ $inventory->description }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <h2 class="section-title">Additional Information</h2>
                    <div class="info-block">
                        <div class="form-group mb-3">
                            <label for="notes" class="info-label">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $accountability->notes) }}</textarea>
                        </div>

                        <div class="form-group mb-0">
                            <label for="status" class="info-label">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="active" {{ old('status', $accountability->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $accountability->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update Accountability</button>
                    <a href="{{ route('accountabilities.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
// ... copy all styles from show.blade.php ...
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select option(s)',
            allowClear: true,
            width: '100%'
        });

        $('.custom-file-input').on('change', function() {
            let fileNames = Array.from(this.files).map(file => file.name).join(', ');
            $(this).next('.custom-file-label').addClass("selected").html(fileNames || 'Choose files');
        });

        $('#editAccountabilityForm').on('submit', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to update this accountability?')) {
                this.submit();
            }
        });
    });
</script>
@endpush
