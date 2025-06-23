<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 | Page Not Found</title>
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
            animation: float 6s ease-in-out infinite;
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

        .search-icon {
            margin-bottom: 2rem;
        }

        .search-icon svg {
            width: 120px;
            height: 120px;
            fill: #6b46c1;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
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

            .search-icon svg {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="search-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
            </svg>
        </div>
        <div class="error-code">404</div>
        <h1 class="error-title">Page Not Found</h1>
        <p class="error-message">
            Oops! The page you're looking for doesn't exist.
            It might have been moved or deleted.
        </p>
        @if (Auth::check())
            <a href="{{ route('home') }}" class="back-button">Go to Dashboard</a>
        @endif
    </div>
</body>
</html>
