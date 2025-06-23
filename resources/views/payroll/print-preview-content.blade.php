<div class="print-content">
    <!-- Print-specific styles -->
    <style type="text/css" media="print">
        @page {
            size: legal landscape;
            margin: 0.25in; /* Reduced margins for better space utilization */
            scale: auto;
            counter-increment: page;
        }
        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            counter-reset: page 1;
        }
        /* Hide all images when printing */
        img {
            display: none !important;
        }
        /* Don't hide department headers in print - allow them to show */
        .print-department-header {
            display: table-row !important;
            background-color: #f0f0f0 !important;
            font-weight: bold !important;
            page-break-before: always;
        }
        /* Hide only subtotal and grand total rows */
        .print-department-subtotal,
        .print-grand-total,
        tr[class*="total"],
        .all-departments-total {
            display: none !important;
        }
        .print-content {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }
        .payroll-print-table {
            width: 100%;
            font-size: 6.5pt; /* Slightly increased from 6pt for better readability */
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: auto;
            page-break-inside: auto;
            transform-origin: top left;
            /* Ensure table scales to fit paper */
            max-width: 100%;
            transform: scale(0.98); /* Slightly reduced to ensure fit */
        }
        .payroll-print-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
            height: 18px; /* Reduced row height for compact layout */
        }
        .payroll-print-table th, .payroll-print-table td {
            border: 1px solid #000;
            padding: 1px 2px; /* Reduced padding for compact layout */
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.1;
            white-space: nowrap; /* Prevent wrapping to maintain consistent row height */
        }
        .payroll-print-table th {
            background-color: #f2f2f2 !important;
            text-align: center !important;
            vertical-align: middle;
            font-weight: bold;
            font-size: 5pt; /* Increased from 4pt for better readability */
            white-space: normal;
            word-wrap: break-word;
            word-break: break-word;
            hyphens: auto;
        }
        .print-department-header td {
            background-color: #eaeaea !important;
            font-weight: bold;
            text-transform: uppercase;
        }
        .print-department-subtotal {
            background-color: #f9f9f9 !important;
        }
        .print-grand-total {
            background-color: #efefef !important;
        }
        .print-company-header {
            text-align: center;
            margin-bottom: 15px;
        }
        .print-company-header h4 {
            margin-bottom: 5px;
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .print-company-header p {
            margin: 3px 0;
            font-size: 10pt;
            text-transform: uppercase;
        }
        .print-company-header p small {
            font-size: 9pt;
            display: block;
            margin-top: 2px;
        }
        .print-company-header p small strong {
            font-weight: bold;
        }
        .print-company-header .date-info {
            margin-top: 5px;
            font-size: 9pt;
            font-style: italic;
        }
        .text-right {
            text-align: right !important;
            font-variant-numeric: tabular-nums !important; /* Use monospace numbers for alignment */
        }
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .font-weight-bold {
            font-weight: bold;
        }
        
        /* Ensure footer stays at bottom of page */
        .footer-section {
            position: fixed;
            bottom: 0.3in;
            left: 0.5in;
            right: 0.5in;
            font-size: 7pt;
        }
        
        /* Repeating table headers on each page */
        thead {
            display: table-header-group;
        }
        tfoot {
            display: table-footer-group;
        }
        
        /* Hide non-printable elements */
        .d-print-none, 
        button, 
        .modal-header, 
        .print-controls {
            display: none !important;
        }
        
        /* Improve numeric column alignment */
        .text-right {
            text-align: right !important;
        }
        
        /* Ensure page breaks begin at each department */
        .print-department-section {
            page-break-inside: avoid;
        }
        
        /* Add department header styling to make each department clearly identifiable */
        .print-department-header td {
            font-weight: bold !important;
            text-transform: uppercase !important;
            background-color: #f0f0f0 !important;
            border-top: 2px solid #000 !important;
            border-bottom: 2px solid #000 !important;
            padding: 5px !important;
            font-size: 7pt !important;
            page-break-before: always !important;
            break-before: always !important;
            counter-reset: department-page 1;
        }

        /* Set department-specific colors for easier identification */
        .print-department-header[data-department*="Support Personnel"] td {
            background-color: #e6f2ff !important;
        }
        
        .print-department-header[data-department*="BGPDI"] td {
            background-color: #e6ffe6 !important;
        }
        
        .print-department-header[data-department*="VHI"] td {
            background-color: #fff2e6 !important;
        }
        
        .print-department-header[data-department*="MHRHCI"] td {
            background-color: #ffe6e6 !important;
        }
        
        /* Confi vs Non Confi styling */
        .print-department-header[data-department*="Confi"] td {
            font-style: italic !important;
        }
        
        .print-department-header[data-department*="Non Confi"] td {
            font-style: normal !important;
        }
        
        /* Trainee styling */
        .print-department-header[data-department*="TRAINEE"] td {
            text-decoration: underline !important;
        }
        
        /* High contrast for better printouts */
        .print-grand-total td,
        .print-department-subtotal td {
            font-weight: bold !important;
            border-top: 2px solid #000 !important;
        }
        
        /* Set specific columns to fit content - match the image exactly */
        .payroll-print-table th:nth-child(1), 
        .payroll-print-table td:nth-child(1) {
            width: 30px !important; /* employee number - slightly reduced */
        }
        .payroll-print-table th:nth-child(2), 
        .payroll-print-table td:nth-child(2) {
            width: 130px !important; /* employee name - slightly reduced */
            text-align: left;
        }
        .payroll-print-table th:nth-child(3), 
        .payroll-print-table td:nth-child(3) {
            width: 110px !important; /* position - slightly reduced */
            text-align: left;
        }
        
        /* For all other columns, make them evenly spaced but narrower */
        .payroll-print-table th:not(:nth-child(1)):not(:nth-child(2)):not(:nth-child(3)), 
        .payroll-print-table td:not(:nth-child(1)):not(:nth-child(2)):not(:nth-child(3)) {
            width: 45px !important; /* reduced from 50px */
        }
        
        /* Control where page breaks occur */
        .page-break-after {
            page-break-after: always;
        }
        
        /* Print header that repeats on each page */
        .print-company-header {
            position: fixed;
            top: 0.3in;
            left: 0;
            right: 0;
            background-color: white !important;
            border-bottom: 1px solid #000;
            z-index: 100;
        }
        
        /* Add space for the company header */
        .payroll-print-table {
            margin-top: 1in;
        }
        
        /* Empty cell styling */
        .payroll-print-table td:empty {
            background-color: #f8f8f8 !important;
        }
        
        /* Ensure compact layout even with small data */
        .payroll-print-table td {
            max-height: 22px;
            height: 22px;
        }
        
        /* Set specific font size for table cells */
        .payroll-print-table td {
            font-size: 6pt !important; /* Force 6pt font size */
            min-font-size: 6pt !important; /* Ensure font doesn't go below 6pt */
            max-font-size: 6pt !important; /* Ensure font doesn't go above 6pt */
            line-height: 1.1 !important; /* Tighter line height for smaller font */
        }
        
        /* Footer for signatures - optimized for space */
        .print-footer {
            margin-top: 0.2in;
            page-break-inside: avoid;
            page-break-before: auto;
        }
        
        .signature-area {
            display: flex;
            justify-content: space-between;
            margin-top: 0.1in;
        }
        
        .signature-block {
            width: 30%;
            text-align: center;
            font-size: 7pt;
        }
        
        .signature-line {
            border-bottom: 1px solid #000;
            margin-bottom: 5px;
            height: 25px;
        }
        
        /* Improve numeric data display */
        .text-right {
            text-align: right !important;
            font-variant-numeric: tabular-nums !important; /* Use monospace numbers for alignment */
        }
        
        /* Ensure employee names have proper space */
        .payroll-print-table td:nth-child(2) {
            white-space: normal !important; /* Allow employee names to wrap */
            max-width: 130px !important;
            min-width: 130px !important;
        }
        
        /* Ensure position titles have proper space */
        .payroll-print-table td:nth-child(3) {
            white-space: normal !important; /* Allow position titles to wrap */
            max-width: 110px !important;
            min-width: 110px !important;
        }
        
        /* Proper formatting for time values (HH:MM) */
        .payroll-print-table td:nth-child(5) {
            font-family: monospace !important; /* Use monospace for time values */
            text-align: center !important;
        }
        
        /* Format numeric columns consistently */
        .payroll-print-table td:nth-child(n+4):not(:nth-child(5)) {
            text-align: right !important;
            font-variant-numeric: tabular-nums !important;
            min-width: 45px !important;
        }
    </style>
    
    <!-- Regular screen styles -->
    <style type="text/css" media="screen">
        /* Table container with responsive scrolling */
        .table-responsive-container {
            width: 100%;
            overflow-x: auto;
            overflow-y: auto;
            max-height: 70vh; /* Limit height to 70% of viewport height */
            position: relative;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            background: #fff;
        }
        
        /* Custom scrollbar for better user experience */
        .table-responsive-container::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        .table-responsive-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .table-responsive-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        
        .table-responsive-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Fixed header for the table */
        .table-responsive-container thead {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #f8f9fa;
        }
        
        .table-responsive-container th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            box-shadow: 0 2px 2px -1px rgba(0,0,0,0.1);
        }
        
        .payroll-print-table {
            width: 100%;
            font-size: 10px;
            border-collapse: collapse;
            table-layout: fixed;
        }
        
        .payroll-print-table th, .payroll-print-table td {
            border: 1px solid #ddd;
            padding: 4px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .payroll-print-table td {
            font-size: 10px; /* Regular screen size */
        }
        
        .print-department-header td {
            background-color: #eaeaea;
            font-weight: bold;
            text-transform: uppercase;
            position: sticky;
            top: 30px; /* Position below the header row */
            z-index: 9;
        }

        /* Department-specific colors for screen view */
        .print-department-header[data-department="Support Personnel"] td {
            background-color: #e6f2ff;
        }
        
        .print-department-header[data-department="BGPDI"] td {
            background-color: #e6ffe6;
        }
        
        .print-department-header[data-department="VHI"] td {
            background-color: #fff2e6;
        }
        
        .print-department-header[data-department="Marketing Department"] td,
        .print-department-header[data-department="Technical Department"] td {
            background-color: #ffe6e6;
        }

        .print-department-subtotal {
            background-color: #f9f9fa;
        }
        
        .print-grand-total {
            background-color: #efefef;
            position: sticky;
            bottom: 0;
            z-index: 8;
            box-shadow: 0 -2px 2px -1px rgba(0,0,0,0.1);
        }
        
        /* Enhance readability of numeric data */
        .text-right {
            text-align: right !important;
            font-variant-numeric: tabular-nums !important;
        }
        
        /* Loading indicator for large tables */
        .table-loading {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 20;
            display: none;
        }
        
        /* Responsive adjustments */
        @media screen and (max-width: 1200px) {
            .table-responsive-container {
                max-height: 60vh;
            }
        }
        
        @media screen and (max-width: 768px) {
            .table-responsive-container {
                max-height: 50vh;
            }
        }
        
        /* Format employee name column for screen view */
        .payroll-print-table td:nth-child(2) {
            white-space: normal;
            max-width: 200px;
            word-break: break-word;
        }
        
        /* Format position column for screen view */
        .payroll-print-table td:nth-child(3) {
            white-space: normal;
            max-width: 180px;
            word-break: break-word;
        }
        
        /* Format time values column for screen view */
        .payroll-print-table td:nth-child(5) {
            font-family: monospace;
            text-align: center;
        }
        
        /* Format numeric columns for screen view */
        .payroll-print-table td:nth-child(n+4):not(:nth-child(5)) {
            text-align: right;
            font-variant-numeric: tabular-nums;
        }
        
        /* Date information styling for screen */
        .print-company-header p small {
            display: block;
            margin-top: 5px;
            font-size: 14px;
        }
        
        .print-company-header .date-info {
            margin-top: 8px;
            border-top: 1px solid #eee;
            padding-top: 8px;
            font-style: italic;
            color: #555;
        }
    </style>
    
    <!-- Company header -->
    <div class="print-company-header">
        <h4>MEDICAL & HOSPITAL RESOURCES HEALTH CARE, INC.</h4>
        <p>PAYROLL {{ $payrollYear }}</p>
        <p><small>Period Covered: <strong>{{ $periodStart }} - {{ $periodEnd }}</strong></small></p>
        @php
            // Calculate payroll and payout dates based on period covered
            $periodStartDate = \Carbon\Carbon::parse($periodStart);
            $periodEndDate = \Carbon\Carbon::parse($periodEnd);
            
            // If period is from 26th to 10th (first half of month), payout on 15th
            // Otherwise (11th to 25th), payout on end of month
            if ($periodStartDate->day >= 26 || $periodEndDate->day <= 10) {
                // Period is 26th to 10th, so payout on 15th of current or next month
                if ($periodStartDate->day >= 26) {
                    // Period starts on 26th or later, so payout is on 15th of next month
                    $payoutDate = $periodStartDate->copy()->addMonth()->day(15);
                } else {
                    // Period ends on or before 10th, so payout is on 15th of current month
                    $payoutDate = $periodEndDate->copy()->day(15);
                }
            } else {
                // Period is 11th to 25th, so payout on last day of current month
                $payoutDate = $periodEndDate->copy()->endOfMonth();
            }
            
            // Payroll date is same as payout date in this system
            $payrollDate = $payoutDate->copy();
            
            // Format dates
            $formattedPayrollDate = $payrollDate->format('F j, Y');
            $formattedPayoutDate = $payoutDate->format('F j, Y');
        @endphp
        <p><small>Payroll Date: <strong>{{ $formattedPayrollDate }}</strong> | Payout Date: <strong>{{ $formattedPayoutDate }}</strong></small></p>
    </div>
    
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
    
    <!-- Loading indicator for large data sets -->
    <div class="table-loading">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <!-- Payroll Table with responsive container -->
    <div class="table-responsive-container">
    <table class="payroll-print-table table-bordered">
        <thead>
            <tr>
                <th>Employee Number</th>
                <th>Employee</th>
                <th>Position</th>
                <th>Semi-Monthly</th>
                <th>L/UT/LWOP</th>
                <th>Total BasicPay</th>
                <th>Overtime</th>
                <th>Holiday with Pay</th>
                <th>Night Premiums</th>
                <th>Allowance</th>
                <th>Other Adjustments</th>
                <th>Total Gross Pay</th>
                <th>SSS Prem EE Share</th>
                <th>SSS ER</th>
                <th>Pag-ibig Prem EE Share</th>
                <th>Pag-ibig ER</th>
                <th>PHIC Prem EE Share</th>
                <th>PHIC ER</th>
                <th>SSS Loan</th>
                <th>Pag-ibig Loan</th>
                <th>Cash Advance Salary</th>
                <th>Cash Bond</th>
                <th>Other Deduction</th>
                <th>W/Tax</th>
                <th>Total Ded.</th>
                <th>Net Pay</th>
            </tr>
        </thead>
        <tbody>
                @if($regroupedPayrolls->isEmpty())
                <tr>
                    <td colspan="26" class="text-center">No payroll records found for the selected date range.</td>
                </tr>
            @else
                    @foreach($regroupedPayrolls as $groupName => $payrolls)
                        <tr class="print-department-header print-department-section" data-department="{{ $groupName }}">
                        <td colspan="26" class="text-left font-weight-bold">
                                {{ $groupName }}
                        </td>
                    </tr>
                    
                    @foreach($payrolls as $payroll)
                            <tr class="print-employee-row print-department-section" data-department="{{ $groupName }}">
                            <td class="text-center">{{ substr($payroll->employee->company_id, -4) }}</td>
                            <td>{{ $payroll->employee->last_name }}, {{ $payroll->employee->first_name }}</td>
                            <td>{{ $payroll->employee->position->name ?? $payroll->employee->position }}</td>
                            <td class="text-right">{{ number_format($payroll->employee->salary/2, 2) }}</td>
                            <td class="text-center">
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
                            <td class="text-right">
                                @php
                                    // Calculate total_basic_pay using the formula:
                                    // employee->salary/2 - (late,undertime,leave without pay deductions) + adjustments
                                    $semiMonthlySalary = $payroll->employee->salary / 2;
                                    $deductions = $payroll->late_deduction + $payroll->undertime_deduction;
                                    
                                    // Calculate deduction for unpaid leave hours if present
                                    $unpaidLeaveDeduction = 0;
                                    if(isset($payroll->unpaid_leave_hours) && $payroll->unpaid_leave_hours > 0) {
                                        // Calculate daily rate and then deduct based on hours
                                        $dailyRate = $payroll->employee->salary / 26;
                                        $hourlyRate = $dailyRate / 8;
                                        $unpaidLeaveDeduction = $hourlyRate * $payroll->unpaid_leave_hours;
                                    }
                                    
                                    // Add adjustments if present
                                    $adjustments = $payroll->other_adjustments ?? 0;
                                    
                                    // Calculate total basic pay
                                    $totalBasicPay = $semiMonthlySalary - $deductions - $unpaidLeaveDeduction + $adjustments;
                                    
                                    // Store this value if needed for later calculations
                                    $payroll->total_basic_pay = $totalBasicPay;
                                    
                                    echo number_format($totalBasicPay, 2);
                                @endphp
                            </td>
                            <td class="text-center">
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
                                        echo '-';
                                    }
                                @endphp
                            </td>
                            <td class="text-right">
                                @php
                                    $regularHolidayHours = isset($payroll->regular_holiday_hours) ? $payroll->regular_holiday_hours : 0;
                                    $specialHolidayHours = isset($payroll->special_holiday_hours) ? $payroll->special_holiday_hours : 0;
                                    $totalHolidayHours = $regularHolidayHours + $specialHolidayHours;
                                    
                                    if ($totalHolidayHours > 0) {
                                        echo number_format($totalHolidayHours, 2);
                                    } else {
                                        echo '00:00';
                                    }
                                @endphp
                            </td>
                            <td class="text-right">{{ isset($payroll->night_premium_hours) ? number_format($payroll->night_premium_hours, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->allowances) ? number_format($payroll->allowances, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->other_adjustments) ? number_format($payroll->other_adjustments, 2) : '-' }}</td>
                            <td class="text-right">
                                @php
                                    // Calculate total_gross_pay
                                    // Formula: total_basic_pay + overtime_pay + holiday_pay + night_premium_pay + allowances + other_adjustments
                                    
                                    // Get existing values or default to 0
                                    $overtimePay = $payroll->overtime_pay ?? 0;
                                    $holidayPay = $payroll->holiday_pay ?? 0;
                                    $nightPremiumPay = $payroll->night_premium_pay ?? 0;
                                    $allowances = $payroll->allowances ?? 0;
                                    $otherAdjustments = $payroll->other_adjustments ?? 0;
                                    
                                    // Get total_basic_pay from previous calculation or from model
                                    $totalBasicPay = $payroll->total_basic_pay ?? 0;
                                    
                                    // Calculate total gross pay
                                    $totalGrossPay = $totalBasicPay + $overtimePay + $holidayPay + $nightPremiumPay + $allowances + $otherAdjustments;
                                    
                                    // Store this value if needed for later calculations
                                    $payroll->total_gross_pay = $totalGrossPay;
                                    
                                    echo number_format($totalGrossPay, 2);
                                @endphp
                            </td>
                            <td class="text-right">{{ isset($payroll->sss_contribution) ? number_format($payroll->sss_contribution, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->employer_sss_contribution) ? number_format($payroll->employer_sss_contribution, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->pagibig_contribution) ? number_format($payroll->pagibig_contribution, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->employer_pagibig_contribution) ? number_format($payroll->employer_pagibig_contribution, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->philhealth_contribution) ? number_format($payroll->philhealth_contribution, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->employer_philhealth_contribution) ? number_format($payroll->employer_philhealth_contribution, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->sss_loan) ? number_format($payroll->sss_loan, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->pagibig_loan) ? number_format($payroll->pagibig_loan, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->cash_advance) ? number_format($payroll->cash_advance, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->cash_bond) ? number_format($payroll->cash_bond, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->other_deduction) ? number_format($payroll->other_deduction, 2) : '-' }}</td>
                            <td class="text-right">{{ isset($payroll->tax) ? number_format($payroll->tax, 2) : '00:00' }}</td>
                            <td class="text-right">
                                @php
                                    // Calculate total_deduction
                                    // Formula: sss_contribution + pagibig_contribution + philhealth_contribution + sss_loan + pagibig_loan + cash_advance + cash_bond + other_deduction + tax
                                    
                                    // Get existing values or default to 0
                                    $sssContribution = $payroll->sss_contribution ?? 0;
                                    $pagibigContribution = $payroll->pagibig_contribution ?? 0;
                                    $philhealthContribution = $payroll->philhealth_contribution ?? 0;
                                    $sssLoan = $payroll->sss_loan ?? 0;
                                    $pagibigLoan = $payroll->pagibig_loan ?? 0;
                                    $cashAdvance = $payroll->cash_advance ?? 0;
                                    $cashBond = $payroll->cash_bond ?? 0;
                                    $otherDeduction = $payroll->other_deduction ?? 0;
                                    $tax = $payroll->tax ?? 0;
                                    
                                    // Calculate total deduction
                                    $totalDeduction = $sssContribution + $pagibigContribution + $philhealthContribution + 
                                                    $sssLoan + $pagibigLoan + $cashAdvance + $cashBond + 
                                                    $otherDeduction + $tax;
                                    
                                    // Store this value if needed for later calculations
                                    $payroll->total_deduction = $totalDeduction;
                                    
                                    echo number_format($totalDeduction, 2);
                                @endphp
                            </td>
                            <td class="text-right font-weight-bold">{{ number_format($payroll->net_salary, 2) }}</td>
                        </tr>
                    @endforeach
                    
                        <!-- Group subtotal row -->
                        <tr class="print-department-subtotal print-department-section" data-department="{{ $groupName }}">
                            <td colspan="3" class="text-right font-weight-bold">{{ $groupName }} Total:</td>
                        <td class="text-right font-weight-bold">
                            @php
                                $deptSalaryTotal = $payrolls->sum(function($p) {
                                    return $p->employee->salary/2;
                                });
                                echo number_format($deptSalaryTotal, 2);
                            @endphp
                        </td>
                        <td></td>
                        <td class="text-right font-weight-bold">
                            @php
                                $totalBasicPaySum = 0;
                                foreach ($payrolls as $payroll) {
                                    $semiMonthlySalary = $payroll->employee->salary / 2;
                                    $deductions = $payroll->late_deduction + $payroll->undertime_deduction;
                                    
                                    // Calculate deduction for unpaid leave hours if present
                                    $unpaidLeaveDeduction = 0;
                                    if(isset($payroll->unpaid_leave_hours) && $payroll->unpaid_leave_hours > 0) {
                                        $dailyRate = $payroll->employee->salary / 26;
                                        $hourlyRate = $dailyRate / 8;
                                        $unpaidLeaveDeduction = $hourlyRate * $payroll->unpaid_leave_hours;
                                    }
                                    
                                    $adjustments = $payroll->other_adjustments ?? 0;
                                    $totalBasicPaySum += ($semiMonthlySalary - $deductions - $unpaidLeaveDeduction + $adjustments);
                                }
                                echo number_format($totalBasicPaySum, 2);
                            @endphp
                        </td>
                        <td></td>
                        <td class="text-right font-weight-bold">
                            @php
                                $holidayTotal = $payrolls->sum('regular_holiday_hours') + $payrolls->sum('special_holiday_hours');
                                echo number_format($holidayTotal, 2);
                            @endphp
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('night_premium_hours'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('allowances'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            {{ number_format($payrolls->sum('other_adjustments'), 2) }}
                        </td>
                        <td class="text-right font-weight-bold">
                            @php
                                $totalGrossPaySum = 0;
                                foreach ($payrolls as $payroll) {
                                    // Get values or default to 0
                                    $semiMonthlySalary = $payroll->employee->salary / 2;
                                    $deductions = $payroll->late_deduction + $payroll->undertime_deduction;
                                    
                                    // Calculate unpaid leave deduction
                                    $unpaidLeaveDeduction = 0;
                                    if(isset($payroll->unpaid_leave_hours) && $payroll->unpaid_leave_hours > 0) {
                                        $dailyRate = $payroll->employee->salary / 26;
                                        $hourlyRate = $dailyRate / 8;
                                        $unpaidLeaveDeduction = $hourlyRate * $payroll->unpaid_leave_hours;
                                    }
                                    
                                    // Calculate total basic pay
                                    $adjustments = $payroll->other_adjustments ?? 0;
                                    $totalBasicPay = $semiMonthlySalary - $deductions - $unpaidLeaveDeduction + $adjustments;
                                    
                                    // Other pay components
                                    $overtimePay = $payroll->overtime_pay ?? 0;
                                    $holidayPay = $payroll->holiday_pay ?? 0;
                                    $nightPremiumPay = $payroll->night_premium_pay ?? 0;
                                    $allowances = $payroll->allowances ?? 0;
                                    
                                    // Sum up total gross pay
                                    $totalGrossPaySum += ($totalBasicPay + $overtimePay + $holidayPay + $nightPremiumPay + $allowances);
                                }
                                echo number_format($totalGrossPaySum, 2);
                            @endphp
                        </td>
                            <!-- Continue with the rest of the subtotal columns -->
                    </tr>
                @endforeach
                
                <!-- Grand total row for all departments -->
                <tr class="print-grand-total all-departments-total">
                    <td colspan="3" class="text-right font-weight-bold">GRAND TOTAL (ALL DEPARTMENTS):</td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandSalaryTotal = 0;
                                foreach($regroupedPayrolls as $payrolls) {
                                $grandSalaryTotal += $payrolls->sum(function($p) {
                                    return $p->employee->salary/2;
                                });
                            }
                            echo number_format($grandSalaryTotal, 2);
                        @endphp
                    </td>
                    <td></td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandBasicTotal = 0;
                                foreach($regroupedPayrolls as $payrolls) {
                                foreach ($payrolls as $payroll) {
                                    $semiMonthlySalary = $payroll->employee->salary / 2;
                                    $deductions = $payroll->late_deduction + $payroll->undertime_deduction;
                                    
                                    // Calculate deduction for unpaid leave hours if present
                                    $unpaidLeaveDeduction = 0;
                                    if(isset($payroll->unpaid_leave_hours) && $payroll->unpaid_leave_hours > 0) {
                                        $dailyRate = $payroll->employee->salary / 26;
                                        $hourlyRate = $dailyRate / 8;
                                        $unpaidLeaveDeduction = $hourlyRate * $payroll->unpaid_leave_hours;
                                    }
                                    
                                    $adjustments = $payroll->other_adjustments ?? 0;
                                    $grandBasicTotal += ($semiMonthlySalary - $deductions - $unpaidLeaveDeduction + $adjustments);
                                }
                            }
                            echo number_format($grandBasicTotal, 2);
                        @endphp
                    </td>
                    <td></td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandHolidayTotal = 0;
                                foreach($regroupedPayrolls as $payrolls) {
                                $grandHolidayTotal += $payrolls->sum('regular_holiday_hours') + $payrolls->sum('special_holiday_hours');
                            }
                            echo number_format($grandHolidayTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandNightTotal = 0;
                                foreach($regroupedPayrolls as $payrolls) {
                                $grandNightTotal += $payrolls->sum('night_premium_hours');
                            }
                            echo number_format($grandNightTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandAllowanceTotal = 0;
                                foreach($regroupedPayrolls as $payrolls) {
                                $grandAllowanceTotal += $payrolls->sum('allowances');
                            }
                            echo number_format($grandAllowanceTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandAdjustmentsTotal = 0;
                                foreach($regroupedPayrolls as $payrolls) {
                                $grandAdjustmentsTotal += $payrolls->sum('other_adjustments');
                            }
                            echo number_format($grandAdjustmentsTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandGrossTotal = 0;
                                foreach($regroupedPayrolls as $payrolls) {
                                foreach ($payrolls as $payroll) {
                                    // Get values or default to 0
                                    $semiMonthlySalary = $payroll->employee->salary / 2;
                                    $deductions = $payroll->late_deduction + $payroll->undertime_deduction;
                                    
                                    // Calculate unpaid leave deduction
                                    $unpaidLeaveDeduction = 0;
                                    if(isset($payroll->unpaid_leave_hours) && $payroll->unpaid_leave_hours > 0) {
                                        $dailyRate = $payroll->employee->salary / 26;
                                        $hourlyRate = $dailyRate / 8;
                                        $unpaidLeaveDeduction = $hourlyRate * $payroll->unpaid_leave_hours;
                                    }
                                    
                                    // Calculate total basic pay
                                    $adjustments = $payroll->other_adjustments ?? 0;
                                    $totalBasicPay = $semiMonthlySalary - $deductions - $unpaidLeaveDeduction + $adjustments;
                                    
                                    // Other pay components
                                    $overtimePay = $payroll->overtime_pay ?? 0;
                                    $holidayPay = $payroll->holiday_pay ?? 0;
                                    $nightPremiumPay = $payroll->night_premium_pay ?? 0;
                                    $allowances = $payroll->allowances ?? 0;
                                    
                                    // Sum up total gross pay
                                    $grandGrossTotal += ($totalBasicPay + $overtimePay + $holidayPay + $nightPremiumPay + $allowances);
                                }
                            }
                            echo number_format($grandGrossTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandSSSTotal = 0;
                                foreach($regroupedPayrolls as $payrolls) {
                                $grandSSSTotal += $payrolls->sum('sss_contribution');
                            }
                            echo number_format($grandSSSTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandSSSERTotal = 0;
                                foreach($regroupedPayrolls as $payrolls) {
                                $grandSSSERTotal += $payrolls->sum('employer_sss_contribution');
                            }
                            echo number_format($grandSSSERTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandPagibigTotal = 0;
                                foreach($regroupedPayrolls as $payrolls) {
                                $grandPagibigTotal += $payrolls->sum('pagibig_contribution');
                            }
                            echo number_format($grandPagibigTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandPagibigERTotal = 0;
                                foreach($regroupedPayrolls as $payrolls) {
                                $grandPagibigERTotal += $payrolls->sum('employer_pagibig_contribution');
                            }
                            echo number_format($grandPagibigERTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandPhilhealthTotal = 0;
                                foreach($regroupedPayrolls as $payrolls) {
                                $grandPhilhealthTotal += $payrolls->sum('philhealth_contribution');
                            }
                            echo number_format($grandPhilhealthTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandPhilhealthERTotal = 0;
                                foreach($regroupedPayrolls as $payrolls) {
                                $grandPhilhealthERTotal += $payrolls->sum('employer_philhealth_contribution');
                            }
                            echo number_format($grandPhilhealthERTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandSSSLoanTotal = 0;
                                foreach($regroupedPayrolls as $payrolls) {
                                $grandSSSLoanTotal += $payrolls->sum('sss_loan');
                            }
                            echo number_format($grandSSSLoanTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandPagibigLoanTotal = 0;
                                foreach($regroupedPayrolls as $payrolls) {
                                $grandPagibigLoanTotal += $payrolls->sum('pagibig_loan');
                            }
                            echo number_format($grandPagibigLoanTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandCashAdvanceTotal = 0;
                                foreach($regroupedPayrolls as $payrolls) {
                                $grandCashAdvanceTotal += $payrolls->sum('cash_advance');
                            }
                            echo number_format($grandCashAdvanceTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandCashBondTotal = 0;
                                foreach($regroupedPayrolls as $payrolls) {
                                $grandCashBondTotal += $payrolls->sum('cash_bond');
                            }
                            echo number_format($grandCashBondTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandOtherDeductionTotal = 0;
                                foreach($regroupedPayrolls as $payrolls) {
                                $grandOtherDeductionTotal += $payrolls->sum('other_deduction');
                            }
                            echo number_format($grandOtherDeductionTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandTaxTotal = 0;
                                foreach($regroupedPayrolls as $payrolls) {
                                $grandTaxTotal += $payrolls->sum('tax');
                            }
                            echo number_format($grandTaxTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandDeductionTotal = 0;
                                foreach($regroupedPayrolls as $payrolls) {
                                foreach ($payrolls as $payroll) {
                                    // Get values or default to 0
                                    $sssContribution = $payroll->sss_contribution ?? 0;
                                    $pagibigContribution = $payroll->pagibig_contribution ?? 0;
                                    $philhealthContribution = $payroll->philhealth_contribution ?? 0;
                                    $sssLoan = $payroll->sss_loan ?? 0;
                                    $pagibigLoan = $payroll->pagibig_loan ?? 0;
                                    $cashAdvance = $payroll->cash_advance ?? 0;
                                    $cashBond = $payroll->cash_bond ?? 0;
                                    $otherDeduction = $payroll->other_deduction ?? 0;
                                    $tax = $payroll->tax ?? 0;
                                    
                                    // Sum up total deductions
                                    $grandDeductionTotal += ($sssContribution + $pagibigContribution + $philhealthContribution + 
                                                      $sssLoan + $pagibigLoan + $cashAdvance + $cashBond + 
                                                      $otherDeduction + $tax);
                                }
                            }
                            echo number_format($grandDeductionTotal, 2);
                        @endphp
                    </td>
                    <td class="text-right font-weight-bold">
                        @php
                            $grandNetTotal = 0;
                                foreach($regroupedPayrolls as $payrolls) {
                                $grandNetTotal += $payrolls->sum('net_salary');
                            }
                            echo number_format($grandNetTotal, 2);
                        @endphp
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    </div>

    <!-- Footer section with pagination -->
    <div class="footer-section row">
        <div class="col-6">
            <small class="text-muted">Generated on: {{ date('F j, Y g:i A') }}</small>
        </div>
        <div class="col-6 text-right">
            <small class="text-muted">Page <span class="pageNumber">1</span> of <span class="totalPages">1</span></small>
        </div>
    </div>
    
    
    <style>
        /* Signature section styles */
        .print-footer {
            margin-top: 20px;
            page-break-inside: avoid;
            page-break-before: auto;
        }
        
        .payroll-details {
            margin-bottom: 15px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 10pt;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 5px;
        }
        
        .detail-label {
            font-weight: bold;
            width: 120px;
        }
        
        .signature-area {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .signature-block {
            width: 30%;
            text-align: center;
            font-size: 9pt;
        }
        
        .signature-line {
            border-bottom: 1px solid #000;
            margin-bottom: 5px;
            height: 25px;
        }
        
        .signature-name {
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .signature-title {
            font-style: italic;
            margin-bottom: 8px;
        }
        
        .signature-date {
            font-size: 8pt;
            margin-top: 8px;
        }
        
        @media print {
            .print-footer {
                position: relative;
                margin-top: 0.5in;
            }
            
            .signature-block {
                font-size: 7pt;
            }
            
            .signature-date {
                font-size: 6pt;
            }
        }
    </style>
    
    <!-- Print trigger script -->
    <script>
        // Professional payroll print module
        const PayrollPrinter = (function() {
            // Configuration
            const config = {
                pageSize: 'legal landscape',
                delayBeforePrint: 800,
                animationDuration: 300,
                fontSizes: {
                    table: '6.5pt',
                    header: '5pt',
                    departmentHeader: '7pt',
                    companyHeader: '12pt'
                }
            };
            
            // Print state tracking
            let printState = {
                isReady: false,
                departmentCount: 0,
                employeeCount: 0,
                currentPage: 1,
                totalPages: 1,
                hasError: false,
                metadata: {
                    periodCovered: '',
                    payrollDate: '',
                    payoutDate: '',
                    totalAmount: 0
                }
            };
            
            // Initialize the print system
            function initialize() {
                console.log('Initializing payroll print module...');
                
                // Count departments and employees for metrics
                printState.departmentCount = document.querySelectorAll('.print-department-header').length;
                printState.employeeCount = document.querySelectorAll('.print-employee-row').length;
                
                // Extract metadata for reporting
                extractPayrollMetadata();
                
                // Estimate total pages (approximate calculation)
                printState.totalPages = Math.ceil(printState.employeeCount / 20) || 1;
                document.querySelectorAll('.totalPages').forEach(el => el.textContent = printState.totalPages);
                
                // Wire up print events
                setupPrintEvents();
                
                return true;
            }
            
            // Extract metadata from the document for reporting
            function extractPayrollMetadata() {
                // Get period covered
                const periodCoveredEl = document.querySelector('.print-company-header p small strong');
                if (periodCoveredEl) {
                    printState.metadata.periodCovered = periodCoveredEl.textContent.trim();
                }
                
                // Get payroll and payout dates
                const dateInfoEls = document.querySelectorAll('.print-company-header p small strong');
                if (dateInfoEls.length >= 3) {
                    printState.metadata.payrollDate = dateInfoEls[1].textContent.trim();
                    printState.metadata.payoutDate = dateInfoEls[2].textContent.trim();
                }
                
                // Calculate total net amount (optional)
                try {
                    const netAmountCells = document.querySelectorAll('.print-employee-row td:last-child');
                    let total = 0;
                    netAmountCells.forEach(cell => {
                        // Parse amount removing commas and currency symbols
                        const amount = parseFloat(cell.textContent.replace(/[^\d.-]/g, '')) || 0;
                        total += amount;
                    });
                    printState.metadata.totalAmount = total;
                } catch (e) {
                    console.warn('Could not calculate total amount:', e);
                }
            }
            
            // Set up print-related event listeners
            function setupPrintEvents() {
                // Before print preparation
                window.addEventListener('beforeprint', preparePrintLayout);
                
                // After print cleanup
                window.addEventListener('afterprint', cleanupAfterPrint);
                
                // Handle print errors
                window.addEventListener('error', function(e) {
                    if (e.message && e.message.includes('print')) {
                        handlePrintError(e);
                    }
                });
            }
            
            // Apply all print-specific optimizations
            function applyPrintOptimizations() {
                applyGroupStyles();
                removeImagesForPrinting();
                hideTotalRowsForPrinting();
                setFontSizesForPrinting();
                setupPageBreaks();
                optimizeTableForPrinting();
                enforcePrintableStyles();
                
                printState.isReady = true;
            }
            
            // Apply specific styles to different department groups
            function applyGroupStyles() {
                console.log('Applying group styles...');
                
                document.querySelectorAll('.print-department-header').forEach(function(header) {
                    const groupName = header.getAttribute('data-department') || '';
                    
                    // Clear any previous style classes
                    header.classList.remove(
                        'support-personnel-group', 'mhrhci-group', 
                        'bgpdi-group', 'vhi-group',
                        'confidential-group', 'non-confidential-group',
                        'trainee-group'
                    );
                    
                    // Add department-specific classes
                    if (groupName.includes('Support Personnel')) {
                        header.classList.add('support-personnel-group');
                    } else if (groupName.includes('MHRHCI')) {
                        header.classList.add('mhrhci-group');
                    } else if (groupName.includes('BGPDI')) {
                        header.classList.add('bgpdi-group');
                    } else if (groupName.includes('VHI')) {
                        header.classList.add('vhi-group');
                    }
                    
                    // Add confidentiality level classes
                    if (groupName.includes('Confi')) {
                        header.classList.add('confidential-group');
                    } else if (groupName.includes('Non Confi')) {
                        header.classList.add('non-confidential-group');
                    }
                    
                    // Add class for trainees
                    if (groupName.includes('TRAINEE')) {
                        header.classList.add('trainee-group');
                    }
                });
            }
            
            // Remove images before printing to save ink/toner
            function removeImagesForPrinting() {
                console.log('Removing images for printing...');
                
                document.querySelectorAll('img').forEach(function(img) {
                    img.setAttribute('data-original-display', img.style.display || '');
                    img.style.display = 'none';
                });
            }
            
            // Hide total rows for a cleaner print
            function hideTotalRowsForPrinting() {
                console.log('Hiding total rows for printing...');
                
                // Hide department subtotals
                document.querySelectorAll('.print-department-subtotal').forEach(function(row) {
                    row.style.display = 'none';
                });
                
                // Hide grand totals
                document.querySelectorAll('.print-grand-total').forEach(function(row) {
                    row.style.display = 'none';
                });
                
                // Hide any row with "TOTAL" in its text except department headers
                document.querySelectorAll('tr').forEach(function(row) {
                    if ((row.textContent.includes('TOTAL') || 
                        row.textContent.includes('Total:') || 
                        row.classList.contains('all-departments-total')) &&
                        !row.classList.contains('print-department-header')) {
                        row.setAttribute('data-original-display', row.style.display || '');
                        row.style.display = 'none';
                    }
                });
            }
            
            // Apply consistent font sizing for printing
            function setFontSizesForPrinting() {
                console.log('Setting font sizes for printing...');
                
                // Set all td elements to exactly 6.5pt
                document.querySelectorAll('.payroll-print-table td').forEach(function(cell) {
                    cell.style.fontSize = config.fontSizes.table;
                    cell.style.cssText += `; font-size: ${config.fontSizes.table} !important;`;
                    cell.style.lineHeight = '1.1';
                    cell.style.fontWeight = 'normal';
                    cell.style.color = '#000000';
                });
                
                // Set all th elements to 5pt
                document.querySelectorAll('.payroll-print-table th').forEach(function(cell) {
                    cell.style.fontSize = config.fontSizes.header;
                    cell.style.textAlign = 'center';
                    cell.style.cssText += '; text-align: center !important;';
                });
                
                // Make department headers larger for better visual hierarchy
                document.querySelectorAll('.print-department-header td').forEach(function(cell) {
                    cell.style.fontSize = config.fontSizes.departmentHeader;
                    cell.style.cssText += `; font-size: ${config.fontSizes.departmentHeader} !important;`;
                    cell.style.fontWeight = 'bold';
                });
            }
            
            // Setup optimal page breaks for departments
            function setupPageBreaks() {
                console.log('Setting up page breaks...');
                
                // Ensure company header is on every page
                const companyHeader = document.querySelector('.print-company-header');
                if (companyHeader) {
                    companyHeader.style.position = 'running(header)';
                }
                
                // Force page breaks before each department header (except first)
                document.querySelectorAll('.print-department-header').forEach(function(header, index) {
                    if (index > 0) {
                        header.style.pageBreakBefore = 'always';
                        header.style.breakBefore = 'always'; // Modern browsers
                    }
                });
                
                // Keep signatures together
                const footer = document.querySelector('.print-footer');
                if (footer) {
                    footer.style.pageBreakInside = 'avoid';
                    footer.style.breakInside = 'avoid'; // Modern browsers
                }
                
                // Avoid breaking inside employee rows
                document.querySelectorAll('.print-employee-row').forEach(function(row) {
                    row.style.pageBreakInside = 'avoid';
                    row.style.breakInside = 'avoid'; // Modern browsers
                });
            }
            
            // Optimize table for printing
            function optimizeTableForPrinting() {
                console.log('Optimizing table for printing...');
                
                // Make table more compact
                const table = document.querySelector('.payroll-print-table');
                if (table) {
                    table.style.borderCollapse = 'collapse';
                    table.style.width = '100%';
                    table.style.maxWidth = '100%';
                    table.style.marginBottom = '20px';
                }
                
                // Optimize cell padding
                document.querySelectorAll('.payroll-print-table td').forEach(function(cell) {
                    cell.style.padding = '1px 2px';
                    cell.style.height = '16px';
                    cell.style.maxHeight = '16px';
                });
                
                // Optimize column widths
                optimizeColumnWidths();
                
                // Reduce empty space in headers
                document.querySelectorAll('.payroll-print-table th').forEach(function(header) {
                    header.style.padding = '1px';
                    header.style.height = '24px';
                    header.style.verticalAlign = 'middle';
                });
            }
            
            // Fine-tune column widths for best fit
            function optimizeColumnWidths() {
                // Set employee number column width
                document.querySelectorAll('.payroll-print-table th:nth-child(1), .payroll-print-table td:nth-child(1)').forEach(function(cell) {
                    cell.style.width = '30px';
                    cell.style.maxWidth = '30px';
                    cell.style.minWidth = '30px';
                });
                
                // Set employee name column width
                document.querySelectorAll('.payroll-print-table th:nth-child(2), .payroll-print-table td:nth-child(2)').forEach(function(cell) {
                    cell.style.width = '130px';
                    cell.style.maxWidth = '130px';
                    cell.style.minWidth = '130px';
                    cell.style.textAlign = 'left';
                });
                
                // Set position column width
                document.querySelectorAll('.payroll-print-table th:nth-child(3), .payroll-print-table td:nth-child(3)').forEach(function(cell) {
                    cell.style.width = '110px';
                    cell.style.maxWidth = '110px';
                    cell.style.minWidth = '110px';
                    cell.style.textAlign = 'left';
                });
                
                // Set all numeric columns to a standard width
                const numericColumns = [4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26];
                numericColumns.forEach(function(colIndex) {
                    document.querySelectorAll(`.payroll-print-table th:nth-child(${colIndex}), .payroll-print-table td:nth-child(${colIndex})`).forEach(function(cell) {
                        cell.style.width = '45px';
                        cell.style.maxWidth = '45px';
                        cell.style.minWidth = '45px';
                        if (cell.tagName === 'TD') {
                            cell.style.textAlign = 'right';
                        }
                    });
                });
                
                // Exception for time column (L/UT/LWOP)
                document.querySelectorAll('.payroll-print-table td:nth-child(5)').forEach(function(cell) {
                    cell.style.textAlign = 'center';
                    cell.style.fontFamily = 'monospace';
                });
            }
            
            // Ensure print-specific styles are properly applied
            function enforcePrintableStyles() {
                console.log('Enforcing print-specific styles...');
                
                // Ensure proper text alignment
                document.querySelectorAll('.text-right').forEach(el => {
                    el.style.textAlign = 'right';
                    el.style.cssText += '; text-align: right !important;';
                });
                
                document.querySelectorAll('.text-center').forEach(el => {
                    el.style.textAlign = 'center';
                    el.style.cssText += '; text-align: center !important;';
                });
                
                document.querySelectorAll('.text-left').forEach(el => {
                    el.style.textAlign = 'left';
                    el.style.cssText += '; text-align: left !important;';
                });
                
                // Ensure proper font weight
                document.querySelectorAll('.font-weight-bold').forEach(el => {
                    el.style.fontWeight = 'bold';
                    el.style.cssText += '; font-weight: bold !important;';
                });
                
                // Hide elements that should not be printed
                document.querySelectorAll('.d-print-none, button, .modal-header, .print-controls').forEach(el => {
                    el.style.display = 'none';
                    el.style.cssText += '; display: none !important;';
                });
            }
            
            // Prepare layout for printing
            function preparePrintLayout() {
                console.log('Preparing print layout...');
                
                // Update page numbering
                document.querySelectorAll('.pageNumber').forEach(el => el.textContent = '1');
                document.querySelectorAll('.totalPages').forEach(el => el.textContent = printState.totalPages.toString());
                
                // Show print status indicator
                showPrintStatus('Preparing document for printing...');
                
                // Mark print as in progress
                document.body.classList.add('is-printing');
            }
            
            // Cleanup after printing
            function cleanupAfterPrint() {
                console.log('Cleaning up after printing...');
                
                // Hide print status indicator
                hidePrintStatus();
                
                // Remove print-in-progress marker
                document.body.classList.remove('is-printing');
                
                // Reset any temporary print styles if needed
                // Currently this function is a placeholder for future cleanup operations
            }
            
            // Display print status message with metadata
            function showPrintStatus(message) {
                let statusEl = document.getElementById('print-status-message');
                
                if (!statusEl) {
                    statusEl = document.createElement('div');
                    statusEl.id = 'print-status-message';
                    statusEl.className = 'd-print-none';
                    statusEl.style.cssText = 'position: fixed; top: 20px; right: 20px; background: rgba(0,0,0,0.8); color: white; padding: 10px 15px; border-radius: 4px; z-index: 9999; box-shadow: 0 2px 10px rgba(0,0,0,0.2); font-size: 14px;';
                    document.body.appendChild(statusEl);
                }
                
                // Format message with metadata if available
                let statusMessage = message;
                if (printState.metadata.periodCovered && 
                    printState.metadata.payrollDate && 
                    printState.metadata.payoutDate) {
                    
                    statusMessage += `<br><small>
                        Period: ${printState.metadata.periodCovered}<br>
                        Payroll Date: ${printState.metadata.payrollDate}<br>
                        Payout Date: ${printState.metadata.payoutDate}
                    </small>`;
                }
                
                statusEl.innerHTML = statusMessage;
                statusEl.style.display = 'block';
            }
            
            // Hide print status message
            function hidePrintStatus() {
                const statusEl = document.getElementById('print-status-message');
                if (statusEl) {
                    statusEl.style.display = 'none';
                }
            }
            
            // Handle print errors
            function handlePrintError(error) {
                console.error('Print error:', error);
                
                printState.hasError = true;
                
                // Show error message
                showPrintStatus('Error printing document: ' + (error.message || 'Unknown error'));
                
                // Hide error after a delay
                setTimeout(hidePrintStatus, 5000);
            }
            
            // Trigger the print process
            function triggerPrint() {
                console.log('Triggering print process...');
                
                // Apply all optimizations
                applyPrintOptimizations();
                
                // Show status
                showPrintStatus('Preparing document for printing...');
                
                // Add a short delay to ensure all styles are applied
                setTimeout(function() {
                    try {
                        // Hide status right before print dialog
                        hidePrintStatus();
                        
                        // Trigger browser print
                        window.print();
                    } catch (e) {
                        handlePrintError(e);
                    }
                }, config.delayBeforePrint);
            }
            
            // Public API
            return {
                initialize: initialize,
                print: triggerPrint,
                getPrintState: function() { return {...printState}; }
            };
        })();

        // Initialize on page load
        window.onload = function() {
            // Initialize the printer module
            PayrollPrinter.initialize();
            
            // Auto-trigger print on load
            PayrollPrinter.print();
        };
    </script>
    
    <!-- Print status indicator (hidden in print) -->
    <div id="print-status-overlay" class="d-print-none" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.8); z-index: 9999;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Preparing print...</span>
            </div>
            <div style="margin-top: 15px; font-weight: bold;">Preparing document for printing...</div>
        </div>
    </div>
</div>
</div>