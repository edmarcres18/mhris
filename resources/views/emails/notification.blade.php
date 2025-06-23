<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Notification</title>
    <style>
        /* Base styles with mobile-first approach */
        body {
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            line-height: 1.6;
            color: #333;
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
            padding: 20px;
            text-align: center;
        }
        .email-content {
            padding: 30px 20px;
        }
        .notification {
            margin-bottom: 25px;
        }
        h1 {
            color: #2c5282;
            font-size: 24px;
            margin-top: 0;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .email-header h1 {
            color: white;
            margin: 0;
        }
        p {
            margin: 0 0 16px;
            color: #4a5568;
        }
        .button {
            display: inline-block;
            background-color: #3182ce;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin: 15px 0;
            font-weight: 600;
            text-align: center;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #2b6cb0;
        }
        .divider {
            border-top: 1px solid #e2e8f0;
            margin: 20px 0;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            font-size: 14px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
        }
        .small-text {
            font-size: 12px;
            color: #a0aec0;
            margin-top: 15px;
        }
        .detail-item {
            margin-bottom: 8px;
        }
        .detail-label {
            font-weight: 600;
            color: #4a5568;
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
            .button {
                display: block;
                text-align: center;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>{{ $notifications->first()['subject'] }}</h1>
        </div>
        
        <div class="email-content">
            @foreach($notifications as $notification)
                <div class="notification">
                    <p>{{ $notification['content']['greeting'] }}</p>

                    <p>{{ $notification['content']['message'] }}</p>

                    @foreach($notification['content']['details'] as $label => $value)
                        <div class="detail-item">
                            <span class="detail-label">{{ $label }}:</span> {{ $value }}
                        </div>
                    @endforeach

                    @if(isset($notification['content']['action']))
                        <a href="{{ $notification['content']['action']['url'] }}" class="button">
                            {{ $notification['content']['action']['text'] }}
                        </a>
                    @endif

                    @if(!$loop->last)
                        <div class="divider"></div>
                    @endif
                </div>
            @endforeach
        </div>
        
        <div class="footer">
            <p>Thank you,<br>
            <strong>{{ config('app.name') }}</strong></p>

            <p class="small-text">If you don't want to receive these emails, you can update your notification preferences in your account settings.</p>
        </div>
    </div>
</body>
</html>
