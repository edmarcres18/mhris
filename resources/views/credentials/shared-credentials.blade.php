<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shared Credentials</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --info-color: #3498db;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }
        
        .shared-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.5rem 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .shared-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: linear-gradient(135deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0) 100%);
            z-index: 1;
        }
        
        .shared-header .container {
            position: relative;
            z-index: 2;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .logo {
            height: 90px;
            margin-right: 1.5rem;
            filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.3));
            transition: transform 0.3s ease;
        }
        
        .logo:hover {
            transform: scale(1.05);
        }
        
        .title-container {
            border-left: 3px solid rgba(255, 255, 255, 0.3);
            padding-left: 1rem;
        }
        
        .shared-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 1.8rem;
            margin-bottom: 2rem;
            border-top: 4px solid var(--primary-color);
        }
        
        .expiration-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            margin-top: 0.5rem;
            display: inline-block;
        }
        
        .badge-expiring-soon {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        
        .badge-active {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .table-container {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e9ecef;
            margin-top: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }
        
        table.dataTable {
            border-collapse: collapse !important;
        }
        
        table.dataTable thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            padding: 14px 15px;
            color: var(--primary-color);
            font-size: 0.95rem;
        }
        
        table.dataTable tbody td {
            padding: 14px 15px;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
            transition: background-color 0.15s ease;
        }
        
        table.dataTable tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }
        
        .footer {
            background-color: var(--primary-color);
            padding: 1.5rem 0;
            text-align: center;
            margin-top: 3rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .footer p {
            margin-bottom: 0;
            font-size: 0.9rem;
        }
        
        .password-field {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 30px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            transition: color 0.2s ease;
        }
        
        .password-toggle:hover {
            color: var(--primary-color);
        }
        
        .copy-btn {
            margin-left: 0.5rem;
            color: var(--info-color);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .copy-btn:hover {
            color: var(--primary-color);
            transform: scale(1.1);
        }
        
        .search-wrapper {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        #credential-search {
            padding-left: 40px;
            border-radius: 20px;
            border: 1px solid #ced4da;
            transition: all 0.2s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }
        
        #credential-search:focus {
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            border-color: #80bdff;
        }
        
        .clipboard-toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: var(--success-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            display: none;
            z-index: 9999;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .info-box {
            padding: 1rem;
            background-color: rgba(52, 152, 219, 0.1);
            border-left: 4px solid var(--info-color);
            border-radius: 4px;
            margin-top: 2rem;
        }
        
        .dataTables_paginate {
            margin-top: 1rem;
            display: flex;
            justify-content: center;
        }
        
        .paginate_button {
            padding: 0.5rem 0.75rem;
            margin: 0 0.25rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .paginate_button:hover {
            background-color: rgba(52, 152, 219, 0.1);
        }
        
        .paginate_button.current {
            background-color: var(--secondary-color);
            color: white;
        }
        
        @media (max-width: 768px) {
            .shared-header {
                padding: 1.2rem 0;
                text-align: center;
            }
            
            .logo-container {
                justify-content: center;
                flex-direction: column;
                text-align: center;
            }
            
            .logo {
                margin-bottom: 1rem;
                margin-right: 0;
            }
            
            .title-container {
                border-left: none;
                border-top: 3px solid rgba(255, 255, 255, 0.3);
                padding-left: 0;
                padding-top: 1rem;
            }
            
            .shared-header h1 {
                font-size: 1.75rem;
            }
            
            .expiration-badge {
                display: block;
                margin: 1rem auto;
                text-align: center;
            }
            
            .shared-container {
                padding: 1.2rem;
            }
        }
        
        /* Blur effect for unauthenticated users */
        .blur-content {
            filter: blur(5px);
            user-select: none;
            pointer-events: none;
            transition: filter 0.5s ease;
        }
        
        .auth-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.2);
            z-index: 10;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .auth-prompt {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            max-width: 500px;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .auth-btn {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            padding: 10px 20px;
            font-weight: 500;
            margin-top: 1rem;
        }
        
        .auth-status {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .auth-status-authenticated {
            background-color: var(--success-color);
            color: white;
        }
        
        .auth-status-unauthenticated {
            background-color: var(--warning-color);
            color: white;
        }
        
        .auth-status i {
            margin-right: 0.5rem;
        }
        
        .auth-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 500;
            margin-left: 1rem;
        }
        
        .auth-badge i {
            margin-right: 0.4rem;
        }
        
        .auth-time {
            font-size: 0.8rem;
            margin-top: 0.3rem;
            opacity: 0.85;
        }
    </style>
</head>
<body>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(!$isAuthenticated)
    <div class="auth-overlay">
        <div class="auth-prompt">
            <img src="{{ asset('vendor/adminlte/dist/img/whiteLOGO4.png') }}" alt="Company Logo" style="height: 60px; margin-bottom: 1rem;">
            <h4>Authentication Required</h4>
            <p>Please authenticate with your email to view credential details securely.</p>
            <a href="{{ route('credentials.email-auth', $token) }}" class="btn btn-primary auth-btn">
                <i class="fas fa-envelope mr-2"></i> Verify with Email
            </a>
            <div class="mt-3 text-muted small">
                <i class="fas fa-info-circle"></i> Your access will be securely logged for security purposes
            </div>
        </div>
    </div>
    @endif
    
    <header class="shared-header">
        <div class="container">
            <div class="logo-container">
                <img src="{{ asset('vendor/adminlte/dist/img/whiteLOGO4.png') }}" alt="Company Logo" class="logo">
                <div class="title-container">
                    <h1>Shared Credentials</h1>
                    <p class="mb-0">Access to company account credentials</p>
                </div>
            </div>
            
            @if($shareableLink->expires_at)
            <div>
                <p class="mb-1"><i class="far fa-clock mr-2"></i> Expires: {{ $shareableLink->expires_at->format('F j, Y, g:i a') }}</p>
                
                @php
                    $now = now();
                    $expiresAt = $shareableLink->expires_at;
                    $hoursLeft = $now->diffInHours($expiresAt);
                @endphp
                
                @if($hoursLeft < 24 && $now->lt($expiresAt))
                    <span class="expiration-badge badge-expiring-soon">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Expires in {{ $hoursLeft }} hour{{ $hoursLeft != 1 ? 's' : '' }}
                    </span>
                @elseif($now->lt($expiresAt))
                    <span class="expiration-badge badge-active">
                        <i class="fas fa-check-circle mr-1"></i> Active for {{ $now->diffInDays($expiresAt) }} more day{{ $now->diffInDays($expiresAt) != 1 ? 's' : '' }}
                    </span>
                @endif
            </div>
            @endif
        </div>
    </header>
    
    <div class="container">
        <div class="shared-container">
            <h3 class="mb-4"><i class="fas fa-key text-primary mr-2"></i>Shared Credentials</h3>
            
            @if($shareableLink->description)
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i> {{ $shareableLink->description }}
            </div>
            @endif
            
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="credential-search" class="form-control" placeholder="Search credentials...">
            </div>
            
            <div class="table-container">
                <table id="credentials-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Company Number</th>
                            <th>Company Email</th>
                            <th>Email Password</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($credentials as $credential)
                        <tr>
                            <td>
                                @if($credential->employee)
                                    {{ $credential->employee->first_name }} {{ $credential->employee->last_name }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $credential->company_number ?? 'N/A' }}</td>
                            <td>
                                @if($credential->company_email)
                                    {{ $credential->company_email }}
                                    <i class="far fa-copy copy-btn" data-copy="{{ $credential->company_email }}" title="Copy to clipboard"></i>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="password-field">
                                @if($credential->email_password && $isAuthenticated)
                                    <span class="password-text">{{ $credential->email_password }}</span>
                                    <i class="far fa-eye password-toggle" title="Show/Hide password"></i>
                                    <i class="far fa-copy copy-btn" data-copy="{{ $credential->email_password }}" title="Copy to clipboard"></i>
                                @elseif($credential->email_password && !$isAuthenticated)
                                    <span class="password-text">••••••••</span>
                                    <small class="text-muted">(Authentication required)</small>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($isAuthenticated)
            <div class="info-box mt-4">
                <i class="fas fa-info-circle mr-2"></i>
                These credentials are only to be used for official company business. Your access has been logged.
            </div>
            @endif
        </div>
    </div>
    
    <div class="clipboard-toast">
        <i class="fas fa-check-circle mr-2"></i> Copied to clipboard
    </div>
    
    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} MHR Property Conglomerates, Inc. All rights reserved.</p>
        </div>
    </footer>
    
    <!-- jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#credentials-table').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search...",
                    paginate: {
                        previous: "<i class='fas fa-chevron-left'></i>",
                        next: "<i class='fas fa-chevron-right'></i>"
                    }
                },
                lengthChange: false,
                pageLength: 10
            });
            
            // Use the custom search box
            $('#credential-search').on('keyup', function() {
                table.search(this.value).draw();
            });
            
            // Password toggle functionality
            $('.password-toggle').on('click', function() {
                var passwordField = $(this).siblings('.password-text');
                
                if (passwordField.hasClass('showing')) {
                    var originalPassword = passwordField.data('password');
                    passwordField.text('••••••••');
                    passwordField.removeClass('showing');
                    $(this).removeClass('fa-eye-slash').addClass('fa-eye');
                } else {
                    passwordField.data('password', passwordField.text());
                    passwordField.text($(this).parent().find('.copy-btn').data('copy'));
                    passwordField.addClass('showing');
                    $(this).removeClass('fa-eye').addClass('fa-eye-slash');
                }
            });
            
            // Copy to clipboard functionality
            $('.copy-btn').on('click', function() {
                var textToCopy = $(this).data('copy');
                var tempInput = $('<input>');
                $('body').append(tempInput);
                tempInput.val(textToCopy).select();
                document.execCommand('copy');
                tempInput.remove();
                
                // Show toast
                $('.clipboard-toast').fadeIn().delay(2000).fadeOut();
            });
        });
    </script>
</body>
</html> 