<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HRIS') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --secondary-color: #f3f4f6;
            --text-color: #1f2937;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --navbar-height: 64px;
            --transition-speed: 0.3s;
        }

        [data-theme="dark"] {
            --primary-color: #3b82f6;
            --primary-hover: #2563eb;
            --secondary-color: #1f2937;
            --text-color: #f3f4f6;
            --text-muted: #9ca3af;
            --border-color: #374151;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            color: var(--text-color);
            margin: 0;
            min-height: 100vh;
        }

        /* Layout */
        .app-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            border-right: 1px solid var(--border-color);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 50;
            transition: width var(--transition-speed) ease;
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .logo-section {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .logo-section .icon {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }

        .logo-section h1 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            white-space: nowrap;
        }

        /* Menu Sections */
        .menu-section {
            padding: 1.5rem;
            flex: 1;
        }

        .section-title {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1rem;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--text-color);
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: all var(--transition-speed) ease;
            gap: 1rem;
            position: relative;
        }

        .menu-item i {
            font-size: 1.25rem;
            color: var(--text-muted);
            transition: color var(--transition-speed) ease;
            width: 24px;
            text-align: center;
        }

        .menu-item span {
            font-weight: 500;
            white-space: nowrap;
        }

        .menu-item:hover {
            background: var(--secondary-color);
            transform: translateX(4px);
        }

        .menu-item.active {
            background: var(--primary-color);
            color: white;
        }

        .menu-item.active i {
            color: white;
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--navbar-height);
            background: white;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            z-index: 40;
            transition: left var(--transition-speed) ease;
        }

        .sidebar.collapsed + .navbar {
            left: var(--sidebar-collapsed-width);
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .navbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            padding: 2rem;
            transition: margin-left var(--transition-speed) ease;
            min-height: calc(100vh - var(--navbar-height));
        }

        .sidebar.collapsed + .navbar + .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Notification Badge */
        .notification-badge {
            background: #ef4444;
            color: white;
            font-size: 0.75rem;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            position: absolute;
            top: -5px;
            right: -5px;
        }

        /* User Profile */
        .user-profile {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: auto;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--secondary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            flex-shrink: 0;
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-email {
            font-size: 0.875rem;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Collapsed State */
        .sidebar.collapsed .logo-section div:not(.icon),
        .sidebar.collapsed .menu-item span,
        .sidebar.collapsed .user-info {
            display: none;
        }

        .sidebar.collapsed .menu-item {
            justify-content: center;
            padding: 0.75rem;
        }

        .sidebar.collapsed .menu-item i {
            margin: 0;
        }

        .sidebar.collapsed .user-profile {
            justify-content: center;
            padding: 1rem;
        }

        /* Mobile Responsiveness */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .navbar {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 45;
            }

            .sidebar.mobile-open + .sidebar-overlay {
                display: block;
            }
        }

        @media (max-width: 640px) {
            .navbar {
                padding: 0 1rem;
            }

            .main-content {
                padding: 1rem;
            }

            .menu-item span {
                font-size: 0.875rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="logo-section">
                <div class="icon">
                    <i class="fas fa-cube"></i>
                </div>
                <div>
                    <h1>ALF Pte. Ltd.</h1>
                </div>
            </div>

            <div class="menu-section">
                <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('notifications') }}" class="menu-item {{ request()->routeIs('notifications') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                    <div class="notification-badge">4</div>
                </a>

                <a href="{{ route('projects') }}" class="menu-item {{ request()->routeIs('projects') ? 'active' : '' }}">
                    <i class="fas fa-project-diagram"></i>
                    <span>Projects</span>
                </a>

                <a href="{{ route('tasks') }}" class="menu-item {{ request()->routeIs('tasks') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i>
                    <span>Tasks</span>
                </a>

                <a href="{{ route('analytics') }}" class="menu-item {{ request()->routeIs('analytics') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Analytics</span>
                </a>
            </div>

            <div class="menu-section mt-auto">
                <a href="{{ route('settings') }}" class="menu-item {{ request()->routeIs('settings') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>

                <a href="{{ route('support') }}" class="menu-item {{ request()->routeIs('support') ? 'active' : '' }}">
                    <i class="fas fa-question-circle"></i>
                    <span>Support</span>
                </a>
            </div>

            <div class="user-profile">
                <div class="user-avatar">
                    {{ substr(Auth::user()->first_name, 0, 1) }}
                </div>
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                    <div class="user-email">{{ Auth::user()->email }}</div>
                </div>
            </div>
        </aside>

        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Navbar -->
        <nav class="navbar">
            <div class="navbar-left">
                <button id="sidebarToggle" class="menu-item" style="margin: 0; padding: 0.5rem;">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <div class="navbar-right">
                <button class="menu-item" style="margin: 0; padding: 0.5rem;">
                    <i class="fas fa-search"></i>
                </button>
                <button class="menu-item" style="margin: 0; padding: 0.5rem;">
                    <i class="fas fa-bell"></i>
                </button>
                <button class="menu-item" style="margin: 0; padding: 0.5rem;">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            // Toggle sidebar on button click
            sidebarToggle.addEventListener('click', () => {
                if (window.innerWidth <= 1024) {
                    sidebar.classList.toggle('mobile-open');
                } else {
                    sidebar.classList.toggle('collapsed');
                }
            });

            // Close sidebar when clicking overlay
            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.remove('mobile-open');
            });

            // Handle window resize
            window.addEventListener('resize', () => {
                if (window.innerWidth > 1024) {
                    sidebar.classList.remove('mobile-open');
                }
            });

            // Handle dark mode toggle
            const darkModeToggle = document.querySelector('.navbar-right .fa-moon').parentElement;
            darkModeToggle.addEventListener('click', () => {
                document.body.setAttribute('data-theme', 
                    document.body.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'
                );
            });
        });
    </script>

    @stack('scripts')
</body>
</html> 