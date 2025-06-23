@extends('layouts.app')

@section('content')
<br>
<div class="container-fluid">
<!-- Enhanced professional-looking link buttons -->
<div class="mb-4">
    <div class="contribution-nav" role="navigation" aria-label="Contribution Types">
        <a href="{{ route('attendances.index') }}" class="contribution-link {{ request()->routeIs('attendances.index') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-clock"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Attendance</span>
                <small class="description">Attendance List</small>
            </div>
        </a>
        @if(Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('HR ComBen'))
        <a href="{{ route('attendances.create') }}" class="contribution-link {{ request()->routeIs('attendances.create') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-sign-in-alt"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Time In/Time Out</span>
                <small class="description">Attendance Create</small>
            </div>
        </a>
        @endif
        @if(Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Finance') || Auth::user()->hasRole('VP Finance') || Auth::user()->hasRole('HR ComBen') || Auth::user()->hasRole('Supervisor'))
        <a href="{{ url('/timesheets') }}" class="contribution-link {{ request()->routeIs('attendances.timesheets') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Timesheets</span>
                <small class="description">Employee attendance records</small>
            </div>
        </a>
        @endif
        @if(Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Finance'))
        <a href="{{ route('overtime.index') }}" class="contribution-link {{ request()->routeIs('overtime.index') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-clock"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Overtime</span>
                <small class="description">Employee overtime records</small>
            </div>
        </a>
        @endif
        @if(Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Finance'))
        <a href="{{ route('night-premium.index') }}" class="contribution-link {{ request()->routeIs('night-premium.index') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-moon"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Night Premium</span>
                <small class="description">Employee night premium records</small>
            </div>
        </a>
        @endif
    </div>
</div>
    <!-- Filter and Search Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Employee Timesheet</h5>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6 mb-2">
                    <label for="filter">Filter by Department:</label>
                    <select id="filter" class="form-control">
                        <option value="all">All Employees</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="search">Search Employee:</label>
                    <input type="text" id="search" class="form-control" placeholder="Enter company ID, last name, or first name">
                </div>
            </div>
        </div>
    </div>

    <!-- List of Employees Card -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>Company ID</th>
                        <th>Name</th>
                        <th>Timesheet</th>
                    </tr>
                </thead>
                <tbody id="employee-list">
                    @foreach ($employees as $employee)
                        <tr class="employee-row" data-department-id="{{ $employee->department_id }}" data-employee-name="{{ $employee->last_name }} {{ $employee->first_name }} {{ $employee->middle_name }}" data-company-id="{{ $employee->company_id }}">
                            <td>{{ $employee->company_id }}</td>
                            <td>{{ $employee->last_name }}, {{ $employee->first_name }} {{ $employee->middle_name }}</td>
                            <td>
                                @if (count($timesheets[$employee->id]) > 0)
                                    <a href="{{ route('employee.attendance', ['employee_id' => $employee->id]) }}" class="btn btn-primary toggle-attendance" data-employee-id="{{ $employee->id }}"><i class="fas fa-file-alt"></i> Timesheet</a>
                                @else
                                    No record
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div id="no-employees" class="text-center p-4" style="display: none;">
            No employees found.
        </div>
    </div>
</div>

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

            function filterAndSearch() {
                var departmentId = $('#filter').val();
                var searchQuery = $('#search').val().toLowerCase();

                $('.employee-row').each(function() {
                    var $row = $(this);
                    var rowDepartmentId = $row.data('department-id').toString();
                    var employeeName = $row.data('employee-name').toLowerCase();
                    var companyId = $row.data('company-id').toString().toLowerCase();

                    var departmentMatch = departmentId === 'all' || rowDepartmentId === departmentId;
                    var searchMatch = searchQuery.length === 0 ||
                                      employeeName.includes(searchQuery) ||
                                      companyId.includes(searchQuery);

                    if (departmentMatch && searchMatch) {
                        $row.show();
                    } else {
                        $row.hide();
                    }
                });

                if ($('.employee-row:visible').length === 0) {
                    $('#no-employees').show();
                } else {
                    $('#no-employees').hide();
                }
            }

            // Bind events
            $('#filter').on('change', filterAndSearch);
            $('#search').on('input', filterAndSearch);

            // Initial filter and search
            filterAndSearch();
        });
    </script>
@stop
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        function filterAndSearch() {
            var departmentId = $('#filter').val();
            var searchQuery = $('#search').val().toLowerCase();

            $('.employee-row').show();

            if (departmentId !== 'all') {
                $('.employee-row').filter(function() {
                    return $(this).data('department-id') !== departmentId;
                }).hide();
            }

            if (searchQuery.length > 0) {
                $('.employee-row').filter(function() {
                    var employeeName = $(this).data('employee-name').toLowerCase();
                    var companyId = $(this).data('company-id').toString().toLowerCase();
                    return !(employeeName.includes(searchQuery) || companyId.includes(searchQuery));
                }).hide();
            }

            if ($('.employee-row:visible').length === 0) {
                $('#no-employees').show();
            } else {
                $('#no-employees').hide();
            }
        }

        $('#filter').change(filterAndSearch);
        $('#search').on('keyup', filterAndSearch);

        filterAndSearch();
    });
</script>
@endsection
