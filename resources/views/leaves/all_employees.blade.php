@extends('layouts.app')

@section('content')
<br>
    <div class="container-fluid">
        <!-- Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Active Employees</h3>
            </div>
            <div class="card-body">
                <!-- Department Filter Dropdown -->
                <div class="row mb-3">
                    <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                            <label for="departmentFilter">Filter by Department:</label>
                            <select id="departmentFilter" class="form-control">
                                <option value="">All Departments</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->name }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="employees-table">
                        <thead>
                            <tr>
                                <th>Company ID</th>
                                <th>Employee Name</th>
                                <th>Department</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                            <tr>
                                <td>{{ $employee->company_id }}</td>
                                <td>{{ $employee->last_name }} {{ $employee->first_name }}, {{ $employee->middle_name ?: ' ' }} {{ $employee->suffix ?: ' ' }}</td>
                                <td>{{ $employee->department->name }}</td> <!-- Assuming you have a relationship set up in the Employee model -->
                                <td>
                                    <a href="{{ route('leaves.employee_leaves', $employee->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i> View Leaves</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End of Card Body -->
        </div>
        <!-- End of Card -->
    </div>

    @section('js')
    <script>
        $(document).ready(function() {
            var table = $('#employees-table').DataTable();

            $('#departmentFilter').on('change', function() {
                var selectedDepartment = $(this).val();
                console.log("Selected Department: ", selectedDepartment); // Debugging log
                if (selectedDepartment) {
                    table.column(2).search('^' + selectedDepartment + '$', true, false).draw();
                } else {
                    table.column(2).search('').draw();
                }
            });
        });
    </script>
    @endsection
@endsection
