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
        @canany(['hrcomben', 'admin', 'super-admin', 'finance', 'vpfinance-admin', 'supervisor'])
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
        @canany(['hrcomben', 'admin', 'super-admin', 'finance', 'vpfinance-admin', 'supervisor'])
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
        @canany(['hrcomben', 'admin', 'super-admin', 'finance', 'vpfinance-admin', 'supervisor'])
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
                <div class="card-header">
                    <h3 class="card-title">Attendance Management</h3>
                    {{-- <div class="card-tools">
                        @can('attendance-create')
                        <a href="{{ route('attendances.create') }}" class="btn btn-success btn-sm rounded-pill">
                            Add Attendance <i class="fas fa-plus-circle"></i>
                        </a>
                        @endcan
                    </div> --}}
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">{{ $message }}</div>
                        @endif

                    <!-- Date range filter form -->
                    <form id="date-range-form" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="start_date">Start Date:</label>
                                    <input type="date" id="start_date" name="start_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="end_date">End Date:</label>
                                    <input type="date" id="end_date" name="end_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary form-control">Filter</button>
                                </div>
                            </div>
                            @canany(['hrcomben', 'super-admin'])
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" id="store-attendance" class="btn btn-success form-control">
                                        <i class="fas fa-sync"></i> Store Attendance
                                    </button>
                                </div>
                            </div>
                            @endcanany
                        </div>
                    </form>

                    <table id="attendances-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Remark</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->employee->company_id }} {{ $attendance->employee->last_name }} {{ $attendance->employee->first_name }}, {{ $attendance->employee->middle_name ?? ' '}} {{ $attendance->employee->suffix ?? '' }}</td>
                                    <td data-sort="{{ date('Y-m-d', strtotime($attendance->date_attended)) }}">
                                        {{ date('F d, Y', strtotime($attendance->date_attended)) }}
                                    </td>
                                    <td>{{ $attendance->time_in ? date('h:i A', strtotime($attendance->time_in)) : '--:-- --' }}</td>
                                    <td>
                                        {{ $attendance->time_out ? date('h:i A', strtotime($attendance->time_out)) : '--:-- --' }}
                                    </td>
                                    <td>{{ $attendance->remarks }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('attendances.show',$attendance->id) }}"><i class="fas fa-eye"></i>&nbsp;Preview</a>
                                                @can('attendance-edit')
                                                    <a class="dropdown-item" href="{{ route('attendances.edit',$attendance->id) }}"><i class="fas fa-edit"></i>&nbsp;Edit</a>
                                                @endcan
                                                @can('attendance-delete')
                                                    <form action="{{ route('attendances.destroy', $attendance->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this attendance?')"><i class="fas fa-trash"></i>&nbsp;Delete</button>
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

@section('js')
<script>
    $(document).ready(function () {
        var table = $('#attendances-table').DataTable({
            "order": [[1, "desc"]],
            "columnDefs": [
                {
                    "targets": 1,
                    "render": function(data, type, row) {
                        var date = new Date(data);
                        return date.getFullYear() + '-' +
                               ('0' + (date.getMonth() + 1)).slice(-2) + '-' +
                               ('0' + date.getDate()).slice(-2);
                    }
                }
            ]
        });

        // Date range filter
        $('#date-range-form').on('submit', function(e) {
            e.preventDefault();
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            // Validate dates
            if (!startDate && !endDate) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Please select at least one date',
                    showConfirmButton: false,
                    timer: 3000
                });
                return;
            }

            if (startDate && endDate && startDate > endDate) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Start date cannot be later than end date',
                    showConfirmButton: false,
                    timer: 3000
                });
                return;
            }

            // Custom filtering function
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                var date = data[1];

                if (
                    (startDate === "" && endDate === "") ||
                    (startDate === "" && date <= endDate) ||
                    (startDate <= date && endDate === "") ||
                    (startDate <= date && date <= endDate)
                ) {
                    return true;
                }
                return false;
            });

            table.draw();

            // Show success toast
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Date filter applied successfully',
                showConfirmButton: false,
                timer: 2000
            });

            // Clear the custom filter
            $.fn.dataTable.ext.search.pop();
        });

        // Store Attendance button click handler
        $('#store-attendance').on('click', function() {
            // Show loading state
            $(this).prop('disabled', true);
            $(this).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

            // Make AJAX request
            $.ajax({
                url: '{{ route("attendance.store-command") }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Attendance records stored successfully',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    // Reload the page to show new records
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                },
                error: function(xhr) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Error storing attendance records',
                        text: xhr.responseJSON?.message || 'An error occurred',
                        showConfirmButton: false,
                        timer: 3000
                    });
                },
                complete: function() {
                    // Reset button state
                    $('#store-attendance').prop('disabled', false);
                    $('#store-attendance').html('<i class="fas fa-sync"></i> Store Attendance');
                }
            });
        });
    });
</script>
@endsection
