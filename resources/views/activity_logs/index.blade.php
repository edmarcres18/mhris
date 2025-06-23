@extends('layouts.app')

@section('content')
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><b>{{$department}}</b> - User Activity Logs</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <!-- Date Range Filter -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dateFrom">Date From</label>
                                <input type="date" class="form-control" id="dateFrom" name="dateFrom">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dateTo">Date To</label>
                                <input type="date" class="form-control" id="dateTo" name="dateTo">
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button id="clearDateFilter" class="btn btn-secondary">Clear Filter</button>
                        </div>
                    </div>
                    
                    <table class="table table-bordered table-hover" id="activityLogsTable">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Action</th>
                                <th>IP Address</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                <tr>
                                    <td>{{ $log->user->first_name }} {{ $log->user->last_name }}</td>
                                    <td>{{ $log->action }}</td>
                                    <td>{{ $log->ip_address }}</td>
                                    <td>{{ $log->created_at->format('F d, Y H:i:s') }}</td>
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

@section('css')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('js')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize DataTable with custom date range filter
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                const dateFrom = $('#dateFrom').val();
                const dateTo = $('#dateTo').val();
                const dateStr = data[3]; // Index 3 is the timestamp column

                if (!dateFrom && !dateTo) {
                    return true;
                }

                const date = moment(dateStr, 'MMMM DD, YYYY HH:mm:ss').format('YYYY-MM-DD');
                
                if (dateFrom && !dateTo) {
                    return date >= dateFrom;
                }
                if (!dateFrom && dateTo) {
                    return date <= dateTo;
                }
                return date >= dateFrom && date <= dateTo;
            }
        );

        const table = $('#activityLogsTable').DataTable({
            order: [[3, 'desc']], // Sort by timestamp column in descending order
            responsive: true,
            pageLength: 25,
            language: {
                search: "Search records:",
                lengthMenu: "Show _MENU_ records per page",
                info: "Showing _START_ to _END_ of _TOTAL_ records",
                infoEmpty: "Showing 0 to 0 of 0 records",
                infoFiltered: "(filtered from _MAX_ total records)"
            }
        });

        // Event listener for date filter changes
        $('#dateFrom, #dateTo').on('change', function() {
            table.draw();
        });

        // Clear date filter
        $('#clearDateFilter').on('click', function() {
            $('#dateFrom').val('');
            $('#dateTo').val('');
            table.draw();
        });

        // Common toast configuration
        const toastConfig = {
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            background: '#fff',
            color: '#424242',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast'
            }
        };

        // Success toast
        @if(Session::has('success'))
            Swal.fire({
                ...toastConfig,
                icon: 'success',
                title: 'Success',
                text: "{{ Session::get('success') }}",
                background: '#28a745',
                color: '#fff'
            });
        @endif

        // Error toast
        @if(Session::has('error'))
            Swal.fire({
                ...toastConfig,
                icon: 'error',
                title: 'Error',
                text: "{{ Session::get('error') }}",
                background: '#dc3545',
                color: '#fff'
            });
        @endif
    });
</script>

<style>
    /* Date filter styles */
    .form-group {
        margin-bottom: 0;
    }
    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    #clearDateFilter {
        height: 38px;
    }
    
    /* Toast styles */
    .colored-toast.swal2-icon-success {
        box-shadow: 0 0 12px rgba(40, 167, 69, 0.4) !important;
    }
    .colored-toast.swal2-icon-error {
        box-shadow: 0 0 12px rgba(220, 53, 69, 0.4) !important;
    }
</style>
@endsection
