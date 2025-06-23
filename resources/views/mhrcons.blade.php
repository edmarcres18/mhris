<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MHR Construction - Building Excellence</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        :root {
            --primary-color: #1a4d7c;
            --primary-dark: #153d63;
            --secondary-color: #e67e22;
            --secondary-light: #f39c12;
            --accent-color: #2ecc71;
            --text-color: #2c3e50;
            --text-light: #7f8c8d;
            --light-bg: #f8f9fa;
            --dark-bg: #2c3e50;
            --gray-bg: #f4f6f8;
            --white: #ffffff;
            --border-radius: 8px;
            --border-radius-lg: 12px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --box-shadow-hover: 0 10px 20px rgba(0, 0, 0, 0.15);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --container-width: 1400px;
            --section-spacing: 8rem;
        }

        body {
            line-height: 1.8;
            color: var(--text-color);
            scroll-behavior: smooth;
            background-color: var(--white);
        }

        .container {
            max-width: var(--container-width);
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Header Styles */
        .header {
            background-color: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 15px rgba(0,0,0,0.04);
            transition: var(--transition);
        }

        .header.scrolled {
            background-color: rgba(255, 255, 255, 0.98);
            box-shadow: var(--box-shadow);
        }

        .nav-container {
            max-width: var(--container-width);
            margin: 0 auto;
            padding: 1.2rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
            text-decoration: none;
            letter-spacing: -1px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo i {
            font-size: 1.8rem;
            color: var(--secondary-color);
        }

        .nav-links {
            display: flex;
            gap: 3.5rem;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-color);
            font-weight: 500;
            font-size: 1.1rem;
            transition: var(--transition);
            position: relative;
            padding: 0.5rem 0;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(to right, var(--secondary-color), var(--secondary-light));
            transition: var(--transition);
            border-radius: 2px;
        }

        .nav-links a:hover {
            color: var(--secondary-color);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
                        url('https://images.unsplash.com/photo-1541888946425-d81bb19240f5?ixlib=rb-4.0.3') center/cover fixed;
            display: flex;
            align-items: center;
            text-align: center;
            color: var(--white);
            padding: 0 1rem;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 200px;
            background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
        }

        .hero-content {
            max-width: 1000px;
            margin: 0 auto;
            z-index: 1;
            opacity: 0;
            animation: fadeInUp 1s ease forwards 0.5s;
        }

        .hero h1 {
            font-size: 4.5rem;
            margin-bottom: 1.5rem;
            font-weight: 700;
            line-height: 1.2;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            background: linear-gradient(45deg, var(--white), #f8f9fa);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hero p {
            font-size: 1.4rem;
            margin-bottom: 2.5rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            color: rgba(255, 255, 255, 0.9);
        }

        .cta-button {
            display: inline-block;
            padding: 1.2rem 3.5rem;
            background: linear-gradient(45deg, var(--secondary-color), var(--secondary-light));
            color: var(--white);
            text-decoration: none;
            border-radius: var(--border-radius);
            transition: var(--transition);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: var(--box-shadow);
            position: relative;
            overflow: hidden;
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: var(--transition);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: var(--box-shadow-hover);
        }

        .cta-button:hover::before {
            left: 100%;
        }

        /* Services Section */
        .services {
            padding: var(--section-spacing) 2rem;
            background-color: var(--light-bg);
            position: relative;
            overflow: hidden;
        }

        .services::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100%;
            background: linear-gradient(45deg, transparent 49.5%, var(--gray-bg) 49.5%, var(--gray-bg) 50.5%, transparent 50.5%);
            background-size: 20px 20px;
            opacity: 0.5;
        }

        .section-title {
            text-align: center;
            margin-bottom: 5rem;
            position: relative;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 50px;
            height: 3px;
            background: linear-gradient(to right, var(--secondary-color), var(--secondary-light));
            margin: 1rem auto 0;
            border-radius: 2px;
        }

        .section-title h2 {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .section-title p {
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
            font-size: 1.1rem;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 3rem;
            max-width: var(--container-width);
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .service-card {
            background: var(--white);
            padding: 3rem 2rem;
            border-radius: var(--border-radius-lg);
            text-align: center;
            transition: var(--transition);
            box-shadow: var(--box-shadow);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(to right, var(--secondary-color), var(--secondary-light));
            transform: scaleX(0);
            transform-origin: left;
            transition: var(--transition);
        }

        .service-card:hover::before {
            transform: scaleX(1);
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--box-shadow-hover);
        }

        .service-card i {
            font-size: 3rem;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 1.5rem;
            display: inline-block;
            transition: var(--transition);
        }

        .service-card:hover i {
            transform: rotateY(360deg);
        }

        .service-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--text-color);
            font-weight: 600;
        }

        .service-card p {
            color: var(--text-light);
            line-height: 1.8;
            font-size: 1.1rem;
        }

        /* About Section */
        .about {
            padding: var(--section-spacing) 2rem;
            position: relative;
            overflow: hidden;
            background: var(--white);
        }

        .about-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 4rem;
            align-items: center;
            max-width: var(--container-width);
            margin: 0 auto;
        }

        .about-content {
            padding: 2rem;
            position: relative;
        }

        .about-content h3 {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 700;
            line-height: 1.3;
        }

        .about-content p {
            color: var(--text-light);
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            line-height: 1.8;
        }

        .about-image {
            position: relative;
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--box-shadow);
        }

        .about-image img {
            width: 100%;
            height: auto;
            transition: var(--transition);
        }

        .about-image:hover img {
            transform: scale(1.05);
        }

        /* Contact Section */
        .contact {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--white);
            padding: var(--section-spacing) 2rem;
            position: relative;
            overflow: hidden;
        }

        .contact::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><rect width="1" height="1" fill="rgba(255,255,255,0.05)"/></svg>');
            background-size: 20px 20px;
        }

        .contact-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .contact-form {
            background: var(--white);
            padding: 3.5rem;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--box-shadow-hover);
            margin-top: 3rem;
            text-align: left;
        }

        .form-group {
            margin-bottom: 2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.8rem;
            color: var(--text-color);
            font-weight: 500;
            font-size: 1.1rem;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--gray-bg);
            border-radius: var(--border-radius);
            transition: var(--transition);
            font-size: 1rem;
            color: var(--text-color);
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(26, 77, 124, 0.1);
        }

        .submit-btn {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            color: var(--white);
            padding: 1.2rem 2rem;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            font-weight: 600;
            width: 100%;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .submit-btn:hover {
            background: linear-gradient(45deg, var(--secondary-color), var(--secondary-light));
            transform: translateY(-2px);
            box-shadow: var(--box-shadow);
        }

        /* Footer */
        .footer {
            background-color: var(--dark-bg);
            color: var(--white);
            padding: 6rem 2rem 4rem;
            position: relative;
            overflow: hidden;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(to right, var(--secondary-color), var(--secondary-light));
        }

        .footer-content {
            max-width: var(--container-width);
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 4rem;
        }

        .footer-section h4 {
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            color: var(--secondary-color);
            font-weight: 600;
            position: relative;
            padding-bottom: 1rem;
        }

        .footer-section h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 30px;
            height: 2px;
            background: var(--secondary-color);
        }

        .footer-section p {
            color: rgba(255,255,255,0.7);
            line-height: 1.8;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin-bottom: 1rem;
        }

        .footer-section ul li a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-section ul li a:hover {
            color: var(--secondary-color);
            transform: translateX(5px);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            transition: var(--transition);
        }

        .social-links a:hover {
            background: var(--secondary-color);
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            margin-top: 4rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.7);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .footer-content {
                grid-template-columns: repeat(2, 1fr);
                gap: 3rem;
            }
        }

        @media (max-width: 992px) {
            :root {
                --section-spacing: 6rem;
            }

            .hero h1 {
                font-size: 3.5rem;
            }

            .about-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--white);
                flex-direction: column;
                padding: 2rem;
                text-align: center;
                box-shadow: var(--box-shadow);
            }

            .nav-links.active {
                display: flex;
            }

            .mobile-menu-btn {
                display: block;
            }

            .hero h1 {
                font-size: 2.8rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }

            .contact-form {
                padding: 2rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }

        @media (max-width: 480px) {
            .hero h1 {
                font-size: 2.3rem;
            }

            .section-title h2 {
                font-size: 2.2rem;
            }
        }

        /* Mobile Menu */
        .mobile-menu-btn {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate {
            opacity: 0;
        }

        .animate.fade-in {
            animation: fadeInUp 1s ease forwards;
        }

        .animate.slide-left {
            animation: slideInLeft 1s ease forwards;
        }

        .animate.slide-right {
            animation: slideInRight 1s ease forwards;
        }

        .animate.scale-in {
            animation: scaleIn 1s ease forwards;
        }
        
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav-container">
            <a href="#" class="logo">MHRCONS</a>
            <div class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </div>
            <div class="nav-links">
                <a href="#home">Home</a>
                <a href="#services">Services</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Building the Future Through Excellence</h1>
            <p>Leading construction company specializing in commercial, residential, and industrial property development</p>
            <a href="#services" class="cta-button">Learn More</a>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="section-title animate">
            <h2>Our Services</h2>
        </div>
        <div class="services-grid">
            <div class="service-card animate">
                <i class="fas fa-building"></i>
                <h3>Commercial Construction</h3>
                <p>Development of office buildings, retail spaces, and commercial complexes</p>
            </div>
            <div class="service-card animate">
                <i class="fas fa-home"></i>
                <h3>Residential Projects</h3>
                <p>Construction of housing developments and residential complexes</p>
            </div>
            <div class="service-card animate">
                <i class="fas fa-industry"></i>
                <h3>Industrial Development</h3>
                <p>Building industrial facilities and manufacturing plants</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            <div class="section-title animate">
                <h2>About Us</h2>
                <p>Building Excellence, Delivering Results</p>
            </div>
            <div class="about-grid">
                <div class="about-content animate slide-left">
                    <h3>Leading the Way in Construction Excellence</h3>
                    <p>MHR Construction is a leading construction company specializing in commercial, residential, and industrial property development. As part of the MHR Properties Conglomerate, we bring decades of expertise to every project we undertake.</p>
                    <p>Our company has been instrumental in shaping the urban landscape of key cities across the Philippines through innovative construction solutions and sustainable development practices.</p>
                    <p>With our skilled workforce and commitment to quality, we continue to set new standards in the construction industry, delivering projects that stand the test of time.</p>
                </div>
                <div class="about-image animate slide-right">
                    <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?ixlib=rb-4.0.3" alt="Construction Site">
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="container">
            <div class="section-title animate">
                <h2 style="color: white;">Contact Us</h2>
                <p>Get in touch with us for your construction needs</p>
            </div>
            <div class="contact-content">
                <form class="contact-form animate scale-in">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email address" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number">
                    </div>
                    <div class="form-group">
                        <label for="message">Your Message</label>
                        <textarea id="message" name="message" rows="5" placeholder="Tell us about your project" required></textarea>
                    </div>
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>About Us</h4>
                <p>MHR Construction is a leading construction company specializing in commercial, residential, and industrial property development.</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Contact Info</h4>
                <ul>
                    <li><i class="fas fa-phone"></i> +63 123 456 7890</li>
                    <li><i class="fas fa-envelope"></i> info@mhrcons.com</li>
                    <li><i class="fas fa-map-marker-alt"></i> Manila, Philippines</li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Follow Us</h4>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 MHR Construction. All rights reserved.</p>
            <p>A subsidiary of MHR Properties Conglomerate, Inc.</p>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const navLinks = document.querySelector('.nav-links');

        mobileMenuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Header Scroll Effect
        window.addEventListener('scroll', () => {
            const header = document.querySelector('.header');
            if (window.scrollY > 100) {
                header.style.background = 'rgba(255, 255, 255, 0.95)';
            } else {
                header.style.background = 'white';
            }
        });

        // Scroll Animations
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (entry.target.classList.contains('service-card')) {
                        entry.target.classList.add('fade-in');
                        entry.target.style.animationDelay = `${entry.target.dataset.delay || 0}s`;
                    } else {
                        entry.target.classList.add(
                            entry.target.classList.contains('slide-left') ? 'slide-left' :
                            entry.target.classList.contains('slide-right') ? 'slide-right' :
                            entry.target.classList.contains('scale-in') ? 'scale-in' : 'fade-in'
                        );
                    }
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Add animation delays to service cards
        document.querySelectorAll('.service-card').forEach((card, index) => {
            card.dataset.delay = (index * 0.2).toString();
        });

        // Observe all animate elements
        document.querySelectorAll('.animate').forEach(element => {
            observer.observe(element);
        });
    </script>
</body>
</html>
