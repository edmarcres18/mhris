<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service | MHR Property Conglomerate Inc.</title>
    <meta name="description" content="Terms of Service for MHR Property Conglomerate Inc. (MHRPCI) - Learn about our policies and guidelines for using our services.">
    <meta name="keywords" content="MHRPCI terms of service, user guidelines, service policies, legal terms">
    
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
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        }
        
        .text-gradient {
            background: linear-gradient(90deg, #4F46E5, #7C3AED);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
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
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header & Navigation -->
    <header class="bg-white shadow-md fixed w-full z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('vendor/adminlte/dist/img/LOGO_ICON.png') }}" alt="MHRPCI Logo" class="h-12 w-auto">
                    <div>
                        <h1 class="text-xl font-bold text-indigo-700">MHRPCI</h1>
                        <p class="text-xs text-gray-500">Property Conglomerate Inc.</p>
                    </div>
                </div>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('welcome') }}#home" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition duration-300">Home</a>
                    <a href="{{ route('welcome') }}#about" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition duration-300">About Us</a>
                    <a href="{{ route('welcome') }}#services" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition duration-300">Our Services</a>
                    <a href="{{ route('welcome') }}#history" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition duration-300">Our History</a>
                    <a href="{{ route('welcome') }}#careers" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition duration-300">MHR Careers</a>
                </nav>
                
                <!-- Contact Button -->
                <div class="hidden md:block">
                    <a href="{{ route('welcome') }}#contact" class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition duration-300">Contact Us</a>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-700 hover:text-indigo-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="md:hidden hidden border-t border-gray-200 py-2 animate-fadeIn">
                <a href="{{ route('welcome') }}#home" class="block py-2 px-4 text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Home</a>
                <a href="{{ route('welcome') }}#about" class="block py-2 px-4 text-gray-700 hover:text-indigo-600 hover:bg-gray-50">About Us</a>
                <a href="{{ route('welcome') }}#services" class="block py-2 px-4 text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Our Services</a>
                <a href="{{ route('welcome') }}#history" class="block py-2 px-4 text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Our History</a>
                <a href="{{ route('welcome') }}#careers" class="block py-2 px-4 text-gray-700 hover:text-indigo-600 hover:bg-gray-50">MHR Careers</a>
                <a href="{{ route('welcome') }}#contact" class="block py-2 px-4 text-indigo-600 font-medium">Contact Us</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-gradient pt-32 pb-20 md:pt-40 md:pb-32">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-white text-4xl md:text-5xl font-bold leading-tight mb-6" data-aos="fade-up">Terms of Service</h1>
                <p class="text-indigo-100 text-lg mb-8 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                    Read our terms and conditions for using MHR Property Conglomerate Inc.'s services and career portal.
                </p>
            </div>
        </div>
    </section>

    <!-- Terms of Service Content -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <!-- Introduction -->
                <div class="mb-16" data-aos="fade-up">
                    <h2 class="text-2xl font-bold text-indigo-700 mb-6">1. Introduction</h2>
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                        <p class="text-gray-600">These Terms of Service govern your use of the MHRPCI career portal and recruitment services. MHRPCI operates as a conglomerate with multiple subsidiary companies including MHRHCI, VHI, BGPDI, MAX, MHRCONS, CIO, LUSCO, and RCG. By accessing our career portal, you agree to these terms.</p>
                    </div>
                </div>

                <!-- Eligibility -->
                <div class="mb-16" data-aos="fade-up" data-aos-delay="100">
                    <h2 class="text-2xl font-bold text-indigo-700 mb-6">2. Eligibility</h2>
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                        <p class="text-gray-600">You must be at least 18 years old and legally eligible to work in the Philippines to use our career portal. By submitting an application, you confirm that all information provided is true, accurate, and complete.</p>
                    </div>
                </div>

                <!-- Application Process -->
                <div class="mb-16" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="text-2xl font-bold text-indigo-700 mb-6">3. Application Process</h2>
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                        <p class="text-gray-600 mb-4">When you submit an application through our career portal:</p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Your information may be shared across our subsidiary companies for relevant opportunities</li>
                            <li>You authorize us to verify your information and conduct background checks</li>
                            <li>You understand that submission of an application does not guarantee employment</li>
                            <li>You agree to participate in our recruitment process in good faith</li>
                        </ul>
                    </div>
                </div>

                <!-- Acceptance of Terms -->
                <div class="mb-16" data-aos="fade-up" data-aos-delay="300">
                    <h2 class="text-2xl font-bold text-indigo-700 mb-6">4. Acceptance of Terms</h2>
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                        <p class="text-gray-600">By accessing and using the MHRPCI career portal, you agree to be bound by these Terms of Service.</p>
                    </div>
                </div>

                <!-- User Responsibilities -->
                <div class="mb-16" data-aos="fade-up" data-aos-delay="400">
                    <h2 class="text-2xl font-bold text-indigo-700 mb-6">5. User Responsibilities</h2>
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                        <p class="text-gray-600">You are responsible for maintaining the confidentiality of your account and password. You agree to accept responsibility for all activities that occur under your account.</p>
                    </div>
                </div>

                <!-- Privacy -->
                <div class="mb-16" data-aos="fade-up" data-aos-delay="500">
                    <h2 class="text-2xl font-bold text-indigo-700 mb-6">6. Privacy</h2>
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                        <p class="text-gray-600">Your use of the MHRPCI career portal is also governed by our Privacy Policy.</p>
                    </div>
                </div>

                <!-- Intellectual Property -->
                <div class="mb-16" data-aos="fade-up" data-aos-delay="600">
                    <h2 class="text-2xl font-bold text-indigo-700 mb-6">7. Intellectual Property</h2>
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                        <p class="text-gray-600">The content, organization, graphics, design, and other matters related to the MHRPCI career portal are protected under applicable copyrights and other proprietary laws.</p>
                    </div>
                </div>

                <!-- Termination -->
                <div class="mb-16" data-aos="fade-up" data-aos-delay="700">
                    <h2 class="text-2xl font-bold text-indigo-700 mb-6">8. Termination</h2>
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                        <p class="text-gray-600">We reserve the right to terminate or suspend your account and access to the MHRPCI career portal at our sole discretion, without notice, for conduct that we believe violates these Terms of Service or is harmful to other users of the MHRPCI career portal, us, or third parties, or for any other reason.</p>
                    </div>
                </div>

                <!-- Changes to Terms -->
                <div class="mb-16" data-aos="fade-up" data-aos-delay="800">
                    <h2 class="text-2xl font-bold text-indigo-700 mb-6">9. Changes to Terms</h2>
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                        <p class="text-gray-600">We reserve the right to modify these Terms of Service at any time. We will post notification of changes on this page. Your continued use of the MHRPCI career portal following posted changes constitutes your acceptance of the changes.</p>
                    </div>
                </div>

                <!-- Governing Law -->
                <div class="mb-16" data-aos="fade-up" data-aos-delay="900">
                    <h2 class="text-2xl font-bold text-indigo-700 mb-6">10. Governing Law</h2>
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                        <p class="text-gray-600">These Terms of Service shall be governed by and construed in accordance with the laws of the Republic of the Philippines. Any disputes shall be subject to the exclusive jurisdiction of the courts of Cebu City.</p>
                    </div>
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
                        <img src="{{ asset('vendor/adminlte/dist/img/whiteLOGO4.png') }}" alt="MHRPCI Logo" class="h-10 w-auto">
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
                        <li><a href="{{ route('welcome') }}#about" class="hover:text-indigo-400 transition duration-300">About Us</a></li>
                        <li><a href="{{ route('welcome') }}#services" class="hover:text-indigo-400 transition duration-300">Our Services</a></li>
                        <li><a href="{{ route('welcome') }}#history" class="hover:text-indigo-400 transition duration-300">Our History</a></li>
                        <li><a href="{{ route('welcome') }}#careers" class="hover:text-indigo-400 transition duration-300">MHR Careers</a></li>
                        <li><a href="{{ route('welcome') }}#contact" class="hover:text-indigo-400 transition duration-300">Contact Us</a></li>
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
            AOS.init();
            
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        });
    </script>
</body>
</html>
