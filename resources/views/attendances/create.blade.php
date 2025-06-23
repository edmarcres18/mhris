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
        @canany(['hrcomben', 'admin', 'super-admin'])
        <a href="{{ route('attendances.create') }}" class="contribution-link {{ request()->routeIs('attendances.create') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-sign-in-alt"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Time In/Time Out</span>
                <small class="description">Attendance Create</small>
            </div>
        </a>
        @endcanany
        @canany(['hrcomben', 'admin', 'super-admin'])
        <a href="{{ url('/timesheets') }}" class="contribution-link {{ request()->routeIs('attendances.timesheets') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Timesheets</span>
                <small class="description">Employee attendance records</small>
            </div>
        </a>
        @endcanany
        @canany(['hrcomben', 'admin', 'super-admin', 'finance'])
        <a href="{{ route('overtime.index') }}" class="contribution-link {{ request()->routeIs('overtime.index') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-clock"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Overtime</span>
                <small class="description">Employee overtime records</small>
            </div>
        </a>
        @endcanany
        @canany(['hrcomben', 'admin', 'super-admin', 'finance'])
        <a href="{{ route('night-premium.index') }}" class="contribution-link {{ request()->routeIs('night-premium.index') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-moon"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Night Premium</span>
                <small class="description">Employee night premium records</small>
            </div>
        </a>
        @endcanany
    </div>
