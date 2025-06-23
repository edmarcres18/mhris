<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} - MHR</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #f39c12;
            --text-color: #333;
            --light-bg: #f8f9fa;
            --white: #ffffff;
            --gray: #6c757d;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--light-bg);
        }

        /* Header and Navigation styles */
        header {
            background-color: var(--white);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 40px;
            margin-right: 10px;
        }

        .app-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .nav-links {
            display: flex;
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .nav-links li {
            margin-left: 2rem;
            position: relative;
        }

        .nav-links a {
            color: var(--text-color);
            font-weight: 500;
            text-decoration: none;
            transition: color 0.3s ease;
            padding: 0.5rem 0;
            display: inline-block;
        }

        .nav-links a:hover {
            color: var(--primary-color);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--primary-color);
            visibility: hidden;
            transform: scaleX(0);
            transition: all 0.3s ease-in-out;
        }

        .nav-links a:hover::after {
            visibility: visible;
            transform: scaleX(1);
        }

        /* Enhanced content styles */
        .content-wrapper {
            padding-top: 100px;
            padding-bottom: 40px;
        }

        .post-container {
            background-color: var(--white);
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .post-title {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 2.5rem;
        }

        .post-meta {
            color: var(--gray);
            font-size: 0.9rem;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
        }

        .post-content {
            margin-bottom: 2rem;
            line-height: 1.8;
        }

        /* Related posts styles */
        .related-posts h2 {
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .related-posts .card {
            transition: transform 0.3s ease;
        }

        .related-posts .card:hover {
            transform: translateY(-5px);
        }

        .related-posts .card-title {
            font-size: 1.1rem;
            color: var(--primary-color);
        }

        .related-posts .card-text {
            font-size: 0.9rem;
            color: var(--gray);
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {
            .content-wrapper {
                padding-top: 80px;
            }

            .post-title {
                font-size: 2rem;
            }

            .related-posts {
                margin-top: 2rem;
            }
        }

        @media (min-width: 769px) {
            .mobile-menu-toggle {
                display: none;
            }
        }

        /* Preloader styles */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .preloader-content {
            position: relative;
            width: 100px;
            height: 100px;
        }

        .preloader-spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #8e44ad; /* Purple color */
            border-radius: 50%;
            width: 100px;
            height: 100px;
            animation: spin 1s linear infinite;
        }

        .preloader-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            font-weight: bold;
            color: #8e44ad; /* Purple color */
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    @include('preloader')

    <header>
        <nav>
            <div class="logo-container">
                <div class="logo">
                    <img src="{{ asset('vendor/adminlte/dist/img/LOGO4.png') }}" alt="MHR Logo">
                </div>
                <div class="app-name">
                    MHRPCI
                </div>
            </div>
            <ul class="nav-links">
                <li><a href="{{ url('/') }}">Home</a></li>
            </ul>
            <div class="mobile-menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </nav>
    </header>

    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <!-- Main post column -->
                <div class="col-lg-8">
                    <div class="post-container">
                        <h1 class="post-title">{{ $post->title }}</h1>
                        <div class="post-meta">
                            <span>By: {{ $post->user->first_name }} {{ $post->user->last_name }}</span>
                            <span>Posted on: {{ $post->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="post-content">
                            {{ $post->content }}
                        </div>
                        <a href="{{ url('/') }}" class="btn btn-primary">
                            <span class="btn-text">Back to Home</span>
                            <span class="btn-loader d-none">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Related posts column -->
                <div class="col-lg-4">
                    @if($relatedPosts->count() > 0)
                        <div class="related-posts">
                            <h2>Related Posts from Today</h2>
                            @foreach($relatedPosts as $relatedPost)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $relatedPost->title }}</h5>
                                        <p class="card-text">{{ Str::limit($relatedPost->content, 100) }}</p>
                                        <a href="{{ url('/posts/' . $relatedPost->id) }}" class="btn btn-secondary btn-sm">
                                            <span class="btn-text">Read More</span>
                                            <span class="btn-loader d-none">
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                Loading...
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} MHR. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
            var navLinks = document.querySelector('.nav-links');

            mobileMenuToggle.addEventListener('click', function() {
                navLinks.classList.toggle('show');
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!navLinks.contains(event.target) && !mobileMenuToggle.contains(event.target)) {
                    navLinks.classList.remove('show');
                }
            });

            // Modified preloader functionality
            setTimeout(function() {
                const preloader = document.querySelector('.preloader');
                preloader.style.opacity = '0';
                preloader.style.transition = 'opacity 0.5s ease';
                setTimeout(function() {
                    preloader.style.display = 'none';
                }, 500);
            }, 1500);
            
            // Add loading indicators to buttons
            document.querySelectorAll('a.btn, button.btn').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    // Don't show loader for buttons with cancel or data-no-loader attribute
                    if (button.classList.contains('cancel') || button.hasAttribute('data-no-loader')) {
                        return;
                    }
                    
                    const btnText = button.querySelector('.btn-text');
                    const btnLoader = button.querySelector('.btn-loader');
                    
                    if (btnText && btnLoader) {
                        btnText.classList.add('d-none');
                        btnLoader.classList.remove('d-none');
                        button.disabled = true;
                        
                        // For demonstration, enable the button after 2 seconds
                        // In a real scenario, this would happen after the AJAX call completes
                        setTimeout(function() {
                            btnText.classList.remove('d-none');
                            btnLoader.classList.add('d-none');
                            button.disabled = false;
                        }, 2000);
                    }
                });
            });
        });
    </script>
</body>
</html>
