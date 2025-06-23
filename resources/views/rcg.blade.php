<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RCG Investment - Strategic Asset Management</title>
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary-color: #1a3b5c;
            --secondary-color: #2c5282;
            --accent-color: #f6ad55;
            --text-color: #2d3748;
            --light-bg: #f7fafc;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
        }

        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 1rem 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .mobile-menu-btn {
            display: none;
            font-size: 1.5rem;
            color: var(--primary-color);
            cursor: pointer;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            transition: all 0.3s ease;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-color);
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--accent-color);
        }

        .hero {
            height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
                        url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            text-align: center;
            color: white;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .hero-title {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            font-weight: 700;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .cta-button {
            display: inline-block;
            padding: 1rem 2rem;
            background-color: var(--accent-color);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: transform 0.3s ease;
        }

        .cta-button:hover {
            transform: translateY(-3px);
        }

        .section {
            padding: 6rem 2rem;
        }

        .about-section {
            background-color: var(--light-bg);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 3rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--accent-color);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        .feature-title {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .stats-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            text-align: center;
        }

        .stat-item h3 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .contact-section {
            background-color: white;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 4rem;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .contact-icon {
            font-size: 1.5rem;
            color: var(--accent-color);
        }

        footer {
            background-color: var(--primary-color);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }

            .nav-links {
                position: fixed;
                top: 70px;
                left: -100%;
                width: 100%;
                height: auto;
                flex-direction: column;
                background: white;
                padding: 2rem;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                text-align: center;
            }

            .nav-links.active {
                left: 0;
            }

            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
            }

            .contact-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .subsidiary-text {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 500;
        }

        .copyright {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .section {
                padding: 4rem 1rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Loading Animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2000;
        }

        /* Add these styles before the media queries */
        .loader {
            width: 48px;
            height: 48px;
            border: 5px solid var(--primary-color);
            border-bottom-color: transparent;
            border-radius: 50%;
            animation: rotation 1s linear infinite;
        }

        @keyframes rotation {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .nav-contact-btn {
            padding: 0.5rem 1.5rem;
            background-color: var(--accent-color);
            color: white !important;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .nav-contact-btn:hover {
            background-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .scroll-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--primary-color);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .scroll-to-top.visible {
            opacity: 1;
        }
    </style>
</head>
<body>
    <!-- Add Loading Overlay -->
    <div class="loading-overlay">
        <div class="loader"></div>
    </div>

    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo">RCG Investment</div>
            <div class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </div>
            <div class="nav-links">
                <a href="#home">Home</a>
                <a href="#about">About Us</a>
                <a href="#services">Our Services</a>
            </div>
        </div>
    </nav>

    <section id="home" class="hero">
        <div class="hero-content" data-aos="fade-up">
            <h1 class="hero-title">Strategic Asset Management Excellence</h1>
            <p class="hero-subtitle">Your trusted partner in building sustainable wealth through strategic investments</p>
            <a href="#contact" class="cta-button">Get Started</a>
        </div>
    </section>

    <section id="about" class="section about-section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">About RCG Investment</h2>
            <p data-aos="fade-up" style="text-align: center; max-width: 800px; margin: 0 auto; margin-bottom: 3rem;">
                RCG Investment is the dedicated investment arm of MHR Properties Conglomerate, Inc. (MHRPCI), specializing in strategic asset management and business development. We focus on identifying, acquiring, and managing high-potential investments across diverse industries.
            </p>
            <div class="features-grid">
                <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                    <i class="fas fa-chart-line feature-icon"></i>
                    <h3 class="feature-title">Strategic Investment</h3>
                    <p>Expert analysis and strategic planning for optimal investment outcomes</p>
                </div>
                <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-shield-alt feature-icon"></i>
                    <h3 class="feature-title">Risk Management</h3>
                    <p>Sophisticated risk assessment and mitigation strategies</p>
                </div>
                <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                    <i class="fas fa-handshake feature-icon"></i>
                    <h3 class="feature-title">Partnership Growth</h3>
                    <p>Building strong relationships for sustainable business development</p>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Services</h2>
            <div class="features-grid">
                <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                    <i class="fas fa-money-bill-trend-up feature-icon"></i>
                    <h3 class="feature-title">Asset Management</h3>
                    <p>Professional management of investment portfolios and assets</p>
                </div>
                <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-building feature-icon"></i>
                    <h3 class="feature-title">Real Estate Investment</h3>
                    <p>Strategic property acquisition and development</p>
                </div>
                <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                    <i class="fas fa-chart-pie feature-icon"></i>
                    <h3 class="feature-title">Portfolio Management</h3>
                    <p>Diversified investment strategies for optimal returns</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <p class="subsidiary-text">A subsidiary of MHR Properties Conglomerate, Inc.</p>
            <p class="copyright">&copy; {{ date('Y') }} RCG Investment. All rights reserved.</p>
        </div>
    </footer>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.padding = '0.5rem 0';
                navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.98)';
            } else {
                navbar.style.padding = '1rem 0';
                navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Loading Screen
        window.addEventListener('load', function() {
            const loader = document.querySelector('.loading-overlay');
            loader.style.display = 'none';
        });

        // Mobile Menu Toggle
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const navLinks = document.querySelector('.nav-links');

        mobileMenuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!navLinks.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                navLinks.classList.remove('active');
            }
        });

        // Counter Animation
        const counters = document.querySelectorAll('.counter');
        const speed = 200;

        const animateCounter = () => {
            counters.forEach(counter => {
                const target = +counter.dataset.target;
                const count = +counter.innerText;
                const increment = target / speed;

                if (count < target) {
                    counter.innerText = Math.ceil(count + increment);
                    setTimeout(animateCounter, 1);
                } else {
                    counter.innerText = target;
                }
            });
        }

        // Intersection Observer for counter animation
        const observerOptions = {
            threshold: 0.5
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter();
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelector('.stats-grid').forEach(stat => observer.observe(stat));

        // Scroll to Top Button
        const scrollBtn = document.querySelector('.scroll-to-top');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                scrollBtn.classList.add('visible');
            } else {
                scrollBtn.classList.remove('visible');
            }
        });

        scrollBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
</body>
</html>
