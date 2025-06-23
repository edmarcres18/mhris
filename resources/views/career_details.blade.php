<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $hiring->position }} | MHR Property Conglomerate Inc.</title>
    <meta name="description" content="Join our team as a {{ $hiring->position }} at MHR Property Conglomerate Inc. Explore job details, requirements, and apply now!">
    <meta name="keywords" content="MHRPCI careers, {{ $hiring->position }}, job opportunity, Cebu jobs, Philippines careers">
    
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
            --secondary-color: #7C3AED;
            --text-color: #1F2937;
            --text-light: #6B7280;
            --background-color: #F9FAFB;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
            width: 100%;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        }
        
        .text-gradient {
            background: linear-gradient(90deg, #4F46E5, #7C3AED);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .job-card {
            transition: all 0.3s ease;
        }
        
        .job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
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

        .requirements-list li, .benefits-list li {
            padding-left: 1.75rem;
            position: relative;
            margin-bottom: 0.75rem;
        }
        
        .requirements-list li:before, .benefits-list li:before {
            content: "";
            position: absolute;
            left: 0;
            top: 0.5rem;
            height: 0.75rem;
            width: 0.75rem;
            background-color: #4F46E5;
            border-radius: 50%;
        }
        
        /* Responsive Typography */
        h1 {
            font-size: clamp(1.875rem, 5vw, 2.5rem);
        }
        
        h2 {
            font-size: clamp(1.5rem, 4vw, 2rem);
        }
        
        h3 {
            font-size: clamp(1.25rem, 3vw, 1.5rem);
        }
        
        p, li {
            font-size: clamp(0.875rem, 2vw, 1rem);
        }
        
        /* Enhanced Mobile Styles */
        @media (max-width: 640px) {
            .job-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .hero-content {
                padding: 1.5rem !important;
            }
            
            .main-content {
                padding-bottom: 5rem !important; /* Extra padding for mobile sticky button */
            }
        }
        
        /* Tablet Optimizations */
        @media (min-width: 641px) and (max-width: 1024px) {
            .hero-content {
                padding: 2rem !important;
            }
            
            .main-content {
                padding-bottom: 2rem !important;
            }
        }
        
        /* Touch-friendly targets */
        button, a, .nav-link, [role="button"] {
            min-height: 44px;
            min-width: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Improved scrolling experience */
        .scroll-container {
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }
        
        /* Modal responsiveness */
        .modal-content {
            max-height: 90vh;
            overflow-y: auto;
        }
        
        /* Hover effects only on non-touch devices */
        @media (hover: hover) {
            .hover-effect:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            }
        }
        
        /* Prevent text size adjustment after orientation changes on mobile */
        html {
            -webkit-text-size-adjust: 100%;
        }
        
        /* Better form controls for mobile */
        input, select, textarea {
            font-size: 16px !important; /* Prevents zoom on focus in iOS */
        }
        
        /* CSS for the sticky sidebar */
        .sticky-sidebar {
            position: sticky;
            top: 100px;
            z-index: 10;
        }
        
        @media (max-width: 1023px) {
            .sticky-sidebar {
                position: static;
                margin-bottom: 1.5rem;
            }
        }
        
        /* Fixed bottom bar for mobile */
        .mobile-sticky-bar {
            box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        /* Toast Notifications */
        .toast-container {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            z-index: 1000;
        }
        
        @media (max-width: 640px) {
            .toast-container {
                width: calc(100% - 2rem);
                right: 1rem;
            }
        }

        /* Job Summary Card Fixes */
        .job-summary-item {
            display: flex;
            align-items: flex-start;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(229, 231, 235, 0.5);
        }
        
        .job-summary-item:last-child {
            border-bottom: none;
        }
        
        .job-summary-icon {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.375rem;
            background-color: rgb(238, 242, 255);
            color: rgb(79, 70, 229);
        }
        
        .job-summary-content {
            flex: 1;
            min-width: 0; /* Prevents text overflow issues */
        }
        
        .job-summary-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: rgb(107, 114, 128);
            margin-bottom: 0.25rem;
        }
        
        .job-summary-value {
            font-size: 0.875rem;
            color: rgb(31, 41, 55);
            font-weight: 400;
            word-wrap: break-word;
            overflow-wrap: break-word;
            line-height: 1.25;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        @media (min-width: 640px) {
            .job-summary-value {
                font-size: 1rem;
            }
        }

        /* Focus styles for accessibility */
        a:focus, button:focus, input:focus, select:focus, textarea:focus {
            outline: 2px solid #4F46E5;
            outline-offset: 2px;
        }

        /* Skip to content link for accessibility */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: #4F46E5;
            color: white;
            padding: 8px;
            z-index: 100;
            transition: top 0.3s ease;
        }

        .skip-link:focus {
            top: 0;
        }

        /* Print styles */
        @media print {
            header, footer, .mobile-sticky-bar, .toast-container, #toast, #loader, 
            button[data-bs-toggle], #shareButton, #shareOptions {
                display: none !important;
            }

            body {
                font-size: 12pt;
                color: #000;
                background: #fff;
                margin: 0;
                padding: 0;
            }

            .main-content {
                padding: 0;
                margin: 0;
            }

            .hero-gradient {
                background: none;
                color: #000;
                padding: 1cm 0;
                border-bottom: 1pt solid #ddd;
            }

            .hero-content {
                background: none;
                color: #000;
                box-shadow: none;
                padding: 0 !important;
            }

            .hero-content h1 {
                font-size: 24pt;
                color: #000;
            }

            .bg-white, .rounded-xl, .shadow-sm, .border {
                background: none !important;
                box-shadow: none !important;
                border: none !important;
            }

            .requirements-list li:before, .benefits-list li:before {
                background-color: #000;
            }

            .sticky-sidebar {
                position: static;
            }

            h1, h2, h3, h4, h5, h6 {
                page-break-after: avoid;
                page-break-inside: avoid;
            }

            img {
                max-width: 100% !important;
                page-break-inside: avoid;
            }

            ul, ol, li, p {
                page-break-inside: avoid;
            }

            a {
                text-decoration: none;
                color: #000;
            }

            .print-break-before {
                page-break-before: always;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header & Navigation -->
    <header class="bg-white shadow-md fixed w-full z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <img src="{{ asset('vendor/adminlte/dist/img/LOGO_ICON.png') }}" alt="MHRPCI Logo" class="h-8 sm:h-12 w-auto">
                    <div>
                        <h1 class="text-base sm:text-xl font-bold text-indigo-700">MHRPCI</h1>
                        <p class="text-xs text-gray-500 hidden sm:block">Property Conglomerate Inc.</p>
                    </div>
                </div>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex space-x-6 lg:space-x-8">
                    <a href="{{ url('/') }}" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition duration-300">Home</a>
                    <a href="{{ route('welcome') }}#about" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition duration-300">About Us</a>
                    <a href="{{ route('welcome') }}#services" class="nav-link text-gray-700 hover:text-indigo-600 font-medium transition duration-300">Our Services</a>
                    <a href="{{ route('careers') }}" class="nav-link text-indigo-600 hover:text-indigo-600 font-medium transition duration-300 border-b-2 border-indigo-600">MHR Careers</a>
                </nav>
                
                <!-- User Auth/Contact Button -->
                <div class="hidden md:flex items-center space-x-4">
                    @if(Auth::guard('google')->check())
                    <div class="flex items-center space-x-3">
                        <img src="{{ Auth::guard('google')->user()->avatar }}" alt="{{ Auth::guard('google')->user()->name }}" class="rounded-full h-8 w-8">
                        <span class="text-gray-700">{{ Auth::guard('google')->user()->name }}</span>
                    </div>
                    @else
                    <a href="{{ route('google.login') }}" class="inline-flex items-center text-gray-700 hover:text-indigo-600">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
                            <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                            <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                            <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                            <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
                        </svg>
                        Sign in
                    </a>
                    @endif
                    <a href="{{ route('welcome') }}#contact" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-300">Contact Us</a>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-700 hover:text-indigo-600 focus:outline-none p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="md:hidden hidden border-t border-gray-200 py-2 animate-fadeIn">
                <a href="{{ url('/') }}" class="block py-2 px-4 text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Home</a>
                <a href="{{ route('welcome') }}#about" class="block py-2 px-4 text-gray-700 hover:text-indigo-600 hover:bg-gray-50">About Us</a>
                <a href="{{ route('welcome') }}#services" class="block py-2 px-4 text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Our Services</a>
                <a href="{{ route('careers') }}" class="block py-2 px-4 text-indigo-600 hover:bg-gray-50 font-medium">MHR Careers</a>
                <a href="{{ route('welcome') }}#contact" class="block py-2 px-4 text-gray-700 hover:text-indigo-600 hover:bg-gray-50">Contact Us</a>
                @if(!Auth::guard('google')->check())
                <a href="{{ route('google.login') }}" class="block py-2 px-4 text-indigo-600 font-medium">
                    <i class="fab fa-google mr-2"></i> Sign in with Google
                </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Hero Banner -->
    <section class="hero-gradient pt-24 sm:pt-32 pb-12 sm:pb-16 md:pt-36 md:pb-20 text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 md:p-8 shadow-lg hero-content" data-aos="fade-up">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-3 sm:mb-4">{{ $hiring->position }}</h1>
                            <div class="flex flex-wrap gap-2 sm:gap-3 mb-4">
                                <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-white/20 backdrop-blur-sm">
                                    <i class="fas fa-map-marker-alt mr-1"></i> {{ $hiring->location }}
                                </span>
                                <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-white/20 backdrop-blur-sm">
                                    <i class="fas fa-briefcase mr-1"></i> {{ $hiring->employment_type }}
                                </span>
                                <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-white/20 backdrop-blur-sm">
                                    <i class="fas fa-users mr-1"></i> {{ $hiring->department->name }}
                                </span>
                                <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-white/20 backdrop-blur-sm">
                                    <i class="fas fa-clock mr-1"></i> Posted {{ $hiring->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            @if(Auth::guard('google')->check())
                                <button class="px-3 sm:px-5 py-2 bg-white text-indigo-700 rounded-lg hover:bg-gray-100 transition duration-300 inline-flex items-center font-medium" onclick="openModal('applyModal')">
                                    <i class="fas fa-paper-plane mr-2"></i> <span class="hidden xs:inline">Apply Now</span><span class="inline xs:hidden">Apply</span>
                                </button>
                            @else
                                <button class="px-3 sm:px-5 py-2 bg-white text-indigo-700 rounded-lg hover:bg-gray-100 transition duration-300 inline-flex items-center font-medium" onclick="openModal('loginModal')">
                                    <i class="fas fa-paper-plane mr-2"></i> <span class="hidden xs:inline">Apply Now</span><span class="inline xs:hidden">Apply</span>
                                </button>
                            @endif
                            <div class="relative" id="shareDropdown">
                                <button class="px-3 py-2 bg-white/20 backdrop-blur-sm text-white rounded-lg hover:bg-white/30 transition duration-300" id="shareButton">
                                    <i class="fas fa-share-alt"></i>
                                </button>
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden" id="shareOptions">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" id="copyLink">
                                        <i class="fas fa-link mr-2"></i> Copy Link
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" id="shareTwitter">
                                        <i class="fab fa-twitter mr-2"></i> Twitter
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" id="shareLinkedIn">
                                        <i class="fab fa-linkedin mr-2"></i> LinkedIn
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" id="shareFacebook">
                                        <i class="fab fa-facebook mr-2"></i> Facebook
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 sm:mt-6">
                        <a href="{{ route('careers') }}" class="inline-flex items-center text-white/90 hover:text-white transition duration-300">
                            <i class="fas fa-arrow-left mr-2"></i> Back to all positions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section id="main-content" class="py-8 sm:py-12 bg-white main-content">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                    <!-- Job Details (Left Column - 2/3 width) -->
                    <div class="lg:col-span-2">
                        <!-- Job Overview -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6 sm:mb-8" data-aos="fade-up">
                            <div class="p-4 sm:p-6">
                                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3 sm:mb-4">
                                    <i class="fas fa-file-alt text-indigo-600 mr-2"></i>Job Description
                                </h2>
                                <div class="prose max-w-none text-gray-600">
                                    {!! nl2br(e($hiring->description)) !!}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Job Requirements -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6 sm:mb-8" data-aos="fade-up" data-aos-delay="100">
                            <div class="p-4 sm:p-6">
                                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3 sm:mb-4">
                                    <i class="fas fa-check-circle text-indigo-600 mr-2"></i>Requirements
                                </h2>
                                <ul class="requirements-list text-gray-600 space-y-2 sm:space-y-3">
                                    @foreach(explode("\n", $hiring->requirements) as $requirement)
                                        @if(!empty(trim($requirement)))
                                            <li class="ml-4 sm:ml-6 pl-2">{{ trim($requirement) }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Job Responsibilities -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6 sm:mb-8" data-aos="fade-up" data-aos-delay="200">
                            <div class="p-4 sm:p-6">
                                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3 sm:mb-4">
                                    <i class="fas fa-tasks text-indigo-600 mr-2"></i>Responsibilities
                                </h2>
                                <ul class="requirements-list text-gray-600 space-y-2 sm:space-y-3">
                                    @foreach(explode("\n", $hiring->responsibilities) as $responsibility)
                                        @if(!empty(trim($responsibility)))
                                            <li class="ml-4 sm:ml-6 pl-2">{{ trim($responsibility) }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Application Process -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                            <div class="p-4 sm:p-6">
                                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3 sm:mb-4">
                                    <i class="fas fa-paper-plane text-indigo-600 mr-2"></i>How to Apply
                                </h2>
                                <div class="prose max-w-none text-gray-600 mb-4 sm:mb-6">
                                    <p>To apply for this position, please click the "Apply Now" button and complete the application form. Make sure to include your updated resume and any other required documents.</p>
                                </div>
                                <div class="bg-indigo-50 p-3 sm:p-4 rounded-lg">
                                    <p class="text-indigo-800 font-medium mb-3 sm:mb-4"><i class="fas fa-info-circle mr-2"></i>What happens next?</p>
                                    <ul class="text-indigo-700 space-y-2 sm:space-y-3">
                                        <li class="flex items-start">
                                            <span class="inline-flex items-center justify-center h-5 w-5 sm:h-6 sm:w-6 rounded-full bg-indigo-200 text-indigo-800 font-semibold text-xs sm:text-sm mr-2 sm:mr-3 flex-shrink-0 mt-0.5">1</span>
                                            <span>Our team will review your application within 1-3 business days</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="inline-flex items-center justify-center h-5 w-5 sm:h-6 sm:w-6 rounded-full bg-indigo-200 text-indigo-800 font-semibold text-xs sm:text-sm mr-2 sm:mr-3 flex-shrink-0 mt-0.5">2</span>
                                            <span>Selected candidates will be contacted or sent an email for an initial interview</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="inline-flex items-center justify-center h-5 w-5 sm:h-6 sm:w-6 rounded-full bg-indigo-200 text-indigo-800 font-semibold text-xs sm:text-sm mr-2 sm:mr-3 flex-shrink-0 mt-0.5">3</span>
                                            <span>Final candidates will participate in a follow-up interview with the department head</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sidebar (Right Column - 1/3 width) -->
                    <div class="lg:col-span-1">                        
                        <!-- Benefits Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6 sm:mb-8" data-aos="fade-up" data-aos-delay="100">
                            <div class="p-4 sm:p-6">
                                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">
                                    <i class="fas fa-gift text-indigo-600 mr-2"></i>Benefits
                                </h3>
                                <ul class="benefits-list text-gray-600 space-y-2 sm:space-y-3">
                                    @foreach(explode("\n", $hiring->benefits) as $benefit)
                                        @if(!empty(trim($benefit)))
                                            <li class="ml-4 sm:ml-6 pl-2">{{ trim($benefit) }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Related Jobs -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                            <div class="p-4 sm:p-6">
                                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">
                                    <i class="fas fa-briefcase text-indigo-600 mr-2"></i>Similar Positions
                                </h3>
                                
                                @if($relatedJobs->count() > 0)
                                    <div class="space-y-3 sm:space-y-4">
                                        @foreach($relatedJobs as $job)
                                            @if($job->id !== $hiring->id)
                                                <a href="{{ route('careers.show', $job->slug) }}" class="block group">
                                                    <div class="p-3 sm:p-4 border border-gray-100 rounded-lg hover:border-indigo-200 hover:bg-indigo-50 transition duration-300 hover-effect">
                                                        <h4 class="font-semibold text-gray-800 group-hover:text-indigo-700 transition duration-300">{{ $job->position }}</h4>
                                                        <div class="flex items-center text-xs sm:text-sm text-gray-500 mt-1">
                                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                                            <span>{{ Str::limit($job->location, 30) }}</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-500">No similar positions available at this time.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Job Summary Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6 sm:mb-8 sticky-sidebar" data-aos="fade-up">
                            <div class="p-4 sm:p-6">
                                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">Job Summary</h3>
                                <ul class="space-y-0">
                                    <li class="job-summary-item">
                                        <div class="job-summary-icon h-8 w-8 sm:h-10 sm:w-10">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="ml-3 sm:ml-4 job-summary-content">
                                            <p class="job-summary-label">Location</p>
                                            <p class="job-summary-value">{{ $hiring->location }}</p>
                                        </div>
                                    </li>
                                    <li class="job-summary-item">
                                        <div class="job-summary-icon h-8 w-8 sm:h-10 sm:w-10">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <div class="ml-3 sm:ml-4 job-summary-content">
                                            <p class="job-summary-label">Department</p>
                                            <p class="job-summary-value">{{ $hiring->department->name }}</p>
                                        </div>
                                    </li>
                                    <li class="job-summary-item">
                                        <div class="job-summary-icon h-8 w-8 sm:h-10 sm:w-10">
                                            <i class="fas fa-briefcase"></i>
                                        </div>
                                        <div class="ml-3 sm:ml-4 job-summary-content">
                                            <p class="job-summary-label">Employment Type</p>
                                            <p class="job-summary-value">{{ $hiring->employment_type }}</p>
                                        </div>
                                    </li>
                                    <li class="job-summary-item">
                                        <div class="job-summary-icon h-8 w-8 sm:h-10 sm:w-10">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div class="ml-3 sm:ml-4 job-summary-content">
                                            <p class="job-summary-label">Posted</p>
                                            <p class="job-summary-value">{{ $hiring->created_at->format('F d, Y') }}</p>
                                        </div>
                                    </li>
                                </ul>
                                
                                <div class="mt-5 sm:mt-6 hidden lg:block">
                                    @if(Auth::guard('google')->check())
                                        <button class="w-full py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-300 flex items-center justify-center font-medium" onclick="openModal('applyModal')">
                                            <i class="fas fa-paper-plane mr-2"></i> Apply Now
                                        </button>
                                    @else
                                        <button class="w-full py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-300 flex items-center justify-center font-medium" onclick="openModal('loginModal')">
                                            <i class="fas fa-paper-plane mr-2"></i> Apply Now
                                        </button>
                                    @endif

                                    <button class="w-full mt-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-300 flex items-center justify-center font-medium print-job-details">
                                        <i class="fas fa-print mr-2"></i> Print Job Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Application Modal -->
    <div class="fixed inset-0 z-50 hidden overflow-y-auto overflow-x-hidden modal-container" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Modal backdrop -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 modal-backdrop" aria-hidden="true"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <!-- Modal content -->
            <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom bg-white rounded-lg shadow-xl transition-all transform sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6 modal-content">
                <!-- Close button -->
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" 
                        class="flex items-center justify-center w-8 h-8 text-gray-400 bg-white rounded-md hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors modal-close" 
                        id="applyModalClose"
                        onclick="document.getElementById('applyModal').classList.add('hidden'); document.body.classList.remove('overflow-hidden');"
                        aria-label="Close modal">
                        <span class="sr-only">Close</span>
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" style="pointer-events: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="pointer-events: none;" />
                        </svg>
                    </button>
                </div>
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-xl font-medium leading-6 text-gray-900 mb-4" id="applyModalLabel">
                            Apply for {{ $hiring->position }}
                        </h3>
                        <div class="mt-2">
                            <form action="{{ route('careers.apply') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <input type="hidden" name="hiring_id" value="{{ $hiring->id }}">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors" id="firstName" name="first_name" placeholder="Enter your first name" required>
                                    </div>
                                    <div>
                                        <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors" id="lastName" name="last_name" placeholder="Enter your last name" required>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors" id="email" name="email" placeholder="Enter your email address" required>
                                </div>
                                
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <input type="tel" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors" id="phone" name="phone" placeholder="Enter your phone number" required>
                                </div>
                                
                                <div>
                                    <label for="linkedin" class="block text-sm font-medium text-gray-700 mb-1">LinkedIn Profile (optional)</label>
                                    <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors" id="linkedin" name="linkedin" placeholder="Enter your LinkedIn profile URL">
                                </div>
                                
                                <div>
                                    <label for="experience" class="block text-sm font-medium text-gray-700 mb-1">Years of Experience</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors" id="experience" name="experience" required>
                                        <option value="">Select experience</option>
                                        <option value="0-1">0-1 years</option>
                                        <option value="1-3">1-3 years</option>
                                        <option value="3-5">3-5 years</option>
                                        <option value="5+">5+ years</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="resume" class="block text-sm font-medium text-gray-700 mb-1">Resume</label>
                                    <div class="mt-1 flex justify-center px-4 sm:px-6 pt-4 pb-5 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-500 transition-colors" id="resumeDropArea">
                                        <div class="space-y-1 text-center" id="resumeUploadContainer">
                                            <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600 justify-center flex-wrap">
                                                <label for="resume" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none py-1 px-1">
                                                    <span>Upload a file</span>
                                                    <input id="resume" name="resume" type="file" class="sr-only" accept=".pdf,.doc,.docx" required>
                                                </label>
                                                <p class="pl-1 py-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500" id="fileTypeHelp">PDF, DOC, or DOCX up to 10MB</p>
                                        </div>
                                        
                                        <!-- File Preview (Hidden by default) -->
                                        <div class="hidden w-full" id="resumePreview">
                                            <div class="flex items-center p-2 bg-indigo-50 rounded-md">
                                                <div class="mr-3 flex-shrink-0">
                                                    <svg class="h-8 w-8 sm:h-10 sm:w-10 text-indigo-500" id="fileIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-indigo-700 truncate" id="fileName">
                                                        resume-filename.pdf
                                                    </p>
                                                    <p class="text-xs text-indigo-500" id="fileSize">
                                                        File size: 2.3 MB
                                                    </p>
                                                </div>
                                                <div>
                                                    <button type="button" class="text-indigo-600 hover:text-indigo-900 p-2" id="removeFile">
                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="coverLetter" class="block text-sm font-medium text-gray-700 mb-1">Cover Letter (optional)</label>
                                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors" id="coverLetter" name="cover_letter" rows="4" placeholder="Enter your cover letter"></textarea>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" id="agreeTerms" name="agree_terms" required>
                                    <label class="ml-2 block text-sm text-gray-700" for="agreeTerms">I agree to the <a href="{{ route('terms') }}" class="text-indigo-600 hover:text-indigo-800">terms and conditions</a></label>
                                </div>
                                
                                <div class="pt-4 border-t border-gray-200">
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        <i class="fas fa-paper-plane mr-2"></i> Submit Application
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <div class="fixed inset-0 z-50 hidden overflow-y-auto overflow-x-hidden modal-container" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Modal backdrop -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 modal-backdrop" aria-hidden="true"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <!-- Modal content -->
            <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom bg-white rounded-lg shadow-xl transition-all transform sm:my-8 sm:align-middle max-w-xs sm:max-w-md w-full sm:p-6 modal-content">
                <!-- Close button -->
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" 
                        class="flex items-center justify-center w-8 h-8 text-gray-400 bg-white rounded-md hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors modal-close" 
                        id="loginModalClose"
                        onclick="document.getElementById('loginModal').classList.add('hidden'); document.body.classList.remove('overflow-hidden');"
                        aria-label="Close modal">
                        <span class="sr-only">Close</span>
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" style="pointer-events: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="pointer-events: none;" />
                        </svg>
                    </button>
                </div>
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-center w-full">
                        <h3 class="text-xl font-medium leading-6 text-gray-900 mb-4" id="loginModalLabel">
                            Sign In to Apply
                        </h3>
                        <div class="mt-2">
                            <div class="rounded-md bg-indigo-50 p-3 sm:p-4 mb-5 sm:mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-indigo-700">
                                            Please sign in with your Google account to view details or apply for this position.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <a href="{{ route('google.login') }}" class="w-full inline-flex justify-center items-center px-4 py-3 border border-gray-300 shadow-sm rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
                                    <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                                    <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                                    <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                                    <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
                                </svg>
                                Sign in with Google
                            </a>
                            
                            <p class="mt-4 text-xs text-gray-500">
                                By signing in, you agree to our <a href="{{ route('terms') }}" class="text-indigo-600 hover:text-indigo-800">Terms of Service</a> and <a href="{{ route('privacy') }}" class="text-indigo-600 hover:text-indigo-800">Privacy Policy</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sticky Apply Button for Mobile -->
    <div class="fixed bottom-0 left-0 right-0 bg-white p-4 shadow-md border-t border-gray-200 z-40 lg:hidden mobile-sticky-bar">
        @if(Auth::guard('google')->check())
            <button class="w-full py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-300 flex items-center justify-center font-medium" onclick="openModal('applyModal')">
                <i class="fas fa-paper-plane mr-2"></i> Apply Now
            </button>
        @else
            <button class="w-full py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-300 flex items-center justify-center font-medium" onclick="openModal('loginModal')">
                <i class="fas fa-paper-plane mr-2"></i> Apply Now
            </button>
        @endif
    </div>

    <!-- Notification Toast (Hidden by default) -->
    <div id="toast" class="fixed z-50 flex items-center transform transition-all duration-300 translate-y-full opacity-0 toast-container">
        <div class="bg-gray-800 text-white px-4 py-3 rounded-lg shadow-lg flex items-center">
            <i id="toastIcon" class="fas fa-check-circle mr-3 text-green-400"></i>
            <span id="toastMessage">Link copied to clipboard!</span>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8 sm:py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4 sm:mb-6">
                        <img src="{{ asset('vendor/adminlte/dist/img/whiteLOGO4.png') }}" alt="MHRPCI Logo" class="h-8 sm:h-10 w-auto">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold">MHRPCI</h3>
                            <p class="text-xs text-gray-400">Property Conglomerate Inc.</p>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-4 text-sm">
                        A diverse business conglomerate operating across healthcare, fuel distribution, construction, and hospitality sectors.
                    </p>
                </div>
                
                <div class="mt-4 sm:mt-0">
                    <h4 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="{{ route('welcome') }}#about" class="hover:text-indigo-400 transition duration-300">About Us</a></li>
                        <li><a href="{{ route('welcome') }}#services" class="hover:text-indigo-400 transition duration-300">Our Services</a></li>
                        <li><a href="{{ route('welcome') }}#history" class="hover:text-indigo-400 transition duration-300">Our History</a></li>
                        <li><a href="{{ route('careers') }}" class="hover:text-indigo-400 transition duration-300">MHR Careers</a></li>
                        <li><a href="{{ route('welcome') }}#contact" class="hover:text-indigo-400 transition duration-300">Contact Us</a></li>
                    </ul>
                </div>
                
                <div class="mt-4 sm:mt-0">
                    <h4 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Our Companies</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="{{ route('mhrhci') }}" class="hover:text-indigo-400 transition duration-300">MHRHCI</a></li>
                        <li><a href="{{ route('bgpdi') }}" class="hover:text-indigo-400 transition duration-300">Bay Gas</a></li>
                        <li><a href="{{ route('cio') }}" class="hover:text-indigo-400 transition duration-300">Cebic Industries</a></li>
                        <li><a href="{{ route('rcg') }}" class="hover:text-indigo-400 transition duration-300">RCG Construction</a></li>
                    </ul>
                </div>
                
                <div class="mt-4 sm:mt-0">
                    <h4 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Legal</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="{{ route('terms') }}" class="hover:text-indigo-400 transition duration-300">Terms of Service</a></li>
                        <li><a href="{{ route('privacy') }}" class="hover:text-indigo-400 transition duration-300">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-6 text-center text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} MHR Property Conglomerate Inc. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Toast Notification -->
    <div id="toast" class="fixed z-50 p-4 rounded-lg shadow-xl transform transition-all duration-300 ease-in-out translate-y-full opacity-0 flex items-center bg-white border-l-4 border-green-500 sm:max-w-md w-full" style="box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
        <svg id="toastIcon" class="w-6 h-6 mr-3 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <p id="toastMessage" class="text-sm md:text-base font-medium text-gray-800">Form submitted successfully!</p>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg p-1.5 inline-flex h-8 w-8 items-center justify-center" onclick="document.getElementById('toast').classList.add('translate-y-full', 'opacity-0');">
            <span class="sr-only">Close</span>
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
    <!-- End Toast Notification -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- AOS Animation Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add error handling wrapper
            const errorHandler = function(fn) {
                return function(...args) {
                    try {
                        return fn.apply(this, args);
                    } catch (error) {
                        console.error('Error:', error);
                        // Show user-friendly error message
                        showToast('Something went wrong. Please try again.', 'error');
                        return false;
                    }
                };
            };

            // Fix Job Summary display and scroll issues
            const fixJobSummaryDisplay = function() {
                const jobSummary = document.querySelector('.sticky-sidebar');
                if (!jobSummary) return;
                
                // Set max-height for long values to prevent overflow
                const jobSummaryValues = document.querySelectorAll('.job-summary-value');
                jobSummaryValues.forEach(value => {
                    // Check for overflow
                    if (value.scrollWidth > value.clientWidth) {
                        // Add title for hover effect showing full text
                        value.setAttribute('title', value.textContent);
                    }
                });
                
                // Adjust sticky behavior based on scroll position and screen size
                if (window.innerWidth >= 1024) {
                    const header = document.querySelector('header');
                    const headerHeight = header ? header.offsetHeight : 80;
                    
                    const handleScroll = function() {
                        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                        const topPosition = Math.max(headerHeight + 20, 100 - scrollTop);
                        jobSummary.style.top = `${topPosition}px`;
                    };
                    
                    window.addEventListener('scroll', handleScroll, { passive: true });
                    handleScroll(); // Initial call
                    
                    // Recalculate on resize
                    window.addEventListener('resize', function() {
                        if (window.innerWidth >= 1024) {
                            handleScroll();
                        } else {
                            jobSummary.style.top = '';
                        }
                    }, { passive: true });
                }
            };
            
            // Call the function on load
            fixJobSummaryDisplay();

            // Check for touch device
            const isTouchDevice = ('ontouchstart' in window) || 
                                 (navigator.maxTouchPoints > 0) || 
                                 (navigator.msMaxTouchPoints > 0);
            
            // Add touch device class to body
            if (isTouchDevice) {
                document.body.classList.add('touch-device');
            }
            
            // Initialize AOS animations with different settings for mobile
            try {
                AOS.init({
                    duration: window.innerWidth < 768 ? 600 : 800,
                    once: true,
                    offset: window.innerWidth < 768 ? 40 : 120,
                    delay: window.innerWidth < 768 ? 0 : 100,
                    disable: 'mobile' // Disable on mobile if performance issues
                });
                
                // Reload AOS on window resize to fix potential layout issues
                let resizeTimer;
                window.addEventListener('resize', function() {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(function() {
                        AOS.refresh();
                    }, 250);
                });
            } catch (e) {
                console.warn('AOS initialization failed:', e);
                // Fallback when AOS fails
                document.querySelectorAll('[data-aos]').forEach(el => {
                    el.removeAttribute('data-aos');
                    el.style.opacity = 1;
                });
            }
            
            // Improve mobile menu for accessibility
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                // Add ARIA attributes for accessibility
                mobileMenuButton.setAttribute('aria-expanded', 'false');
                mobileMenuButton.setAttribute('aria-controls', 'mobile-menu');
                mobileMenu.setAttribute('aria-hidden', 'true');
                
                mobileMenuButton.addEventListener('click', errorHandler(function(e) {
                    e.preventDefault();
                    const isExpanded = mobileMenu.classList.contains('hidden') ? false : true;
                    mobileMenu.classList.toggle('hidden');
                    document.body.classList.toggle('overflow-hidden', !mobileMenu.classList.contains('hidden'));
                    
                    // Update ARIA attributes
                    mobileMenuButton.setAttribute('aria-expanded', !isExpanded);
                    mobileMenu.setAttribute('aria-hidden', isExpanded);
                    
                    // Trap focus in mobile menu when open
                    if (!isExpanded) {
                        setTimeout(() => {
                            const firstFocusable = mobileMenu.querySelector('a, button');
                            if (firstFocusable) firstFocusable.focus();
                        }, 100);
                    }
                }));
                
                // Close mobile menu when clicking outside
                document.addEventListener('click', errorHandler(function(e) {
                    if (!mobileMenuButton.contains(e.target) && !mobileMenu.contains(e.target) && !mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                        mobileMenuButton.setAttribute('aria-expanded', 'false');
                        mobileMenu.setAttribute('aria-hidden', 'true');
                        mobileMenuButton.focus(); // Return focus to the button
                    }
                }));
            }
            
            // Handle alert closings
            const closeButtons = document.querySelectorAll('.close-alert');
            closeButtons.forEach(button => {
                button.addEventListener('click', errorHandler(function() {
                    this.parentElement.remove();
                }));
            });
            
            // Optimize preloader for better performance
            const loader = document.getElementById('loader');
            if (loader) {
                if (document.readyState === 'complete') {
                    hideLoader();
                } else {
                    window.addEventListener('load', hideLoader);
                    // Fallback in case the load event doesn't fire
                    setTimeout(hideLoader, 2000);
                }
                
                function hideLoader() {
                    try {
                        loader.classList.add('fade-out');
                        setTimeout(function() {
                            loader.style.display = 'none';
                        }, 500);
                    } catch (e) {
                        console.warn('Error hiding loader:', e);
                        // Force hide if animation fails
                        loader.style.display = 'none';
                    }
                }
            }
            
            // Enhanced share dropdown toggle with better touch support
            const shareButton = document.getElementById('shareButton');
            const shareOptions = document.getElementById('shareOptions');
            
            if (shareButton && shareOptions) {
                // Add ARIA attributes for accessibility
                shareButton.setAttribute('aria-expanded', 'false');
                shareButton.setAttribute('aria-controls', 'shareOptions');
                shareButton.setAttribute('aria-label', 'Share this job');
                shareOptions.setAttribute('aria-hidden', 'true');
                
                shareButton.addEventListener('click', errorHandler(function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const isExpanded = shareOptions.classList.contains('hidden') ? false : true;
                    shareOptions.classList.toggle('hidden');
                    
                    // Update ARIA states
                    shareButton.setAttribute('aria-expanded', !isExpanded);
                    shareOptions.setAttribute('aria-hidden', isExpanded);
                    
                    // If on mobile, make sure the dropdown is fully visible
                    if (!shareOptions.classList.contains('hidden') && window.innerWidth < 768) {
                        const rect = shareOptions.getBoundingClientRect();
                        if (rect.right > window.innerWidth) {
                            shareOptions.style.right = '0';
                            shareOptions.style.left = 'auto';
                        }
                        if (rect.bottom > window.innerHeight) {
                            shareOptions.style.bottom = '50px';
                            shareOptions.style.top = 'auto';
                        }
                        
                        // Focus first share option for keyboard navigation
                        setTimeout(() => {
                            const firstOption = shareOptions.querySelector('a');
                            if (firstOption) firstOption.focus();
                        }, 100);
                    }
                }));
                
                // Close share dropdown when clicking outside
                document.addEventListener('click', errorHandler(function(e) {
                    if (!shareButton.contains(e.target) && !shareOptions.contains(e.target)) {
                        shareOptions.classList.add('hidden');
                        shareButton.setAttribute('aria-expanded', 'false');
                        shareOptions.setAttribute('aria-hidden', 'true');
                    }
                }));
                
                // Close share dropdown on scroll (mobile optimization)
                window.addEventListener('scroll', function() {
                    if (!shareOptions.classList.contains('hidden')) {
                        shareOptions.classList.add('hidden');
                        shareButton.setAttribute('aria-expanded', 'false');
                        shareOptions.setAttribute('aria-hidden', 'true');
                    }
                }, { passive: true });
                
                // Add keyboard support for dropdown
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !shareOptions.classList.contains('hidden')) {
                        shareOptions.classList.add('hidden');
                        shareButton.setAttribute('aria-expanded', 'false');
                        shareOptions.setAttribute('aria-hidden', 'true');
                        shareButton.focus(); // Return focus to share button
                    }
                });
            }
            
            // Improved toast notification function
            window.showToast = errorHandler(function(message, type = 'success', duration = 3000) {
                const toast = document.getElementById('toast');
                const toastMessage = document.getElementById('toastMessage');
                const toastIcon = document.getElementById('toastIcon');
                
                if (!toast || !toastMessage) {
                    console.warn('Toast elements not found');
                    return false;
                }
                
                toastMessage.textContent = message;
                
                // Set icon and color based on type
                if (type === 'success') {
                    toastIcon.className = 'fas fa-check-circle mr-3 text-green-400';
                    toast.className = toast.className.replace(/border-\w+-500/g, 'border-green-500');
                } else if (type === 'error') {
                    toastIcon.className = 'fas fa-exclamation-circle mr-3 text-red-400';
                    toast.className = toast.className.replace(/border-\w+-500/g, 'border-red-500');
                } else if (type === 'info') {
                    toastIcon.className = 'fas fa-info-circle mr-3 text-blue-400';
                    toast.className = toast.className.replace(/border-\w+-500/g, 'border-blue-500');
                } else if (type === 'warning') {
                    toastIcon.className = 'fas fa-exclamation-triangle mr-3 text-yellow-400';
                    toast.className = toast.className.replace(/border-\w+-500/g, 'border-yellow-500');
                }
                
                // Set role and aria for screen readers
                toast.setAttribute('role', 'alert');
                toast.setAttribute('aria-live', 'polite');
                
                // Position the toast based on device size and ensure it's visible
                if (window.innerWidth < 768) {
                    toast.classList.add('bottom-20', 'left-4', 'right-4');
                    toast.classList.remove('bottom-4', 'right-4');
                } else {
                    toast.classList.add('bottom-4', 'right-4');
                    toast.classList.remove('bottom-20', 'left-4', 'right-4');
                }
                
                // Show toast with animation
                toast.classList.remove('translate-y-full', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
                
                // Clear any existing timeout
                if (toast.timeoutId) {
                    clearTimeout(toast.timeoutId);
                }
                
                // Hide toast after duration
                toast.timeoutId = setTimeout(function() {
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    toast.classList.add('translate-y-full', 'opacity-0');
                    
                    // Remove alert attributes after animation completes
                    setTimeout(() => {
                        toast.removeAttribute('role');
                        toast.removeAttribute('aria-live');
                    }, 500);
                }, duration);
                
                return true;
            });
            
            // Enhanced sharing functionality with mobile optimizations
            const copyLink = document.getElementById('copyLink');
            const shareTwitter = document.getElementById('shareTwitter');
            const shareLinkedIn = document.getElementById('shareLinkedIn');
            const shareFacebook = document.getElementById('shareFacebook');
            
            const pageTitle = '{{ $hiring->position }} - Job Details | MHRPCI Careers';
            const pageUrl = window.location.href;
            
            // Native Share API for mobile devices
            const shareViaAPI = errorHandler(function() {
                if (navigator.share) {
                    navigator.share({
                        title: pageTitle,
                        url: pageUrl
                    }).then(() => {
                        console.log('Shared successfully');
                    }).catch((error) => {
                        console.log('Error sharing:', error);
                        // Fall back to traditional sharing options
                        if (shareOptions) {
                            shareOptions.classList.remove('hidden');
                            shareButton.setAttribute('aria-expanded', 'true');
                            shareOptions.setAttribute('aria-hidden', 'false');
                        }
                    });
                    return true;
                }
                return false;
            });
            
            // Try native share API first on mobile
            if (shareButton && isTouchDevice && window.innerWidth < 768) {
                shareButton.addEventListener('click', errorHandler(function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    if (!shareViaAPI()) {
                        // Fall back to dropdown if native sharing is not available
                        shareOptions.classList.toggle('hidden');
                        const isExpanded = !shareOptions.classList.contains('hidden');
                        shareButton.setAttribute('aria-expanded', isExpanded);
                        shareOptions.setAttribute('aria-hidden', !isExpanded);
                    }
                }));
            }
            
            if (copyLink) {
                copyLink.addEventListener('click', errorHandler(function(e) {
                    e.preventDefault();
                    navigator.clipboard.writeText(pageUrl).then(function() {
                        showToast('Link copied to clipboard!', 'success');
                        shareOptions.classList.add('hidden');
                        shareButton.setAttribute('aria-expanded', 'false');
                        shareOptions.setAttribute('aria-hidden', 'true');
                        shareButton.focus(); // Return focus
                    }).catch(function(err) {
                        console.error('Failed to copy link:', err);
                        showToast('Failed to copy link', 'error');
                        
                        // Fallback for browsers that don't support clipboard API
                        const textArea = document.createElement('textarea');
                        textArea.value = pageUrl;
                        textArea.style.position = 'fixed';
                        textArea.style.opacity = 0;
                        document.body.appendChild(textArea);
                        textArea.focus();
                        textArea.select();
                        
                        try {
                            document.execCommand('copy');
                            showToast('Link copied to clipboard!', 'success');
                        } catch (err) {
                            console.error('Fallback copy failed:', err);
                            showToast('Could not copy link. Please copy it manually.', 'error');
                        }
                        
                        document.body.removeChild(textArea);
                    });
                }));
            }
            
            // Sharing links with accessibility improvements
            const setupSharingLink = function(element, url, windowName) {
                if (element) {
                    element.setAttribute('aria-label', `Share on ${windowName}`);
                    element.addEventListener('click', errorHandler(function(e) {
                        e.preventDefault();
                        try {
                            const shareWindow = window.open(url, `share_${windowName}`, 'width=550,height=450');
                            
                            // If popup blocked, show message
                            if (!shareWindow || shareWindow.closed || typeof shareWindow.closed === 'undefined') {
                                showToast(`Popup blocked. Please allow popups to share on ${windowName}.`, 'warning');
                            }
                            
                            shareOptions.classList.add('hidden');
                            shareButton.setAttribute('aria-expanded', 'false');
                            shareOptions.setAttribute('aria-hidden', 'true');
                            shareButton.focus(); // Return focus
                        } catch (err) {
                            console.error(`Error sharing to ${windowName}:`, err);
                            showToast(`Could not share to ${windowName}`, 'error');
                        }
                    }));
                }
            };
            
            // Setup social sharing links
            if (shareTwitter) {
                setupSharingLink(
                    shareTwitter, 
                    `https://twitter.com/intent/tweet?text=${encodeURIComponent(pageTitle)}&url=${encodeURIComponent(pageUrl)}`,
                    'Twitter'
                );
            }
            
            if (shareLinkedIn) {
                setupSharingLink(
                    shareLinkedIn,
                    `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(pageUrl)}`,
                    'LinkedIn'
                );
            }
            
            if (shareFacebook) {
                setupSharingLink(
                    shareFacebook,
                    `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pageUrl)}`,
                    'Facebook'
                );
            }

            /**********************************************
             * ENHANCED MODAL HANDLING FUNCTIONS
             **********************************************/
            // Improved modal handling with better accessibility
            window.closeModal = errorHandler(function(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    
                    // Update ARIA attributes
                    modal.setAttribute('aria-hidden', 'true');
                    
                    // Return focus to the element that opened the modal
                    const opener = document.querySelector(`[data-modal-opener="${modalId}"]`);
                    if (opener) opener.focus();
                    
                    return true;
                }
                return false;
            });
            
            // Open modal function with animations and accessibility
            window.openModal = errorHandler(function(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    // Mark the opening element
                    if (document.activeElement) {
                        document.activeElement.setAttribute('data-modal-opener', modalId);
                    }
                    
                    // Ensure all other modals are closed
                    document.querySelectorAll('.modal-container').forEach(function(m) {
                        if (m.id !== modalId) {
                            m.classList.add('hidden');
                            m.setAttribute('aria-hidden', 'true');
                        }
                    });
                    
                    modal.classList.remove('hidden');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('overflow-hidden');
                    
                    // Focus the first interactive element
                    setTimeout(function() {
                        // Try to focus the close button first for easy escape
                        const closeButton = modal.querySelector('.modal-close');
                        if (closeButton) {
                            closeButton.focus();
                        } else {
                            const firstInput = modal.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                            if (firstInput) {
                                firstInput.focus();
                            }
                        }
                    }, 100);
                    
                    return true;
                }
                return false;
            });
            
            // Setup focus trap for modals
            const trapTabKey = function(e, modal) {
                if (e.key !== 'Tab') return;
                
                const focusableElements = modal.querySelectorAll(
                    'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
                );
                
                if (focusableElements.length === 0) return;
                
                const firstElement = focusableElements[0];
                const lastElement = focusableElements[focusableElements.length - 1];
                
                // If shift+tab on first element, go to last
                if (e.shiftKey && document.activeElement === firstElement) {
                    lastElement.focus();
                    e.preventDefault();
                }
                // If tab on last element, go to first
                else if (!e.shiftKey && document.activeElement === lastElement) {
                    firstElement.focus();
                    e.preventDefault();
                }
            };
            
            // Add tabkey trapping to all modals
            document.querySelectorAll('.modal-container').forEach(function(modal) {
                modal.addEventListener('keydown', function(e) {
                    if (!modal.classList.contains('hidden')) {
                        trapTabKey(e, modal);
                    }
                });
            });
            
            // Modal backdrop click handler - enhanced for mobile and accessibility
            document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
                backdrop.addEventListener('click', errorHandler(function(e) {
                    // Only if clicking directly on the backdrop
                    if (e.target === backdrop) {
                        const modal = backdrop.closest('.modal-container');
                        if (modal) {
                            closeModal(modal.id);
                        }
                    }
                }));
            });
            
            // Close modals with escape key (with improved handling)
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const visibleModal = document.querySelector('.modal-container:not(.hidden)');
                    if (visibleModal) {
                        closeModal(visibleModal.id);
                        e.preventDefault();
                    }
                }
            });
            
            // Setup all apply now buttons to use our enhanced modal handling
            document.querySelectorAll('[data-bs-target="#applyModal"]').forEach(function(button) {
                button.addEventListener('click', errorHandler(function(e) {
                    e.preventDefault();
                    openModal('applyModal');
                }));
            });
            
            // Setup all login buttons to use our enhanced modal handling
            document.querySelectorAll('[data-bs-target="#loginModal"]').forEach(function(button) {
                button.addEventListener('click', errorHandler(function(e) {
                    e.preventDefault();
                    openModal('loginModal');
                }));
            });
            
            // Direct references to specific modal elements
            const applyModal = document.getElementById('applyModal');
            const loginModal = document.getElementById('loginModal');
            const applyModalClose = document.getElementById('applyModalClose');
            const loginModalClose = document.getElementById('loginModalClose');
            
            // Set appropriate roles and aria for modals
            if (applyModal) {
                applyModal.setAttribute('role', 'dialog');
                applyModal.setAttribute('aria-modal', 'true');
                applyModal.setAttribute('aria-labelledby', 'applyModalLabel');
                applyModal.setAttribute('aria-hidden', 'true');
            }
            
            if (loginModal) {
                loginModal.setAttribute('role', 'dialog');
                loginModal.setAttribute('aria-modal', 'true');
                loginModal.setAttribute('aria-labelledby', 'loginModalLabel');
                loginModal.setAttribute('aria-hidden', 'true');
            }
            
            // ADDITIONAL DIRECT BINDINGS FOR THE APPLY MODAL CLOSE BUTTON
            if (applyModalClose) {
                applyModalClose.setAttribute('aria-label', 'Close application form');
                applyModalClose.onclick = errorHandler(function(e) {
                    if (e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    closeModal('applyModal');
                    return false;
                });
            }
            
            // ADDITIONAL DIRECT BINDINGS FOR THE LOGIN MODAL CLOSE BUTTON
            if (loginModalClose) {
                loginModalClose.setAttribute('aria-label', 'Close login dialog');
                loginModalClose.onclick = errorHandler(function(e) {
                    if (e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    closeModal('loginModal');
                    return false;
                });
            }
            
            /********************************************
             * IMPROVED RESUME UPLOAD FUNCTIONALITY
             ********************************************/
            
            const resumeInput = document.getElementById('resume');
            const resumeUploadContainer = document.getElementById('resumeUploadContainer');
            const resumePreview = document.getElementById('resumePreview');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const fileIcon = document.getElementById('fileIcon');
            const removeFile = document.getElementById('removeFile');
            const resumeDropArea = document.getElementById('resumeDropArea');
            
            if (resumeInput && resumePreview) {
                // Improved file validation
                const validTypes = ['.pdf', '.doc', '.docx'];
                const validMimeTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                const maxSizeMB = 10;
                
                // Set appropriate ARIA attributes
                if (resumeDropArea) {
                    resumeDropArea.setAttribute('role', 'region');
                    resumeDropArea.setAttribute('aria-label', 'File upload area');
                }
                
                // Handle file selection with error handling
                resumeInput.addEventListener('change', errorHandler(function(e) {
                    validateAndDisplayFile(this.files);
                }));
                
                // Enhance drag and drop for mobile
                if (resumeDropArea) {
                    // Only setup drag and drop on non-touch devices or larger screens
                    if (!isTouchDevice || window.innerWidth >= 768) {
                        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                            resumeDropArea.addEventListener(eventName, preventDefaults, false);
                        });
                        
                        function preventDefaults(e) {
                            e.preventDefault();
                            e.stopPropagation();
                        }
                        
                        ['dragenter', 'dragover'].forEach(eventName => {
                            resumeDropArea.addEventListener(eventName, highlight, false);
                        });
                        
                        ['dragleave', 'drop'].forEach(eventName => {
                            resumeDropArea.addEventListener(eventName, unhighlight, false);
                        });
                        
                        function highlight() {
                            resumeDropArea.classList.add('border-indigo-500', 'bg-indigo-50');
                            resumeDropArea.setAttribute('aria-live', 'polite');
                            resumeDropArea.setAttribute('aria-atomic', 'true');
                        }
                        
                        function unhighlight() {
                            resumeDropArea.classList.remove('border-indigo-500', 'bg-indigo-50');
                            resumeDropArea.removeAttribute('aria-live');
                            resumeDropArea.removeAttribute('aria-atomic');
                        }
                        
                        resumeDropArea.addEventListener('drop', errorHandler(function(e) {
                            validateAndDisplayFile(e.dataTransfer.files);
                        }));
                    } else {
                        // For touch devices, make the entire area clickable
                        resumeDropArea.addEventListener('click', function() {
                            resumeInput.click();
                        });
                    }
                }
                
                // Handle remove file button with a larger touch target
                if (removeFile) {
                    removeFile.setAttribute('aria-label', 'Remove uploaded file');
                    removeFile.addEventListener('click', errorHandler(function(e) {
                        e.preventDefault();
                        resumeInput.value = '';
                        if (resumeUploadContainer) {
                            resumeUploadContainer.classList.remove('hidden');
                            resumeUploadContainer.setAttribute('aria-hidden', 'false');
                        }
                        if (resumePreview) {
                            resumePreview.classList.add('hidden');
                            resumePreview.setAttribute('aria-hidden', 'true');
                        }
                        
                        // Announce file removed for screen readers
                        const announce = document.createElement('div');
                        announce.setAttribute('aria-live', 'polite');
                        announce.className = 'sr-only';
                        announce.textContent = 'File removed';
                        document.body.appendChild(announce);
                        
                        setTimeout(() => {
                            document.body.removeChild(announce);
                        }, 1000);
                    }));
                }
                
                // Improved file validation and display
                function validateAndDisplayFile(files) {
                    if (!files || files.length === 0) return;
                    
                    const file = files[0];
                    const fileExt = '.' + file.name.split('.').pop().toLowerCase();
                    const fileMimeType = file.type;
                    
                    // Validate file type
                    if (!validTypes.includes(fileExt) && !validMimeTypes.includes(fileMimeType)) {
                        showToast(`Invalid file type. Please upload PDF, DOC, or DOCX files only.`, 'error');
                        return;
                    }
                    
                    // Validate file size
                    const fileSizeMB = file.size / (1024 * 1024);
                    if (fileSizeMB > maxSizeMB) {
                        showToast(`File too large. Maximum size is ${maxSizeMB}MB.`, 'error');
                        return;
                    }
                    
                    // Update file icon based on extension
                    updateFileIcon(fileExt);
                    
                    // Display file information
                    if (fileName) fileName.textContent = file.name;
                    if (fileSize) fileSize.textContent = `Size: ${fileSizeMB.toFixed(2)} MB`;
                    
                    // Show file preview, hide upload container
                    if (resumeUploadContainer) {
                        resumeUploadContainer.classList.add('hidden');
                        resumeUploadContainer.setAttribute('aria-hidden', 'true');
                    }
                    if (resumePreview) {
                        resumePreview.classList.remove('hidden');
                        resumePreview.setAttribute('aria-hidden', 'false');
                    }
                    
                    // Announce file uploaded for screen readers
                    const announce = document.createElement('div');
                    announce.setAttribute('aria-live', 'polite');
                    announce.className = 'sr-only';
                    announce.textContent = `File ${file.name} uploaded successfully`;
                    document.body.appendChild(announce);
                    
                    setTimeout(() => {
                        document.body.removeChild(announce);
                    }, 1000);
                }
                
                function updateFileIcon(fileExt) {
                    if (!fileIcon) return;
                    
                    if (fileExt === '.pdf') {
                        fileIcon.innerHTML = `
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            <text x="12" y="16" text-anchor="middle" font-size="7" fill="currentColor" font-weight="bold">PDF</text>
                        `;
                    } else if (fileExt === '.doc' || fileExt === '.docx') {
                        fileIcon.innerHTML = `
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            <text x="12" y="16" text-anchor="middle" font-size="5" fill="currentColor" font-weight="bold">DOC</text>
                        `;
                    }
                }
            }
            
            // Enhanced form validation with better error messages
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', errorHandler(function(e) {
                    const requiredFields = form.querySelectorAll('[required]');
                    let isValid = true;
                    let firstInvalidField = null;
                    
                    requiredFields.forEach(field => {
                        // Remove any existing error styling and messages
                        field.classList.remove('border-red-500');
                        const existingErrorMsg = field.parentNode.querySelector('.error-message');
                        if (existingErrorMsg) {
                            existingErrorMsg.remove();
                        }
                        
                        // Check if field is empty or invalid
                        let fieldInvalid = false;
                        if (field.type === 'checkbox' || field.type === 'radio') {
                            fieldInvalid = !field.checked;
                        } else if (field.type === 'email') {
                            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            fieldInvalid = !field.value.trim() || !emailRegex.test(field.value.trim());
                        } else if (field.type === 'file') {
                            fieldInvalid = !field.files || field.files.length === 0;
                        } else {
                            fieldInvalid = !field.value.trim();
                        }
                        
                        if (fieldInvalid) {
                            isValid = false;
                            field.classList.add('border-red-500');
                            
                            // Add error message with ARIA attributes
                            const errorMsg = document.createElement('p');
                            errorMsg.className = 'text-red-500 text-xs mt-1 error-message';
                            errorMsg.setAttribute('role', 'alert');
                            
                            if (field.type === 'email' && field.value.trim() !== '') {
                                errorMsg.textContent = 'Please enter a valid email address';
                            } else if (field.type === 'checkbox') {
                                errorMsg.textContent = 'This is required';
                            } else {
                                errorMsg.textContent = 'This field is required';
                            }
                            
                            // Connect error message to field with aria
                            const errorId = `error-${field.id || Math.random().toString(36).substring(2, 9)}`;
                            errorMsg.id = errorId;
                            field.setAttribute('aria-invalid', 'true');
                            field.setAttribute('aria-describedby', errorId);
                            
                            field.parentNode.insertBefore(errorMsg, field.nextSibling);
                            
                            if (!firstInvalidField) {
                                firstInvalidField = field;
                            }
                        } else {
                            field.setAttribute('aria-invalid', 'false');
                            field.removeAttribute('aria-describedby');
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                        showToast('Please fill in all required fields', 'error');
                        
                        // Scroll to the first invalid field for better UX
                        if (firstInvalidField) {
                            setTimeout(() => {
                                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                firstInvalidField.focus();
                            }, 100);
                        }
                    }
                }));
            });
            
            // Improved smooth scroll with better mobile performance
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', errorHandler(function (e) {
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        e.preventDefault();
                        
                        const headerOffset = window.innerWidth < 768 ? 80 : 100;
                        const elementPosition = targetElement.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                        
                        // Use requestAnimationFrame for smoother scrolling
                        if ('scrollBehavior' in document.documentElement.style) {
                            // Use native smooth scrolling if available
                            window.scrollTo({
                                top: offsetPosition,
                                behavior: 'smooth'
                            });
                        } else {
                            // Fallback for browsers that don't support smooth scrolling
                            window.scrollTo(0, offsetPosition);
                        }
                        
                        // Close mobile menu if open
                        if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                            mobileMenu.classList.add('hidden');
                            mobileMenuButton.setAttribute('aria-expanded', 'false');
                            mobileMenu.setAttribute('aria-hidden', 'true');
                        }
                        
                        // Focus the target for accessibility
                        targetElement.setAttribute('tabindex', '-1');
                        targetElement.focus({ preventScroll: true });
                        
                        // Remove tabindex after a delay
                        setTimeout(() => {
                            targetElement.removeAttribute('tabindex');
                        }, 1000);
                    }
                }));
            });
            
            // Detect orientation changes on mobile devices for better layout
            window.addEventListener('orientationchange', function() {
                // Refresh AOS animations
                setTimeout(() => {
                    if (typeof AOS !== 'undefined') {
                        AOS.refresh();
                    }
                }, 200);
                
                // Update toast position
                const toast = document.getElementById('toast');
                if (toast) {
                    if (window.innerWidth < 768) {
                        toast.classList.add('bottom-20', 'left-4', 'right-4');
                        toast.classList.remove('bottom-4', 'right-4');
                    } else {
                        toast.classList.add('bottom-4', 'right-4');
                        toast.classList.remove('bottom-20', 'left-4', 'right-4');
                    }
                }
            }, false);
            
            // Browser back button handling
            window.addEventListener('popstate', function(e) {
                // Close any open modals when using browser back
                const openModals = document.querySelectorAll('.modal-container:not(.hidden)');
                openModals.forEach(modal => {
                    modal.classList.add('hidden');
                    modal.setAttribute('aria-hidden', 'true');
                });
                
                // Remove body overflow
                document.body.classList.remove('overflow-hidden');
            });
            
            // Print button functionality
            const printButtons = document.querySelectorAll('.print-job-details');
            printButtons.forEach(button => {
                button.addEventListener('click', errorHandler(function(e) {
                    e.preventDefault();
                    window.print();
                }));
            });
        });
    </script>
</body>
</html>
