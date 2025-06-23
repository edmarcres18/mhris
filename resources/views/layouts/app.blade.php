<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">
    <title>MHR Property Conglomerates, Inc.</title>
    <link rel="icon" type="image/png" href="{{ asset('vendor/adminlte/dist/img/ICON_APP.png') }}">
    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Responsive Dropdowns CSS -->
    <link rel="stylesheet" href="{{ asset('css/responsive-dropdowns.css') }}">
    <!-- Toast Notification System - Removed -->

    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
                .then(reg => {
                    console.log("Service Worker Registered");
                    
                    // Check if push is supported
                    if ('PushManager' in window) {
                        // Initialize Push Notifications after login
                        document.addEventListener('DOMContentLoaded', () => {
                            initPushNotifications(reg);
                        });
                    } else {
                        console.log('Push messaging is not supported');
                    }
                })
                .catch(err => console.log("Service Worker Failed", err));
        }

        // Function to initialize push notifications
        async function initPushNotifications(registration) {
            try {
                // Check if already subscribed
                const status = await fetch('{{ route("notifications.status") }}');
                const statusData = await status.json();
                
                if (!statusData.enabled) {
                    // Check notification permission
                    if (Notification.permission === 'granted') {
                        await subscribeToPush(registration);
                    } else if (Notification.permission !== 'denied') {
                        // Show a bell icon in the header to allow users to enable notifications
                        const notifyBtn = document.getElementById('enableNotifications');
                        if (notifyBtn) {
                            notifyBtn.style.display = 'block';
                        }
                    }
                }
            } catch (error) {
                console.error('Error initializing push notifications:', error);
            }
        }

        // Function to request notification permission and subscribe
        async function requestNotificationPermission() {
            try {
                const permission = await Notification.requestPermission();
                
                if (permission === 'granted') {
                    // Hide the notification button
                    const notifyBtn = document.getElementById('enableNotifications');
                    if (notifyBtn) {
                        notifyBtn.style.display = 'none';
                    }
                    
                    // Get service worker registration and subscribe
                    const registration = await navigator.serviceWorker.ready;
                    await subscribeToPush(registration);
                    
                    // Show success message using SweetAlert2 instead of toast
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Push notifications enabled successfully!',
                        timer: 3000,
                        timerProgressBar: true
                    });
                } else {
                    // Show warning message using SweetAlert2 instead of toast
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning',
                        text: 'Notification permission denied',
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            } catch (error) {
                console.error('Error requesting notification permission:', error);
                // Show error message using SweetAlert2 instead of toast
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to enable notifications',
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        }

        // Function to subscribe to push notifications
        async function subscribeToPush(registration) {
            try {
                // Get VAPID public key from server
                const response = await fetch('{{ route("notifications.vapid-public-key") }}');
                const data = await response.json();
                
                // Convert VAPID key to Uint8Array
                const vapidKey = urlBase64ToUint8Array(data.publicKey);
                
                // Subscribe to push
                const subscription = await registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: vapidKey
                });
                
                // Send subscription to server
                await fetch('{{ route("push-subscription.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(subscription)
                });
                
                console.log('Push notification subscription successful');
                
                // Test the notification
                setTimeout(async () => {
                    try {
                        await fetch('{{ route("notifications.test") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                    } catch (e) {
                        console.error('Error sending test notification:', e);
                    }
                }, 2000);
                
            } catch (error) {
                console.error('Error subscribing to push:', error);
            }
        }

        // Helper function to convert base64 to Uint8Array
        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding)
                .replace(/-/g, '+')
                .replace(/_/g, '/');
            
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }
    </script>

    <!-- Flash messages script - Removed -->

    @yield('styles')
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <meta name="app-env" content="{{ config('app.env') }}">

    <!-- Add this in the head section after other CSS links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/shepherd.js/10.0.1/css/shepherd.css"/>

    <!-- Add SweetAlert2 CSS and JS in the head section -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/shepherd.js/10.0.1/js/shepherd.min.js"></script>

    {{-- Set toast data as data attribute for JavaScript to avoid syntax errors --}}
    @if(session('toast'))
    <div id="toastDataContainer" 
         data-from="{{ session('toast')['from'] }}" 
         data-to="{{ session('toast')['to'] }}" 
         style="display: none;"></div>
    @endif

    <style>
        /* Button Loading State */
        .btn .loading-state {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn.is-loading .normal-state {
            display: none;
        }
        
        .btn.is-loading .loading-state {
            display: inline-flex !important;
        }

        /* Preloader styles using ICON_APP.png */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        
        .dark-mode #preloader {
            background-color: #343a40;
        }
        
        .preloader-content {
            text-align: center;
            position: relative;
        }
        
        /* Circle container */
        .circle-container {
            position: relative;
            width: 180px;
            height: 180px;
            margin: 0 auto 20px;
        }
        
        /* Logo inside circle */
        .preloader-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 150px;
            height: 150px;
            z-index: 2;
        }
        
        /* Half circle animations */
        .half-circle {
            position: absolute;
            width: 180px;
            height: 180px;
            border: 6px solid transparent;
            border-radius: 50%;
        }
        
        .half-circle-1 {
            border-top-color: #7b1fa2; /* Deep Purple */
            animation: rotate1 2s linear infinite;
        }
        
        .half-circle-2 {
            border-right-color: #9c27b0; /* Purple */
            animation: rotate2 2s linear infinite;
        }
        
        .half-circle-3 {
            border-bottom-color: #ba68c8; /* Light Purple */
            animation: rotate3 2s linear infinite;
        }
        
        .half-circle-4 {
            border-left-color: #6a1b9a; /* Darker Purple */
            animation: rotate4 2s linear infinite;
        }
        
        @keyframes rotate1 {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes rotate2 {
            0% { transform: rotate(45deg); }
            100% { transform: rotate(405deg); }
        }
        
        @keyframes rotate3 {
            0% { transform: rotate(90deg); }
            100% { transform: rotate(450deg); }
        }
        
        @keyframes rotate4 {
            0% { transform: rotate(135deg); }
            100% { transform: rotate(495deg); }
        }

        /* Page Loading Transition - Animates content after preloader */
        body {
            opacity: 1;
            transition: opacity 0.5s ease;
        }
        
        /* Fade In Animation for Progressive Loading */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .fade-in-element {
            animation: fadeIn 0.6s ease-in-out;
        }
        
        /* Loading text styling */
        .loading-text {
            font-family: 'Source Sans Pro', sans-serif;
            font-weight: 600;
            font-size: 1.25rem;
            color: #7b1fa2; /* Deep Purple to match circle */
            letter-spacing: 1px;
            margin-top: 15px;
            text-transform: uppercase;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .loading-text::after {
            content: "...";
            width: 20px;
            text-align: left;
            animation: dots 1.5s infinite;
        }
        
        @keyframes dots {
            0%, 20% { content: "."; }
            40% { content: ".."; }
            60%, 100% { content: "..."; }
        }
        
        .dark-mode .loading-text {
            color: #ba68c8; /* Lighter purple for dark mode */
        }

        /* Contribution Notification Button & Modal Styling */
        .contribution-notify-btn {
            position: relative;
            transition: all 0.3s ease;
        }

        .contribution-notify-btn:hover {
            transform: scale(1.05);
        }

        .contribution-notify-btn .notification-badge {
            position: absolute;
            top: 0px;
            right: 0px;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
            }
            70% {
                box-shadow: 0 0 0 5px rgba(255, 193, 7, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0);
            }
        }

        /* Modal Styling */
        .contribution-modal {
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .contribution-modal .modal-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            background-color: var(--primary, #007bff);
            color: #fff;
        }

        .contribution-modal .modal-header .close {
            color: #fff;
            text-shadow: none;
            opacity: 0.8;
        }

        .contribution-modal .modal-header .close:hover {
            opacity: 1;
        }

        .contribution-modal .modal-body {
            padding: 1.5rem;
        }

        .contribution-description {
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }

        .contribution-input {
            height: calc(2.25rem + 10px);
            border-radius: 0.25rem;
            border: 1px solid #ced4da;
            padding: 0.5rem 1rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .contribution-options {
            margin-top: 1.5rem;
        }

        .contribution-option-item {
            background-color: rgba(0, 0, 0, 0.02);
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.2s ease;
        }

        .contribution-option-item:hover {
            background-color: rgba(0, 0, 0, 0.04);
        }

        .contribution-option-item .form-check {
            margin-bottom: 0;
        }

        .contribution-option-item .custom-control-label {
            font-weight: 500;
            padding-left: 0.25rem;
        }

        .contribution-option-item .custom-control-label small {
            font-weight: normal;
        }

        .contribution-submit-btn {
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }

        /* Dark Mode Styles */
        .dark-mode .contribution-notify-btn i {
            color: #f8f9fa;
        }

        .dark-mode .contribution-modal {
            background-color: #343a40;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .dark-mode .contribution-modal .modal-header {
            background-color: #343a40;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .dark-mode .contribution-modal .modal-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .dark-mode .contribution-description {
            color: #adb5bd;
        }

        .dark-mode .contribution-input {
            background-color: #495057;
            border-color: #6c757d;
            color: #f8f9fa;
        }

        .dark-mode .contribution-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .dark-mode .contribution-option-item {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .dark-mode .contribution-option-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .dark-mode .contribution-option-item .custom-control-label {
            color: #f8f9fa;
        }

        .dark-mode .contribution-option-item .custom-control-label small {
            color: #adb5bd;
        }

        .dark-mode .contribution-submit-btn {
            background-color: #007bff;
            border-color: #007bff;
        }

        .dark-mode .contribution-submit-btn:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }
    </style>

    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <!-- Preloader using ICON_APP.png with animated half circles -->
    <div id="preloader">
        <div class="preloader-content">
            <div class="circle-container">
                <!-- Animated half circles -->
                <div class="half-circle half-circle-1"></div>
                <div class="half-circle half-circle-2"></div>
                <div class="half-circle half-circle-3"></div>
                <div class="half-circle half-circle-4"></div>
                
                <!-- Logo centered inside circles -->
                <img src="{{ asset('vendor/adminlte/dist/img/ICON_APP.png') }}" class="preloader-icon" alt="MHR Icon">
            </div>
            <div class="loading-text">Loading</div>
        </div>
    </div>

    <div class="wrapper">

        @include('layouts.partials.navbar')
        @include('layouts.partials.sidebar')
        @include('layouts.partials.rightsidebar')
        
        <!-- Contributions Notification Modal -->
        <div class="modal fade" id="contributeNotifyModal" tabindex="-1" role="dialog" aria-labelledby="contributeNotifyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content contribution-modal">
                    <div class="modal-header">
                        <h5 class="modal-title" id="contributeNotifyModalLabel">
                            <i class="fas fa-bell mr-2"></i>Send All Contribution Notifications
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="allContributionsForm" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p class="text-muted contribution-description">Send notifications for SSS, Pag-IBIG, and PhilHealth contributions for the selected month and year.</p>
                            <div class="form-group">
                                <label for="all_notification_date">Select Month and Year</label>
                                <input type="month" class="form-control contribution-input" id="all_notification_date" name="notification_date" required>
                            </div>
                            <div class="contribution-options">
                                <div class="contribution-option-item">
                                    <div class="form-check custom-control custom-checkbox mb-3">
                                        <input class="form-check-input custom-control-input" type="checkbox" value="sss" id="notify_sss" name="contribution_types[]" checked>
                                        <label class="form-check-label custom-control-label" for="notify_sss">
                                            <i class="fas fa-shield-alt mr-1"></i> SSS
                                            <small class="d-block mt-1 text-muted">Social Security System</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="contribution-option-item">
                                    <div class="form-check custom-control custom-checkbox mb-3">
                                        <input class="form-check-input custom-control-input" type="checkbox" value="pagibig" id="notify_pagibig" name="contribution_types[]" checked>
                                        <label class="form-check-label custom-control-label" for="notify_pagibig">
                                            <i class="fas fa-home mr-1"></i> Pag-IBIG
                                            <small class="d-block mt-1 text-muted">Home Development Mutual Fund</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="contribution-option-item">
                                    <div class="form-check custom-control custom-checkbox mb-3">
                                        <input class="form-check-input custom-control-input" type="checkbox" value="philhealth" id="notify_philhealth" name="contribution_types[]" checked>
                                        <label class="form-check-label custom-control-label" for="notify_philhealth">
                                            <i class="fas fa-heartbeat mr-1"></i> PhilHealth
                                            <small class="d-block mt-1 text-muted">Philippine Health Insurance</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary contribution-submit-btn" id="send-all-btn">
                                <span class="normal-state"><i class="fas fa-paper-plane mr-1"></i> Send Notifications</span>
                                <span class="loading-state d-none">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        @include('layouts.partials.footer')
    </div>
    <!-- ./wrapper -->

    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
    <!-- Toast System Script - Removed -->

    @stack('scripts')

    @yield('js')

    <script>
        // Preloader control
        document.addEventListener('DOMContentLoaded', function() {
            // Hide preloader after page loaded
            const preloader = document.getElementById('preloader');
            if (preloader) {
                setTimeout(() => {
                    preloader.style.opacity = '0';
                    preloader.style.visibility = 'hidden';
                    
                    // Apply fade-in animation to key elements for smooth loading experience
                    const elementsToAnimate = [
                        '.content-wrapper',
                        '.main-sidebar',
                        '.main-header'
                    ];
                    
                    // Apply animation with slight delay between elements
                    elementsToAnimate.forEach((selector, index) => {
                        const elements = document.querySelectorAll(selector);
                        elements.forEach(el => {
                            setTimeout(() => {
                                el.classList.add('fade-in-element');
                            }, index * 100);
                        });
                    });
                }, 800); // Show preloader for 800ms minimum for better UX
            }
            
            // Replace window.showToast with SweetAlert2 based function
            window.showToast = function(message, type = 'info', duration = 3000) {
                // Map toast types to SweetAlert2 icons
                const iconMap = {
                    'success': 'success',
                    'error': 'error', 
                    'warning': 'warning',
                    'info': 'info'
                };
                
                // Show SweetAlert2 notification
                Swal.fire({
                    icon: iconMap[type] || 'info',
                    title: type.charAt(0).toUpperCase() + type.slice(1),
                    text: message,
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false,
                    timer: duration,
                    timerProgressBar: true
                });
            };
            
            // Check for session toast data
            const toastContainer = document.getElementById('toastDataContainer');
            if (toastContainer) {
                const from = toastContainer.getAttribute('data-from');
                const to = toastContainer.getAttribute('data-to');
                if (from && to) {
                    showToastNotification(`Switched from ${from} to ${to}`, "success");
                }
            }
        });
        
        // Helper function for account switch toast
        function showToastNotification(message, type) {
            Swal.fire({
                icon: type,
                title: 'Account Switched',
                text: message,
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
        
        // All Contributions Notification Form
        $(document).ready(function() {
            // Update the form action when the modal is shown
            $('#contributeNotifyModal').on('shown.bs.modal', function() {
                $('#all_notification_date').trigger('focus');
                $('#allContributionsForm').attr('action', "{{ route('contributions.notify-all') }}");
            });
            
            // Handle form submission with loading state
            $('#allContributionsForm').on('submit', function() {
                const submitBtn = $('#send-all-btn');
                submitBtn.prop('disabled', true)
                        .addClass('is-loading');
                $('.normal-state', submitBtn).addClass('d-none');
                $('.loading-state', submitBtn).removeClass('d-none');
            });
            
            // Reset button state if modal is closed
            $('#contributeNotifyModal').on('hidden.bs.modal', function() {
                const submitBtn = $('#send-all-btn');
                submitBtn.prop('disabled', false)
                        .removeClass('is-loading');
                $('.normal-state', submitBtn).removeClass('d-none');
                $('.loading-state', submitBtn).addClass('d-none');
            });
        });
    </script>

    <!-- Add this modal structure before the closing body tag -->
    <div class="modal fade" id="celebrantsModal" tabindex="-1" role="dialog" aria-labelledby="celebrantsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="celebrantsModalLabel">
                        <i class="fas fa-birthday-cake mr-2"></i>Today's Celebrants
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="celebrantsModalBody">
                    <!-- Celebrants will be loaded here -->
                </div>
                <div class="modal-footer">
                    <div class="form-check mr-auto">
                        <input type="checkbox" class="form-check-input" id="dontShowToday">
                        <label class="form-check-label" for="dontShowToday">Don't show this to me today</label>
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Add this before the closing </body> tag -->
@canany(['admin', 'super-admin', 'hrcomben', 'hrcompliance', 'hrpolicy', 'normal-employee', 'supervisor', 'finance'])
    <div class="quick-actions-fab">
        <button class="quick-actions-button" id="quickActionsToggle" title="Quick Actions">
            <i class="fas fa-cog"></i>
        </button>
        
        <div class="quick-actions-card" id="quickActionsCard">
            <div class="quick-actions-header">
                <i class="fas fa-bolt"></i>
                <span>Quick Actions</span>
            </div>
        @if(Auth::user()->hasRole('Employee') || Auth::user()->hasRole('Supervisor'))
            <div class="quick-actions-content">
                <a href="{{ route('leaves.create') }}" class="quick-action-item">
                    <div class="quick-action-icon bg-success">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div class="quick-action-text">
                        <div class="quick-action-title">Apply Leave</div>
                        <div class="quick-action-description">Request time off work</div>
                    </div>
                </a>
                
                <a href="{{ route('cash_advances.create') }}" class="quick-action-item">
                    <div class="quick-action-icon bg-info">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="quick-action-text">
                        <div class="quick-action-title">Apply Company Loan</div>
                        <div class="quick-action-description">Request financial assistance</div>
                    </div>
                </a>
            @endif
                <a href="https://t.me/edmarcrescencio" target="_blank" class="quick-action-item" id="helpAction">
                    <div class="quick-action-icon bg-warning">
                        <i class="fab fa-telegram"></i>
                    </div>
                    <div class="quick-action-text">
                        <div class="quick-action-title">IT Support</div>
                        <div class="quick-action-description">Contact IT via Telegram</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endcanany

    <!-- Link Account Modal -->
    <div class="modal fade" id="linkAccountModal" tabindex="-1" role="dialog" aria-labelledby="linkAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="linkAccountModalLabel">Link Another Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="linkAccountForm" action="{{ route('account.link') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-danger" id="linkAccountError" style="display: none;"></div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email Address" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="linkAccountBtn">
                            <span class="normal-text">Link Account</span>
                            <span class="loading-text" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i> Linking...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @yield('scripts')
    @include('layouts.partials.script')
    <!-- Responsive Dropdowns JS -->
    <script src="{{ asset('js/responsive-dropdowns.js') }}"></script>
    </body>
</html>