<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Payroll</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Payroll Details</h1>
    <p><strong>Employee:</strong> {{ $payroll->employee->name }}</p>
    <p><strong>Gross Salary:</strong> {{ number_format($payroll->gross_salary, 2) }}</p>
    <p><strong>Total Deductions:</strong> {{ number_format($payroll->total_deductions, 2) }}</p>
    <p><strong>Overtime Pay:</strong> {{ number_format($payroll->overtime_pay, 2) }}</p>
    <p><strong>Net Salary:</strong> {{ number_format($payroll->net_salary, 2) }}</p>
    <p><strong>Period:</strong> {{ $payroll->start_date }} - {{ $payroll->end_date }}</p>

    <p>Date Printed: {{ now()->format('Y-m-d H:i:s') }}</p>
</div>
<script>
    window.print();
</script>
</body>
</html>
