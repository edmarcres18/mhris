<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Profile</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')

    <style>
        :root {
            --primary-color: #0d6efd;
            --primary-light: rgba(13, 110, 253, 0.1);
            --text-color: #2d3748;
            --text-muted: #6c757d;
            --border-color: #e2e8f0;
            --sidebar-width: 280px;
            --topbar-height: 60px;
            --transition-speed: 0.3s;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-color);
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        /* Layout */
        .profile-layout {
            display: flex;
            min-height: 100vh;
        }

        .profile-sidebar {
            width: var(--sidebar-width);
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            position: fixed;
            top: var(--topbar-height);
            left: 0;
            height: calc(100vh - var(--topbar-height));
            z-index: 1000;
            transition: transform var(--transition-speed) ease;
            overflow-y: auto;
        }

        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 2rem;
            transition: margin var(--transition-speed) ease;
        }

        .content-wrapper {
            padding-top: calc(var(--topbar-height) + 1rem);
        }

        /* Top Navbar */
        .top-navbar {
            height: var(--topbar-height);
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1001;
            padding: 0 1.5rem;
        }

        .navbar-brand {
            font-weight: 600;
            color: var(--text-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand:hover {
            color: var(--primary-color);
        }

        /* User Info */
        .user-info {
            padding: 0.5rem 1rem;
            color: var(--text-color);
            font-weight: 500;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Sidebar Navigation */
        .profile-nav-item {
            padding: 0.875rem 1.5rem;
            color: var(--text-muted);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all var(--transition-speed);
            border-left: 3px solid transparent;
            margin: 0.25rem 0;
            cursor: pointer;
        }
        
        .profile-nav-item:hover,
        .profile-nav-item.active {
            color: var(--primary-color);
            background-color: var(--primary-light);
            border-left-color: var(--primary-color);
        }

        .profile-nav-item.text-danger:hover {
            color: #dc3545 !important;
            background-color: rgba(220, 53, 69, 0.1);
            border-left-color: #dc3545;
        }
        
        .profile-nav-item i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        /* Remove old dropdown styles */
        .user-dropdown,
        .dropdown-menu,
        .dropdown-item {
            display: none;
        }

        /* Mobile Responsiveness */
        @media (max-width: 991.98px) {
            :root {
                --sidebar-width: 240px;
            }

            .profile-sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-open .profile-sidebar {
                transform: translateX(0);
            }

            .sidebar-open .main-content {
                margin-left: 0;
            }

            .sidebar-backdrop {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.3);
                z-index: 999;
            }

            .sidebar-open .sidebar-backdrop {
                display: block;
            }
        }

        @media (max-width: 767.98px) {
            :root {
                --topbar-height: 56px;
            }

            .main-content {
                padding: 1rem;
            }

            .content-wrapper {
                padding-top: calc(var(--topbar-height) + 0.5rem);
            }

            .profile-nav-item {
                padding: 0.75rem 1rem;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .fade-in {
            animation: fadeIn var(--transition-speed) ease;
        }

        /* Card Enhancements */
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: transform var(--transition-speed), box-shadow var(--transition-speed);
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* Preloader */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s, visibility 0.5s;
        }

        .preloader.fade-out {
            opacity: 0;
            visibility: hidden;
        }

        .loader {
            position: relative;
            width: 120px;
            height: 120px;
        }

        .loader-circle {
            position: absolute;
            width: 100%;
            height: 100%;
            border: 3px solid transparent;
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 2s linear infinite;
        }

        .loader-circle:nth-child(2) {
            width: 80%;
            height: 80%;
            top: 10%;
            left: 10%;
            border-top-color: var(--text-color);
            animation-duration: 1.5s;
            animation-direction: reverse;
        }

        .loader-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            text-align: center;
        }

        .loader-logo {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .loader-text {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="loader">
            <div class="loader-circle"></div>
            <div class="loader-circle"></div>
            <div class="loader-content">
                <div class="loader-logo">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="loader-text">Loading your profile...</div>
            </div>
        </div>
    </div>

    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light top-navbar">
        <div class="container-fluid px-0">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Home</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="ms-auto d-flex align-items-center">
                    <div class="user-info d-flex align-items-center">
                        <div class="user-avatar">
                            {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}
                        </div>
                        <span class="d-none d-sm-inline ms-2">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="profile-layout">
        <!-- Sidebar Backdrop -->
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <!-- Sidebar -->
        <aside class="profile-sidebar py-4">
            <div class="d-flex flex-column h-100">
                <!-- Navigation Links -->
                <div class="flex-grow-1">
                    <a href="{{ route('profile.details') }}" class="profile-nav-item {{ request()->routeIs('profile.details') ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        <span>Profile Details</span>
                    </a>
                    <a href="{{ route('profile.show') }}" class="profile-nav-item {{ request()->routeIs('profile.show') ? 'active' : '' }}">
                        <i class="fas fa-edit"></i>
                        <span>Edit Profile</span>
                    </a>
                </div>

                <!-- Logout Button -->
                <div class="mt-auto border-top pt-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="profile-nav-item text-danger w-100 border-0 bg-transparent">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content fade-in">
            <div class="content-wrapper">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Preloader
        window.addEventListener('load', function() {
            const preloader = document.querySelector('.preloader');
            preloader.classList.add('fade-out');
            
            // Remove preloader from DOM after animation
            setTimeout(() => {
                preloader.remove();
            }, 500);
        });

        document.addEventListener('DOMContentLoaded', function() {
            const body = document.body;
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');

            // Toggle sidebar on mobile
            function toggleSidebar() {
                body.classList.toggle('sidebar-open');
            }

            sidebarToggle.addEventListener('click', toggleSidebar);
            sidebarBackdrop.addEventListener('click', toggleSidebar);

            // Close sidebar when clicking a link on mobile
            const sidebarLinks = document.querySelectorAll('.profile-nav-item');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 992) {
                        body.classList.remove('sidebar-open');
                    }
                });
            });

            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    if (window.innerWidth >= 992) {
                        body.classList.remove('sidebar-open');
                    }
                }, 250);
            });
        });
    </script>
    @stack('scripts')
</body>
</html> 