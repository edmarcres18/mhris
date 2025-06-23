<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>All Subsidiaries & Services | MHR Property Conglomerate Inc.</title>
    <meta name="description" content="Explore all subsidiaries and services of MHR Property Conglomerate Inc. (MHRPCI) - A diverse business conglomerate operating across multiple sectors in the Philippines.">
    <meta name="keywords" content="MHRPCI subsidiaries, MHR services, healthcare, fuel distribution, construction, hospitality, Cebu business, medical supplies">
    
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
        
        .service-card:hover {
            transform: translateY(-5px);
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
                    <a href="{{ route('welcome') }}#services" class="nav-link text-indigo-600 hover:text-indigo-600 font-medium transition duration-300 border-b-2 border-indigo-600">Our Services</a>
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
                <a href="{{ route('welcome') }}#services" class="block py-2 px-4 text-indigo-600 hover:bg-gray-50 font-medium">Our Services</a>
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
                <h2 class="text-white text-4xl md:text-5xl font-bold leading-tight mb-6" data-aos="fade-up">Our Subsidiaries & Services</h2>
                <p class="text-indigo-100 text-lg mb-8 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                    Explore the diverse portfolio of companies and services that make up MHR Property Conglomerate Inc., spanning across healthcare, fuel distribution, construction, and hospitality sectors.
                </p>
                <div class="flex justify-center" data-aos="fade-up" data-aos-delay="300">
                    <a href="#subsidiaries" class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition duration-300 inline-flex items-center">
                        View All Subsidiaries <i class="fas fa-arrow-down ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- All Subsidiaries & Services Section -->
    <section id="subsidiaries" class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4" data-aos="fade-up">Our Subsidiaries</h2>
                <div class="w-20 h-1 bg-indigo-600 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                    Discover the diverse portfolio of companies that make up MHR Property Conglomerates, Inc., each contributing to our vision of excellence and innovation.
                </p>
            </div>

            <!-- Subsidiaries Grid -->
            <div class="grid md:grid-cols-2 gap-8 mb-16">
                <!-- Cebic Industries -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden service-card transition-all duration-300" data-aos="fade-up">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-2/5 bg-indigo-50 p-6 flex items-center justify-center">
                            <img src="{{ asset('vendor/adminlte/dist/img/cebic.png') }}" alt="Cebic Industries" class="h-32 w-auto">
                        </div>
                        <div class="md:w-3/5 p-6">
                            <h4 class="text-xl font-semibold mb-3 text-gray-800">Cebic Industries OPC</h4>
                            <p class="text-sm text-indigo-600 mb-2">Cebu, Philippines</p>
                            <p class="text-gray-600 mb-4">
                                Cebic Trading is the original business that laid the foundation for MHRPCI. Initially focused on hospital and medical supplies distribution, it has grown into a key player in the healthcare industry.
                            </p>
                            <a href="{{ route('cio') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-indigo-700 transition duration-300 inline-flex items-center">
                                View Website
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- MHRHCI -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden service-card transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-2/5 bg-indigo-50 p-6 flex items-center justify-center">
                            <img src="{{ asset('vendor/adminlte/dist/img/mhrhci.png') }}" alt="MHRHCI" class="h-32 w-auto">
                        </div>
                        <div class="md:w-3/5 p-6">
                            <h4 class="text-xl font-semibold mb-3 text-gray-800">Medical & Hospital Resources Health Care, Inc.</h4>
                            <p class="text-sm text-indigo-600 mb-2">Cebu, Philippines</p>
                            <p class="text-gray-600 mb-4">
                                MHRHCI specializes in the importation and distribution of medical supplies and equipment, serving healthcare institutions across the Philippines with a commitment to quality and reliability.
                            </p>
                            <a href="{{ route('mhrhci') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-indigo-700 transition duration-300 inline-flex items-center">
                                View Website
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- BGPDI -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden service-card transition-all duration-300" data-aos="fade-up">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-2/5 bg-indigo-50 p-6 flex items-center justify-center">
                            <img src="{{ asset('vendor/adminlte/dist/img/bgpdi.png') }}" alt="BGPDI" class="h-32 w-auto">
                        </div>
                        <div class="md:w-3/5 p-6">
                            <h4 class="text-xl font-semibold mb-3 text-gray-800">Bay Gas Petroleum Distribution Inc.</h4>
                            <p class="text-sm text-indigo-600 mb-2">Philippines</p>
                            <p class="text-gray-600 mb-4">
                                Founded in 2015, BGPDI started as a small fuel distribution company and has grown into a significant player in the energy sector, providing reliable fuel solutions across the Philippines.
                            </p>
                            <a href="{{ route('bgpdi') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-indigo-700 transition duration-300 inline-flex items-center">
                                View Website
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- VHI -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden service-card transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-2/5 bg-indigo-50 p-6 flex items-center justify-center">
                            <img src="{{ asset('vendor/adminlte/dist/img/vhi.png') }}" alt="VHI" class="h-32 w-auto">
                        </div>
                        <div class="md:w-3/5 p-6">
                            <h4 class="text-xl font-semibold mb-3 text-gray-800">Valued Healthcare Innovations</h4>
                            <p class="text-sm text-indigo-600 mb-2">Philippines</p>
                            <p class="text-gray-600 mb-4">
                                VHI focuses on providing innovative solutions for the healthcare industry, specializing in cutting-edge medical technologies and services that enhance patient care.
                            </p>
                            <a href="{{ route('vhi') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-indigo-700 transition duration-300 inline-flex items-center">
                                View Website
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- MAX -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden service-card transition-all duration-300" data-aos="fade-up">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-2/5 bg-indigo-50 p-6 flex items-center justify-center">
                            <img src="{{ asset('vendor/adminlte/dist/img/max.png') }}" alt="MAX" class="h-32 w-auto">
                        </div>
                        <div class="md:w-3/5 p-6">
                            <h4 class="text-xl font-semibold mb-3 text-gray-800">Max Hauling and Logistics</h4>
                            <p class="text-sm text-indigo-600 mb-2">Philippines</p>
                            <p class="text-gray-600 mb-4">
                                MAX was born out of necessity during the pandemic, turning a challenge into an opportunity by providing essential logistics and hauling services across the country.
                            </p>
                            <a href="{{ route('max') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-indigo-700 transition duration-300 inline-flex items-center">
                                View Website
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- RCG Pharmaceutical -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden service-card transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-2/5 bg-indigo-50 p-6 flex items-center justify-center">
                            <img src="{{ asset('vendor/adminlte/dist/img/rcg.png') }}" alt="RCG" class="h-32 w-auto">
                        </div>
                        <div class="md:w-3/5 p-6">
                            <h4 class="text-xl font-semibold mb-3 text-gray-800">RCG Pharmaceutical</h4>
                            <p class="text-sm text-indigo-600 mb-2">Philippines</p>
                            <p class="text-gray-600 mb-4">
                                RCG is an investment arm under MHRPCI, responsible for managing the conglomerate's financial assets and pharmaceutical ventures, ensuring sustainable growth and development.
                            </p>
                            <a href="{{ route('rcg') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-indigo-700 transition duration-300 inline-flex items-center">
                                View Website
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Luscious Co. -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden service-card transition-all duration-300" data-aos="fade-up">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-2/5 bg-indigo-50 p-6 flex items-center justify-center">
                            <img src="{{ asset('vendor/adminlte/dist/img/lus.png') }}" alt="Luscious Co" class="h-32 w-auto">
                        </div>
                        <div class="md:w-3/5 p-6">
                            <h4 class="text-xl font-semibold mb-3 text-gray-800">Luscious Co.</h4>
                            <p class="text-sm text-indigo-600 mb-2">Cebu, Philippines</p>
                            <p class="text-gray-600 mb-4">
                                Luscious Co. operates in the food and hospitality sector, offering high-quality dining experiences and catering services with a focus on customer satisfaction and culinary excellence.
                            </p>
                            <a href="{{ route('lus') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-indigo-700 transition duration-300 inline-flex items-center">
                                View Website
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- MHR Construction -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden service-card transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-2/5 bg-indigo-50 p-6 flex items-center justify-center">
                            <img src="{{ asset('vendor/adminlte/dist/img/mhrconstruction.jpg') }}" alt="MHR Construction" class="h-32 w-auto">
                        </div>
                        <div class="md:w-3/5 p-6">
                            <h4 class="text-xl font-semibold mb-3 text-gray-800">MHR Construction</h4>
                            <p class="text-sm text-indigo-600 mb-2">Philippines</p>
                            <p class="text-gray-600 mb-4">
                                MHR Construction handles various infrastructure projects, including the development of commercial, residential, and industrial properties, with a commitment to quality and innovation.
                            </p>
                            <a href="{{ route('mhrcons') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-indigo-700 transition duration-300 inline-flex items-center">
                                View Website
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back to Services Button -->
            <div class="text-center mt-16" data-aos="fade-up">
                <a href="{{ route('welcome') }}#services" class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-medium hover:bg-indigo-700 transition duration-300 inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Services Overview
                </a>
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
