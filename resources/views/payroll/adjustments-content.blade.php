<!-- Company header with fixed position -->
<div class="payroll-header py-3 bg-light border-bottom sticky-top">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 text-center">
                <h5 class="font-weight-bold mb-0">MEDICAL & HOSPITAL RESOURCES HEALTH CARE, INC.</h5>
                <p class="mb-0">PAYROLL <span class="payroll-year">{{ date('Y') }}</span></p>
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
                            @foreach($departments as $code => $name)
                                <option value="{{ $code }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100" id="toggleAllDepartments">
                            <i class="fas fa-compress-alt"></i> <span>Collapse All</span>
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
        <table class="table table-bordered table-sm table-striped" id="adjustmentTable">
            <thead class="thead-light sticky-header">
                <tr>
                    <th colspan="2" class="text-center position-relative">
                        Employee
                        <i class="fas fa-info-circle text-info ml-1" data-toggle="tooltip" title="Employee information"></i>
                    </th>
                    <th rowspan="2" class="position-relative">
                        Position
                        <i class="fas fa-info-circle text-info ml-1" data-toggle="tooltip" title="Employee position"></i>
                    </th>
                    <th rowspan="2" class="position-relative">
                        Department
                        <i class="fas fa-info-circle text-info ml-1" data-toggle="tooltip" title="Employee department"></i>
                    </th>
                    <th rowspan="2" class="position-relative">
                        Monthly Rate
                        <i class="fas fa-info-circle text-info ml-1" data-toggle="tooltip" title="Base monthly salary"></i>
                    </th>
                    <th rowspan="2">Daily Rate</th>
                    <th rowspan="2" class="position-relative">
                        L/UT/LWOP
                        <i class="fas fa-info-circle text-info ml-1" data-toggle="tooltip" title="Leave without pay/late undertime"></i>
                    </th>
                    <th rowspan="2" class="editable-column position-relative">
                        Adjustments
                        <i class="fas fa-edit text-warning ml-1" data-toggle="tooltip" title="Editable: Salary adjustments"></i>
                    </th>
                    <th colspan="4" class="text-center position-relative">
                        In hours
                        <i class="fas fa-info-circle text-info ml-1" data-toggle="tooltip" title="Overtime and holiday hours"></i>
                    </th>
                    <th rowspan="2" class="editable-column position-relative">
                        Allowances
                        <i class="fas fa-edit text-warning ml-1" data-toggle="tooltip" title="Editable: Employee allowances"></i>
                    </th>
                    <th rowspan="2" class="editable-column position-relative">
                        Other Adjustments
                        <i class="fas fa-edit text-warning ml-1" data-toggle="tooltip" title="Editable: Additional adjustments"></i>
                    </th>
                    <th rowspan="2">SSS EE</th>
                    <th rowspan="2">SSS ER</th>
                    <th rowspan="2">HDMF EE</th>
                    <th rowspan="2">PHIC EE</th>
                    <th rowspan="2">SSS Loan</th>
                    <th rowspan="2">HDMF Loan</th>
                    <th rowspan="2">Cash Advance Salary</th>
                    <th rowspan="2" class="editable-column position-relative">
                        Cash Bond
                        <i class="fas fa-edit text-warning ml-1" data-toggle="tooltip" title="Editable: Cash bond deductions"></i>
                    </th>
                    <th rowspan="2" class="editable-column position-relative">
                        Other Deduction
                        <i class="fas fa-edit text-warning ml-1" data-toggle="tooltip" title="Editable: Additional deductions"></i>
                    </th>
                    <th rowspan="2" class="editable-column position-relative">
                        Description
                        <i class="fas fa-edit text-warning ml-1" data-toggle="tooltip" title="Editable: Description for other deductions"></i>
                    </th>
                    <th rowspan="2">TAX</th>
                    <th rowspan="2">Net Salary</th>
                </tr>
                <tr>
                    <th class="position-relative">
                        Number
                        <i class="fas fa-info-circle text-info ml-1" data-toggle="tooltip" title="Last 4 digits of employee company ID"></i>
                    </th>
                    <th>Name</th>
                    <th class="position-relative">
                        OT Hours
                        <i class="fas fa-info-circle text-info ml-1" data-toggle="tooltip" title="Overtime hours worked"></i>
                    </th>
                    <th>LH</th>
                    <th>SH</th>
                    <th>NP</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Filter payrolls based on user role
                    $filteredPayrollsByDepartment = collect();
                    
                    if ($payrollsByDepartment->isNotEmpty()) {
                        if (auth()->user()->hasRole('HR ComBen')) {
                            // HR ComBen can only see Rank File employees
                            foreach ($payrollsByDepartment as $department => $payrolls) {
                                $filteredPayrolls = $payrolls->filter(function($payroll) {
                                    return isset($payroll->employee->rank) && $payroll->employee->rank === 'Rank File';
                                });
                                
                                if ($filteredPayrolls->isNotEmpty()) {
                                    $filteredPayrollsByDepartment[$department] = $filteredPayrolls;
                                }
                            }
                        } elseif (auth()->user()->hasRole(['Finance', 'Super Admin'])) {
                            // Finance and Super Admin can see all payrolls
                            $filteredPayrollsByDepartment = $payrollsByDepartment;
                        }
                    }

                    // Regroup based on the new grouping logic
                    $regroupedPayrolls = collect();
                    
                    foreach ($filteredPayrollsByDepartment as $department => $payrolls) {
                        foreach ($payrolls as $payroll) {
                            $deptName = $payroll->employee->department->name ?? 'Unknown';
                            $employeeRank = $payroll->employee->rank ?? 'Unknown';
                            $employmentStatus = $payroll->employee->employment_status ?? 'Unknown';
                            
                            // Determine the main group
                            $mainGroup = "";
                            if (in_array($deptName, ['Admin Department', 'Supply Chain Department', 'Finance and Accounting Department', 'Human Resources Department'])) {
                                $mainGroup = "Support Personnel";
                            } elseif (in_array($deptName, ['Marketing Department', 'Technical Department'])) {
                                $mainGroup = "MHRHCI";
                            } elseif ($deptName === 'BGPDI') {
                                $mainGroup = "BGPDI";
                            } elseif ($deptName === 'VHI') {
                                $mainGroup = "VHI";
                            } else {
                                $mainGroup = $deptName; // Use department name for any other departments
                            }
                            
                            // Add employment status grouping for trainees
                            if ($employmentStatus === 'TRAINEE') {
                                $mainGroup .= " - TRAINEE";
                            } else {
                                // Add confidentiality level to group
                                $confLevel = ($employeeRank === 'Rank File') ? "Non Confi" : "Confi";
                                $mainGroup .= " - " . $confLevel;
                            }
                            
                            // Add to the regrouped collection
                            if (!isset($regroupedPayrolls[$mainGroup])) {
                                $regroupedPayrolls[$mainGroup] = collect();
                            }
                            
                            $regroupedPayrolls[$mainGroup]->push($payroll);
                        }
                    }
                    
                    // Sort the main groups alphabetically
                    $regroupedPayrolls = $regroupedPayrolls->sortKeys();
                @endphp
                
                @if($regroupedPayrolls->isEmpty())
                    <tr>
                        <td colspan="25" class="text-center">No payroll records found for the selected date range.</td>
                    </tr>
                @else
                    @foreach($regroupedPayrolls as $group => $payrolls)
                        <tr class="bg-light department-header" data-department="{{ str_replace(' ', '-', $group) }}">
                            <td colspan="25" class="font-weight-bold">
                                <i class="fas fa-chevron-down mr-2 toggle-department"></i>
                                {{ strtoupper($group) }}
                            </td>
                        </tr>
                        @foreach($payrolls as $payroll)
                            <tr class="employee-row" data-department="{{ str_replace(' ', '-', $group) }}" data-search="{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }} {{ $payroll->employee->position->name ?? $payroll->employee->position }}" data-payroll-id="{{ $payroll->id }}">
                                <td class="employee-id-cell text-center">
                                    <span class="id-badge">{{ substr($payroll->employee->company_id, -4) }}</span>
                                </td>
                                <td>{{ $payroll->employee->last_name }}, {{ $payroll->employee->first_name }}</td>
                                <td>{{ $payroll->employee->position->name ?? $payroll->employee->position }}</td>
                                <td>{{ $payroll->employee->department->name ?? 'N/A' }}</td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm numeric-input" 
                                        readonly 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        value="{{ number_format($payroll->employee->salary, 2, '.', '') }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm numeric-input" 
                                        readonly 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        value="{{ number_format($payroll->employee->salary / 26, 2, '.', '') }}">
                                </td>
                                <td>
                                    <input type="text" 
                                        class="form-control form-control-sm"
                                        readonly
                                        value="@php
                                            // Convert both times to minutes
                                            list($lateHours, $lateMinutes) = array_map('intval', explode(':', $payroll->late_time));
                                            list($underHours, $underMinutes) = array_map('intval', explode(':', $payroll->under_time));
                                            
                                            // Get unpaid leave hours (stored as decimal) and convert to minutes
                                            $unpaidLeaveMinutes = ($payroll->unpaid_leave_hours ?? 0) * 60;
                                            
                                            // Calculate total minutes
                                            $totalMinutes = ($lateHours * 60 + $lateMinutes) + ($underHours * 60 + $underMinutes) + $unpaidLeaveMinutes;
                                            
                                            // Convert back to HH:MM format
                                            $hours = floor($totalMinutes / 60);
                                            $minutes = $totalMinutes % 60;
                                            
                                            echo sprintf('%02d:%02d', $hours, $minutes);
                                        @endphp">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm adjustment-field numeric-input {{ isset($payroll->adjustments) || $payroll->adjustments === 0.0 ? 'existing-value' : 'new-value' }}" 
                                        placeholder="Enter value" 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        data-original="{{ isset($payroll->adjustments) || $payroll->adjustments === 0.0 ? number_format($payroll->adjustments, 2, '.', '') : '0.00' }}"
                                        value="{{ isset($payroll->adjustments) || $payroll->adjustments === 0.0 ? number_format($payroll->adjustments, 2, '.', '') : '' }}">
                                </td>
                                <td class="text-center">
                                    <div class="input-group input-group-sm">
                                        <input type="text" 
                                            class="form-control form-control-sm text-right" 
                                            readonly 
                                            value="@php
                                                if(isset($payroll->overtime_hours)) {
                                                    $hours = floor($payroll->overtime_hours);
                                                    $minutes = round(($payroll->overtime_hours - $hours) * 60);
                                                    // Handle case where minutes round up to 60
                                                    if($minutes == 60) {
                                                        $hours++;
                                                        $minutes = 0;
                                                    }
                                                    echo sprintf('%d:%02d', $hours, $minutes);
                                                } else {
                                                    echo '0:00';
                                                }
                                            @endphp"
                                            style="min-width: 100px;"
                                            data-toggle="tooltip" 
                                            title="Hours (decimal): {{ isset($payroll->overtime_hours) ? number_format($payroll->overtime_hours, 2, '.', ',') : '0.00' }} | Overtime Pay: ₱{{ isset($payroll->overtime_pay) ? number_format($payroll->overtime_pay, 2, '.', ',') : '0.00' }}">
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="input-group input-group-sm">
                                        <input type="number" 
                                            class="form-control form-control-sm numeric-input text-right" 
                                            readonly 
                                            min="0"
                                            step="0.01"
                                            data-type="currency"
                                            value="{{isset($payroll->regular_holiday_hours) ? number_format($payroll->regular_holiday_hours, 2, '.', '') : '0.00' }}"
                                            style="min-width: 100px;">
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="input-group input-group-sm">
                                        <input type="number" 
                                            class="form-control form-control-sm numeric-input text-right" 
                                            readonly 
                                            min="0"
                                            step="0.01"
                                            data-type="currency"
                                            value="{{isset($payroll->special_holiday_hours) ? number_format($payroll->special_holiday_hours, 2, '.', '') : '0.00' }}"
                                            style="min-width: 100px;">
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="input-group input-group-sm">
                                        <input type="number" 
                                            class="form-control form-control-sm numeric-input text-right" 
                                            readonly 
                                            min="0"
                                            step="0.01"
                                            data-type="currency"
                                            value="{{isset($payroll->night_premium_hours) ? number_format($payroll->night_premium_hours, 2, '.', '') : '0.00' }}"
                                            style="min-width: 100px;">
                                    </div>
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm allowance-field numeric-input {{ isset($payroll->allowances) || $payroll->allowances === 0.0 ? 'existing-value' : 'new-value' }}" 
                                        placeholder="Enter value" 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        data-original="{{ isset($payroll->allowances) || $payroll->allowances === 0.0 ? number_format($payroll->allowances, 2, '.', '') : '0.00' }}"
                                        value="{{ isset($payroll->allowances) || $payroll->allowances === 0.0 ? number_format($payroll->allowances, 2, '.', '') : '' }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm other-adj-field numeric-input {{ isset($payroll->other_adjustments) || $payroll->other_adjustments === 0.0 ? 'existing-value' : 'new-value' }}" 
                                        placeholder="Enter value" 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        data-original="{{ isset($payroll->other_adjustments) || $payroll->other_adjustments === 0.0 ? number_format($payroll->other_adjustments, 2, '.', '') : '0.00' }}"
                                        value="{{ isset($payroll->other_adjustments) || $payroll->other_adjustments === 0.0 ? number_format($payroll->other_adjustments, 2, '.', '') : '' }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm numeric-input" 
                                        readonly 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        value="{{ number_format($payroll->sss_contribution, 2, '.', '') }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm numeric-input" 
                                        readonly 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        value="{{ $payroll->employer_sss_contribution, 2 }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm numeric-input" 
                                        readonly 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        value="{{ number_format($payroll->pagibig_contribution, 2, '.', '') }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm numeric-input" 
                                        readonly 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        value="{{ number_format($payroll->philhealth_contribution, 2, '.', '') }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm numeric-input" 
                                        readonly 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        value="{{ number_format($payroll->sss_loan, 2, '.', '') }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm numeric-input" 
                                        readonly 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        value="{{ number_format($payroll->pagibig_loan, 2, '.', '') }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm numeric-input" 
                                        readonly 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        value="{{ number_format($payroll->cash_advance, 2, '.', '') }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm cash-bond-field numeric-input {{ isset($payroll->cash_bond) || $payroll->cash_bond === 0.0 ? 'existing-value' : 'new-value' }}" 
                                        placeholder="Enter value" 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        data-original="{{ isset($payroll->cash_bond) || $payroll->cash_bond === 0.0 ? number_format($payroll->cash_bond, 2, '.', '') : '0.00' }}"
                                        value="{{ isset($payroll->cash_bond) || $payroll->cash_bond === 0.0 ? number_format($payroll->cash_bond, 2, '.', '') : '' }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm other-deduct-field numeric-input {{ isset($payroll->other_deduction) || $payroll->other_deduction === 0.0 ? 'existing-value' : 'new-value' }}" 
                                        placeholder="Enter value" 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        data-original="{{ isset($payroll->other_deduction) || $payroll->other_deduction === 0.0 ? number_format($payroll->other_deduction, 2, '.', '') : '0.00' }}"
                                        value="{{ isset($payroll->other_deduction) || $payroll->other_deduction === 0.0 ? number_format($payroll->other_deduction, 2, '.', '') : '' }}">
                                </td>
                                <td>
                                    <input type="text" 
                                        class="form-control form-control-sm other-deduct-desc-field" 
                                        placeholder="Description"
                                        maxlength="500"
                                        data-original="{{ $payroll->other_deduction_description ?? '' }}"
                                        value="{{ $payroll->other_deduction_description ?? '' }}">
                                </td>
                                <td>
                                    <input type="number" 
                                        class="form-control form-control-sm numeric-input" 
                                        readonly 
                                        min="0"
                                        step="0.01"
                                        data-type="currency"
                                        value="0.00">
                                </td>
                                <td class="text-right font-weight-bold net-salary-value">{{ number_format($payroll->net_salary, 2) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div> 

<style>
/* Add styling for existing and new values */
.existing-value {
    background-color: #e8f4f8;
    border-color: #17a2b8;
}

/* Employee ID styling */
.employee-id-cell {
    background-color: #f8f9fa;
    vertical-align: middle !important;
}

.id-badge {
    display: inline-block;
    min-width: 60px;
    font-family: 'Courier New', monospace;
    font-weight: 600;
    font-size: 1rem;
    padding: 2px 8px;
    border-radius: 4px;
    background-color: #e9ecef;
    border: 1px solid #ced4da;
    color: #495057;
    text-align: center;
    letter-spacing: 1px;
}

/* Make ID visible on hover and add transition effect */
.id-badge:hover {
    background-color: #007bff;
    color: white;
    transform: scale(1.1);
    transition: all 0.2s ease;
    cursor: default;
}

.new-value {
    background-color: #fff8e6;
    border-color: #ffc107;
}

.other-deduct-desc-field {
    border-color: #ced4da;
    font-size: 0.8rem;
    background-color: #f8f9fa;
    width: 100%;
    min-width: 120px;
}

.other-deduct-desc-field:focus {
    background-color: #fff;
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.field-info {
    font-size: 0.7rem;
    display: block;
    margin-top: -3px;
    text-align: right;
    font-style: italic;
}

/* Field focus effects */
.existing-value:focus {
    background-color: #d1ebf1;
    border-color: #17a2b8;
    box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
}

.new-value:focus {
    background-color: #fff3cd;
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

/* Numeric input styling */
.numeric-input {
    text-align: right;
    font-variant-numeric: tabular-nums;
    font-family: 'Courier New', monospace;
    padding-right: 5px;
    width: 100%;
    min-width: 80px;
}

.numeric-input::-webkit-inner-spin-button,
.numeric-input::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.numeric-input[readonly] {
    background-color: #f8f9fa;
    cursor: default;
}

/* Enhanced table cell styling */
#adjustmentTable th, 
#adjustmentTable td {
    padding: 4px 6px;
    vertical-align: middle;
    position: relative;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 120px; /* Reduced from 150px to account for extra column */
}

#adjustmentTable td input {
    max-width: 100%;
}

/* Add hover effect to show full content */
#adjustmentTable td:hover {
    overflow: visible;
    z-index: 1;
}

#adjustmentTable td:hover input {
    position: relative;
    background-color: #ffffff;
    box-shadow: 0 0 5px rgba(0,0,0,0.2);
    z-index: 2;
}

