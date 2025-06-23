<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verbena Hotel Inc - Comfortable Stay in Cebu City</title>
    <link rel="icon" href="/vendor/adminlte/dist/img/vhi.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2E7D32;  /* Dark Green */
            --secondary-color: #4CAF50;  /* Medium Green */
            --accent-color: #FFD700;  /* Yellow */
            --text-light: #FFFFFF;
            --text-dark: #333333;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            overflow-x: hidden;
        }

        .navbar {
            background-color: transparent;
            transition: all 0.5s ease;
            padding: 1.5rem 0;
        }

        .navbar.scrolled {
            background-color: var(--primary-color);
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            color: white !important;
            font-weight: bold;
        }

        .nav-link {
            color: white !important;
            position: relative;
            padding: 0.5rem 1rem !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background-color: var(--accent-color);
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .btn-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color) !important;
            border-color: var(--secondary-color) !important;
        }

        .btn-accent {
            background-color: var(--accent-color) !important;
            border-color: var(--accent-color) !important;
            color: var(--text-dark) !important;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
        }

        .btn-accent:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
        }

        .amenity-card i {
            color: var(--primary-color) !important;
        }

        .filter-button {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .filter-button.active {
            background-color: var(--primary-color);
            color: var(--text-light);
        }

        .footer {
            background-color: var(--primary-color);
            color: white;
            padding: 4rem 0 2rem;
        }

        .hero-section {
            position: relative;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1800&auto=format') !important;
            background-size: cover !important;
            background-position: center !important;
            background-attachment: fixed !important;
            height: 100vh;
            display: flex;
            align-items: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(46, 125, 50, 0.3), rgba(255, 215, 0, 0.3));
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .hero-title {
            font-size: 4.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            animation: fadeInDown 1s ease;
        }

        .hero-subtitle {
            font-size: 1.8rem;
            margin-bottom: 2.5rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
            animation: fadeInUp 1s ease 0.3s;
            opacity: 0;
            animation-fill-mode: forwards;
        }

        .hero-btn {
            animation: fadeInUp 1s ease 0.6s;
            opacity: 0;
            animation-fill-mode: forwards;
        }

        .hero-scroll-indicator {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
            color: white;
            text-align: center;
            animation: bounce 2s infinite;
        }

        .hero-scroll-indicator i {
            font-size: 2rem;
            cursor: pointer;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            60% {
                transform: translateY(-10px);
            }
        }

        .section-title {
            color: var(--primary-color);
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 1rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background-color: var(--accent-color);
        }

        .amenity-card {
            border: none;
            border-radius: 15px;
            transition: all 0.4s ease;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .amenity-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.1);
        }

        .amenity-icon {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            color: var(--accent-color);
            transition: all 0.3s ease;
        }

        .amenity-card:hover .amenity-icon {
            transform: scale(1.1);
        }

        .room-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
        }

        .room-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .room-card img {
            transition: all 0.5s ease;
        }

        .room-card:hover img {
            transform: scale(1.1);
        }

        .room-features {
            padding-left: 0;
        }

        .room-features li {
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .room-features i {
            color: var(--primary-color);
            margin-right: 0.5rem;
        }

        .location-map {
            width: 100%;
            height: 300px;
            border: none;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .contact-info-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .contact-info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .contact-icon {
            color: var(--accent-color);
            font-size: 2rem;
            margin-right: 1rem;
            transition: all 0.3s ease;
        }

        .contact-info-card:hover .contact-icon {
            transform: scale(1.1);
        }

        .form-control {
            border-radius: 10px;
            padding: 0.8rem 1.2rem;
            border: 2px solid #eee;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
        }

        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            border-radius: 50%;
            background-color: rgba(255,255,255,0.1);
            margin-right: 1rem;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background-color: var(--accent-color);
            color: var(--text-dark) !important;
            transform: translateY(-3px);
        }

        .scroll-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background-color: var(--accent-color);
            color: var(--text-dark);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .scroll-to-top.active {
            opacity: 1;
            visibility: visible;
        }

        .scroll-to-top:hover {
            transform: translateY(-5px);
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            .hero-subtitle {
                font-size: 1.2rem;
            }
        }

        .service-card {
            transition: all 0.3s ease;
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .room-price {
            top: 20px;
            right: 20px;
        }

        .badge {
            font-size: 1rem;
            font-weight: 500;
            padding: 8px 16px !important;
        }

        .room-features {
            font-size: 0.9rem;
        }

        .amenity-card .list-unstyled li {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .lead {
            color: #666;
            font-size: 1.1rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .modal-dialog-centered {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
        }

        .room-detail-modal .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .room-detail-modal .modal-header {
            border-bottom: 2px solid var(--accent-color);
            padding: 1.5rem;
        }

        .room-detail-modal .modal-body {
            padding: 2rem;
        }

        .room-detail-modal .close {
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .room-gallery {
            position: relative;
            margin-bottom: 2rem;
        }

        .room-gallery img {
            border-radius: 10px;
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .room-amenities-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .room-amenity-item {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .room-amenity-item i {
            color: var(--primary-color);
            margin-right: 0.5rem;
        }

        .inquiry-btn {
            background: var(--primary-color);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 30px;
            transition: all 0.3s ease;
            border: none;
            font-weight: 600;
        }

        .inquiry-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        .view-details-btn {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            padding: 0.8rem 2rem;
            border-radius: 30px;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .view-details-btn:hover {
            background: var(--primary-color);
            color: white;
        }

        .room-price-tag {
            background: var(--accent-color);
            color: var(--text-dark);
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .feature-category {
            margin-bottom: 1.5rem;
        }

        .feature-category h6 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .availability-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(46, 125, 50, 0.9);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .image-gallery {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 20px;
        }

        .gallery-img {
            width: 100%;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .gallery-img:hover {
            transform: scale(1.05);
        }

        .gallery-img.active {
            border: 2px solid var(--accent-color);
        }

        .main-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        .room-status {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 215, 0, 0.9);
            color: var(--text-dark);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            z-index: 1;
        }

        .amenity-icon-large {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .feature-list {
            columns: 2;
            column-gap: 2rem;
            margin-top: 1rem;
        }

        .feature-item {
            break-inside: avoid;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .booking-summary {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 2rem;
        }

        .price-breakdown {
            border-top: 1px solid #dee2e6;
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 0;
            margin: 1rem 0;
        }

        .virtual-tour-btn {
            background: var(--accent-color);
            color: var(--text-dark);
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 30px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .virtual-tour-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
        }

        .filter-section {
            background-color: #f8f9fa;
            padding: 2rem 0;
            border-radius: 10px;
            margin-top: -1rem;
        }

        .filter-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.8rem;
            transition: all 0.3s ease;
        }

        .filter-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25);
        }

        .filter-tag {
            background-color: var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .filter-tag .remove-filter {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-tag .remove-filter:hover {
            transform: scale(1.2);
        }

        .results-count {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
        }

        .no-results {
            text-align: center;
            padding: 3rem;
            background-color: #f8f9fa;
            border-radius: 10px;
            margin-top: 2rem;
        }

        .no-results i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .contact-section {
            background: linear-gradient(rgba(46, 125, 50, 0.05), rgba(46, 125, 50, 0.1));
            border-radius: 20px;
            padding: 4rem 0;
            margin: 2rem 0;
        }

        .contact-info-card {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .contact-info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: var(--primary-color);
            border-radius: 5px 0 0 5px;
        }

        .contact-info-block {
            display: flex;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding: 1rem;
            background: rgba(46, 125, 50, 0.03);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .contact-info-block:hover {
            background: rgba(46, 125, 50, 0.08);
            transform: translateX(5px);
        }

        .contact-icon-wrapper {
            background: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .contact-icon {
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .contact-form {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-floating > label {
            color: #666;
        }

        .form-control {
            border: 2px solid #eee;
            padding: 1rem;
            height: auto;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.15);
        }

        .location-map {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-top: 2rem;
            height: 400px;
        }

        .submit-btn {
            background: var(--primary-color);
            color: white;
            padding: 1rem 2rem;
            border-radius: 30px;
            border: none;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 125, 50, 0.2);
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Scroll to Top Button -->
    <div class="scroll-to-top">
        <i class="fas fa-arrow-up"></i>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="/vendor/adminlte/dist/img/vhi.png" alt="Verbena Hotel Logo" class="me-2" style="height: 30px; width: auto;">
                Verbena Hotel Inc
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#amenities">Amenities</a></li>
                    <li class="nav-item"><a class="nav-link" href="#rooms">Rooms</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container hero-content">
            <h1 class="hero-title">Welcome to Verbena Hotel Inc</h1>
            <p class="hero-subtitle">Experience luxury and comfort in the heart of Cebu City</p>
            <div class="hero-btn">
                <a href="#rooms" class="btn btn-lg btn-accent me-3">
                    <i class="fas fa-hotel me-2"></i>Explore Rooms
                </a>
                <a href="#contact" class="btn btn-lg btn-outline-light">
                    <i class="fas fa-phone me-2"></i>Contact Us
                </a>
            </div>
        </div>
        <div class="hero-scroll-indicator">
            <p class="mb-2">Scroll to discover</p>
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    <!-- Amenities Section -->
    <section id="amenities" class="py-5">
        <div class="container">
            <h2 class="section-title text-center">Our Amenities</h2>
            <p class="text-center mb-5 lead">Experience luxury and comfort with our carefully curated amenities</p>
            <div class="row">
                <div class="col-md-4">
                    <div class="card amenity-card" data-aos="fade-up">
                        <div class="card-body text-center">
                            <i class="fas fa-wifi amenity-icon"></i>
                            <h4>High-Speed Internet</h4>
                            <p>Complimentary high-speed Wi-Fi throughout the hotel premises</p>
                            <ul class="list-unstyled mt-3">
                                <li><i class="fas fa-check-circle text-success me-2"></i>24/7 Access</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Secure Network</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Video Streaming Quality</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card amenity-card" data-aos="fade-up" data-aos-delay="100">
                        <div class="card-body text-center">
                            <i class="fas fa-swimming-pool amenity-icon"></i>
                            <h4>Infinity Pool</h4>
                            <p>Refreshing pool with stunning city views</p>
                            <ul class="list-unstyled mt-3">
                                <li><i class="fas fa-check-circle text-success me-2"></i>Temperature Controlled</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Poolside Service</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Towel Service</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card amenity-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-body text-center">
                            <i class="fas fa-utensils amenity-icon"></i>
                            <h4>Fine Dining</h4>
                            <p>24/7 restaurant with international cuisine</p>
                            <ul class="list-unstyled mt-3">
                                <li><i class="fas fa-check-circle text-success me-2"></i>Local & International Menu</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Room Service</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Special Dietary Options</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card amenity-card" data-aos="fade-up" data-aos-delay="300">
                        <div class="card-body text-center">
                            <i class="fas fa-spa amenity-icon"></i>
                            <h4>Wellness Center</h4>
                            <p>Rejuvenate your body and mind</p>
                            <ul class="list-unstyled mt-3">
                                <li><i class="fas fa-check-circle text-success me-2"></i>Spa Services</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Fitness Center</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Yoga Classes</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card amenity-card" data-aos="fade-up" data-aos-delay="400">
                        <div class="card-body text-center">
                            <i class="fas fa-car amenity-icon"></i>
                            <h4>Transportation</h4>
                            <p>Convenient travel services</p>
                            <ul class="list-unstyled mt-3">
                                <li><i class="fas fa-check-circle text-success me-2"></i>Airport Transfers</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Car Rental</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>City Tours</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card amenity-card" data-aos="fade-up" data-aos-delay="500">
                        <div class="card-body text-center">
                            <i class="fas fa-concierge-bell amenity-icon"></i>
                            <h4>Concierge Service</h4>
                            <p>24/7 personalized assistance</p>
                            <ul class="list-unstyled mt-3">
                                <li><i class="fas fa-check-circle text-success me-2"></i>Tour Arrangements</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Restaurant Reservations</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Special Requests</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Replace the existing filter section with this simplified version -->
    <div class="filter-section mb-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Room Type</label>
                    <select class="form-select filter-select" id="roomTypeFilter">
                        <option value="all">All Types</option>
                        <option value="standard">Standard</option>
                        <option value="deluxe">Deluxe</option>
                        <option value="suite">Suite</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Capacity</label>
                    <select class="form-select filter-select" id="capacityFilter">
                        <option value="all">Any Capacity</option>
                        <option value="1-2">1-2 Persons</option>
                        <option value="3-4">3-4 Persons</option>
                        <option value="5+">5+ Persons</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="filter-tags d-flex flex-wrap gap-2" id="activeFilters">
                        <!-- Active filters will be displayed here -->
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <p class="results-count" id="resultsCount">Showing all rooms</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Rooms Section -->
    <section id="rooms" class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title text-center">Luxurious Accommodations</h2>
            <p class="text-center mb-5 lead">Choose from our carefully designed rooms for your perfect stay</p>
            
            <div class="row" id="roomsContainer">
                <!-- Room cards will be dynamically populated -->
            </div>
        </div>
    </section>

    <!-- Additional Sections -->
    <section id="services" class="py-5">
        <div class="container">
            <h2 class="section-title text-center">Special Services</h2>
            <p class="text-center mb-5 lead">Enhancing your stay with premium services</p>
            <div class="row">
                <div class="col-md-6 mb-4" data-aos="fade-right">
                    <div class="card h-100 service-card">
                        <div class="card-body">
                            <h4 class="mb-3"><i class="fas fa-calendar-check text-primary me-2"></i>Event Facilities</h4>
                            <p>Perfect for your special occasions:</p>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check-circle text-success me-2"></i>Conference Rooms</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Wedding Venues</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Banquet Halls</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Corporate Meeting Spaces</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4" data-aos="fade-left">
                    <div class="card h-100 service-card">
                        <div class="card-body">
                            <h4 class="mb-3"><i class="fas fa-user-tie text-primary me-2"></i>Business Center</h4>
                            <p>Complete business support:</p>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check-circle text-success me-2"></i>High-Speed Internet</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Printing & Scanning</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Video Conferencing</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Secretarial Services</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Replace the existing contact section with this enhanced version -->
    <section id="contact" class="contact-section">
        <div class="container">
            <h2 class="section-title text-center">Get in Touch</h2>
            <p class="text-center lead mb-5">We're here to help and answer any questions you might have</p>
            
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="contact-info-card">
                        <div class="contact-info-block">
                            <div class="contact-icon-wrapper">
                                <i class="fas fa-map-marker-alt contact-icon"></i>
                            </div>
                            <div>
                                <h5 class="mb-2">Location</h5>
                                <p class="mb-0">584 Don Gil Garcia St, Cebu City, 6000 Cebu</p>
                                <p class="mb-0 text-muted">Near Cebu Provincial Capitol</p>
                                <p class="mb-0 text-muted">Across from Cebu Doctor's Hospital</p>
                            </div>
                        </div>

                        <div class="contact-info-block">
                            <div class="contact-icon-wrapper">
                                <i class="fas fa-phone-alt contact-icon"></i>
                            </div>
                            <div>
                                <h5 class="mb-2">Phone</h5>
                                <p class="mb-0">Main: (032) 253 3430</p>
                                <p class="mb-0">Reservations: (032) 253 3431</p>
                            </div>
                        </div>

                        <div class="contact-info-block">
                            <div class="contact-icon-wrapper">
                                <i class="fas fa-envelope contact-icon"></i>
                            </div>
                            <div>
                                <h5 class="mb-2">Email</h5>
                                <p class="mb-0">info@verbenahotel.com</p>
                                <p class="mb-0">reservations@verbenahotel.com</p>
                            </div>
                        </div>

                        <div class="contact-info-block">
                            <div class="contact-icon-wrapper">
                                <i class="fas fa-clock contact-icon"></i>
                            </div>
                            <div>
                                <h5 class="mb-2">Business Hours</h5>
                                <p class="mb-0">Check-in: 2:00 PM</p>
                                <p class="mb-0">Check-out: 12:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="contact-form">
                        <h4 class="mb-4">Send us a Message</h4>
                        <form>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="nameInput" placeholder="Your Name">
                                <label for="nameInput">Your Name</label>
                            </div>
                            <div class="form-floating">
                                <input type="email" class="form-control" id="emailInput" placeholder="Your Email">
                                <label for="emailInput">Your Email</label>
                            </div>
                            <div class="form-floating">
                                <input type="tel" class="form-control" id="phoneInput" placeholder="Your Phone">
                                <label for="phoneInput">Your Phone</label>
                            </div>
                            <div class="form-floating">
                                <textarea class="form-control" id="messageInput" placeholder="Your Message" style="height: 150px"></textarea>
                                <label for="messageInput">Your Message</label>
                            </div>
                            <button type="submit" class="submit-btn mt-3">
                                <i class="fas fa-paper-plane me-2"></i>Send Message
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-12">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d965.9989017431366!2d123.89082012923416!3d10.312294000000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a9994eaa67ed35%3A0x751eec2e949851e5!2sVerbena%20Hotel!5e0!3m2!1sen!2sph!4v1708007374753!5m2!1sen!2sph"
                        class="location-map w-100"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h4>Verbena Hotel Inc</h4>
                    <p>A subsidiary of MHR Properties Conglomerate, Inc.</p>
                </div>
                <div class="col-md-4">
                    <h4>Quick Links</h4>
                    <ul class="list-unstyled">
                        <li><a href="#home" class="text-white">Home</a></li>
                        <li><a href="#amenities" class="text-white">Amenities</a></li>
                        <li><a href="#rooms" class="text-white">Rooms</a></li>
                        <li><a href="#contact" class="text-white">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h4>Connect With Us</h4>
                    <div class="social-links">
                        <a href="#" class="text-white me-2"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <p>&copy; 2025 Verbena Hotel Inc. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init();

        // Loading Screen
        window.addEventListener('load', function() {
            document.querySelector('.loading-overlay').style.opacity = '0';
            setTimeout(() => {
                document.querySelector('.loading-overlay').style.display = 'none';
            }, 500);
        });

        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Scroll to Top Button
        window.addEventListener('scroll', function() {
            const scrollBtn = document.querySelector('.scroll-to-top');
            if (window.scrollY > 300) {
                scrollBtn.classList.add('active');
            } else {
                scrollBtn.classList.remove('active');
            }
        });

        document.querySelector('.scroll-to-top').addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Room data
        const rooms = [
            {
                type: 'standard',
                name: 'Deluxe Standard Room',
                price: '₱2,500',
                description: 'Comfortable 30 sqm room with modern amenities and city views. Perfect for business travelers and couples seeking comfort and convenience.',
                mainImage: 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800&auto=format',
                images: [
                    'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800&auto=format',
                    'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800&auto=format',
                    'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?w=800&auto=format',
                    'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&auto=format'
                ],
                features: [
                    'Queen-Size Bed',
                    '40-inch Smart TV',
                    'Rain Shower',
                    'Mini Refrigerator',
                    'Work Desk',
                    'Free Wi-Fi',
                    'Coffee Maker',
                    'Safety Deposit Box'
                ],
                amenities: [
                    'Daily Housekeeping',
                    'Room Service',
                    'Laundry Service',
                    'Wake-up Call'
                ],
                status: 'Available Now',
                maxOccupancy: 2,
                bedType: 'Queen Size',
                viewType: 'City View',
                roomSize: '30 sqm'
            },
            {
                type: 'deluxe',
                name: 'Premium Deluxe Room',
                price: '₱3,500',
                description: 'Spacious 40 sqm room featuring premium amenities and breathtaking city views. Ideal for those seeking extra comfort and luxury.',
                mainImage: 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=800&auto=format',
                images: [
                    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=800&auto=format',
                    'https://images.unsplash.com/photo-1595576508898-0ad5c879a061?w=800&auto=format',
                    'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800&auto=format',
                    'https://images.unsplash.com/photo-1587985064135-0366536eab42?w=800&auto=format'
                ],
                features: [
                    'King-Size Bed',
                    '55-inch Smart TV',
                    'Bathtub & Rain Shower',
                    'Mini Bar',
                    'Lounge Area',
                    'High-Speed Wi-Fi',
                    'Nespresso Machine',
                    'Executive Work Space'
                ],
                amenities: [
                    'Premium Toiletries',
                    '24/7 Room Service',
                    'Evening Turndown',
                    'Priority Housekeeping'
                ],
                status: 'Available Now',
                maxOccupancy: 3,
                bedType: 'King Size',
                viewType: 'Premium City View',
                roomSize: '40 sqm'
            },
            {
                type: 'suite',
                name: 'Executive Suite',
                price: '₱5,000',
                description: 'Luxurious 60 sqm suite with separate living area and panoramic ocean views. The ultimate choice for a premium hotel experience.',
                mainImage: 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800&auto=format',
                images: [
                    'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800&auto=format',
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=800&auto=format',
                    'https://images.unsplash.com/photo-1631049552057-403cdb8f0658?w=800&auto=format',
                    'https://images.unsplash.com/photo-1631049421450-348ccd7f8949?w=800&auto=format'
                ],
                features: [
                    'Master Bedroom',
                    'Separate Living Room',
                    'Dining Area',
                    'Kitchenette',
                    'Premium Entertainment System',
                    'Jacuzzi Bathtub',
                    'Walk-in Closet',
                    'Butler Service'
                ],
                amenities: [
                    'Luxury Toiletries',
                    'Private Chef Available',
                    'Butler Service',
                    'VIP Concierge'
                ],
                status: 'Limited Availability',
                maxOccupancy: 4,
                bedType: 'King Size + Sofa Bed',
                viewType: 'Ocean View',
                roomSize: '60 sqm'
            }
        ];

        // Function to create room cards
        function createRoomCard(room) {
            return `
                <div class="col-md-4 room-item ${room.type}" data-aos="fade-up">
                    <div class="card room-card h-100">
                        <div class="position-relative">
                            <img src="${room.mainImage}" class="card-img-top" alt="${room.name}">
                            <div class="room-price position-absolute">
                                <span class="badge bg-primary p-2">${room.price}/night</span>
                            </div>
                            <div class="room-status">
                                ${room.status}
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">${room.name}</h5>
                            <p class="card-text">${room.description}</p>
                            <div class="room-features mt-3">
                                <h6 class="mb-3">Highlights:</h6>
                                <div class="row">
                                    ${room.features.slice(0, 4).map(feature => `
                                        <div class="col-md-6 mb-2">
                                            <i class="fas fa-check text-success me-2"></i>${feature}
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 text-center p-3">
                            <button class="btn view-details-btn mb-2 w-100" onclick="showRoomDetails(${JSON.stringify(room).replace(/"/g, '&quot;')})">
                                View Details
                            </button>
                            <button class="btn inquiry-btn w-100" onclick="showInquiryForm('${room.name}')">
                                Make an Inquiry
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        // Initialize room display
        function displayRooms(filter = 'all') {
            const container = document.getElementById('roomsContainer');
            container.innerHTML = rooms
                .filter(room => filter === 'all' || room.type === filter)
                .map(room => createRoomCard(room))
                .join('');
        }

        // Filter functionality
        document.querySelectorAll('.filter-button').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.filter-button').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                displayRooms(this.getAttribute('data-filter'));
            });
        });

        // Enhanced Smooth Scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                const headerOffset = 80;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            });
        });

        // Add animation to amenity cards
        document.querySelectorAll('.amenity-card').forEach((card, index) => {
            card.setAttribute('data-aos', 'fade-up');
            card.setAttribute('data-aos-delay', (index * 100).toString());
        });

        // Add animation to room cards
        document.querySelectorAll('.room-card').forEach((card, index) => {
            card.setAttribute('data-aos', 'fade-up');
            card.setAttribute('data-aos-delay', (index * 100).toString());
        });

        function showRoomDetails(room) {
            const modalContent = `
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">${room.name}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="room-gallery">
                                <img src="${room.mainImage}" alt="${room.name}" class="main-image" id="mainImage">
                                <div class="image-gallery">
                                    ${room.images.map((img, index) => `
                                        <img src="${img}" 
                                             alt="Room view ${index + 1}" 
                                             class="gallery-img ${index === 0 ? 'active' : ''}"
                                             onclick="updateMainImage(this, '${img}')"
                                        >
                                    `).join('')}
                                </div>
                            </div>
                            
                            <div class="room-status">
                                ${room.status}
                            </div>

                            <div class="room-price-tag">
                                ${room.price} per night
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h6><i class="fas fa-ruler-combined me-2"></i>Room Size</h6>
                                    <p>${room.roomSize}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="fas fa-users me-2"></i>Max Occupancy</h6>
                                    <p>${room.maxOccupancy} Persons</p>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="fas fa-bed me-2"></i>Bed Type</h6>
                                    <p>${room.bedType}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="fas fa-mountain me-2"></i>View</h6>
                                    <p>${room.viewType}</p>
                                </div>
                            </div>

                            <h5 class="mt-4">Room Description</h5>
                            <p>${room.description}</p>
                            
                            <div class="feature-category">
                                <h6><i class="fas fa-star me-2"></i>Room Features</h6>
                                <div class="feature-list">
                                    ${room.features.map(feature => `
                                        <div class="feature-item">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            ${feature}
                                        </div>
                                    `).join('')}
                                </div>
                            </div>

                            <div class="feature-category">
                                <h6><i class="fas fa-concierge-bell me-2"></i>Room Amenities</h6>
                                <div class="feature-list">
                                    ${room.amenities.map(amenity => `
                                        <div class="feature-item">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            ${amenity}
                                        </div>
                                    `).join('')}
                                </div>
                            </div>

                            <button class="virtual-tour-btn w-100">
                                <i class="fas fa-vr-cardboard"></i>
                                Take a Virtual Tour
                            </button>

                            <div class="booking-summary">
                                <h6 class="mb-3">Quick Booking Summary</h6>
                                <div class="price-breakdown">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Room Rate per Night</span>
                                        <span>${room.price}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Taxes & Fees (12%)</span>
                                        <span>₱${(parseInt(room.price.replace('₱', '').replace(',', '')) * 0.12).toLocaleString()}</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <strong>Estimated Total</strong>
                                    <strong>₱${(parseInt(room.price.replace('₱', '').replace(',', '')) * 1.12).toLocaleString()}</strong>
                                </div>
                            </div>

                            <button class="btn inquiry-btn w-100 mt-4" onclick="showInquiryForm('${room.name}')">
                                Make an Inquiry
                            </button>
                        </div>
                    </div>
                </div>
            `;

            const modal = document.createElement('div');
            modal.className = 'modal room-detail-modal fade';
            modal.innerHTML = modalContent;
            document.body.appendChild(modal);

            const modalInstance = new bootstrap.Modal(modal);
            modalInstance.show();

            modal.addEventListener('hidden.bs.modal', function () {
                document.body.removeChild(modal);
            });
        }

        function showInquiryForm(roomName) {
            const modalContent = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Inquiry for ${roomName}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="inquiryForm">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" required>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Check-in Date</label>
                                        <input type="date" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Check-out Date</label>
                                        <input type="date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Number of Guests</label>
                                    <select class="form-control">
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Special Requests</label>
                                    <textarea class="form-control" rows="3"></textarea>
                                </div>
                                <button type="submit" class="btn inquiry-btn w-100">Submit Inquiry</button>
                            </form>
                        </div>
                    </div>
                </div>
            `;

            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = modalContent;
            document.body.appendChild(modal);

            const modalInstance = new bootstrap.Modal(modal);
            modalInstance.show();

            modal.addEventListener('hidden.bs.modal', function () {
                document.body.removeChild(modal);
            });

            const form = modal.querySelector('#inquiryForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                // Handle form submission here
                modalInstance.hide();
                showSuccessMessage();
            });
        }

        function showSuccessMessage() {
            const modalContent = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center py-4">
                            <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                            <h5>Inquiry Submitted Successfully!</h5>
                            <p>We will get back to you within 24 hours.</p>
                            <button type="button" class="btn inquiry-btn" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            `;

            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = modalContent;
            document.body.appendChild(modal);

            const modalInstance = new bootstrap.Modal(modal);
            modalInstance.show();

            modal.addEventListener('hidden.bs.modal', function () {
                document.body.removeChild(modal);
            });
        }

        // Add this new function for image gallery
        function updateMainImage(element, newSrc) {
            // Remove active class from all thumbnails
            document.querySelectorAll('.gallery-img').forEach(img => img.classList.remove('active'));
            // Add active class to clicked thumbnail
            element.classList.add('active');
            // Update main image
            document.getElementById('mainImage').src = newSrc;
        }

        // Simplified filter initialization
        function initializeFilters() {
            const filters = {
                roomType: 'all',
                capacity: 'all'
            };

            function applyFilters(room) {
                // Room Type Filter
                if (filters.roomType !== 'all' && room.type !== filters.roomType) {
                    return false;
                }

                // Capacity Filter
                if (filters.capacity !== 'all') {
                    const [min, max] = filters.capacity.split('-').map(cap => 
                        cap.endsWith('+') ? Infinity : parseInt(cap));
                    if (room.maxOccupancy < min || (max !== Infinity && room.maxOccupancy > max)) {
                        return false;
                    }
                }

                return true;
            }

            function updateRoomDisplay() {
                const container = document.getElementById('roomsContainer');
                const filteredRooms = rooms.filter(applyFilters);

                // Update results count
                const resultsCount = document.getElementById('resultsCount');
                resultsCount.style.opacity = '0';
                setTimeout(() => {
                    resultsCount.textContent = `Showing ${filteredRooms.length} of ${rooms.length} rooms`;
                    resultsCount.style.opacity = '1';
                }, 200);

                // Fade out current rooms
                container.style.opacity = '0';
                setTimeout(() => {
                    if (filteredRooms.length > 0) {
                        container.innerHTML = filteredRooms.map(room => createRoomCard(room)).join('');
                        AOS.refreshHard();
                    } else {
                        container.innerHTML = `
                            <div class="col-12">
                                <div class="no-results" data-aos="fade-up">
                                    <i class="fas fa-search mb-3"></i>
                                    <h4>No Rooms Found</h4>
                                    <p>Try adjusting your filters to find available rooms.</p>
                                    <button class="btn btn-primary mt-3" onclick="resetFilters()">
                                        <i class="fas fa-redo me-2"></i>Reset Filters
                                    </button>
                                </div>
                            </div>
                        `;
                    }
                    container.style.opacity = '1';
                }, 300);

                updateActiveFilters();
            }

            function updateActiveFilters() {
                const activeFiltersContainer = document.getElementById('activeFilters');
                const activeFilterTags = [];

                Object.entries(filters).forEach(([key, value]) => {
                    if (value !== 'all') {
                        let filterText = '';
                        switch (key) {
                            case 'roomType':
                                filterText = `Room Type: ${value.charAt(0).toUpperCase() + value.slice(1)}`;
                                break;
                            case 'capacity':
                                filterText = `Capacity: ${value}`;
                                break;
                        }
                        activeFilterTags.push(`
                            <div class="filter-tag" data-aos="fade-right" data-aos-delay="${activeFilterTags.length * 100}">
                                ${filterText}
                                <span class="remove-filter" onclick="removeFilter('${key}')">
                                    <i class="fas fa-times"></i>
                                </span>
                            </div>
                        `);
                    }
                });

                activeFiltersContainer.style.opacity = '0';
                setTimeout(() => {
                    activeFiltersContainer.innerHTML = activeFilterTags.join('');
                    activeFiltersContainer.style.opacity = '1';
                    AOS.refreshHard();
                }, 200);
            }

            // Add debounce to prevent too frequent updates
            let filterTimeout;
            function debouncedUpdate() {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(updateRoomDisplay, 300);
            }

            // Event listeners for filters
            document.querySelectorAll('.filter-select').forEach(select => {
                select.addEventListener('change', function() {
                    const filterType = this.id.replace('Filter', '');
                    filters[filterType.toLowerCase()] = this.value;
                    debouncedUpdate();
                });
            });

            // Initialize display
            updateRoomDisplay();

            // Make functions available globally
            window.removeFilter = function(filterKey) {
                filters[filterKey] = 'all';
                document.getElementById(`${filterKey}Filter`).value = 'all';
                debouncedUpdate();
            };

            window.resetFilters = function() {
                Object.keys(filters).forEach(key => {
                    filters[key] = 'all';
                    document.getElementById(`${key}Filter`).value = 'all';
                });
                debouncedUpdate();
            };
        }

        // Initialize filters when the page loads
        document.addEventListener('DOMContentLoaded', initializeFilters);
    </script>
</body>
</html>
