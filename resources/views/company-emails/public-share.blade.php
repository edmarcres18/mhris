<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shared Company Emails</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Poppins', sans-serif;
        }
        .countdown {
            font-size: 1.2rem;
            font-weight: 600;
            color: #dc3545;
            background-color: rgba(220, 53, 69, 0.1);
            padding: 4px 10px;
            border-radius: 4px;
            display: inline-block;
        }
        .table-container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 25px;
            margin-top: 25px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        .header {
            background: transparent;
            color: #2b5876;
            padding: 30px 0 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        .logo-container {
            margin-bottom: 25px;
            display: flex;
            justify-content: center;
        }
        .logo {
            max-height: 180px;
            margin-bottom: 20px;
            filter: drop-shadow(0 8px 15px rgba(0, 0, 0, 0.1));
            transition: transform 0.3s ease;
        }
        .logo:hover {
            transform: scale(1.05);
        }
        .app-name {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 5px;
            background: linear-gradient(135deg, #2b5876 0%, #4e4376 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }
        .app-subtitle {
            font-size: 1.4rem;
            color: #4e4376;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .copy-btn {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .copy-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .footer {
            text-align: center;
            margin-top: 35px;
            padding: 20px;
            color: #6c757d;
            font-size: 0.9rem;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        .alert-info {
            background-color: #e7f3ff;
            border-color: #b8daff;
            color: #0c5460;
            border-radius: 8px;
            padding: 15px;
        }
        .table-container h4 {
            color: #2b5876;
            font-weight: 600;
            border-bottom: 2px solid #f0f2f5;
            padding-bottom: 12px;
        }
        .table {
            border-collapse: separate;
            border-spacing: 0;
        }
        .table thead th {
            background: linear-gradient(135deg, #2b5876 0%, #4e4376 100%);
            color: white;
            border: none;
            padding: 12px 15px;
        }
        .table thead th:first-child {
            border-top-left-radius: 8px;
        }
        .table thead th:last-child {
            border-top-right-radius: 8px;
        }
        .table tbody tr:hover {
            background-color: rgba(43, 88, 118, 0.03);
        }
        .table tbody td {
            vertical-align: middle;
            padding: 12px 15px;
        }
        .input-group {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            border-radius: 6px;
            overflow: hidden;
        }
        .form-control {
            border: 1px solid #e7f3ff;
        }
        .toggle-password {
            background-color: #f8f9fa;
            border-color: #e7f3ff;
        }
        .btn-outline-secondary:hover {
            background-color: #e7f3ff;
            color: #0c5460;
        }
        .btn-primary {
            background: linear-gradient(135deg, #2b5876 0%, #4e4376 100%);
            border: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        @media (max-width: 768px) {
            .table-container {
                padding: 15px;
            }
            .header {
                padding: 20px 0 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="header text-center">
            <div class="logo-container">
                <img src="{{ asset('vendor/adminlte/dist/img/LOGO4.png') }}" alt="{{ env('APP_NAME') }} Logo" class="logo">
            </div>
            <h1 class="app-name">{{ env('APP_NAME') }}</h1>
            <p class="app-subtitle">Shared Emails Portal</p>
            <p class="mb-0">This link will expire in <span id="countdown" class="countdown">{{ $remainingTime }}</span> minutes</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> 
                    This is a temporary link to access company email accounts. Please use this information responsibly.
                </div>

                <div class="table-container">
                    <div class="mb-4">
                        <h4>Company Email Accounts</h4>
                        @if($shareableLink->description)
                            <p class="text-muted mt-2">{{ $shareableLink->description }}</p>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="35%">Email Address</th>
                                    <th width="40%">Password</th>
                                    <th width="20%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($companyEmails as $index => $email)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><span class="fw-medium">{{ $email->email }}</span></td>
                                        <td>
                                            <div class="input-group">
                                                <input type="password" class="form-control password-field" value="{{ $email->password }}" readonly>
                                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary copy-btn w-100" data-email="{{ $email->email }}" data-password="{{ $email->password }}">
                                                <i class="fas fa-copy me-1"></i> Copy Details
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <i class="fas fa-inbox fa-2x mb-3 text-muted"></i>
                                            <p class="mb-0">No company emails found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="footer">
                    <p class="mb-0">This link was generated {{ $shareableLink->created_at->diffForHumans() }} and will expire {{ $shareableLink->expires_at->diffForHumans() }}.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle password visibility
            $('.toggle-password').click(function() {
                var passwordField = $(this).closest('.input-group').find('.password-field');
                var icon = $(this).find('i');
                
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Copy email and password
            $('.copy-btn').click(function() {
                var email = $(this).data('email');
                var password = $(this).data('password');
                var textToCopy = `Email: ${email}\nPassword: ${password}`;
                
                navigator.clipboard.writeText(textToCopy).then(function() {
                    var button = $(this);
                    button.html('<i class="fas fa-check me-1"></i> Copied!');
                    
                    setTimeout(function() {
                        button.html('<i class="fas fa-copy me-1"></i> Copy Details');
                    }, 2000);
                }.bind(this));
            });

            // Countdown timer
            var remainingMinutes = {!! json_encode($remainingTime) !!};
            var countdownElement = $('#countdown');
            
            if (remainingMinutes > 0) {
                var interval = setInterval(function() {
                    remainingMinutes--;
                    countdownElement.text(remainingMinutes);
                    
                    if (remainingMinutes <= 0) {
                        clearInterval(interval);
                        $('.table-container').html('<div class="alert alert-danger p-4 text-center"><i class="fas fa-exclamation-circle fa-2x mb-3"></i><h4>This link has expired</h4><p class="mb-0">Please request a new one.</p></div>');
                    }
                }, 60000); // Update every minute
            } else {
                $('.table-container').html('<div class="alert alert-danger p-4 text-center"><i class="fas fa-exclamation-circle fa-2x mb-3"></i><h4>This link has expired</h4><p class="mb-0">Please request a new one.</p></div>');
            }
        });
    </script>
</body>
</html> 