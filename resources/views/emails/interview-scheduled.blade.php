<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Scheduled</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 20px;
        }
        .interview-box {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        .interview-item {
            margin-bottom: 10px;
        }
        .important-note {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 14px;
        }
        .highlight {
            color: #007bff;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- Replace the src with your actual logo URL -->
        <img src="{{ asset('vendor/adminlte/dist/img/LOGO4.png') }}" alt="Company Logo" class="logo">
    </div>

    <p>Dear {{ $career->first_name }} {{ $career->last_name }},</p>

    <p>We are pleased to inform you that an interview has been scheduled for your application for the position of <span class="highlight">{{ $career->hiring->position }}</span>.</p>

    <div class="interview-box">
        <h3 style="margin-top: 0;">Interview Details:</h3>
        <div class="interview-item">
            <strong>Position:</strong> {{ $career->hiring->position }}
        </div>
        <div class="interview-item">
            <strong>Date and Time:</strong> {{ $career->interview_date->format('F j, Y, g:i A') }}
        </div>
        @if($career->interview_location)
        <div class="interview-item">
            <strong>Location:</strong> {{ $career->interview_location }}
        </div>
        @endif
    </div>

    <div class="important-note">
        <strong>Important:</strong> Please make sure to be available at the scheduled time. If you need to reschedule or have any questions, please contact us as soon as possible.
    </div>

    <div class="footer">
        Best regards,<br>
        <strong>MHRPCI Hiring Team</strong>
    </div>
</body>
</html>
