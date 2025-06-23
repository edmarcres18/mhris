<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAX - Max Hauling and Logistics</title>
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
            --primary-color: #1a4d80;
            --secondary-color: #2980b9;
            --accent-color: #f39c12;
            --text-color: #333;
            --light-bg: #f8f9fa;
        }

        body {
            line-height: 1.6;
            color: var(--text-color);
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        /* Header Styles */
        .header {
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-color);
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--secondary-color);
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.pexels.com/photos/2199293/pexels-photo-2199293.jpeg') center/cover;
            display: flex;
            align-items: center;
            text-align: center;
            color: white;
            padding: 0 1rem;
            position: relative;
            overflow: hidden;
            background-attachment: fixed;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(26, 77, 128, 0.7), rgba(41, 128, 185, 0.7));
            z-index: 1;
        }

        .hero-content {
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            animation: fadeInUp 1s ease;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 800;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero p {
            font-size: 1.4rem;
            margin-bottom: 2.5rem;
            animation: fadeInUp 1s ease 0.3s;
            opacity: 0;
            animation-fill-mode: forwards;
            line-height: 1.8;
        }

        .hero-btn {
            display: inline-block;
            padding: 1rem 2.5rem;
            background: var(--accent-color);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            animation: fadeInUp 1s ease 0.6s;
            opacity: 0;
            animation-fill-mode: forwards;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3);
        }

        .hero-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(243, 156, 18, 0.4);
        }

        /* Sections */
        section {
            padding: 5rem 1rem;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            color: var(--primary-color);
        }

        /* Services */
        .services {
            background-color: var(--light-bg);
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .service-card {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--accent-color);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-10px);
        }

        .service-card:hover::before {
            transform: scaleX(1);
        }

        .service-card i {
            font-size: 3rem;
            color: var(--accent-color);
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .service-card:hover i {
            transform: scale(1.1);
        }

        .service-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
            position: relative;
            z-index: 2;
        }

        .service-card p {
            color: #666;
            line-height: 1.8;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 2;
        }

        .service-image {
            margin-top: 1.5rem;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            height: 200px;
        }

        .service-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .service-card:hover .service-image img {
            transform: scale(1.1);
        }

        .service-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0,0,0,0), rgba(0,0,0,0.2));
        }

        /* About Section */
        .about {
            max-width: 1200px;
            margin: 0 auto;
            padding: 6rem 1rem;
        }

        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            position: relative;
        }

        .about-text {
            animation: fadeInLeft 1s ease;
            padding-right: 2rem;
        }

        .about-text p {
            margin-bottom: 1.5rem;
            color: #555;
            font-size: 1.1rem;
            line-height: 1.8;
        }

        .about-image {
            animation: fadeInRight 1s ease;
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .about-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(26, 77, 128, 0.2), rgba(41, 128, 185, 0.2));
            z-index: 1;
        }

        .about-image img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 15px;
            transition: transform 0.5s ease;
        }

        .about-image:hover img {
            transform: scale(1.05);
        }

        /* Stats Section */
        .stats {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            padding: 5rem 1rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .stats::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://images.pexels.com/photos/1427107/pexels-photo-1427107.jpeg') center/cover;
            opacity: 0.1;
            background-attachment: fixed;
        }

        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 3rem;
            position: relative;
            z-index: 1;
        }

        .stat-item {
            text-align: center;
            padding: 2.5rem;
            border-radius: 15px;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .stat-item:hover {
            transform: translateY(-5px);
            background: rgba(255,255,255,0.15);
            border-color: rgba(255,255,255,0.3);
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: var(--accent-color);
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .stat-text {
            font-size: 1.1rem;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 500;
        }

        /* Footer */
        footer {
            background-color: var(--primary-color);
            color: white;
            padding: 4rem 1rem;
            position: relative;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, var(--accent-color), var(--secondary-color));
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
        }

        .footer-section h3 {
            margin-bottom: 1.5rem;
            font-size: 1.4rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .footer-section h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--accent-color);
        }

        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            transition: all 0.3s ease;
            margin-right: 1rem;
        }

        .social-links a:hover {
            background: var(--accent-color);
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
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

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Mobile Menu */
        .mobile-menu-btn {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                padding: 1rem;
                flex-direction: column;
                text-align: center;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }

            .nav-links.active {
                display: flex;
            }

            .mobile-menu-btn {
                display: block;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .about-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav-container">
            <a href="#" class="logo">MAX</a>
            <div class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </div>
            <div class="nav-links">
                <a href="#home">Home</a>
                <a href="#about">Who We Are</a>
                <a href="#services">Services</a>
                <a href="#contact">Contact</a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Your Trusted Partner in Logistics Solutions</h1>
            <p>Specializing in hauling, quarrying, and comprehensive logistics services</p>
            <a href="#contact" class="hero-btn">Get in Touch</a>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="section-title">
            <h2>Who We Are</h2>
        </div>
        <div class="about-content">
            <div class="about-text">
                <p>MAX (Max Hauling and Logistics) emerged during the pandemic, transforming challenges into opportunities. As a dynamic logistics company, we specialize in providing comprehensive hauling, quarrying, and logistics services to meet the evolving needs of various industries.</p>
                <p>Since our establishment, we have grown into a reliable partner in the supply chain industry, serving both corporate clients and construction projects. Our commitment to efficiency and reliability has made us the preferred choice for businesses seeking dependable logistics solutions.</p>
                <a href="#contact" class="hero-btn" style="margin-top: 1rem;">Learn More</a>
            </div>
            <div class="about-image">
                <img src="https://images.pexels.com/photos/6169668/pexels-photo-6169668.jpeg" alt="Logistics Operations">
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-container">
            <div class="stat-item">
                <div class="stat-number">500+</div>
                <div class="stat-text">Projects Completed</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">50+</div>
                <div class="stat-text">Fleet Size</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">100+</div>
                <div class="stat-text">Happy Clients</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">24/7</div>
                <div class="stat-text">Support Available</div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="section-title">
            <h2>Our Services</h2>
        </div>
        <div class="services-grid">
            <div class="service-card">
                <i class="fas fa-truck-moving"></i>
                <h3>Hauling Services</h3>
                <p>Efficient transportation of heavy materials and equipment with our modern fleet of trucks</p>
                <div class="service-image">
                    <img src="https://images.pexels.com/photos/2199293/pexels-photo-2199293.jpeg" alt="Hauling Services">
                </div>
            </div>
            <div class="service-card">
                <i class="fas fa-mountain"></i>
                <h3>Quarrying</h3>
                <p>Professional extraction and transportation of quarry materials with state-of-the-art equipment</p>
                <div class="service-image">
                    <img src="https://images.pexels.com/photos/1009928/pexels-photo-1009928.jpeg" alt="Quarrying Services">
                </div>
            </div>
            <div class="service-card">
                <i class="fas fa-boxes"></i>
                <h3>Logistics Solutions</h3>
                <p>Comprehensive supply chain and logistics management for efficient material handling</p>
                <div class="service-image">
                    <img src="https://images.pexels.com/photos/1427107/pexels-photo-1427107.jpeg" alt="Logistics Solutions">
                </div>
            </div>
            <div class="service-card">
                <i class="fas fa-cogs"></i>
                <h3>Resource Management</h3>
                <p>Efficient handling and distribution of materials with advanced tracking systems</p>
                <div class="service-image">
                    <img src="https://images.pexels.com/photos/6169668/pexels-photo-6169668.jpeg" alt="Resource Management">
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p>Email: info@max-logistics.com</p>
                <p>Phone: (123) 456-7890</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <p><a href="#home" style="color: white;">Home</a></p>
                <p><a href="#about" style="color: white;">About Us</a></p>
                <p><a href="#services" style="color: white;">Services</a></p>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <div class="social-links">
                    <a href="#" style="color: white; margin-right: 1rem;"><i class="fab fa-facebook"></i></a>
                    <a href="#" style="color: white; margin-right: 1rem;"><i class="fab fa-twitter"></i></a>
                    <a href="#" style="color: white; margin-right: 1rem;"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 MAX (Max Hauling and Logistics). All rights reserved.</p>
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

        // Smooth Scrolling
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

        // Intersection Observer for Animation
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        // Observe all service cards
        document.querySelectorAll('.service-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            observer.observe(card);
        });
    </script>
</body>
</html>
