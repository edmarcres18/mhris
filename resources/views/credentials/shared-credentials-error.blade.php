<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Shared Credentials</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --danger-color: #e74c3c;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .header {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .logo {
            height: 60px;
            margin-right: 1rem;
            filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.3));
        }
        
        .error-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
            border-top: 4px solid var(--danger-color);
        }
        
        .error-icon {
            font-size: 4rem;
            color: var(--danger-color);
            margin-bottom: 1.5rem;
        }
        
        .footer {
            background-color: var(--primary-color);
            padding: 1rem 0;
            text-align: center;
            margin-top: auto;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-outline-secondary {
            color: #6c757d;
            border-color: #6c757d;
        }
        
        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="d-flex align-items-center">
                <img src="{{ asset('vendor/adminlte/dist/img/whiteLOGO4.png') }}" alt="Company Logo" class="logo">
                <div>
                    <h4 class="mb-0">MHR Property Conglomerates, Inc.</h4>
                    <p class="mb-0">Shared Credentials</p>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="error-container">
                    <i class="fas fa-exclamation-triangle error-icon"></i>
                    <h2 class="mb-4">Access Error</h2>
                    <p class="lead text-muted mb-4">{{ $error ?? 'Invalid or expired credential sharing link.' }}</p>
                    <p class="mb-4">This may have happened because:</p>
                    <ul class="list-unstyled text-muted">
                        <li><i class="fas fa-clock mr-2"></i> The sharing link has expired</li>
                        <li><i class="fas fa-link mr-2"></i> The link was incorrectly entered</li>
                        <li><i class="fas fa-trash-alt mr-2"></i> The credential sharing has been revoked</li>
                    </ul>
                    <div class="mt-5">
                        <a href="{{ route('welcome') }}" class="btn btn-primary px-4 mr-2">
                            <i class="fas fa-home mr-2"></i> Go to Home Page
                        </a>
                        <button onClick="window.history.back()" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-arrow-left mr-2"></i> Go Back
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} MHR Property Conglomerates, Inc. All rights reserved.</p>
        </div>
    </footer>

    <!-- jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 