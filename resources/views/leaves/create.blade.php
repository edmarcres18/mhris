@extends('layouts.app')

@section('content')
<br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                    @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super Admin'))
                        <h3 class="card-title">Create New Leave</h3>
                        @elseif(Auth::user()->hasRole('Employee') || Auth::user()->hasRole('Supervisor'))
                        <h3 class="card-title">Apply New Leave</h3>
                        @endif
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if(Auth::user()->hasRole('Employee') || Auth::user()->hasRole('Supervisor'))
                            <!-- Employee View - Modern Card Layout -->
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="card shadow-sm border-0 mb-4">
                                        <div class="card-body text-center py-5">
                                            <i class="fas fa-calendar-plus fa-4x text-primary mb-3"></i>
                                            <h4 class="mb-4">Ready to Request Leave?</h4>
                                            <p class="text-muted mb-4">Submit your leave application with just a few clicks</p>
                                            <button type="button" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm" data-toggle="modal" data-target="#leaveRequestModal">
                                                <i class="fas fa-plus-circle mr-2"></i> Apply for Leave
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Quick Info Cards -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card border-0 shadow-sm" data-toggle="modal" data-target="#leaveBalanceModal" style="cursor: pointer;">
                                                <div class="card-body text-center p-4">
                                                    <i class="fas fa-clock text-info mb-3 fa-2x"></i>
                                                    <h6 class="mb-2">Leave Balance</h6>
                                                    <p class="text-muted mb-0">Check your available leaves</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card border-0 shadow-sm" onclick="window.location.href='{{ route('leaves.my_leave_sheet') }}'" style="cursor: pointer;">
                                                <div class="card-body text-center p-4">
                                                    <i class="fas fa-history text-warning mb-3 fa-2x"></i>
                                                    <h6 class="mb-2">Recent Requests</h6>
                                                    <p class="text-muted mb-0">View your leave history</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card border-0 shadow-sm" data-toggle="modal" data-target="#helpModal">
                                                <div class="card-body text-center p-4">
                                                    <i class="fas fa-question-circle text-success mb-3 fa-2x"></i>
                                                    <h6 class="mb-2">Need Help?</h6>
                                                    <p class="text-muted mb-0">Contact HR department</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Leave Request Modal -->
                            <div class="modal fade" id="leaveRequestModal" tabindex="-1" role="dialog" aria-labelledby="leaveRequestModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="leaveRequestModalLabel">Leave Application Form</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            @if(!$employees->first()->signature)
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    <strong>Notice:</strong> Please add your signature to your profile before applying for leave. 
                                                    <a href="{{ url('/my-profile') }}" class="alert-link">Update your profile here</a>.
                                                </div>
                                            @endif
                                            <form id="leaveRequestForm" action="{{ route('leaves.store') }}" method="POST">
                                                @csrf
                                                <!-- Hidden Employee ID -->
                                                <input type="hidden" name="employee_id" value="{{ $employees->first()->id }}">
                                                
                                                <div class="form-group">
                                                    <label for="leave_type">Leave Type<span class="text-danger">*</span></label>
                                                    <select id="leave_type" name="leave_type" class="form-control select2" required onchange="toggleDateInputs()">
                                                        <option value="">Select Leave Type</option>
                                                        <option value="Leave">Leave</option>
                                                        <option value="Halfday">Halfday</option>
                                                        <option value="Undertime">Undertime</option>
                                                    </select>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="date_from">Date From<span class="text-danger">*</span></label>
                                                            <input type="datetime-local" id="date_from" name="date_from" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="date_to">Date To<span class="text-danger">*</span></label>
                                                            <input type="datetime-local" id="date_to" name="date_to" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label id="typeLabel" for="type_id">Type of Leave<span class="text-danger">*</span></label>
                                                    <select id="type_id" name="type_id" class="form-control select2" required>
                                                        <option value="">Select Type of Leave</option>
                                                        @foreach($types as $type)
                                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="reason_to_leave">Reason<span class="text-danger">*</span></label>
                                                    <textarea id="reason_to_leave" name="reason_to_leave" class="form-control" rows="3" required></textarea>
                                                </div>

                                                @if($employees->first()->signature)
                                                    <div class="form-group">
                                                        <label>Your Signature</label>
                                                        <div class="border rounded p-2 text-center">
                                                            <img src="{{ Storage::url($employees->first()->signature) }}" alt="Employee Signature" class="img-fluid" style="max-height: 100px;">
                                                            <input type="hidden" name="signature" value="{{ $employees->first()->signature }}">
                                                        </div>
                                                    </div>
                                                @endif
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" form="leaveRequestForm" class="btn btn-primary" id="submitBtn">
                                                <span class="normal-text">Submit Leave Request</span>
                                                <span class="loading-text d-none">
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    Submitting...
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Existing form for Admin/Super Admin/HR ComBen -->
                            <form action="{{ route('leaves.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('HR ComBen'))
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
                                            <label for="leave_type">Leave Type<span class="text-danger">*</span></label>
                                            <select id="leave_type" name="leave_type" class="form-control" required onchange="toggleDateInputs()">
                                                <option value="">Select Leave Type</option>
                                                <option value="Leave">Leave</option>
                                                <option value="Halfday">Halfday</option>
                                                <option value="Undertime">Undertime</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_from">Date From<span class="text-danger">*</span></label>
                                            <input type="datetime-local" id="date_from" name="date_from" class="form-control"
                                                value="{{ old('date_from') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_to">Date To<span class="text-danger">*</span></label>
                                            <input type="datetime-local" id="date_to" name="date_to" class="form-control"
                                                value="{{ old('date_to') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label id="typeLabel" for="type_id">Type of Leave<span class="text-danger">*</span></label>
                                            <select id="type_id" name="type_id" class="form-control" required>
                                                <option value="">Select Type of Leave</option>
                                                @foreach($types as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="reason_to_leave">Reason<span class="text-danger">*</span></label>
                                            <textarea id="reason_to_leave" name="reason_to_leave" class="form-control" required></textarea>
                                        </div>
                                    </div>
                                    <!-- Add signature for Employees -->
                                    <div class="d-none">
                                    @if(Auth::user()->hasRole('Employee'))
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Employee Signature<span class="text-danger">*</span></label>
                                            <div class="border rounded p-3">
                                                @if($employees->first()->signature)
                                                    <div class="text-center mb-3">
                                                        <img src="{{ Storage::url($employees->first()->signature) }}" alt="Employee Signature" class="img-fluid" style="max-height: 200px;">
                                                        <input type="hidden" name="signature" id="signature" value="{{ $employees->first()->signature }}">
                                                    </div>
                                                @else
                                                    <div class="alert alert-warning text-center mb-3">
                                                        No signature found. Please update your signature in your profile.
                                                    </div>
                                                    <input type="hidden" name="signature" id="signature" value="">
                                                @endif
                                                @error('signature')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Employee Signature<span class="text-danger">*</span></label>
                                            <div class="border rounded p-3" id="signatureContainer">
                                                <div class="text-center mb-3">
                                                    <!-- Signature will be loaded here dynamically -->
                                                </div>
                                            </div>
                                            <input type="hidden" name="signature" id="signature" value="">
                                            @error('signature')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    @endif
                                    </div>
                                </div>
                             {{-- <!-- Add the following after the "Reason" textarea -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="payment_status">Payment Status</label>
                                            <div class="form-check">
                                                <input type="checkbox" id="payment_status" class="form-check-input" disabled>
                                                <label class="form-check-label" for="payment_status" id="payment_status_label">With Pay</label>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="btn-group" role="group" aria-label="Button group">
                                        @if(Auth::user()->hasRole('Employee'))
                                            <button type="submit" class="btn btn-primary">Apply</button>&nbsp;&nbsp;
                                        @else
                                            <button type="submit" class="btn btn-primary">Create</button>&nbsp;&nbsp;
                                        @endif
                                            @can('super-admin')
                                            <a href="{{ route('leaves.index') }}" class="btn btn-info">Back</a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif
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

    <!-- Leave Balance Modal -->
    <div class="modal fade" id="leaveBalanceModal" tabindex="-1" role="dialog" aria-labelledby="leaveBalanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="leaveBalanceModalLabel">
                        <i class="fas fa-clock text-info mr-2"></i>
                        Leave Balance
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-5">
                    <div class="coming-soon-animation mb-4">
                        <i class="fas fa-cog fa-spin fa-4x text-info"></i>
                    </div>
                    <h4 class="mb-3">Coming Soon!</h4>
                    <p class="text-muted">We're working hard to bring you this feature.</p>
                    <p class="text-muted">Stay tuned for updates!</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
    <!-- Add SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        /* Toast styles */
        .colored-toast.swal2-icon-success {
            box-shadow: 0 0 12px rgba(40, 167, 69, 0.4) !important;
        }
        .colored-toast.swal2-icon-error {
            box-shadow: 0 0 12px rgba(220, 53, 69, 0.4) !important;
        }
        
        /* Button loading state styles */
        button:disabled {
            cursor: not-allowed;
            opacity: 0.7;
        }
        
        .loading-text, .normal-text {
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .d-none {
            display: none !important;
        }
        
        /* Enhanced UI Styles */
        .card {
            transition: transform 0.2s ease-in-out;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .btn-lg {
            padding: 12px 30px;
            font-weight: 500;
        }
        
        .rounded-pill {
            border-radius: 50px;
        }
        
        .shadow-sm {
            box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
        }
        
        .text-primary {
            color: #4e73df!important;
        }
        
        .text-info {
            color: #36b9cc!important;
        }
        
        .text-warning {
            color: #f6c23e!important;
        }
        
        .text-success {
            color: #1cc88a!important;
        }
        
        /* Quick Info Cards hover effect */
        .card:hover {
            cursor: pointer;
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        }
        
        /* Modal enhancements */
        .modal-content {
            border: none;
            border-radius: 15px;
        }
        
        .modal-header {
            border-bottom: 1px solid rgba(0,0,0,.1);
            background-color: #f8f9fc;
            border-radius: 15px 15px 0 0;
        }
        
        .modal-footer {
            border-top: 1px solid rgba(0,0,0,.1);
            background-color: #f8f9fc;
            border-radius: 0 0 15px 15px;
        }
        
        /* Help Modal Styles */
        #helpModal .modal-header {
            border-radius: 15px 15px 0 0;
        }
        
        #helpModal .btn-link {
            text-decoration: none;
            font-weight: 500;
        }
        
        #helpModal .btn-link:hover {
            text-decoration: none;
            opacity: 0.8;
        }
        
        #helpModal .card-header {
            border: none;
            background-color: #f8f9fc;
        }
        
        #helpModal .accordion .card {
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        
        #helpModal .accordion .card-body {
            padding: 1.25rem;
            font-size: 0.95rem;
        }
        
        #helpModal .list-unstyled li:last-child {
            margin-bottom: 0 !important;
        }
        
        #helpModal .alert-info {
            background-color: #f8f9fc;
            border-color: #e3e6f0;
            color: #4e73df;
        }

        /* Coming Soon Animation */
        .coming-soon-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.7;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Modal enhancements */
        #leaveBalanceModal .modal-content {
            border-radius: 15px;
            border: none;
        }

        #leaveBalanceModal .modal-dialog {
            max-width: 400px;
        }

        #leaveBalanceModal .fa-cog {
            filter: drop-shadow(0 0 10px rgba(54, 185, 204, 0.3));
        }

        /* Toast error message styling */
        .swal2-html-container {
            margin: 0.5em !important;
            font-size: 0.9em !important;
        }
        
        .swal2-html-container p {
            margin-bottom: 0.5em !important;
            line-height: 1.4 !important;
        }
        
        .swal2-html-container p:last-child {
            margin-bottom: 0 !important;
        }
    </style>
