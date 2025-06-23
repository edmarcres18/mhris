<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MHRPCI AI-Powered Tools and Utilities">
    <meta name="theme-color" content="#1a1f2c">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MHRPCI Tools - AI-Powered Utilities')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        /* Base Variables - Enhanced color scheme and transitions */
        :root {
            --primary-color: #1a1f2c;
            --secondary-color: #2a2f3c;
            --accent-color: #3b82f6;
            --accent-hover: #2563eb;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --error-color: #ef4444;
            --text-color: #ffffff;
            --text-muted: #9ca3af;
            --text-secondary: #d1d5db;
            --border-color: #374151;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1), 0 2px 4px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --transition-base: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-smooth: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            --spacing-base: 1rem;
            --border-radius-sm: 0.375rem;
            --border-radius-md: 0.5rem;
            --border-radius-lg: 0.75rem;
            --max-width-content: 1400px;
            --header-height: 4rem;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
        }

        /* Enhanced Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            font-size: 16px;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--primary-color);
            color: var(--text-color);
            display: flex;
            min-height: 100vh;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
            position: relative;
        }

        /* Enhanced Responsive Breakpoints */
        @media (max-width: 1536px) {
            :root {
                --max-width-content: 1200px;
                --sidebar-width: 260px;
            }
        }

        @media (max-width: 1280px) {
            :root {
                --max-width-content: 1024px;
                --sidebar-width: 240px;
            }
            
            .menu-item {
                padding: 0.75rem 0.875rem;
            }
            
            .menu-item i {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 1024px) {
            :root {
                --max-width-content: 768px;
                --sidebar-width: 240px;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                z-index: 1000;
                box-shadow: var(--shadow-lg);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0 !important;
                width: 100%;
            }

            .sidebar-toggle {
                display: flex !important;
            }

            .right-sidebar {
                width: 240px;
            }
        }

        @media (max-width: 768px) {
            :root {
                --max-width-content: 100%;
                --header-height: 3.5rem;
                --sidebar-width: 100%;
            }

            html {
                font-size: 14px;
            }

            .sidebar {
                width: 85%;
                max-width: 300px;
            }

            .right-sidebar {
                width: 85%;
                max-width: 300px;
            }

            .menu-item {
                padding: 0.75rem;
            }

            .sidebar-collapse-btn {
                display: none;
            }

            .tooltip-wrapper {
                display: none;
            }

            /* Enhance touch targets for mobile */
            .menu-item, 
            .theme-option,
            .sidebar-toggle,
            .theme-toggle {
                min-height: 48px;
                padding: 0.75rem 1rem;
            }
        }

        @media (max-width: 640px) {
            :root {
                --spacing-base: 0.75rem;
            }

            .main-content {
                padding: 1rem !important;
            }

            .sidebar,
            .right-sidebar {
                width: 100%;
                max-width: none;
            }

            .menu-section {
                margin-bottom: 1.5rem;
            }

            .logo-section {
                padding-bottom: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .menu-item {
                margin-bottom: 0.5rem;
            }
        }

        /* Enhanced Touch Handling */
        @media (hover: none) and (pointer: coarse) {
            .menu-item:hover {
                background: transparent;
                transform: none;
            }

            .menu-item:active {
                background: rgba(255, 255, 255, 0.1);
                transform: scale(0.98);
            }

            .sidebar-collapse-btn:hover {
                transform: none;
            }

            .sidebar-collapse-btn:active {
                transform: scale(0.95);
            }
        }

        /* Improved Toggle Buttons Container */
        .toggle-buttons-container {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1001;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            pointer-events: none;
        }

        /* Improved Sidebar Toggle Button */
        .sidebar-toggle {
            display: none;
            position: relative;
            z-index: 1001;
            padding: 0.75rem;
            background: var(--secondary-color);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            color: var(--text-color);
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            pointer-events: auto;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: auto;
        }

        /* Theme Toggle Button */
        .theme-toggle {
            position: relative;
            z-index: 1001;
            padding: 0.75rem;
            background: var(--secondary-color);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            color: var(--text-color);
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            pointer-events: auto;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
        }

        .sidebar-toggle:hover,
        .theme-toggle:hover {
            background: var(--accent-color);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .sidebar-toggle:active,
        .theme-toggle:active {
            transform: translateY(1px);
        }

        .sidebar-toggle i,
        .theme-toggle i {
            font-size: 1.25rem;
        }

        /* Safe Area Handling for Modern Mobile Devices */
        @supports (padding: max(0px)) {
            .toggle-buttons-container {
                padding: max(1rem, env(safe-area-inset-top)) max(1rem, env(safe-area-inset-right)) 1rem max(1rem, env(safe-area-inset-left));
            }
        }

        /* Enhanced Mobile Styles */
        @media (max-width: 768px) {
            .toggle-buttons-container {
                background: linear-gradient(to bottom, rgba(26, 31, 44, 0.8) 0%, rgba(26, 31, 44, 0) 100%);
                padding: max(1rem, env(safe-area-inset-top)) max(1rem, env(safe-area-inset-right)) 1rem max(1rem, env(safe-area-inset-left));
            }

            .sidebar-toggle,
            .theme-toggle {
                width: 38px;
                height: 38px;
                padding: 0.625rem;
            }

            .sidebar-toggle {
                margin-right: auto;
            }

            .theme-toggle {
                margin-left: auto;
            }

            .sidebar-toggle i,
            .theme-toggle i {
                font-size: 1.1rem;
            }
        }

        /* Main Content Adjustment for Mobile */
        @media (max-width: 1024px) {
            .main-content {
                padding-top: calc(var(--spacing-base) * 4) !important;
            }
        }

        /* Enhanced Mobile-First Responsive Design */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--secondary-color);
            padding: calc(var(--spacing-base) * 1.5);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            transition: var(--transition-smooth);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 50;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: calc(var(--spacing-base) * 2);
            max-width: 100%;
            transition: var(--transition-smooth);
            min-height: 100vh;
        }

        /* Enhanced Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
            }
            to {
                transform: translateX(0);
            }
        }

        /* Enhanced Accessibility */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }

        .visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* Enhanced Focus States */
        :focus-visible {
            outline: 2px solid var(--accent-color);
            outline-offset: 2px;
        }

        /* Enhanced Touch Targets */
        @media (pointer: coarse) {
            .menu-item,
            .button,
            .nav-link {
                min-height: 44px;
                padding: 12px;
            }
        }

        /* Print Styles */
        @media print {
            .sidebar,
            .preloader,
            .theme-toggle,
            .sidebar-toggle {
                display: none !important;
            }

            .main-content {
                margin: 0 !important;
                padding: 0 !important;
            }

            body {
                background: white !important;
                color: black !important;
            }
        }

        .sidebar {
            width: 280px;
            background: var(--secondary-color);
            padding: 1.5rem;
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            transition: var(--transition-base);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 50;
            padding-bottom: 5rem;
        }

        .sidebar.collapsed {
            width: 80px;
            padding: 1.5rem 0.75rem;
            transition: width 0.3s ease-in-out, padding 0.3s ease-in-out;
        }

        .sidebar.collapsed .logo-section {
            justify-content: center;
            padding-bottom: 1.5rem;
            position: relative;
            height: 50px;
            overflow: hidden;
        }

        .sidebar.collapsed .logo-section .icon {
            margin: 0;
            transform: scale(1.2);
            transition: transform 0.3s ease-in-out;
        }

        .sidebar.collapsed .logo-section h1,
        .sidebar.collapsed .logo-section .status-badge,
        .sidebar.collapsed .menu-item span,
        .sidebar.collapsed .section-title,
        .sidebar.collapsed .system-status-text,
        .sidebar.collapsed .menu-section > div,
        .sidebar.collapsed .status-badge span {
            opacity: 0;
            visibility: hidden;
            position: absolute;
            transition: opacity 0.2s ease-out, visibility 0.2s ease-out;
        }

        .sidebar .menu-item span,
        .sidebar .logo-section h1,
        .sidebar .logo-section .status-badge,
        .sidebar .section-title,
        .sidebar .system-status-text {
            opacity: 1;
            visibility: visible;
            position: relative;
            transition: opacity 0.3s ease-in, visibility 0.3s ease-in;
        }

        .sidebar.collapsed .menu-item {
            justify-content: center;
            padding: 1rem;
            margin-bottom: 0.75rem;
            border-radius: 0.75rem;
            position: relative;
            height: 48px;
            transition: all 0.3s ease-in-out;
        }

        .sidebar.collapsed .menu-item i {
            margin: 0;
            font-size: 1.35rem;
            transition: all 0.3s ease-in-out;
            position: relative;
            z-index: 2;
        }

        .sidebar.collapsed .menu-item:hover::after {
            content: attr(data-title);
            position: absolute;
            left: calc(100% + 5px);
            top: 50%;
            transform: translateY(-50%);
            background: var(--secondary-color);
            color: var(--text-color);
            padding: 0.625rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            white-space: nowrap;
            box-shadow: var(--shadow-lg);
            margin-left: 0.5rem;
            z-index: 70;
            border: 1px solid var(--border-color);
            backdrop-filter: blur(8px);
            animation: tooltipFadeIn 0.2s ease-out;
        }

        @keyframes tooltipFadeIn {
            from {
                opacity: 0;
                transform: translate(10px, -50%);
            }
            to {
                opacity: 1;
                transform: translate(0, -50%);
            }
        }

        .sidebar.collapsed .menu-item:hover::before {
            content: '';
            position: absolute;
            left: calc(100% - 1px);
            top: 50%;
            transform: translateY(-50%);
            border: 6px solid transparent;
            border-right-color: var(--secondary-color);
            margin-left: 0.25rem;
            z-index: 71;
            filter: drop-shadow(-2px 0 2px rgba(0, 0, 0, 0.1));
            animation: arrowFadeIn 0.2s ease-out;
        }

        @keyframes arrowFadeIn {
            from {
                opacity: 0;
                transform: translate(5px, -50%);
            }
            to {
                opacity: 1;
                transform: translate(0, -50%);
            }
        }

        .sidebar.collapsed .menu-item.active {
            background: var(--accent-color);
            transform: scale(1.05);
        }

        .sidebar.collapsed .menu-item.active::after {
            background: var(--accent-color);
            border-color: var(--accent-hover);
            color: var(--text-color);
            font-weight: 600;
        }

        .sidebar.collapsed .menu-item.active::before {
            border-right-color: var(--accent-color);
        }

        .sidebar.collapsed .menu-item.active i {
            color: var(--text-color);
            transform: scale(1.1);
        }

        .sidebar.collapsed + .main-content {
            margin-left: 80px;
            transition: margin-left 0.3s ease-in-out;
        }

        .sidebar.collapsed .sidebar-collapse-btn {
            width: 40px;
            height: 40px;
            left: 40px;
            border-radius: 50%;
            transition: all 0.3s ease-in-out;
        }

        .sidebar.collapsed .sidebar-collapse-btn i {
            transform: rotate(180deg);
            margin: 0;
            font-size: 1.1rem;
        }

        .sidebar.collapsed .menu-section {
            position: relative;
            padding: 0.5rem 0;
        }

        .sidebar.collapsed .section-title {
            height: 0;
            margin: 0;
            overflow: hidden;
        }

        .sidebar-collapse-btn {
            position: fixed;
            bottom: 1.5rem;
            left: 140px;
            transform: translateX(-50%);
            background: var(--accent-color);
            border: 2px solid var(--border-color);
            border-radius: 0.75rem;
            width: 160px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            color: var(--text-color);
            transition: all 0.3s ease-in-out;
            z-index: 60;
            box-shadow: var(--shadow-md);
            opacity: 0.9;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .sidebar.collapsed .sidebar-collapse-btn {
            width: 50px;
            left: 40px;
        }

        .sidebar-collapse-btn::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 0.75rem;
            background: linear-gradient(45deg, var(--accent-color), var(--accent-hover));
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            z-index: -1;
        }

        .sidebar-collapse-btn:hover {
            background: var(--accent-hover);
            border-color: var(--accent-color);
            box-shadow: var(--shadow-lg);
            transform: translateX(-50%) scale(1.05);
            opacity: 1;
        }

        .sidebar.collapsed .sidebar-collapse-btn:hover {
            transform: translateX(-50%) scale(1.05);
        }

        .sidebar-collapse-btn:hover::before {
            opacity: 0.5;
        }

        .sidebar-collapse-btn i {
            font-size: 1rem;
            transition: transform 0.3s ease-in-out;
        }

        .sidebar.collapsed .sidebar-collapse-btn i {
            transform: rotate(180deg);
        }

        .sidebar-collapse-btn span {
            transition: all 0.3s ease-in-out;
            white-space: nowrap;
        }

        .sidebar.collapsed .sidebar-collapse-btn span {
            display: none;
        }

        /* Pulse animation for the collapse button */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }

        .sidebar:not(.collapsed) .sidebar-collapse-btn {
            animation: pulse 2s infinite;
        }

        .sidebar-collapse-btn:hover {
            animation: none;
        }

        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 60;
            padding: 0.5rem;
            background: var(--secondary-color);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            color: var(--text-color);
            cursor: pointer;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 2rem;
        }

        .logo-section img {
            width: 32px;
            height: 32px;
        }

        .logo-section h1 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-color);
        }

        .section-title {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        .menu-section {
            margin-bottom: 2rem;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            border-radius: var(--border-radius-md);
            color: var(--text-color);
            text-decoration: none;
            transition: var(--transition-base);
            margin-bottom: 0.5rem;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
        }

        .menu-item.active {
            background: var(--accent-color);
            font-weight: 500;
            box-shadow: var(--shadow-md);
        }

        .menu-item i {
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
            transition: var(--transition-base);
        }

        .menu-item:hover i {
            transform: scale(1.1);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            gap: 0.375rem;
        }

        .status-badge.online {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .status-badge.busy {
            background: rgba(239, 68, 68, 0.1);
            color: var(--error-color);
        }

        /* Right Sidebar Styles */
        .right-sidebar {
            width: 280px;
            background: var(--secondary-color);
            padding: 1.5rem;
            border-left: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            transition: var(--transition-base);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 50;
            right: 0;
            top: 0;
            transform: translateX(100%);
        }

        .right-sidebar.active {
            transform: translateX(0);
        }

        .theme-toggle {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 60;
            padding: 0.5rem;
            background: var(--secondary-color);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            color: var(--text-color);
            cursor: pointer;
        }

        .theme-option {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: var(--transition-base);
            margin-bottom: 0.5rem;
        }

        .theme-option:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .theme-option.active {
            background: var(--accent-color);
        }

        .color-preview {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid var(--border-color);
        }

        /* Theme Variables */
        [data-theme="light"] {
            --primary-color: #ffffff;
            --secondary-color: #f3f4f6;
            --text-color: #1f2937;
            --text-muted: #6b7280;
            --text-secondary: #374151;
            --border-color: #e5e7eb;
            --active-menu-bg: #2563eb;
            --active-menu-color: #ffffff;
            --active-menu-hover: #1d4ed8;
        }

        [data-theme="dark"] {
            --primary-color: #1a1f2c;
            --secondary-color: #2a2f3c;
            --text-color: #ffffff;
            --text-muted: #9ca3af;
            --text-secondary: #d1d5db;
            --border-color: #374151;
            --active-menu-bg: var(--accent-color);
            --active-menu-color: #ffffff;
            --active-menu-hover: var(--accent-hover);
        }

        /* Menu Item Active States */
        .menu-item.active {
            background: var(--active-menu-bg);
            color: var(--active-menu-color);
            font-weight: 500;
        }

        .menu-item.active:hover {
            background: var(--active-menu-hover);
        }

        .menu-item.active i {
            color: var(--active-menu-color);
        }

        .sidebar.collapsed .menu-item.active {
            background: var(--active-menu-bg);
            transform: scale(1.05);
        }

        .sidebar.collapsed .menu-item.active::after {
            background: var(--active-menu-bg);
            border-color: var(--active-menu-hover);
            color: var(--active-menu-color);
        }

        .sidebar.collapsed .menu-item.active::before {
            border-right-color: var(--active-menu-bg);
        }

        /* Update the active indicator for light theme */
        [data-theme="light"] .menu-item.active::before {
            background: var(--active-menu-color);
            box-shadow: 0 0 8px rgba(37, 99, 235, 0.3);
        }

        /* Enhanced active state for light theme */
        [data-theme="light"] .menu-item.active {
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
        }

        [data-theme="light"] .menu-item.active:hover {
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.25);
        }

        /* Active state transitions */
        .menu-item {
            transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        /* Enhanced Tooltip Styles */
        .tooltip-wrapper {
            position: fixed;
            pointer-events: none;
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease-out, visibility 0.2s ease-out;
        }

        .tooltip {
            background: var(--secondary-color);
            color: var(--text-color);
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
            backdrop-filter: blur(8px);
            max-width: 250px;
            position: relative;
        }

        .tooltip-arrow {
            position: absolute;
            width: 8px;
            height: 8px;
            background: var(--secondary-color);
            transform: rotate(45deg);
            left: -4px;
            top: 50%;
            margin-top: -4px;
            border-left: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }

        .tooltip-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--accent-color);
        }

        .tooltip-description {
            color: var(--text-secondary);
            font-size: 0.8125rem;
            line-height: 1.4;
        }

        .tooltip-shortcut {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            margin-top: 0.5rem;
            padding: 0.25rem 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 0.25rem;
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .tooltip-shortcut kbd {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
            font-family: monospace;
        }

        .menu-item {
            position: relative;
            cursor: pointer;
        }

        .menu-item:hover .tooltip-wrapper {
            opacity: 1;
            visibility: visible;
        }

        /* Tooltip Animations */
        @keyframes tooltipSlideIn {
            from {
                opacity: 0;
                transform: translateX(10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .tooltip-wrapper {
            animation: tooltipSlideIn 0.2s ease-out;
        }

        /* Tooltip Positions */
        .tooltip-wrapper.top {
            transform: translateY(-100%);
            margin-top: -0.5rem;
        }

        .tooltip-wrapper.bottom {
            margin-top: 0.5rem;
        }

        .tooltip-wrapper.left {
            margin-right: 0.5rem;
            transform: translateX(-100%);
        }

        .tooltip-wrapper.right {
            margin-left: 0.5rem;
        }

        /* Tooltip Themes */
        .tooltip.primary {
            background: var(--accent-color);
            border-color: var(--accent-hover);
        }

        .tooltip.success {
            background: var(--success-color);
            border-color: #059669;
        }

        .tooltip.warning {
            background: var(--warning-color);
            border-color: #d97706;
        }

        .tooltip.error {
            background: var(--error-color);
            border-color: #dc2626;
        }

        /* Update menu items to use new tooltips */
        .sidebar.collapsed .menu-item:hover::after {
            display: none;
        }

        .sidebar.collapsed .menu-item:hover::before {
            display: none;
        }

        /* Enhanced Button Styles */
        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius-md);
            font-weight: 500;
            transition: var(--transition-base);
            cursor: pointer;
            border: none;
            outline: none;
            position: relative;
            overflow: hidden;
        }

        .button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: translateX(-100%);
            transition: var(--transition-base);
        }

        .button:hover::before {
            transform: translateX(100%);
        }

        .button-primary {
            background: var(--accent-color);
            color: white;
        }

        .button-secondary {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-color);
        }

        /* Enhanced Card Styles */
        .card {
            background: var(--secondary-color);
            border-radius: var(--border-radius-lg);
            padding: calc(var(--spacing-base) * 1.5);
            border: 1px solid var(--border-color);
            transition: var(--transition-base);
            position: relative;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), var(--accent-hover));
            opacity: 0;
            transition: var(--transition-base);
        }

        .card:hover::before {
            opacity: 1;
        }

        /* Enhanced Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-md);
            background: var(--primary-color);
            color: var(--text-color);
            transition: var(--transition-base);
        }

        .form-input:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        }

        /* Enhanced Table Styles */
        .table-container {
            overflow-x: auto;
            border-radius: var(--border-radius-lg);
            border: 1px solid var(--border-color);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .table th {
            background: var(--secondary-color);
            font-weight: 600;
            color: var(--text-secondary);
        }

        .table tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        /* Enhanced Loading States */
        .loading {
            position: relative;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: inherit;
        }

        /* Enhanced Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--primary-color);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent-color);
        }

        /* Enhanced Utility Classes */
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 0.5rem; }
        .gap-4 { gap: 1rem; }
        .hidden { display: none !important; }
        .text-center { text-align: center; }
        .relative { position: relative; }
        .w-full { width: 100%; }
        .h-full { height: 100%; }
        .p-4 { padding: 1rem; }
        .m-4 { margin: 1rem; }
        .rounded { border-radius: var(--border-radius-md); }
        .shadow { box-shadow: var(--shadow-md); }

        /* Enhanced Grid System */
        .grid {
            display: grid;
            gap: var(--spacing-base);
        }

        .grid-cols-1 { grid-template-columns: repeat(1, 1fr); }
        .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-cols-4 { grid-template-columns: repeat(4, 1fr); }

        @media (max-width: 1024px) {
            .lg\:grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .md\:grid-cols-1 { grid-template-columns: repeat(1, 1fr); }
        }

        /* Enhanced Animations */
        .animate-fade {
            animation: fadeIn 0.3s ease-out;
        }

        .animate-slide {
            animation: slideIn 0.3s ease-out;
        }

        .animate-pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        /* Enhanced Preloader */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--primary-color);
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

        .preloader-content {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
            animation: preloaderPulse 2s infinite;
        }

        .loader {
            width: 60px;
            height: 60px;
            position: relative;
        }

        .loader-circle {
            position: absolute;
            width: 100%;
            height: 100%;
            border: 3px solid transparent;
            border-radius: 50%;
            animation: rotate 1.5s cubic-bezier(0.42, 0, 0.58, 1) infinite;
        }

        .loader-circle:nth-child(1) {
            border-top-color: var(--accent-color);
            animation-delay: 0s;
        }

        .loader-circle:nth-child(2) {
            border-right-color: var(--success-color);
            animation-delay: 0.2s;
            scale: 0.8;
        }

        .loader-circle:nth-child(3) {
            border-bottom-color: var(--warning-color);
            animation-delay: 0.4s;
            scale: 0.6;
        }

        .loading-text {
            color: var(--text-color);
            font-size: 1.25rem;
            font-weight: 600;
            letter-spacing: 1px;
            position: relative;
        }

        .loading-text::after {
            content: '...';
            position: absolute;
            animation: ellipsis 1.5s infinite;
        }

        .loading-subtext {
            color: var(--text-muted);
            font-size: 0.875rem;
            opacity: 0.8;
        }

        @keyframes preloaderPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        @keyframes ellipsis {
            0% { content: '.'; }
            33% { content: '..'; }
            66% { content: '...'; }
            100% { content: '.'; }
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Glass Morphism Effects */
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-hover:hover {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Modern Shadows */
        .shadow-soft {
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .shadow-sharp {
            box-shadow: 5px 5px 0 rgba(0, 0, 0, 0.2);
        }

        .shadow-glow {
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
        }

        /* Enhanced Gradients */
        .gradient-primary {
            background: linear-gradient(135deg, var(--accent-color), var(--accent-hover));
        }

        .gradient-success {
            background: linear-gradient(135deg, var(--success-color), #059669);
        }

        .gradient-warning {
            background: linear-gradient(135deg, var(--warning-color), #d97706);
        }

        /* Modern Border Styles */
        .border-gradient {
            border: double 1px transparent;
            background-image: linear-gradient(var(--secondary-color), var(--secondary-color)), 
                              linear-gradient(to right, var(--accent-color), var(--accent-hover));
            background-origin: border-box;
            background-clip: padding-box, border-box;
        }

        .border-dashed {
            background-image: repeating-linear-gradient(to right,
                var(--border-color) 0%,
                var(--border-color) 50%,
                transparent 50%,
                transparent 100%);
            background-size: 15px 1px;
            background-repeat: repeat-x;
            background-position: bottom;
        }

        /* Modern Status Indicators */
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }

        .status-dot.online {
            background: var(--success-color);
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }

        .status-dot.offline {
            background: var(--error-color);
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
        }

        .status-dot.away {
            background: var(--warning-color);
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2);
        }

        /* Hover Effects */
        .hover-lift {
            transition: transform 0.2s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
        }

        .hover-scale {
            transition: transform 0.2s ease;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }

        .hover-rotate {
            transition: transform 0.3s ease;
        }

        .hover-rotate:hover {
            transform: rotate(5deg);
        }

        /* Enhanced Navigation Styles */
        .nav-container {
            position: relative;
            width: 100%;
        }

        /* Enhanced Menu Section Styles */
        .menu-section {
            position: relative;
            margin-bottom: 2rem;
        }

        .menu-section:last-child {
            margin-bottom: 5rem; /* Space for collapse button */
        }

        /* Enhanced Menu Item Styles */
        .menu-item {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            border-radius: var(--border-radius-md);
            color: var(--text-color);
            text-decoration: none;
            transition: var(--transition-base);
            margin-bottom: 0.5rem;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }

        .menu-item:focus-visible {
            outline: 2px solid var(--accent-color);
            outline-offset: -2px;
        }

        .menu-item.active {
            background: var(--accent-color);
            font-weight: 500;
            box-shadow: var(--shadow-md);
        }

        /* Enhanced Mobile Navigation */
        @media (max-width: 1024px) {
            .menu-item {
                padding: 1rem;
                margin-bottom: 0.75rem;
            }

            .menu-item i {
                width: 24px;
                text-align: center;
                font-size: 1.25rem;
            }

            .menu-section {
                padding: 0 0.5rem;
            }

            .section-title {
                padding: 0 0.5rem;
            }

            /* Enhanced Mobile Navigation Indicators */
            .menu-item::after {
                content: '';
                position: absolute;
                right: 1rem;
                top: 50%;
                transform: translateY(-50%);
                width: 6px;
                height: 6px;
                border-right: 2px solid var(--text-muted);
                border-bottom: 2px solid var(--text-muted);
                transform: translateY(-50%) rotate(-45deg);
                transition: var(--transition-base);
                opacity: 0.5;
            }

            .menu-item:hover::after,
            .menu-item:active::after,
            .menu-item.active::after {
                opacity: 1;
                transform: translateY(-50%) rotate(-45deg) scale(1.2);
                border-color: var(--text-color);
            }
        }

        /* Enhanced Touch Device Navigation */
        @media (hover: none) and (pointer: coarse) {
            .menu-item {
                position: relative;
                overflow: hidden;
            }

            .menu-item::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 100%;
                height: 100%;
                background: radial-gradient(circle at center, rgba(255,255,255,0.1) 0%, transparent 70%);
                transform: translate(-50%, -50%) scale(0);
                transition: transform 0.5s ease-out;
                pointer-events: none;
            }

            .menu-item:active::before {
                transform: translate(-50%, -50%) scale(2);
            }

            /* Enhanced Active State for Touch */
            .menu-item.active-touch {
                transform: scale(0.98);
                background: rgba(255, 255, 255, 0.1);
            }
        }

        /* Enhanced Navigation for Smaller Screens */
        @media (max-width: 768px) {
            .menu-item {
                padding: 1rem 1.25rem;
                border-radius: var(--border-radius-sm);
            }

            .menu-item i {
                font-size: 1.35rem;
            }

            .section-title {
                font-size: 0.8125rem;
                margin-bottom: 0.75rem;
            }

            /* Enhanced Touch Feedback */
            .menu-item:active {
                background: rgba(255, 255, 255, 0.15);
                transform: scale(0.98);
            }
        }

        /* Navigation Focus Styles */
        .user-is-tabbing .menu-item:focus {
            outline: 2px solid var(--accent-color);
            outline-offset: -2px;
            position: relative;
            z-index: 1;
        }

        /* Enhanced Keyboard Navigation Indicators */
        .menu-item:focus-visible::before {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: inherit;
            border: 2px solid var(--accent-color);
            pointer-events: none;
        }
    </style>
</head>
<body>
    <!-- Enhanced Preloader -->
    <div class="preloader" id="preloader">
        <div class="preloader-content">
            <div class="loader">
                <div class="loader-circle"></div>
                <div class="loader-circle"></div>
                <div class="loader-circle"></div>
            </div>
            <div>
                <div class="loading-text">Loading MHRPCI AI</div>
                <div class="loading-subtext">Preparing your workspace</div>
            </div>
        </div>
    </div>

    <script>
        // Initialize preloader
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const preloader = document.getElementById('preloader');
                preloader.classList.add('fade-out');
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 500);
            }, 1500); // Reduced loading time for better UX
        });
    </script>

    <!-- Toggle Buttons Container -->
    <div class="toggle-buttons-container">
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <button class="theme-toggle" id="themeToggle" aria-label="Toggle Theme">
            <i class="fas fa-palette"></i>
        </button>
    </div>

    <div class="sidebar" id="sidebar">
        <div class="logo-section">
            <div class="icon">
                <i class="fas fa-cube fa-lg"></i>
            </div>
            <div>
                <h1>MHRPCI AI</h1>
                <div class="status-badge online">
                    <i class="fas fa-circle text-xs"></i>
                    <span> Online</span>
                </div>
            </div>
        </div>

        <div class="menu-section">
            <h2 class="section-title">AI Tools</h2>
            <a href="{{ route('mhrpci-ai') }}" class="menu-item {{ request()->routeIs('mhrpci-ai') ? 'active' : '' }}" 
                data-title="AI Assistant" 
                data-description="Powerful AI assistant to help with your tasks">
                <i class="fas fa-robot"></i>
                <span>AI Assistant</span>
            </a>
            <a href="{{ route('image-generator') }}" class="menu-item {{ request()->routeIs('image-generator') ? 'active' : '' }}" 
                data-title="Image Generation" 
                data-description="Create stunning images using AI technology">
                <i class="fas fa-image"></i>
                <span>Image Generation</span>
            </a>
            <a href="{{ route('text-analysis') }}" class="menu-item {{ request()->routeIs('text-analysis') ? 'active' : '' }}" 
                data-title="Text Analysis" 
                data-description="Analyze and understand text content with AI">
                <i class="fas fa-language"></i>
                <span>Text Analysis</span>
            </a>
        </div>

        <div class="menu-section">
            <h2 class="section-title">Document Tools</h2>
            <a href="{{ route('document-scanner') }}" class="menu-item {{ request()->routeIs('document-scanner') ? 'active' : '' }}" 
                data-title="Document Scanner" 
                data-description="Scan and digitize your physical documents">
                <i class="fas fa-file-alt"></i>
                <span>Document Scanner</span>
            </a>
            <a href="{{ route('document-converter') }}" class="menu-item {{ request()->routeIs('document-converter') ? 'active' : '' }}" 
                data-title="Document Converter" 
                data-description="Convert documents between different formats">
                <i class="fas fa-file-pdf"></i>
                <span>Document Converter</span>
            </a>
        </div>

        <div class="menu-section">
            <h2 class="section-title">Media Tools</h2>
            <a href="{{ route('media-converter') }}" class="menu-item {{ request()->routeIs('media-converter') ? 'active' : '' }}" 
                data-title="Media Converter" 
                data-description="Convert media files to different formats">
                <i class="fas fa-photo-video"></i>
                <span>Media Converter</span>
            </a>
            <a href="#" class="menu-item" 
                data-title="Image Editor" 
                data-description="Edit and enhance your images">
                <i class="fas fa-crop"></i>
                <span>Image Editor</span>
            </a>
            <a href="#" class="menu-item" 
                data-title="Caption Generator" 
                data-description="Generate captions for your media content">
                <i class="fas fa-closed-captioning"></i>
                <span>Caption Generator</span>
            </a>
        </div>

        <button class="sidebar-collapse-btn" id="sidebarCollapseBtn" title="Toggle Sidebar">
            <i class="fas fa-chevron-left fa-fw"></i>
            <span>Collapse Sidebar</span>
        </button>
    </div>

    <div class="right-sidebar" id="rightSidebar">
        <div class="menu-section">
            <h2 class="section-title">Theme Settings</h2>
            <div class="theme-option" data-theme="dark">
                <div class="color-preview" style="background: #1a1f2c"></div>
                <span>Dark Theme</span>
            </div>
            <div class="theme-option" data-theme="light">
                <div class="color-preview" style="background: #ffffff; border: 1px solid #e5e7eb"></div>
                <span>Light Theme</span>
            </div>
        </div>

        <div class="menu-section">
            <h2 class="section-title">Font Size</h2>
            <input type="range" min="12" max="20" value="16" class="w-full" id="fontSizeControl">
            <div class="text-sm text-muted mt-2">
                Current Size: <span id="fontSizeValue">16px</span>
            </div>
        </div>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarCollapseBtn = document.getElementById('sidebarCollapseBtn');
            const rightSidebar = document.getElementById('rightSidebar');
            const themeToggle = document.getElementById('themeToggle');
            const themeOptions = document.querySelectorAll('.theme-option');
            const fontSizeControl = document.getElementById('fontSizeControl');
            const fontSizeValue = document.getElementById('fontSizeValue');
            const menuItems = document.querySelectorAll('.menu-item');
            const sections = document.querySelectorAll('.menu-section');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            // Track active menu item
            let activeMenuItem = null;
            let touchStartX = 0;
            let touchEndX = 0;
            let touchStartY = 0;
            const swipeThreshold = 50;

            // Initialize sidebar state from localStorage
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (sidebarCollapsed) {
                sidebar.classList.add('collapsed');
            }

            // Initialize theme from localStorage
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.body.setAttribute('data-theme', savedTheme);
            document.querySelector(`.theme-option[data-theme="${savedTheme}"]`).classList.add('active');

            // Initialize font size from localStorage
            const savedFontSize = localStorage.getItem('fontSize') || '16';
            document.body.style.fontSize = `${savedFontSize}px`;
            fontSizeControl.value = savedFontSize;
            fontSizeValue.textContent = `${savedFontSize}px`;

            // Enhanced Navigation Management
            function initializeNavigation() {
                // Enhanced Menu Item Click Handling
                menuItems.forEach(item => {
                    item.addEventListener('click', function(e) {
                        // Handle navigation items with href
                        const href = this.getAttribute('href');
                        if (href && href !== '#') {
                            // Add loading state
                            this.classList.add('loading');
                            
                            // Show loading indicator
                            const loadingIndicator = document.createElement('div');
                            loadingIndicator.className = 'loading-indicator';
                            this.appendChild(loadingIndicator);
                            
                            // Close sidebar on mobile after click
                            if (window.innerWidth <= 1024) {
                                closeSidebar();
                            }
                        }
                    });

                    // Enhanced Touch Handling
                    item.addEventListener('touchstart', function(e) {
                        this.classList.add('active-touch');
                        
                        // Prevent hover states during touch
                        this.dataset.touching = 'true';
                        
                        // Clear touching state after a delay
                        setTimeout(() => {
                            delete this.dataset.touching;
                        }, 1000);
                    }, { passive: true });

                    item.addEventListener('touchend', function(e) {
                        this.classList.remove('active-touch');
                    }, { passive: true });

                    // Prevent hover effects during touch
                    item.addEventListener('mouseover', function(e) {
                        if (this.dataset.touching) {
                            e.preventDefault();
                            return false;
                        }
                    });
                });
            }

            // Initialize navigation
            initializeNavigation();

            // Toggle sidebar collapse
            sidebarCollapseBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            });

            // Toggle left sidebar for mobile
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });

            // Toggle right sidebar
            themeToggle.addEventListener('click', function() {
                rightSidebar.classList.toggle('active');
            });

            // Theme switching
            themeOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const theme = this.getAttribute('data-theme');
                    document.body.setAttribute('data-theme', theme);
                    localStorage.setItem('theme', theme);

                    // Update active state
                    themeOptions.forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Font size control
            fontSizeControl.addEventListener('input', function() {
                const size = this.value;
                document.body.style.fontSize = `${size}px`;
                fontSizeValue.textContent = `${size}px`;
                localStorage.setItem('fontSize', size);
            });

            // Close sidebars when clicking outside
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 1024) {
                    if (!sidebar.contains(e.target) && 
                        !sidebarToggle.contains(e.target) && 
                        sidebar.classList.contains('active')) {
                        sidebar.classList.remove('active');
                    }

                    if (!rightSidebar.contains(e.target) && 
                        !themeToggle.contains(e.target) && 
                        rightSidebar.classList.contains('active')) {
                        rightSidebar.classList.remove('active');
                    }
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 1024) {
                    sidebar.classList.remove('active');
                    rightSidebar.classList.remove('active');
                }
            });

            // Initialize tooltips
            const tooltipWrapper = document.createElement('div');
            tooltipWrapper.className = 'tooltip-wrapper';
            document.body.appendChild(tooltipWrapper);

            menuItems.forEach(item => {
                const title = item.getAttribute('data-title');
                const description = item.getAttribute('data-description') || 'Quick access to ' + title;

                item.addEventListener('mouseenter', (e) => {
                    const rect = item.getBoundingClientRect();
                    const tooltipContent = `
                        <div class="tooltip">
                            <div class="tooltip-arrow"></div>
                            <div class="tooltip-title">${title}</div>
                            <div class="tooltip-description">${description}</div>
                        </div>
                    `;

                    tooltipWrapper.innerHTML = tooltipContent;
                    tooltipWrapper.style.top = rect.top + (rect.height / 2) + 'px';
                    tooltipWrapper.style.left = rect.right + 10 + 'px';
                    tooltipWrapper.style.opacity = '1';
                    tooltipWrapper.style.visibility = 'visible';
                });

                item.addEventListener('mouseleave', () => {
                    tooltipWrapper.style.opacity = '0';
                    tooltipWrapper.style.visibility = 'hidden';
                });
            });

            // Enhanced Mobile Handling
            sidebarOverlay.addEventListener('click', closeSidebar);

            // Enhanced window resize handling
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    if (window.innerWidth > 1024) {
                        sidebar.classList.remove('active');
                        rightSidebar.classList.remove('active');
                        sidebarOverlay.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                }, 250);
            });

            // Enhanced keyboard navigation
            document.addEventListener('keydown', function(e) {
                // Close sidebars with Escape key
                if (e.key === 'Escape') {
                    if (sidebar.classList.contains('active') || rightSidebar.classList.contains('active')) {
                        closeSidebar();
                        rightSidebar.classList.remove('active');
                    }
                }

                // Toggle sidebar with keyboard shortcuts
                if (e.ctrlKey && e.shiftKey) {
                    switch(e.key.toLowerCase()) {
                        case 'm': // Ctrl+Shift+M - Toggle sidebar
                            e.preventDefault();
                            sidebarToggle.click();
                            break;
                        case 't': // Ctrl+Shift+T - Toggle theme
                            e.preventDefault();
                            themeToggle.click();
                            break;
                    }
                }
            });

            // Enhanced menu item handling for touch devices
            menuItems.forEach(item => {
                item.addEventListener('touchstart', function() {
                    this.classList.add('active-touch');
                });

                item.addEventListener('touchend', function() {
                    this.classList.remove('active-touch');
                });

                // Add keyboard navigation
                item.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.click();
                    }
                });
            });

            // Handle orientation change
            window.addEventListener('orientationchange', function() {
                closeSidebar();
                rightSidebar.classList.remove('active');
                
                // Reset any transform styles that might be applied
                setTimeout(() => {
                    sidebar.style.transition = 'none';
                    sidebar.style.transform = '';
                    setTimeout(() => {
                        sidebar.style.transition = '';
                    }, 100);
                }, 100);
            });

            // Enhanced focus management
            function handleFirstTab(e) {
                if (e.key === 'Tab') {
                    document.body.classList.add('user-is-tabbing');
                    window.removeEventListener('keydown', handleFirstTab);
                }
            }
            window.addEventListener('keydown', handleFirstTab);

            // Add touch feedback styles
            const style = document.createElement('style');
            style.textContent = `
                @media (hover: none) {
                    .menu-item.active-touch {
                        background: rgba(255, 255, 255, 0.1);
                        transform: scale(0.98);
                    }
                }
            `;
            document.head.appendChild(style);

            // Handle back button for mobile
            window.addEventListener('popstate', function() {
                if (sidebar.classList.contains('active')) {
                    closeSidebar();
                }
                if (rightSidebar.classList.contains('active')) {
                    rightSidebar.classList.remove('active');
                }
            });

            // Save scroll position for mobile
            let scrollPosition = 0;
            function handleSidebarOpen() {
                scrollPosition = window.pageYOffset;
                document.body.style.overflow = 'hidden';
                document.body.style.position = 'fixed';
                document.body.style.top = `-${scrollPosition}px`;
                document.body.style.width = '100%';
            }

            function handleSidebarClose() {
                document.body.style.removeProperty('overflow');
                document.body.style.removeProperty('position');
                document.body.style.removeProperty('top');
                document.body.style.removeProperty('width');
                window.scrollTo(0, scrollPosition);
            }

            // Update sidebar toggle handlers
            sidebarToggle.addEventListener('click', function() {
                if (!sidebar.classList.contains('active')) {
                    handleSidebarOpen();
                } else {
                    handleSidebarClose();
                }
            });

            // Handle iOS Safari viewport issues
            function handleIOSViewport() {
                document.documentElement.style.setProperty(
                    '--vh', 
                    `${window.innerHeight * 0.01}px`
                );
            }

            handleIOSViewport();
            window.addEventListener('resize', handleIOSViewport);

            // Enhanced Navigation Management
            function initializeAccessibility() {
                menuItems.forEach(item => {
                    item.setAttribute('role', 'menuitem');
                    if (item.classList.contains('active')) {
                        item.setAttribute('aria-current', 'page');
                    }
                });

                sections.forEach(section => {
                    const title = section.querySelector('.section-title');
                    if (title) {
                        const titleId = 'section-' + title.textContent.toLowerCase().replace(/\s+/g, '-');
                        title.id = titleId;
                        section.setAttribute('aria-labelledby', titleId);
                        section.setAttribute('role', 'navigation');
                    }
                });
            }

            // Initialize accessibility features
            initializeAccessibility();

            // Enhanced Mobile Navigation
            sidebar.addEventListener('touchstart', e => {
                touchStartY = e.touches[0].clientY;
            }, { passive: true });

            sidebar.addEventListener('touchmove', e => {
                const touchY = e.touches[0].clientY;
                const scrollTop = sidebar.scrollTop;
                const scrollHeight = sidebar.scrollHeight;
                const clientHeight = sidebar.clientHeight;

                // Prevent overscroll
                if (scrollTop <= 0 && touchY > touchStartY) {
                    e.preventDefault();
                }
                if (scrollTop + clientHeight >= scrollHeight && touchY < touchStartY) {
                    e.preventDefault();
                }
            }, { passive: false });
        });
    </script>
</body>
</html>
