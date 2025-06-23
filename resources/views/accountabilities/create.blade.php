@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0 fs-4">Create New Accountability</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('accountabilities.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="employee_id">Employee</label>
                            <select name="employee_id" id="employee_id" class="form-control select2" required>
                                <option value="">Select an employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->last_name }}, {{ $employee->first_name }} {{ $employee->middle_name ?? '' }} {{ $employee->suffix ?? '' }}
                                        ({{ $employee->company_id ?? 'No ID' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="documents">Documents</label>
                            <div class="custom-file">
                                <input type="file" name="documents[]" id="documents" class="custom-file-input" multiple>
                                <label class="custom-file-label" for="documents">Choose files</label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="it_inventories">IT Inventories</label>
                            <select name="it_inventories[]" id="it_inventories" class="form-control select2" multiple>
                                @foreach($itInventories as $inventory)
                                    <option value="{{ $inventory->id }}">
                                        {{ $inventory->name }} - <strong>Description:</strong> {{ $inventory->description ?? ' '}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary btn-lg w-100">Create Accountability</button>
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
    .custom-file-input:lang(en)~.custom-file-label::after {
        content: "Browse";
    }
    .form-control, .custom-file-label {
        border-radius: 0.25rem;
    }
    .btn-lg {
        border-radius: 0.3rem;
    }
</style>
@endpush

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
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>
@endpush
