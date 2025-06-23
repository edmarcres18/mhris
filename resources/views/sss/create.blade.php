@extends('layouts.app')

@section('content')
<br>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">SSS Contribution Form</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('sss.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="employee_id">Employee</label>
                    <select name="employee_id" id="employee_id" class="form-control" required>
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->company_id }} {{ $employee->last_name }} {{ $employee->first_name }}, {{ $employee->middle_name ?? ' ' }} {{ $employee->suffix ?? ' ' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="contribution_date">Contribution Month</label>
                    <input type="month" name="contribution_date" id="contribution_date" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Create Contribution</button>
                <a href="{{ route('sss.index') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
@endsection
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
@stop
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for all select elements
            $('select').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });
    </script>
@stop
