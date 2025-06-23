<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 | Forbidden Access</title>
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
            animation: shake 0.5s ease-in-out;
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

        .lock-icon {
            margin-bottom: 2rem;
        }

        .lock-icon svg {
            width: 120px;
            height: 120px;
            fill: #6b46c1;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
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

            .lock-icon svg {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="lock-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 1C8.676 1 6 3.676 6 7v2H4v14h16V9h-2V7c0-3.324-2.676-6-6-6zm0 2c2.276 0 4 1.724 4 4v2H8V7c0-2.276 1.724-4 4-4zm0 10c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z"/>
            </svg>
        </div>
        <div class="error-code">403</div>
        <h1 class="error-title">Access Forbidden</h1>
        <p class="error-message">
            Sorry, you don't have permission to access this page.
            Please contact your administrator if you believe this is a mistake.
        </p>
        @if (Auth::check())
            <a href="{{ route('home') }}" class="back-button">Go to Dashboard</a>
        @endif
    </div>
</body>
</html>
