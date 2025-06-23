<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 | Server Error</title>
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
            animation: glitch 1s linear infinite;
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
            margin-bottom: 2rem;
        }

        .server-icon svg {
            width: 120px;
            height: 120px;
            fill: #6b46c1;
        }

        @keyframes glitch {
            0% { transform: translate(0); }
            20% { transform: translate(-2px, 2px); }
            40% { transform: translate(-2px, -2px); }
            60% { transform: translate(2px, 2px); }
            80% { transform: translate(2px, -2px); }
            100% { transform: translate(0); }
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

            .server-icon svg {
                width: 80px;
                height: 80px;
            }
        }   
    </style>
</head>
<body>
    <div class="error-container">
        <div class="server-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M21 5H3a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h18a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2zm-1 9H4v-2h16v2zm0-4H4V8h16v2z"/>
            </svg>
        </div>
        <div class="error-code">500</div>
        <h1 class="error-title">Server Error</h1>
        <p class="error-message">
            Oops! Something went wrong on our servers.
            We're working to fix the issue. Please try again later.
        </p>
        @if (Auth::check())
            <a href="{{ route('home') }}" class="back-button">Go to Dashboard</a>
        @endif
    </div>
</body>
</html>
