@extends('layouts.app')

@section('content')
<br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create New Attendance</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form action="{{ route('attendances.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super Admin'))
                                        <!-- Employee (for Admins) -->
                                        <div class="form-group">
                                            <label for="employee_id">Employee</label>
                                            <select id="employee_id" name="employee_id" class="form-control" required>
                                                <option value="">Select Employee</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{ $employee->id }}">{{ $employee->company_id }} {{ $employee->last_name }} {{ $employee->first_name }}, {{ $employee->middle_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <!-- Employee (Automatically Selected for Regular Users) -->
                                        <div class="form-group">
                                            <label for="employee_id">Employee:</label>
                                            <select name="employee_id" id="employee_id" class="form-control">
                                                @foreach($employees->where('first_name', Auth::user()->first_name) as $employee)
                                                    <option value="{{ $employee->id }}" selected>{{ $employee->company_id }} {{ $employee->last_name }} {{ $employee->first_name }}, {{ $employee->middle_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_attended">Date:</label>
                                        <input type="date" id="date_attended" name="date_attended" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div id="time_in_group" class="form-group" style="display: none;">
                                        <label for="time_in">Time In:</label>
                                        <input type="time" id="time_in" name="time_in" class="form-control" value="08:00">
                                    </div>
                                    <div id="time_out_group" class="form-group" style="display: none;">
                                        <label for="time_out">Time Out:</label>
                                        <input type="time" id="time_out" name="time_out" class="form-control" value="17:00">
                                    </div>
                                </div>

                                <!-- Camera Capture Fields -->
                                <div class="col-md-6">
                                    <div class="form-group" id="time_stamp1" style="display: none;">
                                        <label for="time_stamp1">Time Stamp (In):</label>
                                        <input type="file" id="time_stamp1" name="time_stamp1" class="form-control" accept="image/*" capture="camera">
                                    </div>
                                    <div class="form-group" id="time_stamp2" style="display: none;">
                                        <label for="time_stamp2">Time Stamp (Out):</label>
                                        <input type="file" id="time_stamp2" name="time_stamp2" class="form-control" accept="image/*" capture="camera">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="btn-group" role="group" aria-label="Button group">
                                        <button type="submit" class="btn btn-primary">Create</button>&nbsp;&nbsp;
                                        @if(Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin'))
                                        <a href="{{ route('attendances.index') }}" class="btn btn-info">Back</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col-md-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const employeeSelect = document.getElementById('employee_id');
            const dateInput = document.getElementById('date_attended');
            const timeInGroup = document.getElementById('time_in_group');
            const timeOutGroup = document.getElementById('time_out_group');
            const timeStampIn = document.getElementById('time_stamp1');
            const timeStampOut = document.getElementById('time_stamp2');

            async function checkAttendance() {
                const employeeId = employeeSelect.value;
                const dateAttended = dateInput.value;

                if (employeeId && dateAttended) {
                    const response = await fetch(`/check-attendance?employee_id=${employeeId}&date_attended=${dateAttended}`);
                    const data = await response.json();

                    if (data.hasTimeIn) {
                        timeInGroup.style.display = 'none';
                        timeStampIn.style.display = 'none';
                        timeOutGroup.style.display = 'block';
                        timeStampOut.style.display = 'block';
                    } else {
                        timeInGroup.style.display = 'block';
                        timeStampIn.style.display = 'block';
                        timeOutGroup.style.display = 'none';
                        timeStampOut.style.display = 'none';
                    }
                }
            }

            employeeSelect.addEventListener('change', checkAttendance);
            dateInput.addEventListener('change', checkAttendance);
        });
    </script>
@endsection
