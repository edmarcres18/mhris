@extends('layouts.app')

@section('content')
<br>
<div class="container-fluid">
    <!-- Enhanced professional-looking link buttons -->
<div class="mb-4">
    <div class="contribution-nav" role="navigation" aria-label="Contribution Types">
        <a href="{{ route('payroll.index') }}" class="contribution-link {{ request()->routeIs('payroll.index') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Payroll</span>
                <small class="description">Payroll List</small>
            </div>
        </a>
        @can('payroll-create')
        <a href="{{ route('payroll.create') }}" class="contribution-link {{ request()->routeIs('payroll.create') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-plus"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Create Payroll</span>
                <small class="description">Generate Payroll</small>
            </div>
        </a>
        @endcan
    </div>
</div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payroll Records</h3>
                    <div class="card-tools">
                        <!-- Download Payrolls Form -->
                        <form action="{{ route('payroll.index') }}" method="GET" class="form-inline">
                            <div class="input-group input-group-sm mr-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Start Date</span>
                                </div>
                                <input type="date" name="start_date" id="start_date" class="form-control" required>
                            </div>
                            <div class="input-group input-group-sm mr-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">End Date</span>
                                </div>
                                <input type="date" name="end_date" id="end_date" class="form-control" required readonly>
                            </div>
                            <input type="hidden" name="download" value="1">
                            <button type="submit" class="btn btn-primary btn-sm mr-2">
                                <i class="fas fa-download"></i> Download Payrolls
                            </button>
                            <button type="button" class="btn btn-warning btn-sm mr-2" id="adjustmentsBtn">
                                <i class="fas fa-sliders-h"></i> Adjustments
                            </button>
                            <button type="button" class="btn btn-info btn-sm mr-2" id="printBtn">
                                <i class="fas fa-print"></i> Print
                            </button>
                            <button type="button" class="btn btn-warning btn-sm mr-2" id="notifyBtn">
                                <i class="fas fa-bell"></i> Notify
                            </button>
                        </form>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="payroll-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Employee Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Gross Salary</th>
                                <th>Net Salary</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payrolls as $payroll)
                            <tr>
                                <td>{{ $payroll->id }}</td>
                                <td>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($payroll->start_date)->format('F j, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($payroll->end_date)->format('F j, Y') }}</td>
                                <td>{{ number_format($payroll->gross_salary, 2) }}</td>
                                <td>{{ number_format($payroll->net_salary, 2) }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('payroll.show', ['id' => $payroll->id]) }}">
                                                <i class="fas fa-eye"></i>&nbsp;View
                                            </a>
                                            @can('payroll-delete')
                                            <form action="{{ route('payroll.destroy', $payroll->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item"><i class="fas fa-trash"></i>&nbsp;Delete</button>
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

<!-- Date Selection for Adjustments Modal -->
<div class="modal fade" id="dateSelectionModal" tabindex="-1" role="dialog" aria-labelledby="dateSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="dateSelectionModalLabel">
                    <i class="fas fa-calendar"></i> Select Payroll Period Adjustments
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="dateSelectionForm">
                    <div class="form-group">
                        <label for="payroll_period_type">Payroll Period Type</label>
                        <select class="form-control" id="payroll_period_type" required>
                            <option value="">Select Period Type</option>
                            <option value="biweekly">BiWeekly (7 days)</option>
                            <option value="bimonthly">BiMonthly</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="adjustment_start_date">Start Date</label>
                        <input type="date" class="form-control" id="adjustment_start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="adjustment_end_date">End Date</label>
                        <input type="date" class="form-control" id="adjustment_end_date" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="proceedToAdjustments">Proceed to Adjustments</button>
            </div>
        </div>
    </div>
</div>

<!-- Adjustments Modal -->
<div class="modal fade" id="adjustmentsModal" tabindex="-1" role="dialog" aria-labelledby="adjustmentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="adjustmentsModalLabel">
                    <i class="fas fa-sliders-h"></i> PAYROLL ADJUSTMENTS
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <!-- Company header with fixed position -->
                <div class="payroll-header py-3 bg-light border-bottom sticky-top">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h5 class="font-weight-bold mb-0">MEDICAL & HOSPITAL RESOURCES HEALTH CARE, INC.</h5>
                                <p class="mb-0">PAYROLL <span class="payroll-year">2024</span></p>
                                <div class="row justify-content-center mb-2">
                                    <div class="col-md-3 col-sm-4">
                                        <p class="mb-0"><small>Period Cov: <span class="period-cov font-weight-bold"></span></small></p>
                                    </div>
                                    <div class="col-md-3 col-sm-4">
                                        <p class="mb-0"><small>Payroll: <span class="payroll-date font-weight-bold"></span></small></p>
                                    </div>
                                    <div class="col-md-3 col-sm-4">
                                        <p class="mb-0"><small>Pay-out: <span class="pay-out-date font-weight-bold"></span></small></p>
                                    </div>
                                </div>

                                <!-- Search and filter controls -->
                                <div class="row mt-2">
                                    <div class="col-md-4 col-sm-12 mb-2">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="adjustmentSearch" placeholder="Search employee...">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 mb-2">
                                        <select class="form-control form-control-sm" id="departmentFilter">
                                            <option value="">All Departments</option>
                                            <option value="SP">Support Personnel</option>
                                            <option value="HR">Human Resources</option>
                                            <option value="IT">Information Technology</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-sm-6 mb-2">
                                        <button type="button" class="btn btn-outline-secondary btn-sm w-100" id="toggleAllDepartments">
                                            <i class="fas fa-expand-alt"></i> <span>Expand All</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table with fixed header -->
                <div class="table-container">
                    <div class="table-responsive">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between w-100">
                    <div>
                        <span class="text-muted"><small>Last updated: <span id="lastUpdated">Today at 12:45 PM</span></small></span>
                    </div>
                    <div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="saveAdjustments">
                            <i class="fas fa-save"></i> Save Adjustments
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Date Selection for Print Modal -->
<div class="modal fade" id="dateSelectionModalForPrint" tabindex="-1" role="dialog" aria-labelledby="dateSelectionModalForPrintLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="dateSelectionModalLabelForPrint">
                    <i class="fas fa-calendar"></i> Select Payroll Period Print
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="dateSelectionFormForPrint">
                    <div class="form-group">
                        <label for="print_period_type">Payroll Period Type</label>
                        <select class="form-control" id="print_period_type" required>
                            <option value="">Select Period Type</option>
                            <option value="biweekly">BiWeekly (7 days)</option>
                            <option value="bimonthly">BiMonthly</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="print_start_date">Start Date</label>
                        <input type="date" class="form-control" id="print_start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="print_end_date">End Date</label>
                        <input type="date" class="form-control" id="print_end_date" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="proceedToPrint">Proceed to Print</button>
            </div>
        </div>
    </div>
