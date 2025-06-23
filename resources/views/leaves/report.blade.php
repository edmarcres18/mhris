<!-- resources/views/leaves/report.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Leaves Report</h1>

    <form method="GET" action="{{ route('leaves.report') }}">
        <div class="form-group">
            <label for="department_id">Filter by Department:</label>
            <select name="department_id" id="department_id" class="form-control">
                <option value="">All Departments</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ $departmentId == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('leaves.report', ['download' => 'pdf']) }}" class="btn btn-success">Download PDF</a>
    </form>
<br>
    <table id="report-table" class="table table-bordered table-hover table-striped mt-4">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Department</th>
                <th>Date From</th>
                <th>Date To</th>
                <th>Type</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leaves as $leave)
                <tr>
                    <td>{{ $leave->employee->last_name }} {{ $leave->employee->first_name }} {{ $leave->employee->middle_name }}</td>
                    <td>{{ $leave->employee->department->name }}</td>
                    <td>{{ $leave->date_from }}</td>
                    <td>{{ $leave->date_to }}</td>
                    <td>{{ $leave->type->name }}</td>
                    <td>{{ $leave->reason_to_leave }}</td>
                    <td>{{ $leave->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No Employee Leaves Data Here</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
@section('js')
    <script>
        $(document).ready(function () {
            $('#report-table').DataTable();
        });
    </script>
@endsection
