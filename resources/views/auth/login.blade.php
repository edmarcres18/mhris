<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login-{{ env('APP_NAME') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('vendor/adminlte/dist/img/LOGO4.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        :root {
            --primary-color:rgb(124, 18, 196);
            --primary-dark: rgb(68, 7, 109);
            --secondary-color: #64748b;
            --background-light: #f8fafc;
            --text-dark: #0f172a;
            --text-light: #64748b;
            --white: #ffffff;
            --error: #ef4444;
            --success: #22c55e;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)),
                        url('vendor/adminlte/dist/img/frontmhrhci.jpg') center/cover no-repeat fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            overflow: hidden;
        }

        .login-container {
            width: 100%;
            max-width: 1400px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .login-image {
            position: relative;
            background: linear-gradient(135deg, rgba(88, 3, 145, 0.97), rgba(12, 50, 156, 0.95));
            padding: 3.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: var(--white);
            position: relative;
            overflow: hidden;
        }

        .login-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="30" height="30" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg"><rect width="30" height="30" fill="none"/><circle cx="3" cy="3" r="1.5" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            opacity: 0.3;
        }

        .brand-logo {
            width: 150px;
            height: auto;
            filter: brightness(0) invert(1);
            position: relative;
            margin-bottom: 2rem;
            transition: transform 0.3s ease;
        }

        .brand-logo:hover {
            transform: translateY(-3px);
        }

        .brand-message {
            position: relative;
            margin-top: auto;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .brand-message h2 {
            font-size: 2.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
            background: linear-gradient(to right, #ffffff, #e2e8f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-message p {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.9);
        }

        .login-form-container {
            padding: 3rem;
            background: var(--white);
            display: flex;
            flex-direction: column;
        }

        .login-header {
            margin-bottom: 2.5rem;
        }

        .login-header h1 {
            font-size: 2rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: var(--text-light);
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            color: var(--text-dark);
            background: var(--background-light);
        }

        .form-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 2.5rem;
            color: var(--secondary-color);
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--text-dark);
        }

        .terms-container {
            margin: 1.5rem 0;
        }

        .terms-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .terms-label input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
            border-radius: 4px;
            border: 2px solid #e2e8f0;
            cursor: pointer;
        }

        .terms-label a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .terms-label a:hover {
            text-decoration: underline;
        }

        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            overflow: hidden;
        }

        .submit-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .submit-btn:disabled {
            background: var(--secondary-color);
            cursor: not-allowed;
            transform: none;
        }

        .button-loader {
            width: 20px;
            height: 20px;
            border: 2.5px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: var(--white);
            animation: spin 0.8s linear infinite;
            opacity: 0;
            position: absolute;
            right: 1.5rem;
            transition: opacity 0.2s ease;
        }

        .submit-btn.loading .button-text {
            transform: translateX(-10px);
        }

        .submit-btn.loading .button-loader {
            opacity: 1;
        }

        .button-text {
            transition: transform 0.3s ease;
        }

        .notification {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            padding: 1rem 2rem;
            border-radius: 10px;
            color: var(--white);
            font-weight: 500;
            display: none;
            animation: slideIn 0.3s ease;
            z-index: 1000;
        }

        .notification.success {
            background: var(--success);
        }

        .notification.error {
            background: var(--error);
        }

        /* Terms Modal Styles */
        .terms-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(8px);
            z-index: 1000;
        }

        .terms-modal-content {
            background: var(--white);
            margin: 2rem auto;
            padding: 2rem;
            width: 90%;
            max-width: 800px;
            border-radius: 20px;
            max-height: 85vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .terms-content {
            position: relative;
            color: var(--text-dark);
            line-height: 1.6;
            z-index: 1;
        }

        .terms-watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            height: 400px;
            opacity: 0.20;
            pointer-events: none;
            z-index: 0;
            background: url('../vendor/adminlte/dist/img/ICON_APP.png') center/contain no-repeat;
        }

        .terms-content h2 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        .terms-content h3 {
            color: var(--text-dark);
            margin: 1.5rem 0 1rem;
            font-size: 1.2rem;
        }

        .close-modal {
            position: absolute;
            right: 1.5rem;
            top: 1.5rem;
            font-size: 1.5rem;
            color: var(--secondary-color);
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close-modal:hover {
            color: var(--text-dark);
        }

        .accept-terms-btn {
            background: var(--primary-color);
            color: var(--white);
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1.5rem;
            width: 100%;
        }

        .accept-terms-btn:disabled {
            background: var(--secondary-color);
            cursor: not-allowed;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @media (max-width: 1024px) {
            .login-container {
                grid-template-columns: 1fr;
                max-width: 500px;
                margin: 1rem;
            }

            .login-image {
                display: none;
            }

            .login-form-container {
                padding: 2.5rem;
            }
        }

        @media (max-width: 640px) {
            body {
                padding: 1rem;
            }

            .login-form-container {
                padding: 1.5rem;
            }

            .login-header h1 {
                font-size: 1.75rem;
            }

            .terms-modal-content {
                margin: 1rem;
                padding: 1.5rem;
            }
        }

        /* Preloader Styles */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease-out;
        }

        .preloader-content {
            text-align: center;
            animation: float 2s ease-in-out infinite;
        }

        .preloader-logo {
            width: 200px;
            height: 200px;
            margin-bottom: 20px;
            animation: pulse 2s ease-in-out infinite;
        }

        .loading-text {
            color: var(--primary-color);
            font-size: 1.2rem;
            font-weight: 500;
            margin-top: 1rem;
            opacity: 0.8;
        }

        .loading-bar {
            width: 200px;
            height: 4px;
            background: #f0f0f0;
            border-radius: 10px;
            margin: 10px auto;
            position: relative;
            overflow: hidden;
        }

        .loading-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 50%;
            background: var(--primary-color);
            border-radius: 10px;
            animation: loading 1.5s ease-in-out infinite;
        }

        .loading-dots {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-top: 10px;
        }

        .dot {
            width: 8px;
            height: 8px;
            background: var(--primary-color);
            border-radius: 50%;
            animation: dot-pulse 1s ease-in-out infinite;
        }

        .dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes loading {
            0% {
                transform: translateX(-100%);
            }
            50% {
                transform: translateX(100%);
            }
            100% {
                transform: translateX(-100%);
            }
        }

        @keyframes dot-pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.5;
            }
            50% {
                transform: scale(1.5);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <!-- Add Preloader -->
    <div class="preloader" id="preloader">
        <div class="preloader-content">
            <img src="vendor/adminlte/dist/img/ICON_APP.png" alt="MHRIS Logo" class="preloader-logo">
            <div class="loading-text">Loading MHRIS</div>
            <div class="loading-bar"></div>
            <div class="loading-dots">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
        </div>
    </div>

    <div class="login-container">
        <div class="login-image">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <img src="vendor/adminlte/dist/img/whiteLOGO4.png" alt="Company Logo" class="brand-logo" style="margin-bottom: 0; width: 250px;">
            </div>
            <div class="brand-message">
                <h2>{{ config('app.company_name') }}</h2>
                <p>Access your employee portal to manage your work-related information efficiently and securely.</p>
            </div>
        </div>

        <div class="login-form-container">
            <div class="login-header" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1>Welcome back</h1>
                    <p>Please sign in to access your account</p>
                </div>
                <img src="vendor/adminlte/dist/img/ICON_APP.png" alt="App Icon" style="width: 100px; height: auto;">
            </div>

            <form id="login-form" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" class="form-input" required placeholder="Enter your email">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-input" required placeholder="Enter your password">
                    <span class="password-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </span>
                </div>

                <div class="terms-container">
                    <label class="terms-label">
                        <input type="checkbox" id="terms-checkbox" required>
                        <span>I agree to the <a href="#" id="terms-link">Terms and Conditions</a></span>
                    </label>
                </div>

                <button type="submit" id="submit-btn" class="submit-btn">
                    <span class="button-text">Sign in to your account</span>
                    <span class="button-loader"></span>
                </button>
            </form>
        </div>
    </div>

    <div id="notification" class="notification"></div>

    <div id="terms-modal" class="terms-modal">
        <div class="terms-modal-content">
            <span class="close-modal">&times;</span>
            <div class="terms-watermark"></div>
            <div class="terms-content">
                <p class="terms-intro">
                    Welcome to the Human Resource Information System (HRIS). This platform is designed to help you manage and access important work-related information such as Payroll, Attendance, Leaves, Loans, Employee Information, and Contributions. By using this system, you agree to follow these guidelines to ensure its secure and proper use.
                </p>

                <h3>1. Purpose of the HRIS</h3>
                <p>The HRIS is provided to facilitate employee access to:</p>
                <ul>
                    <li>Payroll and payslips</li>
                    <li>Attendance records and schedules</li>
                    <li>Leave balances and applications</li>
                    <li>Loan applications and tracking</li>
                    <li>Personal and employment information</li>
                    <li>Government contributions (e.g., SSS, PhilHealth, Pag-IBIG)</li>
                </ul>
                <p>Use the system responsibly and only for its intended purposes.</p>

                <h3>2. Access and Security</h3>
                <ul>
                    <li>Authorized Access Only: Access is granted to employees for official use. Unauthorized access is strictly prohibited.</li>
                    <li>Protect Your Login: Do not share your username or password with anyone. You are responsible for all activities under your account.</li>
                    <li>Secure Your Session: Always log out after using the HRIS, especially on shared or public devices.</li>
                    <li>Report Issues: Notify HR or IT immediately if you notice suspicious activity or unauthorized access.</li>
                </ul>

                <h3>3. Data Privacy and Confidentiality</h3>
                <ul>
                    <li>Protect Personal Data: The HRIS contains confidential information. Do not download, share, or copy data unless authorized.</li>
                    <li>Work-Related Use Only: Use the HRIS exclusively for work-related tasks. Personal or inappropriate use is not allowed.</li>
                    <li>Follow Privacy Laws: Comply with the Data Privacy Act of 2012 and company policies regarding the handling of sensitive information.</li>
                </ul>

                <h3>4. Information Accuracy</h3>
                <ul>
                    <li>Provide Accurate Information: Ensure that all data you input, such as leave applications, attendance logs, or personal details, is accurate and truthful.</li>
                    <li>Avoid Falsification: Deliberately providing false information is a serious violation and may result in disciplinary action.</li>
                </ul>

                <h3>5. Prohibited Actions</h3>
                <p>To maintain the integrity of the HRIS, the following actions are not allowed:</p>
                <ul>
                    <li>Sharing payroll or employee data with unauthorized individuals.</li>
                    <li>Altering or tampering with attendance, payroll, or leave records.</li>
                    <li>Using the HRIS for personal gain, inappropriate activities, or illegal purposes.</li>
                    <li>Uploading, installing, or introducing unauthorized files or programs into the system.</li>
                </ul>

                <h3>6. System Monitoring and Maintenance</h3>
                <ul>
                    <li>Activity Monitoring: Your use of the HRIS is monitored to ensure compliance with these terms.</li>
                    <li>Maintenance Downtime: The HRIS may occasionally be unavailable for updates or repairs. Whenever possible, you will be informed in advance.</li>
                </ul>

                <h3>7. Non-Compliance and Consequences</h3>
                <ul>
                    <li>Disciplinary Action: Misuse of the HRIS or failure to follow these guidelines may result in warnings, access restrictions, or termination of employment.</li>
                    <li>Legal Action: Violations of data privacy laws may lead to criminal or civil liability.</li>
                </ul>

                <h3>8. Updates to Terms</h3>
                <p>The company may update these Terms and Conditions as necessary. You will be notified of any changes, and continued use of the HRIS indicates your acceptance of the updated terms.</p>

                <h3>9. Acceptance of Terms</h3>
                <p>By logging into and using the HRIS, you confirm that you have read, understood, and agreed to these Terms and Conditions.</p>
                
                <p class="contact-info">For questions or concerns, please contact the HR Department or IT Support.</p>
            </div>
            <button id="accept-terms" class="accept-terms-btn">Accept Terms & Conditions</button>
        </div>
    </div>

    <script>
        document.getElementById('login-form').addEventListener('submit', function(event) {
            event.preventDefault();

            if (!termsCheckbox.checked) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terms & Conditions Required',
                    text: 'Please read and accept the Terms and Conditions to continue.',
                    confirmButtonColor: '#0066ff'
                });
                return;
            }

            // Show loader
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;

            let form = event.target;
            let formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                const notification = document.getElementById('notification');
                if (data.success) {
                    notification.className = 'notification success';
                    notification.innerHTML = 'Login Successful! Redirecting...';
                    notification.style.display = 'block';
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                } else {
                    // Reset button state
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;

                    let errorMessage = data.message === 'Your account is disabled.' ? 'Your account is disabled.' :
                                       data.field === 'email' ? 'Invalid email address' : 'Invalid password';
                    notification.className = 'notification error';
                    notification.innerHTML = errorMessage;
                    notification.style.display = 'block';
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 3000);
                }
            })
            .catch(error => {
                // Reset button state
                submitBtn.classList.remove('loading');
                submitBtn.disabled = false;

                const notification = document.getElementById('notification');
                notification.className = 'notification error';
                notification.innerHTML = 'Something went wrong. Please try again later.';
                notification.style.display = 'block';
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 3000);
            });
        });

        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            setTimeout(function() {
                preloader.style.opacity = '0';
                document.body.style.overflow = 'auto';
                setTimeout(function() {
                    preloader.style.display = 'none';
                }, 500);
            }, 2000); // Show preloader for 2 seconds
        });

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Add this new function at the end of your script
        function checkForLogoutMessage() {
            const urlParams = new URLSearchParams(window.location.search);
            const logoutMessage = urlParams.get('logout');
            if (logoutMessage === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Logged Out',
                    text: 'You have been successfully logged out.',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            }
        }

        // Call this function when the page loads
        window.addEventListener('load', function() {
            checkForLogoutMessage();
            // ... your existing load event listener content ...
        });

        // Terms and Conditions Modal
        const termsModal = document.getElementById('terms-modal');
        const termsLink = document.getElementById('terms-link');
        const closeModal = document.querySelector('.close-modal');
        const acceptTermsBtn = document.getElementById('accept-terms');
        const termsCheckbox = document.getElementById('terms-checkbox');
        const submitBtn = document.getElementById('submit-btn');
        const termsContent = document.querySelector('.terms-modal-content');

        // Disable accept button initially
        acceptTermsBtn.disabled = true;

        // Handle checkbox click
        termsCheckbox.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent direct checkbox checking
            termsModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        });

        // Track scrolling in terms modal
        termsContent.addEventListener('scroll', function() {
            const scrollPercentage = (termsContent.scrollTop / (termsContent.scrollHeight - termsContent.clientHeight)) * 100;
            
            // Enable accept button when scrolled to bottom (95% threshold)
            if (scrollPercentage > 95) {
                acceptTermsBtn.disabled = false;
            }
        });

        closeModal.addEventListener('click', function() {
            if (!termsCheckbox.checked) {
                termsModal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });

        acceptTermsBtn.addEventListener('click', function() {
            termsCheckbox.checked = true;
            termsModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        // Prevent closing modal by clicking outside if not accepted
        window.addEventListener('click', function(e) {
            if (e.target === termsModal && !termsCheckbox.checked) {
                termsModal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });

        // Reset scroll position when opening modal
        termsLink.addEventListener('click', function(e) {
            e.preventDefault();
            termsModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
            termsContent.scrollTop = 0;
            acceptTermsBtn.disabled = true;
        });
    </script>
</body>
</html>
