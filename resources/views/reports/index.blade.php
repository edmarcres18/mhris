@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Generate Reports</h1>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Loan Report</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.loans') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="loan_start_date">Start Date</label>
                            <input type="date" class="form-control" id="loan_start_date" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="loan_end_date">End Date</label>
                            <input type="date" class="form-control" id="loan_end_date" name="end_date" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Generate Loan Report</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Detailed Loan Report</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.detailed-loan') }}" method="GET">
                        <div class="form-group">
                            <label for="detailed_loan_start_date">Start Date</label>
                            <input type="date" class="form-control" id="detailed_loan_start_date" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="detailed_loan_end_date">End Date</label>
                            <input type="date" class="form-control" id="detailed_loan_end_date" name="end_date" required>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Generate Detailed Loan Report</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Contribution Report</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.contributions') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="contribution_start_date">Start Date</label>
                            <input type="date" class="form-control" id="contribution_start_date" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="contribution_end_date">End Date</label>
                            <input type="date" class="form-control" id="contribution_end_date" name="end_date" required>
                        </div>
                        <button type="submit" class="btn btn-info btn-block">Generate Contribution Report</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Attendance Report</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.attendances') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="attendance_start_date">Start Date</label>
                            <input type="date" class="form-control" id="attendance_start_date" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="attendance_end_date">End Date</label>
                            <input type="date" class="form-control" id="attendance_end_date" name="end_date" required>
                        </div>
                        <div class="form-group">
                            <label for="attendance_department">Department</label>
                            <select class="form-control" id="attendance_department" name="department_id">
                                <option value="">All Departments</option>
                                @foreach(App\Models\Department::all() as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-warning btn-block">Generate Attendance Report</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Leave Report</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.leaves') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="leave_start_date">Start Date</label>
                            <input type="date" class="form-control" id="leave_start_date" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="leave_end_date">End Date</label>
                            <input type="date" class="form-control" id="leave_end_date" name="end_date" required>
                        </div>
                        <div class="form-group">
                            <label for="leave_department">Department</label>
                            <select class="form-control" id="leave_department" name="department_id">
                                <option value="">All Departments</option>
                                @foreach(App\Models\Department::all() as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-danger btn-block">Generate Leave Report</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Hiring Report</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.hirings') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="hiring_start_date">Start Date</label>
                            <input type="date" class="form-control" id="hiring_start_date" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="hiring_end_date">End Date</label>
                            <input type="date" class="form-control" id="hiring_end_date" name="end_date" required>
                        </div>
                        <button type="submit" class="btn btn-secondary btn-block">Generate Hiring Report</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Career Report</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.careers') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="career_start_date">Start Date</label>
                            <input type="date" class="form-control" id="career_start_date" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="career_end_date">End Date</label>
                            <input type="date" class="form-control" id="career_end_date" name="end_date" required>
                        </div>
                        <button type="submit" class="btn btn-dark btn-block">Generate Career Report</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Set min date for end date inputs based on start date selection
        $('input[name="start_date"]').on('change', function() {
            var minDate = $(this).val();
            $(this).closest('form').find('input[name="end_date"]').attr('min', minDate);
        });
    });
</script>
@endpush

@endsection
