<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email Address - Your Company Name</title>
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
        .login-form {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 360px;
            width: 100%;
            text-align: center;
        }
        .logo {
            margin-bottom: 20px;
        }
        .logo img {
            width: 100px;
        }
        h1 {
            color: #6a1b9a;
            margin-bottom: 20px;
            font-weight: 700;
            font-size: 24px;
        }
        p {
            color: #555;
            margin-bottom: 20px;
            font-size: 14px;
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
        .resend-link {
            margin-top: 20px;
        }
        .resend-link a {
            color: #6a1b9a;
            text-decoration: none;
        }
        .resend-link a:hover {
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
            width: 100px;
            height: 100px;
            border: 5px solid #6a1b9a;
            border-top: 5px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            font-size: 24px;
            color: #6a1b9a;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Add the preloader div -->
    <div class="preloader">
        <div class="preloader-content">
            MHR
        </div>
    </div>

    <div class="container">
        <div class="login-form">
            <div class="logo">
                <img src="vendor/adminlte/dist/img/LOGO4.png" alt="Company Logo">
            </div>
            <h1>Verify Your Email Address</h1>
            <p>Before proceeding, please check your email for a verification link. If you did not receive the email, click below to request another.</p>
            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn-login">Resend Verification Email</button>
            </form>
            <div class="resend-link">
                <a href="{{ route('login') }}">Back to Login</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Add this line to hide the preloader when the page is loaded
            document.querySelector('.preloader').style.display = 'none';

            // Add form submission handler
            const resetPasswordForm = document.querySelector('form');
            resetPasswordForm.addEventListener('submit', async (e) => {
                e.preventDefault();

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
                            text: 'Verification email sent successfully.',
                            confirmButtonColor: '#6a1b9a'
                        });
                    } else {
                        // Error message
                        let errorMessage = 'Failed to send verification email. Please try again.';
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
                }
            });
        });
    </script>
</body>
</html>
