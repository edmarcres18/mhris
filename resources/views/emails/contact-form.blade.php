<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .logo {
            max-width: 200px;
            height: auto;
        }
        .content {
            background: #ffffff;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-radius: 0 0 8px 8px;
        }
        .message-box {
            background: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
        }
        .contact-info {
            margin-top: 20px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('vendor/adminlte/dist/img/mhrhci.png') }}" alt="MHRHCI Logo" class="logo">
    </div>

    <div class="content">
        <h2>New Contact Form Submission</h2>
        <p>A new inquiry has been received from the website contact form.</p>

        <div class="message-box">
            <h3>Contact Details:</h3>
            <p><strong>Name:</strong> {{ $data['name'] }}</p>
            <p><strong>Email:</strong> {{ $data['email'] }}</p>
            
            <h3>Message:</h3>
            <p style="white-space: pre-line;">{{ $data['message'] }}</p>
        </div>

        <div class="contact-info">
            <h3>Company Information:</h3>
            <p><strong>{{ $companyDetails['name'] }}</strong></p>
            <p>📍 {{ $companyDetails['address'] }}</p>
            <p>📞 {{ $companyDetails['phone'] }}</p>
            <p>✉️ {{ $companyDetails['email'] }}</p>
            <p>🌐 {{ $companyDetails['website'] }}</p>
        </div>

        <a href="mailto:{{ $data['email'] }}" class="button">Reply to {{ $data['name'] }}</a>
    </div>

    <div class="footer">
        <p>This email was sent from the MHRHCI website contact form.</p>
        <p>© {{ date('Y') }} {{ $companyDetails['name'] }}. All rights reserved.</p>
        <p>Please do not reply to this automated email.</p>
    </div>
</body>
</html> 