@stop
@section('js')
    <!-- Add SweetAlert2 JS before other scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Select2 initialization
            $('select').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            // Date input validation
            $('#date_from, #date_to').on('change', function() {
                const dateFrom = new Date($('#date_from').val());
                const dateTo = new Date($('#date_to').val());

                if (dateFrom > dateTo) {
                    alert('Date From cannot be later than Date To');
                    $(this).val('');
                }
            });

            // Add event listener for employee selection if user is not an Employee
            @if(!Auth::user()->hasRole('Employee'))
            $('#employee_id').on('change', function() {
                const employeeId = $(this).val();
                if (employeeId) {
                    // Fetch employee signature
                    fetch(`/api/employees/${employeeId}/signature`)
                        .then(response => response.json())
                        .then(data => {
                            const container = $('#signatureContainer .text-center');
                            if (data.signature) {
                                container.html(`
                                    <img src="${data.signature}" alt="Employee Signature" class="img-fluid" style="max-height: 200px;">
                                `);
                                $('#signature').val(data.signature_path);
                            } else {
                                container.html(`
                                    <div class="alert alert-warning">No signature found for this employee.</div>
                                `);
                                $('#signature').val('');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching signature:', error);
                            $('#signatureContainer .text-center').html(`
                                <div class="alert alert-danger">Error loading signature.</div>
                            `);
                            $('#signature').val('');
                        });
                } else {
                    $('#signatureContainer .text-center').empty();
                    $('#signature').val('');
                }
            });
            @endif

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

            // Error toast for validation errors
            @if(count($errors) > 0)
                Swal.fire({
                    ...toastConfig,
                    icon: 'error',
                    title: 'Error',
                    html: `<div class="text-left">
                        @foreach($errors->all() as $error)
                            <p class="mb-1">• {{ $error }}</p>
                        @endforeach
                    </div>`,
                    background: '#dc3545',
                    color: '#fff',
                    timer: 5000, // Increased timer for longer messages
                    width: 'auto',
                    maxWidth: '400px'
                });
            @endif

            // Initialize select2 in modal
            $('#leaveRequestModal select').select2({
                theme: 'bootstrap4',
                width: '100%',
                dropdownParent: $('#leaveRequestModal')
            });

            // Reset form when modal is closed
            $('#leaveRequestModal').on('hidden.bs.modal', function () {
                $('#leaveRequestForm')[0].reset();
                $('#leaveRequestModal select').val('').trigger('change');
            });

            // Form validation and submission handling
            $('#leaveRequestForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                const dateFrom = new Date($('#date_from').val());
                const dateTo = new Date($('#date_to').val());

                if (dateFrom > dateTo) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Dates',
                        text: 'Date From cannot be later than Date To',
                        confirmButtonColor: '#3085d6'
                    });
                    return false;
                }

                // Show loading state
                const submitBtn = $('#submitBtn');
                submitBtn.prop('disabled', true);
                submitBtn.find('.normal-text').addClass('d-none');
                submitBtn.find('.loading-text').removeClass('d-none');

                // Submit form using AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        // Close modal and show success message
                        $('#leaveRequestModal').modal('hide');
                        Swal.fire({
                            ...toastConfig,
                            icon: 'success',
                            title: 'Success',
                            text: response.message || 'Leave request submitted successfully',
                            background: '#28a745',
                            color: '#fff'
                        });

                        // Optional: Reload page after delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    },
                    error: function(xhr) {
                        // Reset button state
                        submitBtn.prop('disabled', false);
                        submitBtn.find('.normal-text').removeClass('d-none');
                        submitBtn.find('.loading-text').addClass('d-none');

                        // Display validation errors in the modal
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            let errorHtml = '<div class="alert alert-danger"><ul class="mb-0">';
                            
                            Object.keys(errors).forEach(function(key) {
                                errors[key].forEach(function(error) {
                                    errorHtml += `<li>${error}</li>`;
                                });
                            });
                            
                            errorHtml += '</ul></div>';

                            // Insert error messages at the top of the form
                            const existingAlert = $('#leaveRequestForm .alert-danger');
                            if (existingAlert.length) {
                                existingAlert.html(errorHtml);
                            } else {
                                $('#leaveRequestForm').prepend(errorHtml);
                            }

                            // Scroll to top of modal to show errors
                            $('.modal-body').scrollTop(0);
                        } else {
                            // Handle other types of errors
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred while submitting your request',
                                confirmButtonColor: '#3085d6'
                            });
                        }
                    }
                });
            });

            // Clear error messages when modal is closed
            $('#leaveRequestModal').on('hidden.bs.modal', function () {
                $('.alert-danger').remove();
                const submitBtn = $('#submitBtn');
                submitBtn.prop('disabled', false);
                submitBtn.find('.normal-text').removeClass('d-none');
                submitBtn.find('.loading-text').addClass('d-none');
            });
        });
    </script>

    <style>
        /* Toast styles */
        .colored-toast.swal2-icon-success {
            box-shadow: 0 0 12px rgba(40, 167, 69, 0.4) !important;
        }
        .colored-toast.swal2-icon-error {
            box-shadow: 0 0 12px rgba(220, 53, 69, 0.4) !important;
        }
    </style>
