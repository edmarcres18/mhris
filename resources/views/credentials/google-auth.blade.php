<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Secure authentication for viewing shared credentials">
    <title>Authentication Required | Shared Credentials</title>
    
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
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --info-color: #3498db;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 20px 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #ebf0f6 100%);
        }
        
        .google-auth-container {
            max-width: 550px;
            margin: 0 auto;
            padding: 2.5rem;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-top: 5px solid var(--primary-color);
            position: relative;
            overflow: hidden;
        }
        
        .google-auth-container::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 5px;
            background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }
        
        .logo {
            height: 100px;
            filter: drop-shadow(0 0 8px rgba(0,0,0,0.15));
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }
        
        .logo:hover {
            transform: scale(1.05);
        }
        
        .auth-title {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .auth-subtitle {
            color: #6c757d;
            font-size: 1rem;
            max-width: 80%;
            margin: 0 auto 1rem;
        }
        
        .credential-info {
            background-color: rgba(52, 152, 219, 0.1);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--info-color);
        }
        
        .credential-info h5 {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        
        .credential-info p {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .credential-info .badge {
            font-weight: 500;
            padding: 0.4rem 0.8rem;
            border-radius: 30px;
        }
        
        .google-btn {
            width: 100%;
            background-color: #fff;
            border: 1px solid #ddd;
            color: #333;
            padding: 12px 15px;
            font-weight: 500;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border-radius: 50px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .google-btn:hover {
            background-color: #f8f9fa;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .google-btn img {
            width: 20px;
            margin-right: 10px;
        }
        
        .or-divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: #777;
            font-size: 0.9rem;
        }
        
        .or-divider::before,
        .or-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #ddd;
        }
        
        .or-divider::before {
            margin-right: 1rem;
        }
        
        .or-divider::after {
            margin-left: 1rem;
        }
        
        .form-group label {
            font-weight: 500;
            color: #495057;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #ced4da;
            transition: all 0.2s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }
        
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .form-text {
            font-size: 0.85rem;
        }
        
        .btn-continue {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transition: all 0.3s ease;
            font-weight: 600;
            padding: 12px;
            border-radius: 50px;
        }
        
        .btn-continue:hover {
            background-color: #2980b9;
            border-color: #2980b9;
            box-shadow: 0 4px 12px rgba(41, 128, 185, 0.3);
            transform: translateY(-2px);
        }
        
        .security-note {
            background-color: rgba(46, 204, 113, 0.1);
            border-radius: 8px;
            padding: 1rem;
            margin: 1.5rem 0;
            border-left: 4px solid var(--success-color);
            font-size: 0.9rem;
        }
        
        .security-note i {
            color: var(--success-color);
            margin-right: 0.5rem;
        }
        
        .skip-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: #777;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        
        .skip-link:hover {
            color: var(--primary-color);
        }
        
        .skip-link i {
            margin-right: 0.3rem;
            font-size: 0.8rem;
        }
        
        /* Error handling */
        .alert {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
            border: none;
        }
        
        .alert-danger {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }
        
        /* Responsive design */
        @media (max-width: 576px) {
            .google-auth-container {
                padding: 1.5rem;
            }
            
            .logo {
                height: 80px;
            }
            
            .auth-title {
                font-size: 1.5rem;
            }
            
            .auth-subtitle {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container py-3">
        <div class="google-auth-container">
            <div class="logo-container">
                <img src="{{ asset('vendor/adminlte/dist/img/whiteLOGO4.png') }}" alt="Company Logo" class="logo">
                <h2 class="auth-title">Authentication Required</h2>
                <p class="auth-subtitle">Please verify your identity to access credential details</p>
            </div>
            
            @if(isset($shareableLink))
            <div class="credential-info">
                <h5><i class="fas fa-info-circle mr-2"></i>Credential Access Information</h5>
                <p>{{ $shareableLink->description ?: 'Shared Credentials' }}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <small>Shared on: {{ $shareableLink->created_at->format('M d, Y') }}</small>
                    @if($shareableLink->remainingTimeInMinutes() < 60)
                        <span class="badge badge-warning">Expires soon ({{ $shareableLink->remainingTimeInMinutes() }} min)</span>
                    @else
                        <span class="badge badge-success">
                            Active for {{ floor($shareableLink->remainingTimeInMinutes() / 60) }}h {{ $shareableLink->remainingTimeInMinutes() % 60 }}m
                        </span>
                    @endif
                </div>
            </div>
            @endif
            
            @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                {{ $errors->first() }}
            </div>
            @endif
            
            <!-- Google Sign-in Button -->
            <button class="google-btn" id="google-signin-btn">
                <i class="fab fa-google mr-2" style="font-size: 20px; color: #4285F4;"></i>
                Sign in with Google
            </button>
            
            <div class="or-divider">or</div>
            
            <!-- Manual Email Input Form -->
            <form action="{{ route('credentials.google-callback') }}" method="POST" id="auth-form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope mr-2 text-secondary"></i>Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required 
                           placeholder="Enter your email" autocomplete="email">
                    <small class="form-text text-muted">
                        <i class="fas fa-shield-alt mr-1"></i> 
                        We track access to ensure credential security.
                    </small>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block btn-continue">
                    <i class="fas fa-lock-open mr-2"></i> Authenticate & View Credentials
                </button>
            </form>
            
            <div class="security-note">
                <i class="fas fa-shield-alt"></i>
                <strong>Security Note:</strong> Authentication helps us maintain a secure record of who accessed these credentials. Your information is kept confidential.
            </div>
            
            <a href="{{ route('credentials.access-shared', $token) }}" class="skip-link">
                <i class="fas fa-eye-slash"></i> Skip authentication (limited view with blurred credentials)
            </a>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Simulate Google sign-in
            $('#google-signin-btn').on('click', function() {
                // Visual feedback
                $(this).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Connecting to Google...');
                $(this).prop('disabled', true);
                
                // In a real implementation, this would redirect to Google
                // For now, we'll just submit the form after a short delay
                setTimeout(function() {
                    $('#auth-form').submit();
                }, 1500);
            });
            
            // Form submission animation
            $('#auth-form').on('submit', function() {
                $('.btn-continue').html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Authenticating...');
                $('.btn-continue').prop('disabled', true);
            });
        });
    </script>
</body>
</html> 