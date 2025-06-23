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
                        <h3 class="card-title">Create Payroll</h3>
                    </div>
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

                        <form action="{{ route('payroll.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payroll_type">Payroll Type<span class="text-danger">*</span></label>
                                        <select name="payroll_type" id="payroll_type" class="form-control" required>
                                            <option value="regular">Regular (Bi-monthly)</option>
                                            <option value="weekly">Weekly (BGPDI)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">Start Date<span class="text-danger">*</span></label>
                                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date">End Date<span class="text-danger">*</span></label>
                                        <input type="date" name="end_date" id="end_date" class="form-control" required readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="btn-group" role="group" aria-label="Button group">
                                        <button type="submit" class="btn btn-primary">Generate Payroll for All</button>&nbsp;&nbsp;
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#specificEmployeeModal">
                                            Generate for Specific Employee
                                        </button>&nbsp;&nbsp;
                                        <a href="{{ route('payroll.index') }}" class="btn btn-info">Back</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Specific Employee -->
    <div class="modal fade" id="specificEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="specificEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="specificEmployeeModalLabel">Generate Payroll for Specific Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('payroll.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="modal_payroll_type">Payroll Type<span class="text-danger">*</span></label>
                            <select name="payroll_type" id="modal_payroll_type" class="form-control" required>
                                <option value="regular">Regular (Bi-monthly)</option>
                                <option value="weekly">Weekly (BGPDI)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="employee_id">Select Employee<span class="text-danger">*</span></label>
                            <select name="employee_id" id="employee_id" class="form-control" required data-placeholder="Search for an employee...">
                                <option value="">Select an employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->last_name }}, {{ $employee->first_name }}{{ !empty($employee->middle_name) ? ' '.$employee->middle_name : '' }}{{ !empty($employee->suffix) ? ' '.$employee->suffix : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="modal_start_date">Start Date<span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="modal_start_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="modal_end_date">End Date<span class="text-danger">*</span></label>
                            <input type="date" name="end_date" id="modal_end_date" class="form-control" required readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Generate Payroll</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
    <style>
        /* Enhanced Select2 styling */
        .select2-container--bootstrap4 .select2-selection {
            height: calc(1.5em + 0.75rem + 2px);
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
        }
        
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: calc(1.5em + 0.25rem);
        }
        
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + 0.75rem);
        }
        
        /* Ensure dropdowns are visible in modal */
        .select2-container--bootstrap4 {
            z-index: 1060;
        }

        /* Highlight selected item */
        .select2-results__option--highlighted {
            background-color: #007bff !important;
            color: white !important;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for all selects
            $('select').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            // Special handling for modal selects
            $('#specificEmployeeModal').on('shown.bs.modal', function () {
                // Reinitialize Select2 elements within the modal to ensure proper rendering
                $('#modal_payroll_type').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    dropdownParent: $('#specificEmployeeModal .modal-content')
                });
                
                // Enhanced employee select with search capabilities
                $('#employee_id').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    dropdownParent: $('#specificEmployeeModal .modal-content'),
                    placeholder: 'Search for an employee...',
                    allowClear: true,
                    minimumInputLength: 1,
                    templateResult: formatEmployeeOption,
                    templateSelection: formatEmployeeSelection
                });
            });
            
            // Format employee dropdown options
            function formatEmployeeOption(employee) {
                if (!employee.id) return employee.text;
                return $('<span style="padding: 6px 0; display: block;">' + employee.text + '</span>');
            }
            
            // Format the selected employee display
            function formatEmployeeSelection(employee) {
                if (!employee.id) return employee.text;
                return employee.text;
            }

            function setEndDate(startDateInput, endDateInput, payrollType) {
                var startDate = new Date(startDateInput.val());
                var endDate = new Date(startDate);

                if (payrollType === 'weekly') {
                    // For weekly payroll (BGPDI)
                    endDate.setDate(startDate.getDate() + 6); // Add 6 days to make it a week
                } else {
                    // For regular bi-monthly payroll
                    if (startDate.getDate() >= 11 && startDate.getDate() <= 25) {
                        endDate.setDate(25);
                    } else if (startDate.getDate() >= 26 || startDate.getDate() <= 10) {
                        if (startDate.getDate() >= 26) {
                            endDate.setMonth(startDate.getMonth() + 1);
                        }
                        endDate.setDate(10);
                    }
                }

                var formattedEndDate = endDate.toISOString().split('T')[0];
                endDateInput.val(formattedEndDate);
            }

            // Handle payroll type change
            function handlePayrollTypeChange() {
                var payrollType = $('#payroll_type').val();
                var startDate = $('#start_date').val();
                if (startDate) {
                    setEndDate($('#start_date'), $('#end_date'), payrollType);
                }
            }

            // Event listeners
            $('#payroll_type').change(handlePayrollTypeChange);
            $('#start_date').change(function() {
                setEndDate($('#start_date'), $('#end_date'), $('#payroll_type').val());
            });

            // Modal handlers
            $('#modal_payroll_type').change(function() {
                if ($('#modal_start_date').val()) {
                    setEndDate($('#modal_start_date'), $('#modal_end_date'), $(this).val());
                }
            });

            $('#modal_start_date').change(function() {
                setEndDate($('#modal_start_date'), $('#modal_end_date'), $('#modal_payroll_type').val());
            });
        });
    </script>
@stop