@stop
<script>
    // document.getElementById('employee_id').addEventListener('change', function() {
    //     const employeeId = this.value;
    //     const paymentStatusCheckbox = document.getElementById('payment_status');
    //     const paymentStatusLabel = document.getElementById('payment_status_label');

    //     if (employeeId) {
    //         fetch(`/employees/${employeeId}/status`)
    //             .then(response => response.json())
    //             .then(data => {
    //                 if (data.employment_status === 'REGULAR') {
    //                     paymentStatusCheckbox.checked = true;
    //                     paymentStatusLabel.innerText = 'With Pay';
    //                 } else {
    //                     paymentStatusCheckbox.checked = false;
    //                     paymentStatusLabel.innerText = 'Without Pay';
    //                 }
    //             })
    //             .catch(error => console.error('Error fetching employee status:', error));
    //     } else {
    //         paymentStatusCheckbox.checked = false;
    //         paymentStatusLabel.innerText = '';
    //     }
    // });

    function toggleDateInputs() {
        const leaveType = document.getElementById('leave_type').value;
        const dateFrom = document.getElementById('date_from');
        const dateTo = document.getElementById('date_to');
        const typeLabel = document.getElementById('typeLabel');

        if (leaveType === 'Leave') {
            dateFrom.type = 'date';
            dateTo.type = 'date';
            typeLabel.innerHTML = 'Type of Leave<span class="text-danger">*</span>';
        } else if (leaveType === 'Undertime' || leaveType === 'Halfday') {
            dateFrom.type = 'datetime-local';
            dateTo.type = 'datetime-local';
            typeLabel.innerHTML = leaveType === 'Undertime' ? 
                'Undertime deducted to<span class="text-danger">*</span>' : 
                'Halfday deducted to<span class="text-danger">*</span>';
        } else {
            dateFrom.type = 'datetime-local';
            dateTo.type = 'datetime-local';
            typeLabel.innerHTML = 'Undertime deducted to<span class="text-danger">*</span>';
        }
    }
