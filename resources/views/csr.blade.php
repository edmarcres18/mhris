<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MHR Property Conglomerate Inc. | Corporate Social Responsibility</title>
    <meta name="description" content="MHR Property Conglomerate Inc. (MHRPCI) Corporate Social Responsibility initiatives - Committed to sustainability, community empowerment and environmental conservation across the Philippines.">
    <meta name="keywords" content="CSR, Corporate Social Responsibility, MHRPCI, MHR Property Conglomerate, Community Development">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('vendor/adminlte/dist/img/LOGO4.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4F46E5;
            --primary-dark: #4338CA;
            --primary-light: #EEF2FF;
            --text-dark: #1F2937;
            --text-light: #F9FAFB;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        html {
            font-size: 100%;
            scroll-behavior: smooth;
            -webkit-text-size-adjust: 100%;
        }
        
        @media (max-width: 320px) {
            html { font-size: 85%; }
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            line-height: 1.5;
            min-width: 320px;
            overflow-x: hidden;
        }
        
        /* Container for smaller screens */
        .container {
            width: 100%;
            max-width: 100%;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        @media (min-width: 640px) {
            .container {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }
        
        @media (min-width: 1280px) {
            .container {
                max-width: 1280px;
                margin-left: auto;
                margin-right: auto;
            }
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        }
        
        .nav-link.active {
            color: #6D28D9;
            border-bottom: 2px solid #6D28D9;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out forwards;
        }

        /* Coming soon animation */
        @keyframes pulseText {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse-animation {
            animation: pulseText 2s infinite ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header & Navigation -->
    <header class="bg-white shadow-md fixed w-full z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <img src="{{ asset('vendor/adminlte/dist/img/LOGO_ICON.png') }}" alt="MHRPCI Logo" class="h-8 sm:h-10 md:h-12 w-auto">
                    <div>
                        <h1 class="text-base sm:text-lg md:text-xl font-bold text-indigo-700">MHRPCI</h1>
                        <p class="text-xs text-gray-500">Property Conglomerate Inc.</p>
                    </div>
                </div>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex space-x-3 lg:space-x-8">
                    <a href="{{ url('/') }}#home" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition duration-300 px-2 py-1">Home</a>
                    <a href="{{ url('/') }}#about" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition duration-300 px-2 py-1">About Us</a>
                    <a href="{{ url('/') }}#services" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition duration-300 px-2 py-1">Our Services</a>
                    <a href="{{ url('/') }}#history" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition duration-300 px-2 py-1">Our History</a>
                    <a href="{{ url('/') }}#careers" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition duration-300 px-2 py-1">MHR Careers</a>
                    <a href="{{ url('/') }}#csr" class="nav-link text-indigo-700 font-medium border-b-2 border-indigo-600 transition duration-300 px-2 py-1">Our CSR</a>
                </nav>
                
                <!-- Contact Button -->
                <div class="hidden md:block">
                    <a href="{{ url('/') }}#contact" class="bg-indigo-600 text-white px-4 lg:px-5 py-2 rounded-lg hover:bg-indigo-700 transition duration-300 text-sm lg:text-base">Contact Us</a>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-700 hover:text-indigo-600 focus:outline-none p-2" aria-label="Toggle menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="md:hidden hidden border-t border-gray-200 py-2 animate-fadeIn">
                <a href="{{ url('/') }}#home" class="block py-3 px-4 text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Home</a>
                <a href="{{ url('/') }}#about" class="block py-3 px-4 text-gray-700 hover:text-indigo-600 hover:bg-gray-50">About Us</a>
                <a href="{{ url('/') }}#services" class="block py-3 px-4 text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Our Services</a>
                <a href="{{ url('/') }}#history" class="block py-3 px-4 text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Our History</a>
                <a href="{{ url('/') }}#careers" class="block py-3 px-4 text-gray-700 hover:text-indigo-600 hover:bg-gray-50">MHR Careers</a>
                <a href="{{ url('/') }}#csr" class="block py-3 px-4 text-indigo-700 font-medium bg-gray-50">Our CSR</a>
                <a href="{{ url('/') }}#contact" class="block py-3 px-4 text-indigo-600 font-medium">Contact Us</a>
            </div>
        </div>
    </header>

    <!-- Coming Soon Hero Section -->
    <section class="hero-gradient pt-32 md:pt-40 pb-24 md:pb-32">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-white text-3xl sm:text-4xl md:text-5xl font-bold mb-6" data-aos="fade-up">Corporate Social Responsibility</h1>
                <div class="w-20 h-1 bg-white mx-auto mb-6"></div>
                <p class="text-indigo-100 text-lg sm:text-xl max-w-2xl mx-auto mb-8" data-aos="fade-up" data-aos-delay="200">
                    Our commitment to sustainable development and ethical business practices
                </p>
            </div>
        </div>
    </section>

    <!-- Coming Soon Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-center max-w-4xl mx-auto text-center" data-aos="fade-up">
                <div class="bg-gray-50 p-8 sm:p-12 rounded-xl shadow-lg w-full">
                    <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-8 text-indigo-600">
                        <i class="fas fa-tools text-3xl"></i>
                    </div>
                    
                    <h2 class="text-3xl sm:text-4xl font-bold mb-6 text-gray-800 pulse-animation">Coming Soon</h2>
                    
                    <p class="text-gray-600 text-lg mb-8">
                        We're currently developing a comprehensive overview of our CSR initiatives. 
                        Check back soon to learn more about our community development projects, 
                        environmental conservation efforts, and educational support programs.
                    </p>
                    
                    <div class="flex flex-wrap justify-center gap-4 mb-8">
                        <div class="bg-white p-4 rounded-lg shadow-sm text-center w-full sm:w-auto sm:min-w-[200px]">
                            <h3 class="font-semibold text-indigo-600 mb-2">Orphanage Support</h3>
                            <p class="text-gray-600 text-sm">Cordova, Cebu</p>
                        </div>
                        
                        <div class="bg-white p-4 rounded-lg shadow-sm text-center w-full sm:w-auto sm:min-w-[200px]">
                            <h3 class="font-semibold text-indigo-600 mb-2">Mangrove Planting</h3>
                            <p class="text-gray-600 text-sm">Busay, Cebu</p>
                        </div>
                    </div>
                    
                    <a href="{{ url('/') }}" class="inline-flex items-center bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition duration-300">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Homepage
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <img src="{{ asset('vendor/adminlte/dist/img/whiteLOGO_ICON.png') }}" alt="MHRPCI Logo" class="h-10 w-auto">
                        <div>
                            <h3 class="text-lg font-bold">MHRPCI</h3>
                            <p class="text-gray-400 text-xs">Property Conglomerate Inc.</p>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-4">
                        A diverse business conglomerate operating across healthcare, fuel distribution, construction, and hospitality sectors.
                    </p>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ url('/') }}#about" class="hover:text-indigo-400 transition duration-300">About Us</a></li>
                        <li><a href="{{ url('/') }}#services" class="hover:text-indigo-400 transition duration-300">Our Services</a></li>
                        <li><a href="{{ url('/') }}#history" class="hover:text-indigo-400 transition duration-300">Our History</a></li>
                        <li><a href="{{ url('/') }}#careers" class="hover:text-indigo-400 transition duration-300">MHR Careers</a></li>
                        <li><a href="{{ url('/') }}#csr" class="hover:text-indigo-400 transition duration-300">Our CSR</a></li>
                        <li><a href="{{ url('/') }}#contact" class="hover:text-indigo-400 transition duration-300">Contact Us</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Our Companies</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('mhrhci') }}" class="hover:text-indigo-400 transition duration-300">MHRHCI</a></li>
                        <li><a href="{{ route('bgpdi') }}" class="hover:text-indigo-400 transition duration-300">Bay Gas</a></li>
                        <li><a href="{{ route('cio') }}" class="hover:text-indigo-400 transition duration-300">Cebic Industries</a></li>
                        <li><a href="{{ route('rcg') }}" class="hover:text-indigo-400 transition duration-300">RCG Construction</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Legal</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('terms') }}" class="hover:text-indigo-400 transition duration-300">Terms of Service</a></li>
                        <li><a href="{{ route('privacy') }}" class="hover:text-indigo-400 transition duration-300">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-10 pt-6 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} MHR Property Conglomerate Inc. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- AOS Animation Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Initialize AOS animations
        document.addEventListener('DOMContentLoaded', function() {
            // Add 'xs' class to body if viewport is less than 400px for extra-small device detection
            if (window.innerWidth < 400) {
                document.body.classList.add('xs');
            }
            
            AOS.init({
                once: true,
                disable: window.innerWidth < 768 ? true : false,
                duration: 700,
                easing: 'ease-out-cubic',
                delay: 100,
                offset: 120
            });
            
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
                
                // Close mobile menu when clicking on a link
                const mobileLinks = document.querySelectorAll('#mobile-menu a');
                mobileLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        mobileMenu.classList.add('hidden');
                    });
                });
            }
            
            // Handle resize events
            window.addEventListener('resize', function() {
                const isXS = window.innerWidth < 400;
                if (isXS) {
                    document.body.classList.add('xs');
                } else {
                    document.body.classList.remove('xs');
                }
            });
        });
    </script>
</body>
</html>
