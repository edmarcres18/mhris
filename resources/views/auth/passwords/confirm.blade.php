<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Password - Your Company Name</title>
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

        /* Updated Loader Styles */
        .loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loader-content {
            position: relative;
            width: 100px;
            height: 100px;
        }

        .spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #6a1b9a;
            border-radius: 50%;
            width: 100px;
            height: 100px;
            animation: spin 1s linear infinite;
        }

        .loader-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            font-weight: bold;
            color: #6a1b9a;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Updated Loader HTML -->
    <div id="loader" class="loader">
        <div class="loader-content">
            <div class="spinner"></div>
            <div class="loader-text">MHR</div>
        </div>
    </div>

    <div class="container">
        <form class="login-form" method="POST" action="{{ route('password.confirm') }}" id="confirm-password-form">
            @csrf
            <div class="logo">
                <img src="/images/LOGO4.png" alt="Company Logo">
            </div>
            <h1>Confirm Password</h1>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>
            <button type="submit" class="btn-login">Confirm Password</button>
        </form>
    </div>

    <script>
        // Loader Script
        window.addEventListener('load', function() {
            const loader = document.getElementById('loader');
            setTimeout(function() {
                loader.style.opacity = '0';
                setTimeout(function() {
                    loader.style.display = 'none';
                }, 500); // Fade out transition time
            }, 2000); // Display loader for 2 seconds
        });

        document.addEventListener('DOMContentLoaded', () => {
            // Add form submission handler
            const confirmPasswordForm = document.getElementById('confirm-password-form');
            confirmPasswordForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                try {
                    const response = await fetch(confirmPasswordForm.action, {
                        method: 'POST',
                        body: new FormData(confirmPasswordForm),
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
                            text: 'Password confirmed successfully.',
                            confirmButtonColor: '#6a1b9a'
                        }).then(() => {
                            // Redirect or perform any other action after success
                            window.location.href = '/dashboard'; // Adjust the URL as needed
                        });
                    } else {
                        // Error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Failed to confirm password. Please try again.',
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