</script>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="helpModalLabel">
                    <i class="fas fa-question-circle mr-2"></i>
                    Leave Application Guide
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="accordion" id="leaveHelpAccordion">
                    <!-- How to Apply -->
                    <div class="card border-0 mb-2">
                        <div class="card-header bg-light" id="howToApplyHeading">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left text-success" type="button" data-toggle="collapse" data-target="#howToApplyCollapse">
                                    <i class="fas fa-file-alt mr-2"></i> How to Apply for Leave
                                </button>
                            </h2>
                        </div>
                        <div id="howToApplyCollapse" class="collapse show" data-parent="#leaveHelpAccordion">
                            <div class="card-body">
                                <ol class="pl-3">
                                    <li>Click the "Apply for Leave" button on the main page</li>
                                    <li>Ensure that you already update you employee profile where you can add an signature</li>
                                    <li>Select your leave type (Leave, Halfday, Undertime)</li>
                                    <li>Choose the appropriate dates</li>
                                    <li>Select the type of leave from the dropdown</li>
                                    <li>Provide a clear reason for your leave</li>
                                    <li>Review your application before submitting</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Leave Types -->
                    <div class="card border-0 mb-2">
                        <div class="card-header bg-light" id="leaveTypesHeading">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed text-success" type="button" data-toggle="collapse" data-target="#leaveTypesCollapse">
                                    <i class="fas fa-list-ul mr-2"></i> Types of Leave
                                </button>
                            </h2>
                        </div>
                        <div id="leaveTypesCollapse" class="collapse" data-parent="#leaveHelpAccordion">
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <strong class="text-success">Vacation Leave:</strong>
                                        <p class="text-muted mb-0">For personal time off, holidays, or recreational purposes.</p>
                                    </li>
                                    <li class="mb-3">
                                        <strong class="text-success">Sick Leave:</strong>
                                        <p class="text-muted mb-0">For medical appointments, illness, or recovery.</p>
                                    </li>
                                    <li class="mb-3">
                                        <strong class="text-success">Emergency Leave:</strong>
                                        <p class="text-muted mb-0">For urgent personal or family matters.</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Important Notes -->
                    <div class="card border-0 mb-2">
                        <div class="card-header bg-light" id="notesHeading">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed text-success" type="button" data-toggle="collapse" data-target="#notesCollapse">
                                    <i class="fas fa-exclamation-circle mr-2"></i> Leave Application Guidelines
                                </button>
                            </h2>
                        </div>
                        <div id="notesCollapse" class="collapse" data-parent="#leaveHelpAccordion">
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <h6 class="font-weight-bold mb-3">Application Timeline Requirements:</h6>
                                    <ul class="mb-3">
                                        <li class="mb-2"><strong>Vacation Leave:</strong> Must be submitted at least 2 weeks (14 working days) prior to the intended leave date to ensure proper work delegation and coverage.</li>
                                        <li class="mb-2"><strong>Sick Leave:</strong> Must be filed on the same day or within 24 hours of returning to work. A medical certificate is required for sick leaves exceeding 2 consecutive days.</li>
                                        <li class="mb-2"><strong>Emergency Leave:</strong> Must be reported to immediate supervisor through call/message on the same day and formally applied within 24 hours of the incident.</li>
                                    </ul>
                                    
                                    <h6 class="font-weight-bold mb-3">Important Reminders:</h6>
                                    <ul class="mb-0">
                                        <li class="mb-2">Verify your leave credit balance before applying</li>
                                        <li class="mb-2">Required Supporting Documents:
                                            <ul>
                                                <li>Sick Leave: Medical certificate for 2+ days of absence</li>
                                                <li>Emergency Leave: Relevant documentation (if applicable)</li>
                                            </ul>
                                        </li>
                                        <li class="mb-2">Leave applications outside these guidelines may be denied or subject to disciplinary action</li>
                                        <li>Managers reserve the right to deny leave requests based on operational requirements</li>
                                    </ul>
                                </div>
                                
                                <div class="alert alert-warning mt-3">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Note:</strong> Failure to comply with these guidelines may result in unauthorized absence (AWOL) and corresponding sanctions as per company policy.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact HR -->
                    <div class="card border-0">
                        <div class="card-header bg-light" id="contactHeading">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed text-success" type="button" data-toggle="collapse" data-target="#contactCollapse">
                                    <i class="fas fa-phone-alt mr-2"></i> HR Department Contact Information
                                </button>
                            </h2>
                        </div>
                        <div id="contactCollapse" class="collapse" data-parent="#leaveHelpAccordion">
                            <div class="card-body">
                                <div class="alert alert-light border">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-envelope text-success fa-2x mr-3"></i>
                                        <div>
                                            <h6 class="mb-1">Email Support</h6>
                                            <p class="mb-0">hr@company.com</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-phone-alt text-success fa-2x mr-3"></i>
                                        <div>
                                            <h6 class="mb-1">Phone Support</h6>
                                            <p class="mb-0">+1 234 567 890</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fab fa-telegram text-success fa-2x mr-3"></i>
                                        <div>
                                            <h6 class="mb-1">Telegram</h6>
                                            <p class="mb-0"><a href="https://t.me/MhrHrDepartment" target="_blank" class="text-primary font-weight-bold">HR Department</a></p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clock text-success fa-2x mr-3"></i>
                                        <div>
                                            <h6 class="mb-1">HR Office Hours</h6>
                                            <p class="mb-0">Monday - Friday: 8:00 AM - 5:00 PM</p>
                                            <p class="mb-0">Closed on Weekends and Holidays</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection
