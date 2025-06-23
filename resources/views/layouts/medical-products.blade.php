<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Medical Products') - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <style>
        :root {
            --navbar-height: 64px;
            --sidebar-width: 280px;
            
            /* Light theme variables */
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --sidebar-bg: #ffffff;
            --content-bg: #f8fafc;
            --text-color: #111827;
            --text-muted: #6b7280;
            --border-color: rgba(0, 0, 0, 0.05);
            --card-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --card-shadow-hover: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --navbar-bg: rgba(255, 255, 255, 0.9);
            --hover-bg: rgba(37, 99, 235, 0.05);
            --active-bg: rgba(37, 99, 235, 0.1);
        }

        [data-bs-theme="dark"] {
            --sidebar-bg: #1e1e2d;
            --content-bg: #151521;
            --text-color: #e5e7eb;
            --text-muted: #9ca3af;
            --border-color: rgba(255, 255, 255, 0.05);
            --card-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.3);
            --card-shadow-hover: 0 10px 15px -3px rgb(0 0 0 / 0.3);
            --navbar-bg: rgba(30, 30, 45, 0.9);
            --hover-bg: rgba(37, 99, 235, 0.15);
            --active-bg: rgba(37, 99, 235, 0.2);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--content-bg);
            padding-top: var(--navbar-height);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            color: var(--text-color);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        .sidebar {
            position: fixed;
            top: var(--navbar-height);
            bottom: 0;
            left: 0;
            z-index: 100;
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            box-shadow: var(--card-shadow);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        background-color 0.3s ease;
            backdrop-filter: blur(8px);
        }

        .sidebar-sticky {
            position: sticky;
            top: 0;
            height: calc(100vh - var(--navbar-height));
            padding: 1.5rem 1rem;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .navbar {
            height: var(--navbar-height);
            background-color: var(--navbar-bg) !important;
            backdrop-filter: blur(8px);
            box-shadow: var(--card-shadow);
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.3s ease;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.25rem;
            color: var(--primary-color);
            letter-spacing: -0.025em;
        }

        .navbar-brand:hover {
            color: var(--primary-hover);
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            color: var(--text-color);
            padding: 0.875rem 1rem;
            border-radius: 0.5rem;
            margin: 0.125rem 0;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            position: relative;
            display: flex;
            align-items: center;
        }

        .nav-link:hover {
            color: var(--primary-color);
            background-color: var(--hover-bg);
        }

        .nav-link.active {
            color: var(--primary-color);
            background-color: var(--active-bg);
        }

        .nav-link i {
            width: 1.5rem;
            font-size: 1.125rem;
            margin-right: 1rem;
            text-align: center;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: calc(100vh - var(--navbar-height));
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: var(--content-bg);
        }

        @media (max-width: 767.98px) {
            .sidebar {
                transform: translateX(-100%);
                backdrop-filter: none;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 1.5rem;
            }

            .navbar-brand {
                font-size: 1.125rem;
            }

            .navbar-toggler {
                padding: 0.5rem;
                border-radius: 0.5rem;
                transition: background-color 0.2s ease;
            }

            .navbar-toggler:hover {
                background-color: rgba(0, 0, 0, 0.05);
            }
        }

        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 2rem;
        }

        .breadcrumb-item a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .breadcrumb-item a:hover {
            color: var(--primary-color);
        }

        .breadcrumb-item.active {
            color: var(--text-color);
        }

        .card {
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            box-shadow: var(--card-shadow);
            transition: box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        background-color 0.3s ease;
            background-color: var(--sidebar-bg);
            backdrop-filter: blur(8px);
        }

        .card:hover {
            box-shadow: var(--card-shadow-hover);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem;
            border-radius: 0.75rem 0.75rem 0 0 !important;
        }

        .content-wrapper {
            padding-top: 1rem;
        }

        .alert {
            border: none;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(8px);
        }

        /* Custom scrollbar for sidebar */
        .sidebar-sticky::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-sticky::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-sticky::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 2px;
        }

        .sidebar-sticky::-webkit-scrollbar-thumb:hover {
            background: #d1d5db;
        }

        /* Mobile menu dropdown */
        .dropdown-menu {
            border: none;
            border-radius: 0.5rem;
            box-shadow: var(--card-shadow-hover);
            backdrop-filter: blur(8px);
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
            font-weight: 500;
            color: #4b5563;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .dropdown-item:hover {
            background-color: rgba(37, 99, 235, 0.05);
            color: var(--primary-color);
        }

        .dropdown-item i {
            font-size: 1rem;
            width: 1.25rem;
            text-align: center;
        }

        /* Button styles */
        .btn {
            font-weight: 500;
            padding: 0.625rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        /* Theme toggle button */
        .theme-toggle {
            width: 40px;
            height: 40px;
            padding: 0;
            border: none;
            background: transparent;
            color: var(--text-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .theme-toggle:hover {
            background-color: var(--hover-bg);
        }

        .theme-toggle i {
            font-size: 1.25rem;
            transition: transform 0.5s ease;
        }

        [data-bs-theme="dark"] .theme-toggle .fa-sun,
        [data-bs-theme="light"] .theme-toggle .fa-moon {
            display: none;
        }

        [data-bs-theme="dark"] .theme-toggle .fa-moon,
        [data-bs-theme="light"] .theme-toggle .fa-sun {
            display: inline-block;
        }

        /* Select2 Dark Mode Styles */
        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-selection {
            background-color: var(--sidebar-bg);
            border-color: var(--border-color);
            color: var(--text-color);
        }

        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            color: var(--text-color);
        }

        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-dropdown {
            background-color: var(--sidebar-bg);
            border-color: var(--border-color);
        }

        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-dropdown .select2-results__option {
            color: var(--text-color);
        }

        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-dropdown .select2-results__option[aria-selected=true] {
            background-color: var(--active-bg);
        }

        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-dropdown .select2-results__option--highlighted {
            background-color: var(--hover-bg);
            color: var(--text-color);
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.1);
        }

        /* Preloader Styles */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--content-bg);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
        }

        .preloader.fade-out {
            opacity: 0;
            visibility: hidden;
        }

        .preloader-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid var(--border-color);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Prevent content flash during load */
        body.loading {
            overflow: hidden;
        }

        body.loading .container-fluid {
            opacity: 0;
        }

        .container-fluid {
            transition: opacity 0.3s ease-in;
        }

        .pending-badge {
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            background-color: #dc3545;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 30px;
            min-width: 1.5rem;
            text-align: center;
            box-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);
            animation: pulse 2s infinite;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(220, 53, 69, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        /* Dark mode styles */
        [data-bs-theme="dark"] .pending-badge {
            background-color: #ff4d4d;
            box-shadow: 0 2px 4px rgba(255, 77, 77, 0.2);
            animation: pulse-dark 2s infinite;
        }

        @keyframes pulse-dark {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 77, 77, 0.4);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(255, 77, 77, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(255, 77, 77, 0);
            }
        }

        /* Mobile responsive styles */
        @media (max-width: 768px) {
            .pending-badge {
                right: auto;
                left: 2rem;
                margin-left: 0.5rem;
            }
        }

        /* User Greeting Styles */
        .user-greeting {
            font-size: 0.875rem;
            color: var(--text-muted);
            max-width: 200px;
            margin-right: 1rem;
            font-weight: 500;
        }

        @media (min-width: 992px) {
            .user-greeting {
                max-width: 300px;
            }
        }

        .greeting {
            color: var(--primary-color);
            font-weight: 600;
            margin-right: 0.25rem;
        }
    </style>

    @stack('styles')
</head>
<body class="loading">
    <!-- Preloader -->
    <div class="preloader">
        <div class="preloader-spinner"></div>
    </div>

    <nav class="navbar navbar-expand-md navbar-light fixed-top">
        <div class="container-fluid">
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                <i class="fas fa-bars"></i>
            </button>
            @if(auth()->user()->hasRole('Super Admin'))
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('/vendor/adminlte/dist/img/mhrhci.png') }}" alt="Medical Icon" class="me-2" style="width: 50px; height: 30px;">
                Medical Products
            </a>
            @else
            <a class="navbar-brand" href="{{ route('analytics.dashboard') }}">
                <img src="{{ asset('/vendor/adminlte/dist/img/mhrhci.png') }}" alt="Medical Icon" class="me-2" style="width: 50px; height: 30px;">
                Medical Products
            </a>
            @endif
            <div class="d-flex align-items-center gap-2">
                <span class="user-greeting d-none d-sm-inline-block text-truncate">
                    <script>
                        document.write(`<span class="greeting"></span>`);
                    </script>
                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                </span>
                <button type="button" class="theme-toggle" id="themeToggle" title="Toggle theme">
                    <i class="fas fa-sun"></i>
                    <i class="fas fa-moon"></i>
                </button>
                <div class="dropdown d-md-none">
                    <button class="btn btn-link text-dark p-2" type="button" id="mobileMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mobileMenuButton">
                        <li>
                            <a class="dropdown-item" href="{{ route('medical-products.create') }}">
                                <i class="fas fa-plus-circle"></i>
                                Add Product
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('categories.index') }}">
                                <i class="fas fa-tags"></i>
                                Categories
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('quotations.index') }}">
                                <i class="fas fa-file-alt"></i>
                                Quote Requests
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="sidebar-sticky d-flex flex-column">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('analytics.dashboard') ? 'active' : '' }}" 
                               href="{{ route('analytics.dashboard') }}">
                                <i class="fas fa-chart-line"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('medical-products.index') ? 'active' : '' }}" 
                               href="{{ route('medical-products.index') }}">
                                <i class="fas fa-th-large"></i>
                                Products Overview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('medical-products.create') ? 'active' : '' }}" 
                               href="{{ route('medical-products.create') }}">
                                <i class="fas fa-plus-circle"></i>
                                Add New Product
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" 
                               href="{{ route('categories.index') }}">
                                <i class="fas fa-tags"></i>
                                Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('quotations.*') ? 'active' : '' }}" 
                               href="{{ route('quotations.index') }}">
                                <i class="fas fa-file-alt"></i>
                                Quote Requests
                                @php
                                    $pendingCount = \App\Models\QuotationRequest::where('status', 'pending')->count();
                                @endphp
                                @if($pendingCount > 0)
                                    <span class="pending-badge">
                                        {{ $pendingCount }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    </ul>
                    <div class="mt-auto border-top">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent text-danger">
                                <i class="fas fa-sign-out-alt text-danger"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="content-wrapper">
                    @if(!request()->routeIs('medical-products.index'))
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('medical-products.index') }}">Medical Products</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    @yield('title')
                                </li>
                            </ol>
                        </nav>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle functionality
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.querySelector('.navbar-toggler');
            
            if (sidebar && sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });

                document.addEventListener('click', function(event) {
                    if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                        sidebar.classList.remove('show');
                    }
                });
            }

            // Theme toggle functionality
            const themeToggle = document.getElementById('themeToggle');
            const html = document.documentElement;
            
            // Check for saved theme preference, otherwise use system preference
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                html.setAttribute('data-bs-theme', savedTheme);
            } else {
                const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                html.setAttribute('data-bs-theme', systemTheme);
                localStorage.setItem('theme', systemTheme);
            }

            // Theme toggle click handler
            themeToggle.addEventListener('click', function() {
                const currentTheme = html.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                
                html.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('theme', newTheme);

                // Animate icon
                const icon = this.querySelector('i:not([style*="display: none"])');
                icon.style.transform = 'rotate(360deg)';
                setTimeout(() => {
                    icon.style.transform = '';
                }, 500);
            });

            // Listen for system theme changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                if (!localStorage.getItem('theme')) {
                    const newTheme = e.matches ? 'dark' : 'light';
                    html.setAttribute('data-bs-theme', newTheme);
                }
            });

            // Dynamic greeting based on time of day
            function setGreeting() {
                const hour = new Date().getHours();
                const greetingElement = document.querySelector('.greeting');
                
                if (greetingElement) {
                    let greeting = '';
                    if (hour >= 5 && hour < 12) {
                        greeting = 'Good Success!';
                    } else if (hour >= 12 && hour < 17) {
                        greeting = 'Good Success!';
                    } else {
                        greeting = 'Good Success!';
                    }
                    greetingElement.textContent = greeting;
                }
            }

            // Set initial greeting and update every minute
            setGreeting();
            setInterval(setGreeting, 60000);

            // Preloader functionality
            window.addEventListener('load', function() {
                const preloader = document.querySelector('.preloader');
                const body = document.body;
                
                // Small delay to ensure smooth transition
                setTimeout(() => {
                    preloader.classList.add('fade-out');
                    body.classList.remove('loading');
                }, 500);

                // Remove preloader from DOM after animation
                preloader.addEventListener('transitionend', function() {
                    if (preloader.classList.contains('fade-out')) {
                        preloader.style.display = 'none';
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html> 
