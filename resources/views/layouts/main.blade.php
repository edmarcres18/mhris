<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    @yield('meta')
    <title>@yield('title', 'MHRPCI')</title>
    <link rel="icon" href="/vendor/adminlte/dist/img/LOGO4.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.js"></script>
<style>
    .preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: white;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.5s ease-in-out;
    }

    .preloader.fade-out {
        opacity: 0;
    }

    .loader {
        width: 80px;
        height: 80px;
        position: relative;
    }

    .loader-circle {
        position: absolute;
        width: 100%;
        height: 100%;
        border: 4px solid transparent;
        border-radius: 50%;
        border-top-color: var(--purple-primary);
        animation: spin 1s linear infinite;
    }

    .loader-circle:nth-child(2) {
        border-top-color: var(--purple-secondary);
        animation-delay: 0.2s;
        scale: 0.8;
    }

    .loader-circle:nth-child(3) {
        border-top-color: var(--purple-light);
        animation-delay: 0.4s;
        scale: 0.6;
    }

    .loader-text {
        position: absolute;
        top: 120%;
        left: 50%;
        transform: translateX(-50%);
        color: var(--purple-primary);
        font-weight: 600;
        white-space: nowrap;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    body.loading {
        overflow: hidden;
    }

    :root {
        --purple-primary: #6b21a8;
        --purple-secondary: #7c3aed;
        --purple-light: #8b5cf6;
        --purple-dark: #4c1d95;
        --purple-gradient: linear-gradient(45deg, #6b21a8, #7c3aed);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: system-ui, -apple-system, sans-serif;
        line-height: 1.6;
        color: #333;
    }

    .nav {
        background: rgba(255, 255, 255, 0.98);
        padding: 0.75rem 0;
        box-shadow: 0 2px 4px rgba(107, 33, 168, 0.1);
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .nav.scrolled {
        padding: 0.5rem 0;
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 4px 6px rgba(107, 33, 168, 0.1);
    }

    .nav-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
        position: relative;
    }

    .brand {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: var(--purple-dark);
        padding: 0.5rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .brand:hover {
        background: rgba(107, 33, 168, 0.05);
    }

    .brand-logo {
        display: flex;
        align-items: center;
        font-weight: 700;
        font-size: 1.5rem;
        color: var(--purple-primary);
        gap: 0.5rem;
    }

    .nav-content {
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .nav-list {
        display: flex;
        align-items: center;
        list-style: none;
        margin: 0;
        padding: 0;
        gap: 1rem;
    }

    .nav-item {
        position: relative;
    }

    .nav-link {
        text-decoration: none;
        color: #333;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .nav-link:hover {
        color: var(--purple-primary);
        background: rgba(107, 33, 168, 0.05);
    }

    .nav-link.active {
        color: var(--purple-primary);
        background: rgba(107, 33, 168, 0.08);
    }

    .google-login {
        display: flex;
        align-items: center;
        background: #fff;
        border: 2px solid var(--purple-light);
        border-radius: 8px;
        padding: 0.5rem 1.25rem;
        font-size: 0.95rem;
        color: var(--purple-primary);
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        gap: 0.5rem;
    }

    .google-login:hover {
        background: var(--purple-primary);
        color: white;
        border-color: var(--purple-primary);
    }

    .google-login:hover svg path {
        fill: white;
    }

    /* Mobile Menu Button */
    .mobile-menu-btn {
        display: none;
        background: none;
        border: none;
        padding: 0.5rem;
        cursor: pointer;
        position: relative;
        z-index: 1001;
    }

    .menu-icon {
        display: block;
        width: 24px;
        height: 2px;
        background: var(--purple-primary);
        position: relative;
        transition: all 0.3s ease;
    }

    .menu-icon::before,
    .menu-icon::after {
        content: '';
        position: absolute;
        width: 24px;
        height: 2px;
        background: var(--purple-primary);
        transition: all 0.3s ease;
    }

    .menu-icon::before {
        top: -6px;
    }

    .menu-icon::after {
        bottom: -6px;
    }

    /* Mobile Menu Open State */
    .mobile-menu-btn.active .menu-icon {
        background: transparent;
    }

    .mobile-menu-btn.active .menu-icon::before {
        top: 0;
        transform: rotate(45deg);
    }

    .mobile-menu-btn.active .menu-icon::after {
        bottom: 0;
        transform: rotate(-45deg);
    }

    @media (max-width: 768px) {
        .mobile-menu-btn {
            display: block;
        }

        .nav-content {
            position: fixed;
            top: 0;
            right: -100%;
            width: 80%;
            max-width: 400px;
            height: 100vh;
            background: white;
            flex-direction: column;
            padding: 5rem 2rem 2rem;
            transition: all 0.3s ease;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .nav-content.active {
            right: 0;
        }

        .nav-list {
            flex-direction: column;
            width: 100%;
            gap: 0.75rem;
        }

        .nav-item {
            width: 100%;
        }

        .nav-link {
            padding: 0.75rem 1rem;
            width: 100%;
            justify-content: flex-start;
            font-size: 1.1rem;
        }

        .google-login {
            width: 100%;
            justify-content: center;
            margin-top: 1rem;
            padding: 0.75rem;
        }

        /* Overlay for mobile menu */
        .nav-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .nav-overlay.active {
            display: block;
            opacity: 1;
        }
    }

    /* Small screen adjustments */
    @media (max-width: 480px) {
        .nav-container {
            padding: 0 1rem;
        }

        .brand-logo {
            font-size: 1.25rem;
        }

        .nav-content {
            width: 100%;
            padding: 4rem 1.5rem 2rem;
        }
    }

    .hero {
        background: var(--purple-gradient);
        color: white;
        padding: 6rem 1rem 3rem;
        text-align: center;
    }

    .hero h1 {
        font-size: clamp(2rem, 5vw, 3.5rem);
        line-height: 1.2;
        margin-bottom: 1rem;
    }

    .hero p {
        font-size: clamp(2.5rem, 3vw, 1.25rem);
    }

    .section {
        padding: 5rem 2rem;
    }

    .section-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
        width: 100%;
    }

    .section-title {
        text-align: center;
        margin-bottom: 3rem;
        font-size: 2.5rem;
        color: var(--purple-dark);
    }

    .section-subtitle {
        text-align: center;
        margin-bottom: 4rem;
        color: #666;
        font-size: 1.2rem;
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin: 0 auto;
        max-width: 1200px;
    }

    .service-card {
        background: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(107, 33, 168, 0.1);
        transition: all 0.3s ease;
    }

    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(107, 33, 168, 0.15);
    }

    .service-icon {
        color: var(--purple-secondary);
        margin-bottom: 1rem;
    }

    .service-title {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        color: var(--purple-dark);
    }

    .about-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }

    .about-text {
        font-size: 1.1rem;
        color: #444;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .contact-card {
        text-align: center;
        padding: 2rem;
        transition: all 0.3s ease;
    }

    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(107, 33, 168, 0.15);
    }

    .contact-icon {
        color: var(--purple-secondary);
        margin-bottom: 1rem;
    }

    .footer {
        background: var(--purple-dark);
        color: white;
        padding: 4rem 2rem 2rem;
    }

    .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        padding: 0 1.5rem;
    }

    .footer-section h3 {
        margin-bottom: 1.5rem;
        font-size: 1.2rem;
    }

    .footer-links {
        list-style: none;
    }

    .footer-links li {
        margin-bottom: 0.8rem;
    }

    .footer-links a {
        color: #e2e8f0;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .footer-links a:hover {
        color: var(--purple-light);
    }

    .footer-bottom {
        text-align: center;
        padding-top: 2rem;
        margin-top: 2rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    @media (max-width: 768px) {
        .footer-content {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 480px) {
        .footer-content {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    }

    @media (max-width: 1280px) {
        .section-container {
            max-width: 100%;
        }
    }

    @keyframes fadeIn {
        from { 
            opacity: 0; 
            transform: translateY(20px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }

    @keyframes slideIn {
        from { 
            transform: translateX(-50px); 
            opacity: 0; 
        }
        to { 
            transform: translateX(0); 
            opacity: 1; 
        }
    }

    @keyframes scaleIn {
        from { 
            transform: scale(0.95); 
            opacity: 0; 
        }
        to { 
            transform: scale(1); 
            opacity: 1; 
        }
    }

    .animate-fade-in {
        animation: fadeIn 1s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        opacity: 0;
    }

    .animate-slide-in {
        animation: slideIn 1s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        opacity: 0;
    }

    .animate-scale-in {
        animation: scaleIn 1s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        opacity: 0;
    }

    .delay-1 { animation-delay: 0.2s; }
    .delay-2 { animation-delay: 0.4s; }
    .delay-3 { animation-delay: 0.6s; }

    .subsidiaries-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .subsidiary-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(107, 33, 168, 0.1);
        transition: all 0.3s ease;
    }

    .subsidiary-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(107, 33, 168, 0.15);
    }

    .subsidiary-image {
        width: 100%;
        height: clamp(150px, 30vw, 200px);
        object-fit: cover;
    }

    .subsidiary-content {
        padding: 1.5rem;
    }

    .subsidiary-title {
        font-size: 1.25rem;
        color: var(--purple-dark);
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .subsidiary-location {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .subsidiary-description {
        color: #444;
        line-height: 1.6;
    }

    /* Base responsive typography */
    html {
        font-size: 16px;
    }

    @media (max-width: 768px) {
        html {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        html {
            font-size: 12px;
        }
    }

    /* Responsive section spacing */
    @media (max-width: 768px) {
        .section {
            padding: 3rem 1rem;
        }

        .section-title {
            font-size: 2rem;
            margin-bottom: 2rem;
        }

        .section-subtitle {
            font-size: 1rem;
            margin-bottom: 2rem;
        }
    }

    /* Touch-friendly interactions */
    @media (hover: none) {
        .service-card:hover,
        .subsidiary-card:hover,
        .contact-card:hover {
            transform: none;
        }

        .nav-link,
        .footer-links a {
            padding: 0.5rem;
        }
    }

    /* Improved card responsiveness */
    .service-card,
    .subsidiary-card,
    .contact-card {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .subsidiary-content,
    .service-card > div {
        flex: 1;
    }

    /* Ensure images are responsive */
    img {
        max-width: 100%;
        height: auto;
    }

    /* Animation optimizations for mobile */
    @media (prefers-reduced-motion: reduce) {
        .animate-fade-in,
        .animate-slide-in,
        .animate-scale-in {
            animation: none;
            opacity: 1;
        }
    }

    /* Enhanced scroll animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translate3d(0, 40px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translate3d(50px, 0, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }

    @keyframes scaleInCenter {
        from {
            opacity: 0;
            transform: scale3d(0.8, 0.8, 0.8);
        }
        to {
            opacity: 1;
            transform: scale3d(1, 1, 1);
        }
    }

    .scroll-fade-up {
        opacity: 0;
        transform: translateY(40px);
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .scroll-slide-right {
        opacity: 0;
        transform: translateX(50px);
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .scroll-scale-in {
        opacity: 0;
        transform: scale(0.8);
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .scroll-animate {
        opacity: 1;
        transform: translate(0) scale(1);
    }

    .delay-100 { transition-delay: 100ms; }
    .delay-200 { transition-delay: 200ms; }
    .delay-300 { transition-delay: 300ms; }
    .delay-400 { transition-delay: 400ms; }

    .subsidiary-link {
        margin-top: 1rem;
    }

    .learn-more {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--purple-primary);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .learn-more:hover {
        gap: 0.75rem;
        color: var(--purple-secondary);
    }

    .learn-more svg {
        transition: transform 0.3s ease;
    }

    .learn-more:hover svg {
        transform: translateX(4px);
    }

    .see-all-container {
        text-align: center;
        margin-top: 3rem;
    }

    .see-all-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--purple-primary);
        text-decoration: none;
        font-weight: 600;
        font-size: 1.1rem;
        padding: 0.75rem 1.5rem;
        border: 2px solid var(--purple-primary);
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .see-all-link:hover {
        background: var(--purple-primary);
        color: white;
        gap: 0.75rem;
    }

    .see-all-link:hover svg {
        transform: translateX(4px);
    }

    .see-all-link svg {
        transition: transform 0.3s ease;
    }
</style>
</head>
<body class="loading">
    <div class="preloader">
        <div class="loader">
            <div class="loader-circle"></div>
            <div class="loader-circle"></div>
            <div class="loader-circle"></div>
            <div class="loader-text">MHRPCI</div>
        </div>
    </div>

    <nav class="nav" aria-label="Main navigation">
        <div class="nav-overlay"></div>
        <div class="nav-container">
            <a href="{{ route('welcome') }}" class="brand">
                <div class="brand-logo">
                    MHRPCI
                </div>
            </a>

            <button class="mobile-menu-btn" aria-label="Toggle menu">
                <span class="menu-icon"></span>
            </button>

            <div class="nav-content">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="{{ route('welcome') }}#about" class="nav-link">
                            About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('welcome') }}#services" class="nav-link">
                            Services
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('welcome') }}#subsidiaries" class="nav-link">
                            Subsidiaries
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('welcome') }}#contact" class="nav-link">
                            Contact
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('careers') }}" class="nav-link">
                            Careers
                        </a>
                    </li>
                </ul>
                @if(Auth::guard('google')->check())
                    <div class="google-login">
                        <img src="{{ Auth::guard('google')->user()->avatar }}" alt="{{ Auth::guard('google')->user()->name }}" style="width: 18px; height: 18px; border-radius: 50%;">
                        {{ Auth::guard('google')->user()->name }}
                    </div>
                @else
                    <a href="{{ route('google.login') }}" class="google-login">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">
                            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                            <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                            <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                            <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                        </svg>
                        Sign in with Google
                    </a>
                @endif
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>About MHRPCI</h3>
                <p>MHR Property Conglomerates, Inc. is a dynamic group of companies with expertise across multiple industries, committed to innovation and excellence.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('welcome') }}#about">About Us</a></li>
                    <li><a href="{{ route('all_subsidiaries') }}">Our Subsidiaries</a></li>
                    <li><a href="{{ route('careers') }}">Careers</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Our Companies</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('cio') }}">Cebic Industries</a></li>
                    <li><a href="{{ route('mhrhci') }}">MHRHCI</a></li>
                    <li><a href="{{ route('bgpdi') }}">BGPDI</a></li>
                    <li><a href="{{ route('vhi') }}">VHI</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Connect With Us</h3>
                <ul class="footer-links">
                    <li>
                        <a href="https://www.facebook.com/mhrpciofficial">
                            <i class="fab fa-facebook" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 6px;"></i>
                            Facebook
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 MHR Property Conglomerates, Inc. All rights reserved.</p>
        </div>
    </footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Preloader functionality
        const preloader = document.querySelector('.preloader');
        const body = document.body;

        // Ensure all content is loaded
        window.addEventListener('load', () => {
            setTimeout(() => {
                preloader.classList.add('fade-out');
                body.classList.remove('loading');
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 500);
            }, 500); // Show preloader for at least 500ms
        });

        // Initialize Lucide icons
        lucide.createIcons();
        
        const animatedElements = document.querySelectorAll('.animate-fade-in, .animate-slide-in, .animate-scale-in');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });

        animatedElements.forEach(element => {
            element.style.animationPlayState = 'paused';
            observer.observe(element);
        });

        // Navbar scroll effect
        const nav = document.querySelector('.nav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });

        // Mobile menu functionality
        const menuBtn = document.querySelector('.mobile-menu-btn');
        const navContent = document.querySelector('.nav-content');
        const overlay = document.querySelector('.nav-overlay');
        const navLinks = document.querySelectorAll('.nav-link');

        function toggleMenu() {
            menuBtn.classList.toggle('active');
            navContent.classList.toggle('active');
            overlay.classList.toggle('active');
            document.body.style.overflow = navContent.classList.contains('active') ? 'hidden' : '';
        }

        menuBtn.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);

        // Close menu when clicking nav links
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (navContent.classList.contains('active')) {
                    toggleMenu();
                }
            });
        });

        // Active link highlighting
        const sections = document.querySelectorAll('section');
        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (window.scrollY >= sectionTop - 100) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').slice(1) === current) {
                    link.classList.add('active');
                }
            });
        });

        // Enhanced scroll animations
        const scrollElements = document.querySelectorAll('.scroll-fade-up, .scroll-slide-right, .scroll-scale-in');
        
        const elementInView = (el, offset = 0) => {
            const elementTop = el.getBoundingClientRect().top;
            return (
                elementTop <= (window.innerHeight || document.documentElement.clientHeight) * (1 - offset)
            );
        };

        const displayScrollElement = (element) => {
            element.classList.add('scroll-animate');
        };

        const hideScrollElement = (element) => {
            element.classList.remove('scroll-animate');
        };

        const handleScrollAnimation = () => {
            scrollElements.forEach((el) => {
                if (elementInView(el, 0.25)) {
                    displayScrollElement(el);
                } else {
                    hideScrollElement(el);
                }
            });
        };

        // Initialize scroll animations
        handleScrollAnimation();

        // Throttle scroll events
        let throttleTimer;
        window.addEventListener('scroll', () => {
            if (throttleTimer) return;
            
            throttleTimer = setTimeout(() => {
                handleScrollAnimation();
                throttleTimer = null;
            }, 50);
        });

        // Add smooth scroll behavior for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
</script>
</body>
</html> 