/* Hover tooltip for truncated content */
.cell-content-tooltip {
    position: absolute;
    background: #333;
    color: #fff;
    padding: 5px 8px;
    border-radius: 4px;
    font-size: 12px;
    z-index: 100;
    max-width: 250px;
    white-space: normal;
    display: none;
}

/* Responsive table layout */
@media (max-width: 1200px) {
    .table-responsive {
        overflow-x: auto;
    }
    
    #adjustmentTable {
        min-width: 1400px;
    }
    
    #adjustmentTable td {
        max-width: 120px;
    }
}

@media (max-width: 768px) {
    #adjustmentTable td {
        padding: 3px 4px;
        font-size: 0.9rem;
    }
    
    .form-control-sm {
        height: calc(1.5em + 0.5rem + 2px);
        padding: 0.15rem 0.3rem;
        font-size: 0.85rem;
    }
}

/* Highlight changes in net salary */
.text-success {
    font-weight: bold !important;
}

.text-danger {
    font-weight: bold !important;
}

.change-indicator {
    font-size: 0.8rem;
    font-style: italic;
}

/* Instructions panel */
.adjustment-instructions {
    background-color: #f8f9fa;
    border-left: 4px solid #17a2b8;
    padding: 10px 15px;
    margin-bottom: 20px;
    font-size: 0.9rem;
}

