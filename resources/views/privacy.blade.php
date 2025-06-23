<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy | MHR Property Conglomerate Inc.</title>
    <meta name="description" content="Privacy Policy for MHR Property Conglomerate Inc. (MHRPCI) - Learn how we protect and handle your personal information.">
    <meta name="keywords" content="MHRPCI privacy policy, data protection, personal information, privacy rights">
    
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
                <h1 class="text-white text-4xl md:text-5xl font-bold leading-tight mb-6" data-aos="fade-up">Privacy Policy</h1>
                <p class="text-indigo-100 text-lg mb-8 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                    Learn how MHR Property Conglomerate Inc. protects and handles your personal information across our diverse portfolio of companies.
                </p>
            </div>
        </div>
    </section>

    <!-- Privacy Policy Content -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <!-- Information Collection -->
                <div class="mb-16" data-aos="fade-up">
                    <h2 class="text-2xl font-bold text-indigo-700 mb-6">1. Information We Collect</h2>
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                        <p class="text-gray-600 mb-4">At MHR Property Conglomerates, Inc. (MHRPCI), we collect information necessary for our recruitment and business operations across our diverse portfolio of companies, including:</p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Personal identification information (Name, email address, phone number, address)</li>
                            <li>Professional credentials (Resume, work history, education, certifications, licenses)</li>
                            <li>Employment eligibility documentation</li>
                            <li>References and background check information</li>
                            <li>Technical information when you use our career portal (IP address, browser type, device information)</li>
                        </ul>
                    </div>
                </div>

                <!-- Information Usage -->
                <div class="mb-16" data-aos="fade-up" data-aos-delay="100">
                    <h2 class="text-2xl font-bold text-indigo-700 mb-6">2. How We Use Your Information</h2>
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                        <p class="text-gray-600 mb-4">As a conglomerate operating across multiple industries including healthcare, hospitality, petroleum, construction, and pharmaceuticals, we use your information to:</p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Process applications across our subsidiary companies (MHRHCI, VHI, BGPDI, MAX, MHRCONS, CIO, LUSCO, RCG)</li>
                            <li>Match candidates with appropriate positions within our group of companies</li>
                            <li>Conduct necessary background checks and verification processes</li>
                            <li>Communicate about opportunities within any of our subsidiary companies</li>
                            <li>Maintain and improve our recruitment processes</li>
                            <li>Comply with Philippine labor laws and regulations</li>
                        </ul>
                    </div>
                </div>

                <!-- Information Sharing -->
                <div class="mb-16" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="text-2xl font-bold text-indigo-700 mb-6">3. Information Sharing and Disclosure</h2>
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                        <p class="text-gray-600 mb-4">As a conglomerate with multiple subsidiaries, we may share your information:</p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Among our subsidiary companies for relevant job opportunities</li>
                            <li>With our HR service providers and recruitment partners</li>
                            <li>With background checking agencies (with your consent)</li>
                            <li>With government authorities as required by Philippine law</li>
                        </ul>
                    </div>
                </div>

                <!-- Data Security -->
                <div class="mb-16" data-aos="fade-up" data-aos-delay="300">
                    <h2 class="text-2xl font-bold text-indigo-700 mb-6">4. Data Security</h2>
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                        <p class="text-gray-600">We implement industry-standard security measures to protect the confidentiality and integrity of your personal information. These measures include encryption, secure servers, and regular security audits.</p>
                    </div>
                </div>

                <!-- Your Rights -->
                <div class="mb-16" data-aos="fade-up" data-aos-delay="400">
                    <h2 class="text-2xl font-bold text-indigo-700 mb-6">5. Your Rights</h2>
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                        <p class="text-gray-600 mb-4">You have the right to:</p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Access your personal information</li>
                            <li>Correct inaccuracies in your personal information</li>
                            <li>Delete your personal information</li>
                            <li>Object to the processing of your personal information</li>
                            <li>Request a copy of your personal information</li>
                        </ul>
                        <p class="text-gray-600 mt-4">To exercise these rights, please contact us using the information provided at the end of this policy.</p>
                    </div>
                </div>

                <!-- Policy Changes -->
                <div class="mb-16" data-aos="fade-up" data-aos-delay="500">
                    <h2 class="text-2xl font-bold text-indigo-700 mb-6">6. Changes to This Policy</h2>
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                        <p class="text-gray-600">We may update this privacy policy from time to time to reflect changes in our practices or for other operational, legal, or regulatory reasons. We will notify you of any material changes by posting the new privacy policy on this page and updating the "Last Updated" date.</p>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="mb-16" data-aos="fade-up" data-aos-delay="600">
                    <h2 class="text-2xl font-bold text-indigo-700 mb-6">7. Contact Information</h2>
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm">
                        <p class="text-gray-600 mb-4">For privacy-related inquiries or to exercise your rights, please contact us at:</p>
                        <div class="text-gray-600">
                            <p>MHR Property Conglomerates, Inc.</p>
                            <p>MHR Building, Jose L. Briones St.,</p>
                            <p>North Reclamation Area, Cebu City,</p>
                            <p>Cebu, Philippines 6000</p>
                            <p>Phone: (032) 238-1887</p>
                            <p>Email: {{ config('app.company_email') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Last Updated -->
                <div class="text-center text-gray-500 italic" data-aos="fade-up" data-aos-delay="700">
                    <p>Last Updated: {{ date('F d, Y') }}</p>
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
