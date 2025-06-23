<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip for {{ $payroll->employee->last_name }} {{ $payroll->employee->first_name }}, {{ $payroll->employee->middle_name ?? ' ' }} {{ $payroll->employee->suffix ?? ' ' }}</title>
    <style>
        @page {
            size: letter landscape;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 8px;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        .payslip-container {
            width: 50%;
            height: 100%;
            float: left;
            padding: 10px;
            box-sizing: border-box;
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 3px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 5px;
            font-size: 7px;
        }
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        .two-column {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }
        .column {
            width: 100%;
        }
        h1 { font-size: 14px; margin: 0; }
        h2 { font-size: 12px; margin: 3px 0; }
        .company-logo { max-height: 50px; max-width: 200px; height: auto; width: auto; }
        .bg-light { background-color: #f8f9fa; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-primary { color: #007bff; }
        .text-uppercase { text-transform: uppercase; }
        .font-weight-bold { font-weight: bold; }

        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ storage_path('fonts/DejaVuSans.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
    </style>
</head>
<body>
    <div class="payslip-container">
        <div class="header">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="Company Logo" class="company-logo">
            @endif
            <h1 class="text-uppercase text-primary">Payslip</h1>
            <p>For the period: {{ \Carbon\Carbon::parse($payroll->start_date)->format('F d, Y') }} - {{ \Carbon\Carbon::parse($payroll->end_date)->format('F d, Y') }}</p>
        </div>

        <div class="two-column">
            <div class="column">
                <p><strong>ID #:</strong> {{ $payroll->employee->company_id }}</p>
                <p><strong>Name:</strong> {{ $payroll->employee->last_name }} {{ $payroll->employee->first_name }}, {{ $payroll->employee->middle_name ?? ' ' }} {{ $payroll->employee->suffix ?? ' ' }}</p>
                <p><strong>Department:</strong> {{ $payroll->employee->department->name }}</p>
                <p><strong>Position:</strong> {{ $payroll->employee->position->name }}</p>
            </div>
        </div>

        <div class="two-column">
            <div class="column">
                <h2 class="text-uppercase text-primary">Earnings</h2>
                <table>
                    <tr>
                        <th>Description</th>
                        <th class="text-right">Amount</th>
                    </tr>
                    <tr>
                        <td>Basic Salary</td>
                        <td class="text-right">PHP {{ number_format($payroll->gross_salary, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Overtime Pay</td>
                        <td class="text-right">PHP {{ number_format($payroll->overtime_pay, 2) }}</td>
                    </tr>
                    <tr class="font-weight-bold">
                        <td>Total Earnings</td>
                        <td class="text-right">PHP {{ number_format($payroll->total_earnings, 2) }}</td>
                    </tr>
                </table>
            </div>
            <div class="column">
                <h2 class="text-uppercase text-primary">Deductions</h2>
                <table>
                    <tr>
                        <th>Description</th>
                        <th class="text-right">Amount</th>
                    </tr>
                    <tr>
                        <td>Late Deduction</td>
                        <td class="text-right">PHP {{ number_format($payroll->late_deduction, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Undertime Deduction</td>
                        <td class="text-right">PHP {{ number_format($payroll->undertime_deduction, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Absent Deduction</td>
                        <td class="text-right">PHP {{ number_format($payroll->absent_deduction, 2) }}</td>
                    </tr>
                    <tr>
                        <td>SSS Contribution</td>
                        <td class="text-right">PHP {{ number_format($payroll->sss_contribution, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Pag-IBIG Contribution</td>
                        <td class="text-right">PHP {{ number_format($payroll->pagibig_contribution, 2) }}</td>
                    </tr>
                    <tr>
                        <td>PhilHealth Contribution</td>
                        <td class="text-right">PHP {{ number_format($payroll->philhealth_contribution, 2) }}</td>
                    </tr>
                    <tr>
                        <td>TIN Contribution</td>
                        <td class="text-right">PHP {{ number_format($payroll->tin_contribution, 2) }}</td>
                    </tr>
                    <tr>
                        <td>SSS Loan</td>
                        <td class="text-right">PHP {{ number_format($payroll->sss_loan, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Pag-IBIG Loan</td>
                        <td class="text-right">PHP {{ number_format($payroll->pagibig_loan, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Cash Advance</td>
                        <td class="text-right">PHP {{ number_format($payroll->cash_advance, 2) }}</td>
                    </tr>
                    <tr class="font-weight-bold">
                        <td>Total Deductions</td>
                        <td class="text-right">PHP {{ number_format(
                            $payroll->late_deduction + $payroll->undertime_deduction + $payroll->absent_deduction +
                            $payroll->sss_contribution + $payroll->pagibig_contribution + $payroll->philhealth_contribution +
                            $payroll->tin_contribution + $payroll->sss_loan + $payroll->pagibig_loan + $payroll->cash_advance, 2) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>


            <div class="two-column">
                <div class="column">
                    <h2 class="text-uppercase">Net Pay</h2>
                    <h1 class="text-primary">PHP {{ number_format($payroll->net_salary, 2) }}</h1>
                    <p class="text-right" style="margin-right: 30%"><strong>MHR HR ComBEN</strong></p>
                </div>
                <div class="column">
                    @php
                    $endDate = \Carbon\Carbon::parse($payroll->end_date);
                    $payoutDate = null;

                    if ($endDate->day <= 10) {
                        $payoutDate = $endDate->copy()->day(15);
                    } else {
                        $payoutDate = $endDate->copy()->lastOfMonth();
                    }
                    @endphp
                    <p><strong>Pay Date:</strong> {{ $payoutDate->format('F d, Y') }}</p>
                </div>
            </div>
    </div>
</body>
</html>
