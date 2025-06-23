<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 | Service Unavailable</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f6f8ff 0%, #f1e4ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2d3748;
        }

        .error-container {
            text-align: center;
            padding: 2rem;
            max-width: 600px;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 700;
            color: #6b46c1;
            line-height: 1;
            text-shadow: 2px 2px 4px rgba(107, 70, 193, 0.2);
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }

        .error-title {
            font-size: 2rem;
            font-weight: 600;
            color: #553c9a;
            margin-bottom: 1rem;
        }

        .error-message {
            font-size: 1.1rem;
            color: #4a5568;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .back-button {
            display: inline-block;
            padding: 0.8rem 2rem;
            background: #6b46c1;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(107, 70, 193, 0.2);
        }

        .back-button:hover {
            background: #553c9a;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(107, 70, 193, 0.3);
        }

        .server-icon {
            width: 120px;
            height: 120px;
            margin-bottom: 2rem;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        @media (max-width: 640px) {
            .error-code {
                font-size: 6rem;
            }

            .error-title {
                font-size: 1.5rem;
            }

            .error-message {
                font-size: 1rem;
                padding: 0 1rem;
            }

            .server-icon {
                width: 80px;
                height: 80px;
            }
        }

        .maintenance-icon {
            margin-bottom: 2rem;
        }

        .maintenance-icon svg {
            width: 120px;
            height: 120px;
            fill: #6b46c1;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="maintenance-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M21.17 7.34L15.66 1.83C15.28 1.45 14.74 1.24 14.17 1.24H9.83C9.26 1.24 8.72 1.45 8.34 1.83L2.83 7.34C2.45 7.72 2.24 8.26 2.24 8.83V13.17C2.24 13.74 2.45 14.28 2.83 14.66L8.34 20.17C8.72 20.55 9.26 20.76 9.83 20.76H14.17C14.74 20.76 15.28 20.55 15.66 20.17L21.17 14.66C21.55 14.28 21.76 13.74 21.76 13.17V8.83C21.76 8.26 21.55 7.72 21.17 7.34ZM12 15.5C11.17 15.5 10.5 14.83 10.5 14C10.5 13.17 11.17 12.5 12 12.5C12.83 12.5 13.5 13.17 13.5 14C13.5 14.83 12.83 15.5 12 15.5ZM13 10H11V6H13V10Z"/>
            </svg>
        </div>
        <div class="error-code">503</div>
        <h1 class="error-title">Service Unavailable</h1>
        <p class="error-message">
            We're currently performing maintenance on our servers.
            We'll be back online shortly. Thank you for your patience.
        </p>
        @if (Auth::check())
            <a href="{{ route('home') }}" class="back-button">Go to Dashboard</a>
        @endif
    </div>

    <script>
        // Optional: Add this if you want to automatically refresh the page every 30 seconds
        setTimeout(function() {
            window.location.reload();
        }, 30000);
    </script>
</body>
</html>