.adjustment-instructions ul {
    padding-left: 20px;
    margin-bottom: 0;
}

.adjustment-instructions li {
    margin-bottom: 5px;
}

/* Value indicator - shows small indicator when value is present */
.has-value::after {
    content: "";
    position: absolute;
    top: 2px;
    right: 2px;
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background-color: #28a745;
}
</style>

<script>
// Format numbers to always display with 2 decimal places
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Handle input changes for numeric fields
    document.querySelectorAll('.numeric-input').forEach(function(input) {
        input.addEventListener('focus', function() {
            // Remove any non-numeric formatting when focused
            let value = this.value.replace(/[^\d.-]/g, '');
            if (value) {
                this.value = parseFloat(value).toFixed(2);
            }
        });
        
        input.addEventListener('blur', function() {
            // Format to 2 decimal places when losing focus
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
                
                // Add 'has-value' class to parent TD if value is greater than zero
                if (parseFloat(this.value) > 0) {
                    this.parentElement.classList.add('has-value');
                } else {
                    this.parentElement.classList.remove('has-value');
                }
            }
        });
        
        // Handle invalid input
        input.addEventListener('input', function() {
            if (this.value !== '' && isNaN(parseFloat(this.value))) {
                this.value = parseFloat(this.dataset.original || 0).toFixed(2);
            }
        });
        
        // Initial setup for existing values
        if (input.value && parseFloat(input.value) > 0) {
            input.parentElement.classList.add('has-value');
        }
    });
    
    // Handle description fields for other deductions
    document.querySelectorAll('.other-deduct-desc-field').forEach(function(input) {
        // Mark as modified when changed
        input.addEventListener('input', function() {
            this.classList.add('modified');
        });
        
        // No need to handle visibility anymore since it's in its own cell
    });
    
    // Make table cells adapt to content
    document.querySelectorAll('#adjustmentTable td').forEach(function(cell) {
        // Add title attribute for hover tooltip on content that might be truncated
        const inputElement = cell.querySelector('input');
        if (inputElement && inputElement.value) {
            cell.setAttribute('title', inputElement.value);
        }
    });
    
    // Implement responsive column toggle for smaller screens
    const handleResponsiveTable = function() {
        if (window.innerWidth < 992) {
            // Add column toggle buttons if not already present
            if (!document.getElementById('columnToggleContainer')) {
                const toggleContainer = document.createElement('div');
                toggleContainer.id = 'columnToggleContainer';
                toggleContainer.className = 'mb-3 d-flex flex-wrap';
                
                const columns = [
                    { id: 'col-position', label: 'Position', target: 2 },
                    { id: 'col-department', label: 'Department', target: 3 },
                    { id: 'col-monthly', label: 'Monthly Rate', target: 4 },
                    { id: 'col-daily', label: 'Daily Rate', target: 5 },
                    { id: 'col-lwop', label: 'L/UT/LWOP', target: 6 }
                ];
                
                columns.forEach(function(col) {
                    const btn = document.createElement('button');
                    btn.className = 'btn btn-sm btn-outline-secondary mr-1 mb-1';
                    btn.textContent = col.label;
                    btn.dataset.target = col.target;
                    btn.addEventListener('click', function() {
                        // Toggle column visibility
                        const target = parseInt(this.dataset.target);
                        const cells = document.querySelectorAll(`#adjustmentTable td:nth-child(${target+1}), #adjustmentTable th:nth-child(${target+1})`);
                        cells.forEach(function(cell) {
                            cell.classList.toggle('d-none');
                        });
                        btn.classList.toggle('active');
                    });
                    toggleContainer.appendChild(btn);
                });
                
                document.querySelector('.payroll-header').appendChild(toggleContainer);
            }
        }
    };
    
    // Run initially and on window resize
    handleResponsiveTable();
    window.addEventListener('resize', handleResponsiveTable);
    
    // Handle department expansion/collapse
    document.querySelectorAll('.toggle-department').forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            const departmentHeader = this.closest('.department-header');
            const department = departmentHeader.dataset.department;
            const rows = document.querySelectorAll(`tr.employee-row[data-department="${department}"]`);
            
            rows.forEach(function(row) {
                row.classList.toggle('d-none');
            });
            
            // Toggle icon
            this.classList.toggle('fa-chevron-down');
            this.classList.toggle('fa-chevron-right');
        });
    });
    
    // Toggle all departments
    document.getElementById('toggleAllDepartments').addEventListener('click', function() {
        const allRows = document.querySelectorAll('.employee-row');
        const allToggles = document.querySelectorAll('.toggle-department');
        const isCollapsed = this.querySelector('span').textContent.includes('Expand');
        
        allRows.forEach(function(row) {
            if (isCollapsed) {
                row.classList.remove('d-none');
            } else {
                row.classList.add('d-none');
            }
        });
        
        allToggles.forEach(function(toggle) {
            if (isCollapsed) {
                toggle.classList.remove('fa-chevron-right');
                toggle.classList.add('fa-chevron-down');
            } else {
                toggle.classList.remove('fa-chevron-down');
                toggle.classList.add('fa-chevron-right');
            }
        });
        
        // Update button text
        if (isCollapsed) {
            this.querySelector('span').textContent = 'Collapse All';
            this.querySelector('i').classList.remove('fa-expand-alt');
            this.querySelector('i').classList.add('fa-compress-alt');
        } else {
            this.querySelector('span').textContent = 'Expand All';
            this.querySelector('i').classList.remove('fa-compress-alt');
            this.querySelector('i').classList.add('fa-expand-alt');
        }
    });
    
    // Add search functionality
    document.getElementById('adjustmentSearch').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.employee-row');
        
        rows.forEach(function(row) {
            const searchText = row.dataset.search.toLowerCase();
            if (searchText.includes(searchTerm)) {
                row.classList.remove('d-none');
                // Make sure parent department is visible
                const department = row.dataset.department;
                const departmentHeader = document.querySelector(`.department-header[data-department="${department}"]`);
                departmentHeader.classList.remove('d-none');
                
                // Make sure chevron icon is correct
                const toggle = departmentHeader.querySelector('.toggle-department');
                toggle.classList.remove('fa-chevron-right');
                toggle.classList.add('fa-chevron-down');
            } else {
                row.classList.add('d-none');
                
                // Check if all rows in department are hidden
                const department = row.dataset.department;
                const departmentRows = document.querySelectorAll(`.employee-row[data-department="${department}"]`);
                let allHidden = true;
                departmentRows.forEach(function(deptRow) {
                    if (!deptRow.classList.contains('d-none')) {
                        allHidden = false;
                    }
                });
                
                // Hide department header if all rows are hidden
                if (allHidden) {
                    const departmentHeader = document.querySelector(`.department-header[data-department="${department}"]`);
                    departmentHeader.classList.add('d-none');
                }
            }
        });
    });
    
    // Department filter - updated to handle the new grouping structure
    document.getElementById('departmentFilter').addEventListener('change', function() {
        const selectedDept = this.value;
        const departmentHeaders = document.querySelectorAll('.department-header');
        const employeeRows = document.querySelectorAll('.employee-row');
        
        if (selectedDept === '') {
            // Show all departments
            departmentHeaders.forEach(header => header.classList.remove('d-none'));
            employeeRows.forEach(row => row.classList.remove('d-none'));
        } else {
            // Show only employees from selected department
            // We need to check the actual department name in each row, not just the group
            employeeRows.forEach(function(row) {
                const deptCell = row.querySelectorAll('td')[3]; // Department column (0-indexed)
                const rowDeptName = deptCell.textContent.trim();
                
                if (rowDeptName === selectedDept) {
                    row.classList.remove('d-none');
                    // Make the group header visible
                    const groupId = row.dataset.department;
                    const header = document.querySelector(`.department-header[data-department="${groupId}"]`);
                    if (header) {
                        header.classList.remove('d-none');
                        // Update chevron
                        const toggle = header.querySelector('.toggle-department');
                        toggle.classList.remove('fa-chevron-right');
                        toggle.classList.add('fa-chevron-down');
                    }
                } else {
                    row.classList.add('d-none');
                }
            });
            
            // Check each group and hide if all rows are hidden
            departmentHeaders.forEach(function(header) {
                const groupId = header.dataset.department;
                const groupRows = document.querySelectorAll(`.employee-row[data-department="${groupId}"]`);
                
                let allHidden = true;
                groupRows.forEach(function(row) {
                    if (!row.classList.contains('d-none')) {
                        allHidden = false;
                    }
                });
                
                if (allHidden) {
                    header.classList.add('d-none');
                }
            });
        }
    });
    
    // Add a custom group filter
    const addGroupFilter = function() {
        // Get all unique groups
        const groups = [];
        document.querySelectorAll('.department-header').forEach(function(header) {
            const groupName = header.querySelector('td').textContent.trim();
            if (!groups.includes(groupName)) {
                groups.push(groupName);
            }
        });
        
        // Create select element
        const filterContainer = document.createElement('div');
        filterContainer.className = 'col-md-4 col-sm-6 mb-2';
        
        const groupSelect = document.createElement('select');
        groupSelect.className = 'form-control form-control-sm';
        groupSelect.id = 'groupFilter';
        
        // Add default option
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'All Groups';
        groupSelect.appendChild(defaultOption);
        
        // Add options for each group
        groups.forEach(function(group) {
            const option = document.createElement('option');
            option.value = group;
            option.textContent = group;
            groupSelect.appendChild(option);
        });
        
        // Add event listener
        groupSelect.addEventListener('change', function() {
            const selectedGroup = this.value;
            const headers = document.querySelectorAll('.department-header');
            
            if (selectedGroup === '') {
                // Show all groups
                headers.forEach(header => header.classList.remove('d-none'));
                document.querySelectorAll('.employee-row').forEach(row => row.classList.remove('d-none'));
            } else {
                // Show only selected group
                headers.forEach(function(header) {
                    const groupText = header.querySelector('td').textContent.trim();
                    if (groupText === selectedGroup) {
                        header.classList.remove('d-none');
                        // Show all rows in this group
                        const groupId = header.dataset.department;
                        document.querySelectorAll(`.employee-row[data-department="${groupId}"]`)
                            .forEach(row => row.classList.remove('d-none'));
                        
                        // Update chevron
                        const toggle = header.querySelector('.toggle-department');
                        toggle.classList.remove('fa-chevron-right');
                        toggle.classList.add('fa-chevron-down');
                    } else {
                        header.classList.add('d-none');
                        // Hide all rows in this group
                        const groupId = header.dataset.department;
                        document.querySelectorAll(`.employee-row[data-department="${groupId}"]`)
                            .forEach(row => row.classList.add('d-none'));
                    }
                });
            }
        });
        
        // Add label and append to container
        const label = document.createElement('label');
        label.htmlFor = 'groupFilter';
        label.className = 'sr-only';
        label.textContent = 'Filter by Group';
        
        filterContainer.appendChild(label);
        filterContainer.appendChild(groupSelect);
        
        // Insert before the toggle all button
        const toggleAllBtn = document.getElementById('toggleAllDepartments').parentNode;
        toggleAllBtn.parentNode.insertBefore(filterContainer, toggleAllBtn);
    };
    
    // Call function to add the group filter
    addGroupFilter();
});
</script> 