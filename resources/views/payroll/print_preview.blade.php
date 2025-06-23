<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Adjustments Print Preview</title>
    <style>
        @page {
            size: legal landscape;
            margin: 0.5cm;
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 9pt;
            background-color: white;
        }
        
        .print-container {
            width: 100%;
        }
        
        .company-header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #333;
        }
        
        .company-header h3 {
            margin: 0;
            font-weight: bold;
            font-size: 12pt;
        }
        
        .company-header p {
            margin: 5px 0;
            font-size: 10pt;
        }
        
        .info-row {
            display: flex;
            justify-content: center;
            margin-bottom: 5px;
            font-size: 9pt;
        }
        
        .info-item {
            margin: 0 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 8pt;
        }
        
        table th, table td {
            border: 1px solid #333;
            padding: 3px 2px;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        table th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 8pt;
        }
        
        .department-header {
            background-color: #e0e0e0;
            font-weight: bold;
        }
        
        .numeric-cell {
            text-align: right;
            font-family: 'Courier New', monospace;
        }
        
        .employee-id {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            text-align: center;
        }
        
        .net-salary {
            font-weight: bold;
            text-align: right;
        }
        
        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-box {
            width: 30%;
            text-align: center;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
        }
        
        .page-footer {
            text-align: right;
            font-size: 8pt;
            margin-top: 10px;
        }
        
        /* Narrower columns */
        .col-id { width: 3%; }
        .col-name { width: 8%; }
        .col-position { width: 8%; }
        .col-department { width: 7%; }
        .col-monthly { width: 4%; }
        .col-daily { width: 4%; }
        .col-lwop { width: 3%; }
        .col-adj { width: 4%; }
        .col-hours { width: 2.5%; }
        .col-allowances { width: 4%; }
        .col-other-adj { width: 4%; }
        .col-contributions { width: 3.5%; }
        .col-loans { width: 3.5%; }
        .col-deductions { width: 4%; }
        .col-net { width: 4%; }
        
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .department-header {
                background-color: #e0e0e0 !important;
            }
            
            table th {
                background-color: #f0f0f0 !important;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <!-- Company header -->
        <div class="company-header">
            <h3>MEDICAL & HOSPITAL RESOURCES HEALTH CARE, INC.</h3>
            <p>PAYROLL <span class="payroll-year">{{ date('Y') }}</span></p>
        </div>
        
        <!-- Payroll information -->
        <div class="info-row">
            <div class="info-item">
                <strong>Period Covered:</strong> <span class="period-cov">{{ $startDate ?? '' }} - {{ $endDate ?? '' }}</span>
            </div>
            <div class="info-item">
                <strong>Payroll:</strong> <span class="payroll-date">{{ $payrollDate ?? '' }}</span>
            </div>
            <div class="info-item">
                <strong>Pay-out:</strong> <span class="pay-out-date">{{ $payoutDate ?? '' }}</span>
            </div>
        </div>
        
        <!-- Print controls - only visible on screen -->
        <div class="no-print" style="margin: 10px 0; text-align: center;">
            <button onclick="window.print();" style="padding: 5px 10px; background-color: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer;">
                Print Payroll
            </button>
            <button onclick="window.close();" style="padding: 5px 10px; background-color: #6c757d; color: white; border: none; border-radius: 3px; cursor: pointer; margin-left: 10px;">
                Close
            </button>
        </div>
        
        <!-- Payroll table -->
        <table>
            <thead>
                <tr>
                    <th rowspan="2" class="col-id">ID</th>
                    <th rowspan="2" class="col-name">Name</th>
                    <th rowspan="2" class="col-position">Position</th>
                    <th rowspan="2" class="col-department">Department</th>
                    <th rowspan="2" class="col-monthly">Monthly Rate</th>
                    <th rowspan="2" class="col-daily">Daily Rate</th>
                    <th rowspan="2" class="col-lwop">L/UT/LWOP</th>
                    <th rowspan="2" class="col-adj">Adjustments</th>
                    <th colspan="4">In hours</th>
                    <th rowspan="2" class="col-allowances">Allowances</th>
                    <th rowspan="2" class="col-other-adj">Other Adjustments</th>
                    <th rowspan="2" class="col-contributions">SSS EE</th>
                    <th rowspan="2" class="col-contributions">SSS ER</th>
                    <th rowspan="2" class="col-contributions">HDMF EE</th>
                    <th rowspan="2" class="col-contributions">PHIC EE</th>
                    <th rowspan="2" class="col-loans">SSS Loan</th>
                    <th rowspan="2" class="col-loans">HDMF Loan</th>
                    <th rowspan="2" class="col-deductions">Cash Advance</th>
                    <th rowspan="2" class="col-deductions">Cash Bond</th>
                    <th rowspan="2" class="col-deductions">Other Deductions</th>
                    <th rowspan="2" class="col-deductions">TAX</th>
                    <th rowspan="2" class="col-net">Net Salary</th>
                </tr>
                <tr>
                    <th class="col-hours">OT</th>
                    <th class="col-hours">LH</th>
                    <th class="col-hours">SH</th>
                    <th class="col-hours">NP</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($payrollsByDepartment) && !$payrollsByDepartment->isEmpty())
                    @foreach($payrollsByDepartment as $department => $payrolls)
                        <tr class="department-header">
                            <td colspan="24">{{ $departments[$department] ?? strtoupper($department) }}</td>
                        </tr>
                        @foreach($payrolls as $payroll)
                            <tr>
                                <td class="employee-id">{{ substr($payroll->employee->company_id, -4) }}</td>
                                <td>{{ $payroll->employee->last_name }}, {{ $payroll->employee->first_name }}</td>
                                <td>{{ $payroll->employee->position->name ?? $payroll->employee->position }}</td>
                                <td>{{ $payroll->employee->department->name ?? 'N/A' }}</td>
                                <td class="numeric-cell">{{ number_format($payroll->employee->salary, 2) }}</td>
                                <td class="numeric-cell">{{ number_format($payroll->employee->salary / 26, 2) }}</td>
                                <td class="numeric-cell">
                                    @php
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
                                    @endphp
                                </td>
                                <td class="numeric-cell">{{ isset($payroll->adjustments) ? number_format($payroll->adjustments, 2) : '0.00' }}</td>
                                <td class="numeric-cell">
                                    @php
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
                                    @endphp
                                </td>
                                <td class="numeric-cell">{{ isset($payroll->regular_holiday_hours) ? number_format($payroll->regular_holiday_hours, 2) : '0.00' }}</td>
                                <td class="numeric-cell">{{ isset($payroll->special_holiday_hours) ? number_format($payroll->special_holiday_hours, 2) : '0.00' }}</td>
                                <td class="numeric-cell">{{ isset($payroll->night_premium_hours) ? number_format($payroll->night_premium_hours, 2) : '0.00' }}</td>
                                <td class="numeric-cell">{{ isset($payroll->allowances) ? number_format($payroll->allowances, 2) : '0.00' }}</td>
                                <td class="numeric-cell">{{ isset($payroll->other_adjustments) ? number_format($payroll->other_adjustments, 2) : '0.00' }}</td>
                                <td class="numeric-cell">{{ number_format($payroll->sss_contribution, 2) }}</td>
                                <td class="numeric-cell">{{ number_format($payroll->employer_sss_contribution, 2) }}</td>
                                <td class="numeric-cell">{{ number_format($payroll->pagibig_contribution, 2) }}</td>
                                <td class="numeric-cell">{{ number_format($payroll->philhealth_contribution, 2) }}</td>
                                <td class="numeric-cell">{{ number_format($payroll->sss_loan, 2) }}</td>
                                <td class="numeric-cell">{{ number_format($payroll->pagibig_loan, 2) }}</td>
                                <td class="numeric-cell">{{ number_format($payroll->cash_advance, 2) }}</td>
                                <td class="numeric-cell">{{ isset($payroll->cash_bond) ? number_format($payroll->cash_bond, 2) : '0.00' }}</td>
                                <td class="numeric-cell">{{ isset($payroll->other_deduction) ? number_format($payroll->other_deduction, 2) : '0.00' }}</td>
                                <td class="numeric-cell">0.00</td>
                                <td class="net-salary">{{ number_format($payroll->net_salary, 2) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                @else
                    <tr>
                        <td colspan="24" style="text-align: center;">No payroll records found for the selected date range.</td>
                    </tr>
                @endif
            </tbody>
        </table>
        
        <!-- Signature section -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">Prepared by</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Checked by</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Approved by</div>
            </div>
        </div>
        
        <!-- Page footer -->
        <div class="page-footer">
            Printed on: {{ date('Y-m-d H:i:s') }}
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-print when the page loads
            window.addEventListener('load', function() {
                // Small delay to ensure the page is fully rendered
                setTimeout(function() {
                    window.print();
                }, 500);
            });
        });
    </script>
</body>
</html>
