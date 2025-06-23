@extends('layouts.app')

@section('css')
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
    <br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Resigned Employees</h3>
                        <div class="card-tools d-flex flex-wrap justify-content-end">
                            <div class="dropdown mr-2 mb-2">
                                <button class="btn btn-warning btn-sm dropdown-toggle rounded-pill" type="button" id="employeeStatusDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Employee Status <i class="fas fa-user-clock"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="employeeStatusDropdown">
                                    <a class="dropdown-item" href="{{ route('employees.index') }}">
                                        <i class="fas fa-user-check"></i> Active Employees
                                    </a>
                                    <a class="dropdown-item" href="{{ route('employees.terminated') }}">
                                        <i class="fas fa-user-times"></i> Terminated Employees
                                    </a>
                                </div>
                            </div>       
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">{{ $message }}</div>
                        @endif

                        <table id="resigned-employees-table" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Resignation Date</th>
                                    <th>Rank</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td>{{ $employee->company_id }}</td>
                                        <td>{{ $employee->last_name }} {{ $employee->first_name }}, {{ $employee->middle_name }} {{ $employee->suffix }}</td>
                                        <td>{{ $employee->department->name }}</td>
                                        <td>{{ $employee->position->name }}</td>
                                        <td>{{ $employee->resigned_date ? \Carbon\Carbon::parse($employee->resigned_date)->format('F j, Y') : 'N/A' }}</td>
                                        <td align="center" style="color: {{ $employee->rank === 'Rank File' ? 'green' : 'blue' }}; font-weight: bold;">
                                            {{ $employee->rank }}
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="{{ route('employees.show', $employee->slug) }}">
                                                        <i class="fas fa-eye"></i>&nbsp;Preview
                                                    </a>
                                                    @can('super-admin')
                                                    <form action="{{ route('employees.destroy', $employee->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item delete-btn">
                                                            <i class="fas fa-trash"></i>&nbsp;Delete
                                                        </button>
                                                    </form>
                                                    @endcan
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            let table = $('#resigned-employees-table').DataTable({
                columnDefs: [
                    {
                        targets: 4, // Targeting the "Resignation Date" column (0-based index)
                        type: 'date'
                    }
                ],
                order: [
                    [4, 'desc'], // Sort by resignation date in descending order (newest first)
                    [1, 'asc']   // Then sort by name as secondary sort
                ]
            });
            
            // Handle delete button click
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                let form = $(this).closest('form');

                Swal.fire({
                    title: 'Delete Employee',
                    text: 'Are you sure you want to delete this employee? This action cannot be undone.',
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
            
            // Replace session alerts with SweetAlert2
            @if(Session::has('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ Session::get('success') }}",
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            @if(Session::has('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "{{ Session::get('error') }}",
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif
        });
    </script>
@endsection 