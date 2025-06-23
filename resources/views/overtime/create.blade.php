@extends('layouts.app')

@section('content')
<br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create New Overtime</h3>
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

                        <form action="{{ route('overtime.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="employee_id">Employee<span class="text-danger">*</span></label>
                                        <select id="employee_id" name="employee_id" class="form-control" required>
                                            <option value="">Select Employee</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}">
                                                    {{ $employee->company_id }}  {{ $employee->last_name }}  {{ $employee->first_name }}, {{ $employee->middle_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date">Date<span class="text-danger">*</span></label>
                                        <input type="date" id="date" name="date" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="time_in">Time In<span class="text-danger">*</span></label>
                                        <input type="datetime-local" id="time_in" name="time_in" class="form-control" required>
                                        <small class="text-muted">Time in date must match the date selected above</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="time_out">Time Out<span class="text-danger">*</span></label>
                                        <input type="datetime-local" id="time_out" name="time_out" class="form-control" required>
                                        <small class="text-muted">Time out can be on a different date</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="overtime_rate">Overtime Rate</label>
                                        <input type="number" step="0.01" name="overtime_rate" id="overtime_rate" class="form-control" value="1.25" required>
                                        <small class="text-muted">1.25 for regular overtime, 1.5 for holiday overtime</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="btn-group" role="group" aria-label="Button group">
                                        <button type="submit" class="btn btn-primary">Create</button>&nbsp;&nbsp;
                                        <a href="{{ route('overtime.index') }}" class="btn btn-info">Back</a>
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

            // Initialize date field with today's date
            const today = new Date();
            const formattedDate = today.toISOString().split('T')[0];
            $('#date').val(formattedDate);

            // Set default time_in and time_out based on the date
            function updateDefaultTimes() {
                const selectedDate = $('#date').val();
                if (selectedDate) {
                    // Set default time_in to 5:00 PM on the selected date
                    const defaultTimeIn = selectedDate + 'T17:00';
                    $('#time_in').val(defaultTimeIn);
                    
                    // Set default time_out to 6:30 PM on the selected date (1.5 hours later)
                    const defaultTimeOut = selectedDate + 'T18:30';
                    $('#time_out').val(defaultTimeOut);
                }
            }
            
            // Call the function initially to set default values
            updateDefaultTimes();
            
            // Set date field as default for time_in and time_out when date changes
            $('#date').on('change', function() {
                updateDefaultTimes();
            });
            
            // Validate that time_in date matches the selected date
            $('form').on('submit', function(e) {
                const selectedDate = $('#date').val();
                const timeIn = $('#time_in').val();
                
                if (timeIn) {
                    const timeInDate = timeIn.split('T')[0];
                    if (timeInDate !== selectedDate) {
                        e.preventDefault();
                        alert('Time in date must match the selected overtime date.');
                    }
                }
            });
        });
    </script>
@stop