</div>

<!-- Print Preview Modal -->
<div class="modal fade" id="printPreviewModal" tabindex="-1" role="dialog" aria-labelledby="printPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width: 95%;">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="printPreviewModalLabel">
                    <i class="fas fa-print"></i> Payroll Print Preview
                </h5>
                <div class="ml-auto">
                    <button type="button" class="btn btn-sm btn-light mr-2" id="actualPrintBtn">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="print-container" id="printContainer">
                    <!-- Print content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notification Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="notificationModalLabel">
                    <i class="fas fa-bell"></i> Send Payroll Notification
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="notificationForm">
                    <div class="form-group">
                        <label for="notification_period_type">Payroll Period Type</label>
                        <select class="form-control" id="notification_period_type" required>
                            <option value="">Select Period Type</option>
                            <option value="biweekly">BiWeekly (7 days)</option>
                            <option value="bimonthly">BiMonthly</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="notification_start_date">Start Date</label>
                        <input type="date" class="form-control" id="notification_start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="notification_end_date">End Date</label>
                        <input type="date" class="form-control" id="notification_end_date" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="sendNotification">
                    <i class="fas fa-paper-plane"></i> Send Notification
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Add SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        // SweetAlert toast configuration
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

        // Format employee IDs to show only last 4 digits
        function formatEmployeeIds() {
            // For any dynamically loaded employee IDs, ensure we only show the last 4 digits
            $('.employee-row').each(function() {
                const idCell = $(this).find('td:first-child');
                const fullId = idCell.text().trim();
                if (fullId && !idCell.find('.badge').length) {
                    // Extract last 4 digits
                    const last4 = fullId.length > 4 ? fullId.slice(-4) : fullId;
                    idCell.html(`<span class="badge badge-secondary">${last4}</span>`);
                }
            });
        }

        // Call format function on load
        formatEmployeeIds();

        // Call format function after any AJAX loads
        $(document).ajaxComplete(function() {
            setTimeout(formatEmployeeIds, 100);
        });

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

        // Delete confirmation
        $(document).on('click', '.dropdown-item[type="submit"]', function(e) {
            e.preventDefault();
            let form = $(this).closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Initialize DataTable
        $('#payroll-table').DataTable();

        // Function to set end date based on start date
        function setEndDate(startDateInput, endDateInput) {
                var startDate = new Date(startDateInput.val());
                var endDate = new Date(startDate);

                if (startDate.getDate() >= 11 && startDate.getDate() <= 25) {
                    endDate.setDate(25);
                } else if (startDate.getDate() >= 26 || startDate.getDate() <= 10) {
                    if (startDate.getDate() >= 26) {
                        endDate.setMonth(startDate.getMonth() + 1);
                    }
                    endDate.setDate(10);
                }

                var formattedEndDate = endDate.toISOString().split('T')[0];
                endDateInput.val(formattedEndDate);
            }

            // Set end date for main form
            $('#start_date').change(function() {
                setEndDate($('#start_date'), $('#end_date'));
            });

        // Set end date for adjustment start date
        $('#adjustment_start_date').change(function() {
            const periodType = $('#payroll_period_type').val();
            if (periodType === 'biweekly') {
                // For BiWeekly, set end date to 7 days after start date
                const startDate = new Date($(this).val());
                const endDate = new Date(startDate);
                endDate.setDate(startDate.getDate() + 6); // 7 days total (start date + 6 days)
                const formattedEndDate = endDate.toISOString().split('T')[0];
                $('#adjustment_end_date').val(formattedEndDate);
            } else if (periodType === 'bimonthly') {
                // For BiMonthly, use the existing logic
                setEndDate($('#adjustment_start_date'), $('#adjustment_end_date'));
            }
        });


        // When period type changes, update end date
        $('#payroll_period_type').change(function() {
            if ($('#adjustment_start_date').val()) {
                $('#adjustment_start_date').trigger('change');
            }
        });

        // Show date selection modal when adjustments button is clicked
        $('#adjustmentsBtn, #payrollAdjustmentsBtn').click(function() {
            $('#dateSelectionModal').modal('show');
        });

        // Show date selection modal when adjustments button is clicked
        $('#printBtn, #payrollPrintBtn').click(function() {
            $('#dateSelectionModalForPrint').modal('show');
        });

        // Show notification modal when notify button is clicked
        $('#notifyBtn').click(function() {
            $('#notificationModal').modal('show');
        });

        // Handle notification period type change
        $('#notification_period_type').change(function() {
            if ($('#notification_start_date').val()) {
                $('#notification_start_date').trigger('change');
            }
        });

        // Set end date for notification start date
        $('#notification_start_date').change(function() {
            const periodType = $('#notification_period_type').val();
            if (periodType === 'biweekly') {
                // For BiWeekly, set end date to 7 days after start date
                const startDate = new Date($(this).val());
                const endDate = new Date(startDate);
                endDate.setDate(startDate.getDate() + 6); // 7 days total (start date + 6 days)
                const formattedEndDate = endDate.toISOString().split('T')[0];
                $('#notification_end_date').val(formattedEndDate);
            } else if (periodType === 'bimonthly') {
                // For BiMonthly, use the existing logic
                setEndDate($('#notification_start_date'), $('#notification_end_date'));
            }
        });

        // Handle send notification button
        $('#sendNotification').click(function() {
            const periodType = $('#notification_period_type').val();
            const startDate = $('#notification_start_date').val();
            const endDate = $('#notification_end_date').val();

            if (!periodType || !startDate || !endDate) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please select period type and both start and end dates',
                    background: '#dc3545',
                    color: '#fff'
                });
                return;
            }

            // Show loading state
            const $btn = $(this);
            const originalText = $btn.html();
            $btn.html('<i class="fas fa-spinner fa-spin"></i> Sending...');
            $btn.prop('disabled', true);

            // Send notification data to server
            $.ajax({
                url: "{{ route('payroll.sendNotification') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    period_type: periodType,
                    start_date: startDate,
                    end_date: endDate
                },
                success: function(response) {
                    // Restore button state
                    $btn.html(originalText);
                    $btn.prop('disabled', false);
                    
                    // Close modal
                    $('#notificationModal').modal('hide');
                    
                    // Show success message
                    Swal.fire({
                        ...toastConfig,
                        icon: 'success',
                        title: 'Success',
                        text: response.message || "Notifications sent successfully",
                        background: '#28a745',
                        color: '#fff'
                    });
                },
                error: function(xhr) {
                    // Restore button state
                    $btn.html(originalText);
                    $btn.prop('disabled', false);
                    
                    // Show error message
                    Swal.fire({
                        ...toastConfig,
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || "Failed to send notifications. Please try again.",
                        background: '#dc3545',
                        color: '#fff'
                    });
                    
                    console.error(xhr.responseText);
                }
            });
        });

        // Handle proceed to adjustments button
        $('#proceedToAdjustments').click(function() {
            var periodType = $('#payroll_period_type').val();
            var startDate = $('#adjustment_start_date').val();
            var endDate = $('#adjustment_end_date').val();

            if (!periodType || !startDate || !endDate) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please select period type and both start and end dates',
                    background: '#dc3545',
                    color: '#fff'
                });
                return;
            }

            // Close date selection modal
            $('#dateSelectionModal').modal('hide');

            // Show loading spinner in adjustments modal
            $('#adjustmentsModal .modal-body').html('<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-3x"></i><p class="mt-3">Loading payroll data...</p></div>');

            // Show adjustments modal
            $('#adjustmentsModal').modal('show');

            // Format dates for display
            var startDateObj = new Date(startDate);
            var endDateObj = new Date(endDate);

            // Format for period coverage
            var periodStart = startDateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            var periodEnd = endDateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

            // Determine payroll date based on period type
            var payrollDate;
            if (periodType === 'biweekly') {
                // For biweekly, payroll date is the end date
                payrollDate = new Date(endDateObj);
            } else {
                // For bimonthly, use the existing logic
                if (endDateObj.getDate() <= 15) {
                    // For first half of the month, payroll date is on the 15th
                    payrollDate = new Date(endDateObj.getFullYear(), endDateObj.getMonth(), 15);
                } else {
                    // For second half, payroll date is the last day of the month
                    payrollDate = new Date(endDateObj.getFullYear(), endDateObj.getMonth() + 1, 0);
                }
            }

            // Format payroll date
            var payrollDateStr = payrollDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

            // Determine payout date
            var payoutDate;
            if (periodType === 'biweekly') {
                // For biweekly, payout date is also the end date
                payoutDate = new Date(endDateObj);
            } else {
                // For bimonthly, payout date is typically same as payroll date, but adjust for weekends
                payoutDate = new Date(payrollDate);
                // If payroll date falls on weekend, move to next Monday
                if (payoutDate.getDay() === 0) { // Sunday
                    payoutDate.setDate(payoutDate.getDate() + 1);
                } else if (payoutDate.getDay() === 6) { // Saturday
                    payoutDate.setDate(payoutDate.getDate() + 2);
                }
            }

            // Format payout date
            var payOutDateStr = payoutDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

            // Get year from the end date
            var payrollYear = endDateObj.getFullYear();

            // Update header information in the modal
            $('#adjustmentsModal .period-cov').text(periodStart + ' - ' + periodEnd);
            $('#adjustmentsModal .payroll-date').text(payrollDateStr);
            $('#adjustmentsModal .pay-out-date').text(payOutDateStr);
            $('#adjustmentsModal .payroll-year').text(payrollYear);

            // Fetch payroll data for the selected date range
            $.ajax({
                url: "{{ route('payroll.getAdjustments') }}",
                type: "GET",
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    period_type: periodType
                },
                success: function(response) {
                    // Replace modal content with the response
                    $('#adjustmentsModal .modal-body').html(response);

                    // Re-set the period coverage, payroll date, and payout date as they may be lost when replacing content
                    $('#adjustmentsModal .period-cov').text(periodStart + ' - ' + periodEnd);
                    $('#adjustmentsModal .payroll-date').text(payrollDateStr);
                    $('#adjustmentsModal .pay-out-date').text(payOutDateStr);
                    $('#adjustmentsModal .payroll-year').text(payrollYear);

                    // Initialize UI elements for the newly loaded content
                    initAdjustmentsUI();
                },
                error: function(xhr) {
                    $('#adjustmentsModal .modal-body').html('<div class="alert alert-danger text-center">Error loading payroll data. Please try again.</div>');
                    console.error(xhr.responseText);
                }
            });
        });

        // Initialize UI for adjustments modal content
        function initAdjustmentsUI() {
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Department toggle functionality
            $('.toggle-department').click(function() {
                const $icon = $(this);
                const $departmentHeader = $icon.closest('.department-header');
                const department = $departmentHeader.data('department');
                const $employeeRows = $('.employee-row[data-department="' + department + '"]');

                if ($icon.hasClass('fa-chevron-down')) {
                    $icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
                    $employeeRows.hide();
                } else {
                    $icon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
                    $employeeRows.show();
                }
            });

            // Clear the department filter dropdown and populate it dynamically with departments from the loaded content
            let $departmentFilter = $('#departmentFilter');
            $departmentFilter.empty();
            $departmentFilter.append('<option value="">All Departments</option>');

            // Get unique departments from the department headers
            $('.department-header').each(function() {
                const department = $(this).data('department');
                const departmentName = $(this).find('td').text().trim();
                if (department && departmentName) {
                    $departmentFilter.append(`<option value="${department}">${departmentName}</option>`);
                }
            });

            // Initialize the toggle button to "Collapse All" state
            var $toggleBtn = $('#toggleAllDepartments');
            $toggleBtn.find('i').removeClass('fa-expand-alt').addClass('fa-compress-alt');
            $toggleBtn.find('span').text('Collapse All');

            // Ensure all departments are expanded initially
            $('.toggle-department').removeClass('fa-chevron-right').addClass('fa-chevron-down');
            $('.employee-row').show();

            // Expand/Collapse all departments
            $('#toggleAllDepartments').click(function() {
                const $button = $(this);
                const $icon = $button.find('i');
                const $text = $button.find('span');

                // Check current state
                if ($icon.hasClass('fa-expand-alt')) {
                    // Currently shows "Expand All", so expand all departments
                    $('.toggle-department').removeClass('fa-chevron-right').addClass('fa-chevron-down');
                    $('.employee-row').show();

                    // Change to "Collapse All"
                    $icon.removeClass('fa-expand-alt').addClass('fa-compress-alt');
                    $text.text('Collapse All');
                } else {
                    // Currently shows "Collapse All", so collapse all departments
                    $('.toggle-department').removeClass('fa-chevron-down').addClass('fa-chevron-right');
                    $('.employee-row').hide();

                    // Change to "Expand All"
                    $icon.removeClass('fa-compress-alt').addClass('fa-expand-alt');
                    $text.text('Expand All');
                }
            });

            // Search functionality
            $('#adjustmentSearch').on('keyup', function() {
                const searchText = $(this).val().toLowerCase();
                const $departmentFilter = $('#departmentFilter');
                const departmentFilter = $departmentFilter.val();

                $('.employee-row').each(function() {
                    const $row = $(this);
                    const rowText = $row.data('search').toLowerCase();
                    const department = $row.data('department');

                    const matchesSearch = rowText.includes(searchText);
                    const matchesDepartment = !departmentFilter || department === departmentFilter;

                    if (matchesSearch && matchesDepartment) {
                        $row.show();
                        // Make sure the department header is visible and expanded
                        const $header = $('.department-header[data-department="' + department + '"]');
                        $header.show();
                        $header.find('.toggle-department').removeClass('fa-chevron-right').addClass('fa-chevron-down');
                    } else {
                        $row.hide();
                    }
                });

                // Check if any rows are visible for each department
                $('.department-header').each(function() {
                    const department = $(this).data('department');
                    const $visibleRows = $('.employee-row[data-department="' + department + '"]:visible');

                    if ($visibleRows.length === 0 && searchText) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
            });

            // Department filter
            $('#departmentFilter').change(function() {
                $('#adjustmentSearch').trigger('keyup');
            });

            // Highlight editable fields on focus
            $('#adjustmentTable input:not([readonly])').focus(function() {
                $(this).closest('td').addClass('cell-highlight');
            }).blur(function() {
                $(this).closest('td').removeClass('cell-highlight');
            });

            // Format number inputs with 2 decimal places on blur
            $('#adjustmentTable input[type="number"]').blur(function() {
                if ($(this).val()) {
                    try {
                        const value = parseFloat($(this).val());
                        if (!isNaN(value)) {
                            $(this).val(value.toFixed(2));
                        }
                    } catch (e) {
                        // If parsing fails, keep the current value
                        console.warn('Error formatting number:', e);
                    }
                }
            });

            // Handle direct editing of numeric inputs to ensure proper formatting
            $('#adjustmentTable input[type="number"]').on('input', function() {
                // Allow only numeric input with up to 2 decimal places
                $(this).val($(this).val().replace(/[^0-9.-]/g, ''));

                // Ensure only one decimal point
                const decimalCount = ($(this).val().match(/\./g) || []).length;
                if (decimalCount > 1) {
                    $(this).val($(this).val().replace(/\.(?=.*\.)/g, ''));
                }

                // Limit to 2 decimal places while typing
                const parts = $(this).val().split('.');
                if (parts.length > 1 && parts[1].length > 2) {
                    $(this).val(parts[0] + '.' + parts[1].substring(0, 2));
                }
            });

            // Make toggle button responsive
            const updateToggleButtonTitle = function() {
                const $button = $('#toggleAllDepartments');
                const text = $button.find('span').text();
                $button.attr('data-title', text);
            };

            updateToggleButtonTitle();

            // Update button title when it changes
            $('#toggleAllDepartments').on('click', function() {
                setTimeout(updateToggleButtonTitle, 100);
            });

            // Track changes in adjustment fields and update net salary preview
            $('.adjustment-field, .allowance-field, .other-adj-field, .cash-bond-field, .other-deduct-field').on('input', function() {
                const $row = $(this).closest('tr');
                const $netSalaryCell = $row.find('.net-salary-value');
                const currentNetSalary = parseFloat($netSalaryCell.text().replace(/,/g, ''));

                // Calculate total adjustments impact
                let totalDifference = 0;

                // Process positive adjustments (add to net salary)
                $row.find('.adjustment-field, .allowance-field, .other-adj-field').each(function() {
                    const $field = $(this);
                    const originalValue = parseFloat($field.data('original') || 0);
                    // Handle empty or invalid input - treat as 0
                    const newValue = $field.val() ? parseFloat($field.val()) : 0;
                    if (!isNaN(newValue)) {
                        const difference = newValue - originalValue;
                        totalDifference += difference;
                    }
                });

                // Process negative adjustments (subtract from net salary)
                $row.find('.cash-bond-field, .other-deduct-field').each(function() {
                    const $field = $(this);
                    const originalValue = parseFloat($field.data('original') || 0);
                    // Handle empty or invalid input - treat as 0
                    const newValue = $field.val() ? parseFloat($field.val()) : 0;
                    if (!isNaN(newValue)) {
                        const difference = newValue - originalValue;
                        totalDifference -= difference; // Subtract deductions
                    }
                });

                // Get original net salary without any adjustment changes
                const payrollId = $row.data('payroll-id');
                if (!$row.data('original-net-salary')) {
                    $row.data('original-net-salary', currentNetSalary);
                }
                const originalNetSalary = $row.data('original-net-salary');

                // Calculate new net salary
                const newNetSalary = originalNetSalary + totalDifference;

                // Update the display with appropriate formatting and highlighting
                $netSalaryCell.text(newNetSalary.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','))
                    .toggleClass('text-success', newNetSalary > originalNetSalary)
                    .toggleClass('text-danger', newNetSalary < originalNetSalary);

                // Show a small indicator of the change
                if (!$netSalaryCell.find('.change-indicator').length && totalDifference !== 0) {
                    const sign = totalDifference > 0 ? '+' : '';
                    const cls = totalDifference > 0 ? 'text-success' : 'text-danger';
                    $netSalaryCell.append(
                        `<small class="change-indicator ${cls} ml-2">(${sign}${totalDifference.toFixed(2)})</small>`
                    );
                } else if ($netSalaryCell.find('.change-indicator').length) {
                    if (totalDifference === 0) {
                        $netSalaryCell.find('.change-indicator').remove();
                    } else {
                        const sign = totalDifference > 0 ? '+' : '';
                        const cls = totalDifference > 0 ? 'text-success' : 'text-danger';
                        $netSalaryCell.find('.change-indicator')
                            .attr('class', `change-indicator ${cls} ml-2`)
                            .text(`(${sign}${totalDifference.toFixed(2)})`);
                    }
                }
            });
        }

        // Save adjustments button
        $('#saveAdjustments').click(function() {
            // Collect all adjustments data
            const adjustments = [];
            $('.employee-row').each(function() {
                const $row = $(this);
                const payrollId = $row.data('payroll-id');

                // Only collect data if it has a valid payroll ID
                if (payrollId) {
                    // Get all adjusted fields (including zero values)
                    // Check if the input has been modified by the user (has the 'modified' class or is different from original)
                    const adjustmentField = $row.find('td:nth-child(8) input');
                    const allowancesField = $row.find('td:nth-child(13) input');
                    const otherAdjustmentsField = $row.find('td:nth-child(14) input');
                    const cashBondField = $row.find('td:nth-child(22) input');
                    const otherDeductionField = $row.find('td:nth-child(23) input');
                    const otherDeductionDescField = $row.find('td:nth-child(24) input.other-deduct-desc-field');

                    // Get values, ensuring explicit zeros are included
                    const adjustmentValue = adjustmentField.val() !== '' ? parseFloat(adjustmentField.val()) : null;
                    const allowancesValue = allowancesField.val() !== '' ? parseFloat(allowancesField.val()) : null;
                    const otherAdjustmentsValue = otherAdjustmentsField.val() !== '' ? parseFloat(otherAdjustmentsField.val()) : null;
                    const cashBondValue = cashBondField.val() !== '' ? parseFloat(cashBondField.val()) : null;
                    const otherDeductionValue = otherDeductionField.val() !== '' ? parseFloat(otherDeductionField.val()) : null;
                    const otherDeductionDescValue = otherDeductionDescField.val() !== '' ? otherDeductionDescField.val() : null;

                    // Check if any field has been explicitly set (including to zero)
                    // Only include fields in the array if they have an explicit value
                    const adjustmentData = {
                        payroll_id: payrollId
                    };

                    // Only include fields that were explicitly set
                    if (adjustmentValue !== null) {
                        adjustmentData.adjustments = adjustmentValue;
                    }

                    if (allowancesValue !== null) {
                        adjustmentData.allowances = allowancesValue;
                    }

                    if (otherAdjustmentsValue !== null) {
                        adjustmentData.other_adjustments = otherAdjustmentsValue;
                    }

                    if (cashBondValue !== null) {
                        adjustmentData.cash_bond = cashBondValue;
                    }

                    if (otherDeductionValue !== null) {
                        adjustmentData.other_deduction = otherDeductionValue;
                    }

                    if (otherDeductionDescValue !== null) {
                        adjustmentData.other_deduction_description = otherDeductionDescValue;
                    }

                    // Only add to adjustments if there's at least one field set
                    if (Object.keys(adjustmentData).length > 1) {
                        adjustments.push(adjustmentData);
                    }
                }
            });

            // Show saving indicator
            const $btn = $(this);
            const originalText = $btn.html();
            $btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...');
            $btn.prop('disabled', true);

            // Send data to server
            $.ajax({
                url: "{{ route('payroll.saveAdjustments') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    adjustments: adjustments
                },
                success: function(response) {
                    $btn.html('<i class="fas fa-check"></i> Saved!');

                    // Update last updated time
                    const now = new Date();
                    const hours = now.getHours();
                    const minutes = now.getMinutes();
                    const ampm = hours >= 12 ? 'PM' : 'AM';
                    const formattedHours = hours % 12 || 12;
                    const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
                    const timeString = 'Today at ' + formattedHours + ':' + formattedMinutes + ' ' + ampm;
                    $('#lastUpdated').text(timeString);

                    // Show success toast
                    Swal.fire({
                        ...toastConfig,
                        icon: 'success',
                        title: 'Success',
                        text: response.message || "Adjustments saved successfully",
                        background: '#28a745',
                        color: '#fff'
                    });

                    // Update net salary values if provided in the response
                    if (response.updated_payrolls) {
                        $.each(response.updated_payrolls, function(payrollId, newNetSalary) {
                            $(`.employee-row[data-payroll-id="${payrollId}"] .net-salary-value`).text(newNetSalary);
                        });
                    }

                    // Redirect to payroll index page after 1.5 seconds if a redirect URL is provided
                    if (response.redirect) {
                        setTimeout(function() {
                            window.location.href = response.redirect;
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    $btn.html(originalText);
                    $btn.prop('disabled', false);

                    // Show error toast
                    Swal.fire({
                        ...toastConfig,
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || "Failed to save adjustments. Please try again.",
                        background: '#dc3545',
                        color: '#fff'
                    });

                    console.error(xhr.responseText);
                }
            });
            });

        // Set end date for print start date
        $('#print_start_date').change(function() {
            const periodType = $('#print_period_type').val();
            if (periodType === 'biweekly') {
                // For BiWeekly, set end date to 7 days after start date
                const startDate = new Date($(this).val());
                const endDate = new Date(startDate);
                endDate.setDate(startDate.getDate() + 6); // 7 days total (start date + 6 days)
                const formattedEndDate = endDate.toISOString().split('T')[0];
                $('#print_end_date').val(formattedEndDate);
            } else if (periodType === 'bimonthly') {
                // For BiMonthly, use the existing logic
                setEndDate($('#print_start_date'), $('#print_end_date'));
            }
        });

        // When period type changes, update end date
        $('#print_period_type').change(function() {
            if ($('#print_start_date').val()) {
                $('#print_start_date').trigger('change');
            }
        });
        
        // Handle proceed to print button
        $('#proceedToPrint').click(function() {
            var periodType = $('#print_period_type').val();
            var startDate = $('#print_start_date').val();
            var endDate = $('#print_end_date').val();

            if (!periodType || !startDate || !endDate) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please select period type and both start and end dates',
                    background: '#dc3545',
                    color: '#fff'
                });
                return;
            }

            // Close date selection modal
            $('#dateSelectionModalForPrint').modal('hide');

            // Show loading spinner in print preview modal
            $('#printContainer').html('<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-3x"></i><p class="mt-3">Generating print preview...</p></div>');
            $('#printPreviewModal').modal('show');

            // Format dates for display
            var startDateObj = new Date(startDate);
            var endDateObj = new Date(endDate);
            
            // Determine payroll date based on period type
            var payrollDate;
            if (periodType === 'biweekly') {
                // For biweekly, payroll date is the end date
                payrollDate = new Date(endDateObj);
            } else {
                // For bimonthly, use the existing logic
                if (endDateObj.getDate() <= 15) {
                    // For first half of the month, payroll date is on the 15th
                    payrollDate = new Date(endDateObj.getFullYear(), endDateObj.getMonth(), 15);
                } else {
                    // For second half, payroll date is the last day of the month
                    payrollDate = new Date(endDateObj.getFullYear(), endDateObj.getMonth() + 1, 0);
                }
            }

            // Fetch payroll data for print preview
            $.ajax({
                url: "{{ route('payroll.getPrintPreview') }}",
                type: "GET",
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    period_type: periodType
                },
                success: function(response) {
                    // Replace print container content with the response
                    $('#printContainer').html(response);
                    
                    // Initialize print preview features
                    initPrintPreview();
                },
                error: function(xhr) {
                    $('#printContainer').html('<div class="alert alert-danger text-center">Error generating print preview. Please try again.</div>');
                    console.error(xhr.responseText);
                }
            });
        });

        // Initialize print preview features
        function initPrintPreview() {
            // Current zoom level (100% by default)
            let zoomLevel = 100;
            
            // Zoom in
            $('#zoomInBtn').click(function() {
                if (zoomLevel < 150) {
                    zoomLevel += 10;
                    updateZoom();
                }
            });
            
            // Zoom out
            $('#zoomOutBtn').click(function() {
                if (zoomLevel > 70) {
                    zoomLevel -= 10;
                    updateZoom();
                }
            });
            
            // Update zoom level
            function updateZoom() {
                $('.payroll-print-table').css('zoom', zoomLevel + '%');
            }
            
            // Toggle gridlines
            $('#showGridlines').change(function() {
                if ($(this).is(':checked')) {
                    $('.payroll-print-table').addClass('table-bordered');
                } else {
                    $('.payroll-print-table').removeClass('table-bordered');
                }
            });
            
            // Filter by department
            $('#printDepartmentFilter').change(function() {
                const department = $(this).val();
                if (department) {
                    $('.print-department-section').hide();
                    $('.print-department-section[data-department="' + department + '"]').show();
                    // Hide the all departments total
                    $('.all-departments-total').hide();
                } else {
                    $('.print-department-section').show();
                    // Show the all departments total
                    $('.all-departments-total').show();
                }
            });
            
            // Handle actual print button
            $('#actualPrintBtn').click(function() {
                // Set print orientation to landscape
                const style = document.createElement('style');
                style.innerHTML = '@page { size: landscape; margin: 0.5in; }';
                style.id = 'forceLandscape';
                document.head.appendChild(style);
                
                // Hide UI elements that shouldn't be printed
                $('.print-controls').addClass('d-print-none');
                $('.modal-header').addClass('d-print-none');
                
                // Prepare table for printing
                $('.payroll-print-table').addClass('table-bordered');
                
                // Wait for styles to apply
                setTimeout(function() {
                    try {
                        window.print();
                        
                        // Restore UI after print dialog closes
                        window.onafterprint = function() {
                            $('.print-controls').removeClass('d-print-none');
                            $('.modal-header').removeClass('d-print-none');
                            document.getElementById('forceLandscape').remove();
                        };
                    } catch (e) {
                        console.error("Print error: ", e);
                        Swal.fire({
                            icon: 'error',
                            title: 'Print Error',
                            text: 'There was an error when trying to print. Please try again or use browser print function.',
                            timer: 3000
                        });
                        
                        // Restore UI
                        $('.print-controls').removeClass('d-print-none');
                        $('.modal-header').removeClass('d-print-none');
                        document.getElementById('forceLandscape').remove();
                    }
                }, 300);
            });
        }
    });
