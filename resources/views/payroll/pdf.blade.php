<!DOCTYPE html>
<html>
<head>
    <title>Payroll PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333333;
        }
        p {
            font-size: 14px;
            color: #555555;
        }
        .payroll-details {
            margin-top: 20px;
        }
        .payroll-details p {
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #aaaaaa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Payroll Details</h1>
        <div class="payroll-details">
            <p>Employee Name: {{ $employee->name }}</p>
            <p>Payroll Period: {{ $payroll->start_date->format('Y-m-d') }} to {{ $payroll->end_date->format('Y-m-d') }}</p>
            <p>Amount: {{ $payroll->amount }}</p>
            <!-- Add more payroll details as needed -->
        </div>
        <div class="footer">
            <p>Generated on {{ \Carbon\Carbon::now()->format('Y-m-d') }}</p>
        </div>
    </div>
</body>
</html>