</div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">
                        {{ Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('HR Compliance') || Auth::user()->hasRole('HR ComBen') ? 'Create New Attendance' : 'Attendance' }}
                    </h3>
                </div>

                    <!-- /.card-header -->
                    <div class="card-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger d-none">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(session('successMessage'))
                            <div class="alert alert-success d-none">
                                {{ session('successMessage') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger d-none">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form action="{{ route('attendances.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('HR ComBen'))
                                    <!-- Admin Interface -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="employee_id">Employee</label>
                                            <select id="employee_id" name="employee_id" class="form-control" required>
                                                <option value="">Select Employee</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{ $employee->id }}">{{ $employee->company_id }} {{ $employee->last_name }} {{ $employee->first_name }}, {{ $employee->middle_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_attended">Date:</label>
                                            <input type="date" id="date_attended" name="date_attended" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="time_in_group" class="form-group">
                                            <label for="time_in">Time In:</label>
                                            <input type="time" id="time_in" name="time_in" class="form-control">
                                        </div>
                                        <div id="time_out_group" class="form-group">
                                            <label for="time_out">Time Out:</label>
                                            <input type="time" id="time_out" name="time_out" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="time_stamp1_group" class="form-group">
                                            <label for="time_stamp1">Time Stamp (In):</label>
                                            <input type="file" id="time_stamp1" name="time_stamp1" class="form-control" accept="image/*">
                                        </div>
                                        <div id="time_stamp2_group" class="form-group">
                                            <label for="time_stamp2">Time Stamp (Out):</label>
                                            <input type="file" id="time_stamp2" name="time_stamp2" class="form-control" accept="image/*">
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if(Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('HR ComBen'))
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="btn-group" role="group" aria-label="Button group">
                                            <button type="submit" class="btn btn-primary">Submit Attendance</button>&nbsp;&nbsp;
                                            <button type="submit" class="btn btn-success" formaction="{{ route('attendances.storeAndCreateAnother') }}">Save & Create Another</button>&nbsp;&nbsp;
                                            <a href="{{ route('attendances.index') }}" class="btn btn-info">Back</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('HR ComBen'))
            const employeeSelect = document.getElementById('employee_id');
            const dateInput = document.getElementById('date_attended');
            const timeInGroup = document.getElementById('time_in_group');
            const timeOutGroup = document.getElementById('time_out_group');
            const timeStamp1Group = document.getElementById('time_stamp1_group');
            const timeStamp2Group = document.getElementById('time_stamp2_group');

            async function checkAttendance() {
                const employeeId = employeeSelect.value;
                const dateInputValue = dateInput.value;
                const dateAttended = new Date(dateInputValue).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                if (employeeId && dateInputValue) {
                    const response = await fetch(`/check-attendance?employee_id=${employeeId}&date_attended=${dateInputValue}`);
                    const data = await response.json();

                    // New logic to check if attendance is already submitted
                    if (data.hasTimeIn && data.hasTimeOut) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Attendance Already Submitted',
                            text: `This employee has already created attendance for ${dateAttended}.`,
                            confirmButtonText: 'OK'
                        });
                    }
                    // No need to hide/show fields conditionally - all fields are always available 
                    // for a simple CRUD experience
                }
            }

            employeeSelect.addEventListener('change', checkAttendance);
            dateInput.addEventListener('change', checkAttendance);
        @else
            // Mobile App Version Logic
            function updateClock() {
                fetch('/server-time')
                    .then(response => response.json())
                    .then(data => {
                        const serverTime = new Date(data.server_time);
                        const timeString = serverTime.toLocaleTimeString();
                        document.getElementById('clock').textContent = timeString;
                    });
            }

            // Call the function every second to update the clock
            setInterval(updateClock, 1000);
            updateClock();

            async function checkAttendance() {
                const employeeId = document.getElementById('employee_id').value;
                const dateAttended = document.getElementById('date_attended').value;

                const response = await fetch(`/check-attendance?employee_id=${employeeId}&date_attended=${dateAttended}`);
                const data = await response.json();

                // Logic for showing completion message only if both exist
                if (data.hasTimeIn && data.hasTimeOut) {
                    document.getElementById('attendance_submitted').style.display = 'block';
                } else {
                    document.getElementById('attendance_submitted').style.display = 'none';
                }
                
                // Always show both time in and time out options for simple CRUD experience
                document.getElementById('time_in_group').style.display = 'block';
                document.getElementById('time_out_group').style.display = 'block';
            }

            checkAttendance();

            document.getElementById('captureTimeIn').addEventListener('click', function() {
                document.getElementById('time_stamp1').click();
            });

            document.getElementById('captureTimeOut').addEventListener('click', function() {
                document.getElementById('time_stamp2').click();
            });

            function handleImageCapture(event, timeInputId, actionType) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('capturedImage').src = e.target.result;
                        document.getElementById('capturedImage').style.display = 'block';
                    }
                    reader.readAsDataURL(file);

                    fetch('/server-time')
                        .then(response => response.json())
                        .then(data => {
                            const serverTime = new Date(data.server_time);
                            const hours = String(serverTime.getHours()).padStart(2, '0');
                            const minutes = String(serverTime.getMinutes()).padStart(2, '0');
                            document.getElementById(timeInputId).value = `${hours}:${minutes}`;
                            document.getElementById('submitAttendance').style.display = 'block';
                        });
                }
            }

            document.getElementById('time_stamp1').addEventListener('change', function(event) {
                handleImageCapture(event, 'time_in', 'Time In');
                // Always keep both time fields visible for CRUD operation
            });

            document.getElementById('time_stamp2').addEventListener('change', function(event) {
                handleImageCapture(event, 'time_out', 'Time Out');
                // Always keep both time fields visible for CRUD operation
            });

            // Handle form submission
            document.getElementById('attendanceForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Check if the form was submitted with the storeAndCreate action
                        const formAction = this.action;
                        const isStoreAndCreate = formAction.includes('store-and-create');
                        
                        if (isStoreAndCreate) {
                            // Reset the form fields
                            this.reset();
                            // Show success message as toast
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: data.message || 'Attendance record saved successfully.',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        } else {
                            // Redirect to the same page
                            window.location.href = window.location.href;
                        }
                    } else {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Submission Failed',
                            text: data.message || 'An error occurred while submitting attendance.',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Submission Failed',
                        text: 'An unexpected error occurred. Please try again.',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
            });

            // Check for flash message and display SweetAlert
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: "{{ session('success') }}",
                    confirmButtonText: 'OK'
                });
            @endif
        @endif
    });
</script>
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

            // Display toast notifications for success messages
            @if (session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000
                });
            @endif

            // Display toast notifications for errors
            @if (session('error'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: "{{ session('error') }}",
                    showConfirmButton: false,
                    timer: 3000
                });
            @endif

            // Display toast notifications for validation errors
            @if (count($errors) > 0)
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please check the form for errors',
                    showConfirmButton: false,
                    timer: 3000
                });
            @endif

            // Display toast notification for successMessage
            @if (session('successMessage'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('successMessage') }}",
                    showConfirmButton: false,
                    timer: 3000
                });
            @endif
        });
    </script>
@stop
