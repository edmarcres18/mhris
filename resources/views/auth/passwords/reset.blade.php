<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - MHRPCI-HRIS</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background: #f7f7f7;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .reset-form {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 360px;
            width: 100%;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            width: 100px;
        }
        h1 {
            text-align: center;
            color: #6a1b9a;
            margin-bottom: 20px;
            font-weight: 700;
            font-size: 24px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-group input:focus {
            border-color: #6a1b9a;
            box-shadow: 0 0 0 2px rgba(106, 27, 154, 0.2);
            outline: none;
        }
        .btn-login {
            width: 100%;
            padding: 10px;
            background-color: #6a1b9a;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.1s;
        }
        .btn-login:hover {
            background-color: #4a0072;
        }
        .btn-login:active {
            transform: scale(0.98);
        }
        .forgot-password {
            text-align: right;
            margin-top: 10px;
        }
        .forgot-password a {
            color: #6a1b9a;
            text-decoration: none;
            font-size: 14px;
        }
        .forgot-password a:hover {
            text-decoration: underline;
        }

        /* Add these new styles for the preloader */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(247, 247, 247, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .preloader-content {
            position: relative;
            width: 60px;
            height: 60px;
        }
        .preloader-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #6a1b9a;
            border-radius: 50%;
            width: 100%;
            height: 100%;
            animation: spin 1s linear infinite;
        }
        .preloader-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #6a1b9a;
            font-weight: bold;
            font-size: 16px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Add the preloader HTML -->
    <div class="preloader" id="preloader">
        <div class="preloader-content">
            <div class="preloader-spinner"></div>
            <div class="preloader-text">MHR</div>
        </div>
    </div>

    <div class="container">
        <form class="reset-form" method="POST" action="{{ route('password.update') }}" id="reset-password-form">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="logo">
                <img src="/images/LOGO4.png" alt="Company Logo">
            </div>
            <h1>Reset Password</h1>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required autofocus placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter new password">
            </div>
            <div class="form-group">
                <label for="password-confirm">Confirm New Password</label>
                <input type="password" id="password-confirm" name="password_confirmation" required placeholder="Confirm new password">
            </div>
            <button type="submit" class="btn-login">Reset Password</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Add preloader logic
            const preloader = document.getElementById('preloader');
            window.addEventListener('load', () => {
                preloader.style.display = 'none';
            });

            // Add form submission handler
            const resetPasswordForm = document.getElementById('reset-password-form');
            resetPasswordForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                preloader.style.display = 'flex'; // Show preloader before request

                try {
                    const response = await fetch(resetPasswordForm.action, {
                        method: 'POST',
                        body: new FormData(resetPasswordForm),
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        // Success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Your password has been reset successfully.',
                            confirmButtonColor: '#6a1b9a'
                        }).then(() => {
                            // Redirect to login page or dashboard
                            window.location.href = '/login'; // Adjust this URL as needed
                        });
                    } else {
                        // Error message
                        let errorMessage = 'Failed to reset password. Please try again.';
                        if (data.errors) {
                            errorMessage = Object.values(data.errors).flat().join('\n');
                        } else if (data.message) {
                            errorMessage = data.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                            confirmButtonColor: '#6a1b9a'
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again later.',
                        confirmButtonColor: '#6a1b9a'
                    });
                } finally {
                    preloader.style.display = 'none'; // Hide preloader after request
                }
            });
        });
    </script>
</body>
</html>
