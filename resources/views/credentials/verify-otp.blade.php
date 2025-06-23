<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Access Code - Shared Credentials</title>
    
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
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            border-radius: 10px 10px 0 0 !important;
            border-bottom: none;
            font-weight: 600;
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
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .form-control-lg {
            font-size: 1.5rem;
            letter-spacing: 5px;
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
                    <p class="mb-0">Shared Credentials Authentication</p>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Verification Code</h5>
                    </div>
                    <div class="card-body p-4">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <div class="text-center mb-4">
                            <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                            <h4>Enter Verification Code</h4>
                            <p class="text-muted">We've sent a 6-digit verification code to <strong>{{ $maskedEmail }}</strong></p>
                        </div>
                        
                        <form method="POST" action="{{ route('credentials.verify-otp-submit', $token) }}">
                            @csrf
                            
                            <div class="form-group mb-4">
                                <label for="otp" class="form-label">6-Digit Verification Code</label>
                                <input type="text" id="otp" name="otp" class="form-control form-control-lg text-center @error('otp') is-invalid @enderror" 
                                       inputmode="numeric" pattern="\d{6}" maxlength="6" required autofocus placeholder="• • • • • •">
                                @error('otp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">
                                    Please check your email for the verification code
                                </small>
                            </div>
                            
                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    <i class="fas fa-check-circle mr-2"></i>Verify Code
                                </button>
                            </div>
                        </form>
                        
                        <div class="mt-4 text-center">
                            <p class="mb-1">Didn't receive the code?</p>
                            <a href="{{ route('credentials.resend-otp', $token) }}" class="text-decoration-none">
                                <i class="fas fa-sync-alt mr-1"></i> Resend Code
                            </a>
                        </div>
                        
                        <div class="mt-3 text-center">
                            <a href="{{ route('credentials.email-auth', $token) }}" class="text-decoration-none text-muted">
                                <i class="fas fa-arrow-left mr-1"></i> Back to Email Entry
                            </a>
                        </div>
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