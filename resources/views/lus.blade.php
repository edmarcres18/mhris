<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luscious Co. - Premium Dining Experiences</title>
    <link rel="icon" href="/vendor/adminlte/dist/img/lus.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary-color: #B8860B;
            --secondary-color: #2C3E50;
            --accent-color: #D4AF37;
            --text-color: #333333;
            --light-color: #FFFFFF;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
        }

        /* Header Styles */
        .header {
            background-color: var(--light-color);
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 700;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--secondary-color);
            font-weight: 500;
            transition: var(--transition);
        }

        .nav-links a:hover {
            color: var(--primary-color);
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3') center/cover;
            display: flex;
            align-items: center;
            text-align: center;
            color: var(--light-color);
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            margin-bottom: 1rem;
            animation: fadeInDown 1s ease;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease;
        }

        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            background-color: var(--primary-color);
            color: var(--light-color);
            text-decoration: none;
            border-radius: 5px;
            transition: var(--transition);
            font-weight: 500;
        }

        .btn:hover {
            background-color: var(--accent-color);
            transform: translateY(-2px);
        }

        /* About Section */
        .section {
            padding: 5rem 0;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 3rem;
            color: var(--secondary-color);
        }

        .about-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .about-card {
            text-align: center;
            padding: 2rem;
            background-color: #f9f9f9;
            border-radius: 10px;
            transition: var(--transition);
        }

        .about-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .about-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        /* Services Section */
        .services {
            background-color: #f5f5f5;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .service-card {
            background-color: var(--light-color);
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            transition: var(--transition);
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .service-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        /* Footer */
        .footer {
            background-color: var(--secondary-color);
            color: var(--light-color);
            padding: 3rem 0;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }

        .footer-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .footer-links h4 {
            margin-bottom: 1rem;
        }

        .footer-links a {
            color: var(--light-color);
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: var(--primary-color);
        }

        .copyright {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

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

        /* Mobile Menu */
        .mobile-menu-btn {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }

            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background-color: var(--light-color);
                padding: 1rem;
                flex-direction: column;
                text-align: center;
            }

            .nav-links.active {
                display: flex;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .section {
                padding: 3rem 0;
            }
        }

        /* About Section Styles */
        .about {
            background-color: #fff;
        }

        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .about-text h3 {
            font-family: 'Playfair Display', serif;
            color: var(--secondary-color);
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .about-text p {
            margin-bottom: 2rem;
            color: var(--text-color);
        }

        .about-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }

        .feature {
            text-align: center;
            padding: 1.5rem;
            background-color: #f9f9f9;
            border-radius: 10px;
            transition: var(--transition);
        }

        .feature:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .feature-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .feature h4 {
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        /* Responsive Design Updates */
        @media (max-width: 768px) {
            .about-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .about-features {
                grid-template-columns: 1fr;
            }

            .feature {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="nav">
                <a href="#" class="logo">LUS</a>
                <div class="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </div>
                <div class="nav-links">
                    <a href="#home">Home</a>
                    <a href="#who-we-are">Who We Are</a>
                    <a href="#about">About</a>
                    <a href="#services">Services</a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Welcome to Luscious Co.</h1>
                <p class="hero-subtitle">Elevating dining experiences with premium cuisine and exceptional service</p>
                <a href="#services" class="btn">Explore Our Services</a>
            </div>
        </div>
    </section>

    <!-- Who We Are Section -->
    <section class="section" id="who-we-are">
        <div class="container">
            <h2 class="section-title">Who We Are</h2>
            <div class="about-grid">
                <div class="about-card">
                    <i class="fas fa-utensils about-icon"></i>
                    <h3>Premium Dining</h3>
                    <p>Distinguished name in food and hospitality, creating extraordinary dining experiences in Cebu. Our commitment to excellence has made us a leader in the culinary scene.</p>
                </div>
                <div class="about-card">
                    <i class="fas fa-star about-icon"></i>
                    <h3>Excellence</h3>
                    <p>Committed to maintaining the highest standards of culinary excellence and customer service. Every dish is crafted with precision and passion.</p>
                </div>
                <div class="about-card">
                    <i class="fas fa-users about-icon"></i>
                    <h3>Expert Team</h3>
                    <p>Our team of skilled chefs and dedicated service staff work tirelessly to ensure each dining experience exceeds expectations.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section about" id="about">
        <div class="container">
            <h2 class="section-title">About Us</h2>
            <div class="about-content">
                <div class="about-text">
                    <h3>Our Story</h3>
                    <p>Since our establishment, Luscious Co. has grown from a small dining venue to a comprehensive hospitality provider. Our journey has been marked by a relentless pursuit of culinary excellence and exceptional service.</p>
                    
                    <h3>Our Mission</h3>
                    <p>To create extraordinary dining experiences that delight the senses and exceed expectations, while maintaining the highest standards of quality and service.</p>
                    
                    <h3>Our Vision</h3>
                    <p>To be the leading culinary destination in Cebu, known for innovative cuisine, exceptional service, and memorable dining experiences.</p>
                </div>
                <div class="about-features">
                    <div class="feature">
                        <i class="fas fa-award feature-icon"></i>
                        <h4>Quality Assurance</h4>
                        <p>Only the finest ingredients and highest standards</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-heart feature-icon"></i>
                        <h4>Passion for Food</h4>
                        <p>Every dish crafted with love and creativity</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-handshake feature-icon"></i>
                        <h4>Customer Focus</h4>
                        <p>Dedicated to exceeding your expectations</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section services" id="services">
        <div class="container">
            <h2 class="section-title">Our Services</h2>
            <div class="services-grid">
                <div class="service-card">
                    <i class="fas fa-wine-glass-alt service-icon"></i>
                    <h3>Fine Dining</h3>
                    <p>Experience culinary excellence in our elegant restaurant setting. Featuring innovative menus, premium ingredients, and expert wine pairings.</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-concierge-bell service-icon"></i>
                    <h3>Event Catering</h3>
                    <p>Premium catering services for weddings, celebrations, and special occasions. Customized menus and professional service staff.</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-briefcase service-icon"></i>
                    <h3>Corporate Services</h3>
                    <p>Specialized catering for business meetings, conferences, and corporate events. Professional service tailored to your needs.</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-glass-cheers service-icon"></i>
                    <h3>Private Events</h3>
                    <p>Bespoke dining experiences for intimate gatherings and private celebrations. Personalized menus and dedicated service.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-info">
                    <div class="footer-logo">Luscious Co.</div>
                    <p>A subsidiary of MHR Properties Conglomerate, Inc.</p>
                </div>
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <a href="#home">Home</a>
                    <a href="#who-we-are">Who We Are</a>
                    <a href="#about">About</a>
                    <a href="#services">Services</a>
                </div>
                <div class="footer-links">
                    <h4>Services</h4>
                    <a href="#services">Fine Dining</a>
                    <a href="#services">Event Catering</a>
                    <a href="#services">Corporate Services</a>
                    <a href="#services">Private Events</a>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2025 Luscious Co. All rights reserved.</p>
            </div>
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
                header.style.background = 'var(--light-color)';
            }
        });

        // Reveal Animation on Scroll
        const revealElements = document.querySelectorAll('.about-card, .service-card');

        const reveal = () => {
            revealElements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;

                if (elementTop < windowHeight - 100) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        };

        window.addEventListener('scroll', reveal);
        window.addEventListener('load', reveal);
    </script>
</body>
</html>