</script>
@endsection

@section('styles')
<style>
    /* Toast styles */
    .colored-toast.swal2-icon-success {
        box-shadow: 0 0 12px rgba(40, 167, 69, 0.4) !important;
    }
    .colored-toast.swal2-icon-error {
        box-shadow: 0 0 12px rgba(220, 53, 69, 0.4) !important;
    }

    /* Enhanced Adjustments Modal Styles */
    #adjustmentsModal .modal-dialog.modal-xl {
        max-width: 95%;
    }

    /* Fixed header styles */
    .sticky-header {
        position: sticky;
        top: 0;
        z-index: 1;
        background-color: #f8f9fa;
    }

    .sticky-top {
        z-index: 2;
    }

    /* Table container with max height */
    .table-container {
        max-height: calc(100vh - 280px);
        overflow-y: auto;
        overflow-x: auto;
        border-radius: 0.25rem;
        box-shadow: inset 0 0 10px rgba(0,0,0,0.05);
    }

    /* Editable columns highlight */
    .editable-column {
        background-color: rgba(255, 243, 205, 0.2);
    }

    /* Cell highlight when focused */
    .cell-highlight {
        background-color: #fff8e6 !important;
        box-shadow: 0 0 5px rgba(255, 193, 7, 0.5);
    }

    /* Highlight editable fields */
    #adjustmentsModal input:not([readonly]) {
        background-color: #fff8e6;
        border-color: #ffc107;
    }

    #adjustmentsModal input:not([readonly]):focus {
        border-color: #ff9800;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }

    /* Department header styling */
    .department-header {
        cursor: pointer;
        background-color: #f0f0f0 !important;
        transition: background-color 0.2s ease;
    }

    .department-header:hover {
        background-color: #e9ecef !important;
    }

    .toggle-department {
        transition: transform 0.3s ease;
        width: 14px;
        display: inline-block;
        text-align: center;
    }

    .toggle-department.fa-chevron-down {
        transform: rotate(0deg);
    }

    .toggle-department.fa-chevron-right {
        transform: rotate(-90deg);
    }

    .employee-row {
        transition: background-color 0.2s ease;
    }

    #toggleAllDepartments {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    #toggleAllDepartments:after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(0, 0, 0, 0.05);
        opacity: 0;
        border-radius: 100%;
        transform: scale(1, 1) translate(-50%);
        transform-origin: 50% 50%;
    }

    #toggleAllDepartments:hover:after {
        animation: ripple 1s ease-out;
    }

    @keyframes ripple {
        0% {
            transform: scale(0, 0);
            opacity: 0.5;
        }
        20% {
            transform: scale(25, 25);
            opacity: 0.5;
        }
        100% {
            opacity: 0;
            transform: scale(40, 40);
        }
    }

    /* Table improvements */
    #adjustmentTable th {
        background-color: #f8f9fa;
        vertical-align: middle;
        text-align: center;
        font-size: 0.85rem;
        white-space: nowrap;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    #adjustmentTable td {
        vertical-align: middle;
        padding: 0.25rem;
        font-size: 0.85rem;
    }

    /* Input fields with readable font size */
    #adjustmentTable input.form-control-sm {
        padding: 0.1rem 0.3rem;
        height: calc(1.5em + 0.5rem + 2px);
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }

    /* Readonly fields styling */
    #adjustmentTable input[readonly] {
        background-color: #f8f9fa;
        cursor: not-allowed;
        color: #495057;
    }

    /* Responsive improvements */
    @media (max-width: 992px) {
        #adjustmentTable th {
            font-size: 0.75rem;
        }

        #adjustmentTable td {
            font-size: 0.75rem;
        }

        #adjustmentTable input.form-control-sm {
            font-size: 0.75rem;
            padding: 0.1rem 0.2rem;
        }
    }

    /* Better tooltips */
    .tooltip .tooltip-inner {
        max-width: 200px;
        padding: 6px 10px;
        background-color: #495057;
        font-size: 0.8rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    /* Modal header improvements */
    #adjustmentsModal .modal-header,
    #dateSelectionModal .modal-header {
        background: linear-gradient(45deg, #ffb347, #ffcc33);
        color: #604a0e;
        border-bottom: 0;
        border-top-left-radius: 0.3rem;
        border-top-right-radius: 0.3rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    /* Modal content improvements */
    #adjustmentsModal .modal-content,
    #dateSelectionModal .modal-content {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    /* Search and filter controls styling */
    #adjustmentsModal .input-group-text {
        background-color: #f8f9fa;
        border-color: #ced4da;
    }

    #adjustmentsModal .form-control-sm:focus,
    #dateSelectionModal .form-control:focus {
        border-color: #ffb347;
        box-shadow: 0 0 0 0.2rem rgba(255, 179, 71, 0.25);
    }

    /* Button styling */
    #adjustmentsModal .btn-outline-secondary:hover,
    #dateSelectionModal .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        color: #495057;
    }

    #adjustmentsModal .btn-success,
    #dateSelectionModal .btn-primary {
        background: linear-gradient(45deg, #28a745, #20c997);
        border: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    #adjustmentsModal .btn-success:hover,
    #dateSelectionModal .btn-primary:hover {
        background: linear-gradient(45deg, #218838, #1ba87e);
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    /* Payroll header styling */
    .payroll-header {
        border-bottom: 1px solid #e9ecef;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .payroll-header h5 {
        color: #495057;
        letter-spacing: 0.5px;
    }

    .font-weight-bold {
        font-weight: 600 !important;
    }

    /* Table striping improvement */
    #adjustmentTable.table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0,0,0,0.02);
    }

    /* Employee row hover effect */
    .employee-row:hover {
        background-color: rgba(0,0,0,0.03) !important;
    }

    /* Date Selection Modal improvements */
    #dateSelectionModal .form-group label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    #dateSelectionModal .form-control {
        font-size: 1rem;
        height: calc(1.5em + 1rem + 2px);
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        border: 1px solid #ced4da;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    /* Scrollbar styling */
    .table-container::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .table-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .table-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }

    .table-container::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Input field placeholder styling */
    #adjustmentTable input::placeholder {
        color: #adb5bd;
        font-style: italic;
        font-size: 0.8rem;
    }

    /* Footer styling */
    #adjustmentsModal .modal-footer,
    #dateSelectionModal .modal-footer {
        border-top: 1px solid #e9ecef;
        background-color: #f8f9fa;
        border-bottom-left-radius: 0.3rem;
        border-bottom-right-radius: 0.3rem;
    }

    /* Responsive improvements for smaller screens */
    @media (max-width: 576px) {
        #adjustmentsModal .modal-body {
            padding: 0;
        }

        #toggleAllDepartments span {
            display: none;
        }

        #toggleAllDepartments i {
            margin-right: 0;
        }

        #toggleAllDepartments:after {
            content: attr(data-title);
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 2px 5px;
            background: rgba(0,0,0,0.7);
            color: white;
            border-radius: 3px;
            font-size: 0.7rem;
            opacity: 0;
            transition: opacity 0.2s ease;
            pointer-events: none;
        }

        #toggleAllDepartments:hover:after {
            opacity: 1;
            top: -25px;
        }
    }

    /* Print Preview Styles */
    @media print {
        body * {
            visibility: hidden;
        }
        
        #printPreviewModal,
        #printPreviewModal .modal-dialog,
        #printPreviewModal .modal-content,
        #printPreviewModal .modal-body,
        #printContainer,
        #printContainer * {
            visibility: visible;
        }
        
        .d-print-none {
            display: none !important;
        }
        
        #printContainer {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        
        .payroll-print-table {
            width: 100%;
            font-size: 9pt;
            border-collapse: collapse;
        }
        
        .print-company-header {
            text-align: center;
            margin-bottom: 1rem;
        }

        /* Force landscape orientation */
        @page {
            size: landscape;
            margin: 1in;
        }
    }
    
    /* Print Preview Table Styles */
    .print-container {
        padding: 1rem;
        background-color: white;
    }
    
    .payroll-print-table {
        width: 100%;
        font-size: 10pt;
        border-collapse: collapse;
        margin-bottom: 1rem;
    }
    
    .payroll-print-table th {
        background-color: #f8f9fa;
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
        padding: 0.25rem;
        font-size: 9pt;
    }
    
    .payroll-print-table td {
        padding: 0.25rem;
        text-align: center;
        vertical-align: middle;
    }
    
    .payroll-print-table th, 
    .payroll-print-table td {
        border: 1px solid #dee2e6;
    }
    
    .print-company-header {
        text-align: center;
        margin-bottom: 1rem;
    }
    
    .print-company-header h4 {
        margin-bottom: 0;
        font-weight: bold;
    }
    
    .print-company-header p {
        margin-bottom: 0.25rem;
    }
    
    .print-department-header {
        background-color: #f0f0f0;
        font-weight: bold;
        text-transform: uppercase;
    }
    
    /* Print margin settings */
    @page {
        margin: 1in;
    }
</style>
@endsection
