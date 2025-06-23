<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Confirmation</title>
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
            text-align: center;
            padding: 25px 20px;
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .logo {
            max-width: 180px;
            height: auto;
        }
        .email-content {
            padding: 30px 20px;
        }
        h3 {
            color: #2c5282;
            font-size: 18px;
            margin-top: 0;
            margin-bottom: 15px;
        }
        p {
            margin: 0 0 16px;
            color: #4a5568;
        }
        .details-box {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #3182ce;
        }
        .details-item {
            margin-bottom: 10px;
            color: #4a5568;
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
            .logo {
                max-width: 150px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <!-- Replace the src with your actual logo URL -->
            <img src="{{ asset('vendor/adminlte/dist/img/LOGO4.png') }}" alt="Company Logo" class="logo">
        </div>

        <div class="email-content">
            <p>Dear {{ $application->first_name }} {{ $application->last_name }},</p>

            <p>Thank you for submitting your application for the position of <strong>{{ $hiringDetails->position }}</strong> at our company.</p>

            <div class="details-box">
                <h3>Application Details:</h3>
                <div class="details-item"><strong>Position:</strong> {{ $hiringDetails->position }}</div>
                <div class="details-item"><strong>Email:</strong> {{ $application->email }}</div>
                <div class="details-item"><strong>Phone:</strong> {{ $application->phone }}</div>
                <div class="details-item"><strong>Experience:</strong> {{ $application->experience }} years</div>
                <div class="details-item"><strong>LinkedIn:</strong> {{ $application->linkedin }}</div>
            </div>

            <p>We have received your resume and cover letter. Our hiring team will review your application and get back to you if your qualifications match our requirements.</p>

            <p>If you have any questions, please don't hesitate to contact us.</p>
        </div>

        <div class="footer">
            Best regards,<br>
            <span class="company-name">MHRPCI Hiring Team</span>
        </div>
    </div>
</body>
</html>
