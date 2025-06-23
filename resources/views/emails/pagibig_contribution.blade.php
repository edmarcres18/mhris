<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pag-IBIG Contribution Notification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        .container {
            max-width: 650px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .header {
            background-color: #00843D;
            background-image: linear-gradient(135deg, #00843D, #24b662);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-weight: 600;
            font-size: 26px;
            letter-spacing: 0.5px;
        }
        .logo-container {
            text-align: center;
            padding: 15px 0;
            background-color: white;
            border-bottom: 1px solid #eaeaea;
        }
        .logo-placeholder {
            font-weight: bold;
            color: #00843D;
            font-size: 18px;
        }
        .content {
            padding: 30px;
            background-color: #ffffff;
            color: #505050;
        }
        .content p {
            margin-bottom: 15px;
            font-size: 15px;
        }
        .content strong {
            color: #00843D;
        }
        .greeting {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 20px;
            color: #333;
        }
        .table-container {
            margin: 25px 0;
            border-radius: 5px;
            overflow: hidden;
            border: 1px solid #e0e0e0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }
        table, th, td {
            border: none;
            border-bottom: 1px solid #e0e0e0;
        }
        tr:last-child td {
            border-bottom: none;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #f5f7fa;
            color: #555;
            font-weight: 600;
            font-size: 14px;
        }
        td {
            font-size: 14px;
        }
        .amount {
            text-align: right;
            font-weight: 500;
        }
        .total-row {
            background-color: #f9fff9;
            font-weight: 600;
        }
        .total-row td {
            color: #00843D;
        }
        .footer {
            text-align: center;
            padding: 20px 15px;
            font-size: 13px;
            color: #888;
            background-color: #f9f9f9;
            border-top: 1px solid #eaeaea;
        }
        .signature {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            font-size: 14px;
        }
        .contact-info {
            background-color: #f0fff5;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #00843D;
        }
        @media only screen and (max-width: 600px) {
            .container {
                width: 100%;
                margin-top: 0;
                margin-bottom: 0;
                border-radius: 0;
            }
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <div class="logo-placeholder">{{ config('app.name', 'Company Name') }}</div>
        </div>
        <div class="header">
            <h1>Pag-IBIG Contribution Notification</h1>
        </div>
        <div class="content">
            <p class="greeting">Dear {{ $employee->first_name }} {{ $employee->last_name }},</p>
            
            <p>We are pleased to inform you that your Pag-IBIG Home Development Mutual Fund (HDMF) contribution for <strong>{{ $month }} {{ $year }}</strong> has been successfully processed.</p>
            
            <p>Please find below the details of your contribution:</p>
            
            <div class="table-container">
                <table>
                    <tr>
                        <th>Pag-IBIG Number</th>
                        <td class="amount">{{ $employee->pagibig_no }}</td>
                    </tr>
                    <tr>
                        <th>Contribution Period</th>
                        <td class="amount">{{ $month }} {{ $year }}</td>
                    </tr>
                    <tr>
                        <th>Employee Contribution</th>
                        <td class="amount">₱ {{ number_format($contribution->employee_contribution, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Employer Contribution</th>
                        <td class="amount">₱ {{ number_format($contribution->employer_contribution, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <th>Total Contribution</th>
                        <td class="amount">₱ {{ number_format($contribution->total_contribution, 2) }}</td>
                    </tr>
                </table>
            </div>
            
            <p>This contribution has been properly recorded and will be remitted to Pag-IBIG according to the scheduled payment date.</p>
            
            <div class="contact-info">
                <p style="margin: 0;">If you have any questions or need further clarification regarding your Pag-IBIG contributions, please contact the HR department at <strong><a href="mailto:mhr.comben2024@gmail.com">mhr.comben2024@gmail.com</a></strong> or telegram <a href="https://t.me/MhrHrDepartment">HR Department</a>.</p>
            </div>
            
            <p>Thank you for your continued service and commitment to our organization.</p>
            
            <div class="signature">
                <p>Best regards,<br>
                <strong>Human Resources Department</strong><br>
                {{ config('app.name', 'Company Name') }}</p>
            </div>
        </div>
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Company Name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 