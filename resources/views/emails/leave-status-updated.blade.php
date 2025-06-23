<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request Status Update</title>
    <style>
        /* Base styles with mobile-first approach */
        body {
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            -webkit-font-smoothing: antialiased;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 6px rgba(0,0,0,0.05);
        }
        .email-header {
            background-color: #2c5282;
            color: white;
            padding: 25px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-content {
            padding: 30px 20px;
        }
        p {
            margin: 0 0 16px;
            color: #4a5568;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
        }
        .status-approved {
            background-color: #ebf8f1;
            color: #2f855a;
        }
        .status-rejected, .status-denied {
            background-color: #fef1f1;
            color: #c53030;
        }
        .status-pending {
            background-color: #fefcef;
            color: #c05621;
        }
        .leave-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #3182ce;
        }
        .footer {
            margin-top: 30px;
            padding: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 14px;
            color: #718096;
            background-color: #f8fafc;
        }
        .company-name {
            color: #2c5282;
            font-weight: 600;
        }
        
        /* Responsive adjustments */
        @media only screen and (max-width: 480px) {
            .email-container {
                width: 100% !important;
                border-radius: 0;
            }
            .email-content {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Leave Request Status Update</h1>
        </div>

        <div class="email-content">
            <p>Dear {{ $leave->employee->first_name }},</p>

            <p>Your leave request for the period {{ $leave->start_date }} to {{ $leave->end_date }} has been 
                <span class="status status-{{ strtolower($leave->status) }}">{{ $leave->status }}</span>
            </p>

            <div class="leave-details">
                <p><strong>Leave Period:</strong> {{ $leave->start_date }} to {{ $leave->end_date }}</p>
                <p><strong>Reason for leave:</strong> {{ $leave->reason }}</p>
                <p><strong>Status:</strong> {{ $leave->status }}</p>
            </div>

            @if($leave->status === 'approved')
                <p>Enjoy your time off! Please ensure any pending work is properly handed over before your leave begins.</p>
            @else
                <p>If you have any questions regarding this decision, please contact your supervisor or HR department for further clarification.</p>
            @endif

            <p>Thank you for your understanding.</p>
        </div>

        <div class="footer">
            Best regards,<br>
            <span class="company-name">HR Department</span>
        </div>
    </div>
</body>
</html>
