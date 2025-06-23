<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cebic Industries</title>
    <link rel="icon" type="image/png" href="{{ asset('vendor/adminlte/dist/img/cebic.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center space-x-3">
                        <img src="{{ asset('vendor/adminlte/dist/img/cebic.png') }}" alt="CIO Logo" class="h-12 w-auto hover:opacity-90 transition-opacity duration-300">
                        <span class="font-bold text-2xl text-blue-600 hover:text-blue-700 transition-colors duration-300">Cebic Industries</span>
                    </div>
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="#home" class="group relative inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors duration-300">
                            <span>Home</span>
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                        </a>
                        <a href="#services" class="group relative inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors duration-300">
                            <span>Services</span>
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                        </a>
                        <a href="#products" class="group relative inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors duration-300">
                            <span>Products</span>
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                        </a>
                        <a href="#about" class="group relative inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors duration-300">
                            <span>About</span>
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                        </a>
                    </div>
                </div>
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button type="button" onclick="toggleMobileMenu()" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-blue-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden hidden" id="mobileMenu">
            <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t">
                <a href="#home" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Home</a>
                <a href="#services" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Services</a>
                <a href="#products" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Products</a>
                <a href="#about" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">About</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div id="home" class="pt-16">
        <div class="relative bg-gradient-to-r from-blue-600 to-blue-800 text-white py-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold mb-6">Welcome to Cebic Industries</h1>
                        <p class="text-xl mb-8">Your trusted partner in medical supplies and healthcare solutions since 1995.</p>
                        <div class="flex space-x-4">
                            <button onclick="openModal()" class="border-2 border-white text-white px-6 py-3 rounded-lg hover:bg-white hover:text-blue-600 transition duration-300">
                                Learn More
                            </button>
                        </div>
                    </div>
                    <div class="hidden md:block relative h-96">
                        <!-- Background overlay image -->
                        <img src="{{ asset('vendor/adminlte/dist/img/cebic.png') }}" alt="Background Overlay" class="absolute w-full h-full object-cover opacity-10 z-0">
                        <!-- Slideshow images -->
                        <img src="{{ asset('vendor/adminlte/dist/img/cio/medicalequipment1.png') }}" alt="Medical Equipment" class="slideshow-image absolute w-full h-full object-contain opacity-100 transition-opacity duration-1000 z-10">
                        <img src="{{ asset('vendor/adminlte/dist/img/cio/sw100.png') }}" alt="Sterilwave 100" class="slideshow-image absolute w-full h-full object-contain p-4 opacity-0 transition-opacity duration-1000 z-10">
                        <img src="{{ asset('vendor/adminlte/dist/img/cio/sw250.png') }}" alt="Sterilwave 250" class="slideshow-image absolute w-full h-full object-contain p-4 opacity-0 transition-opacity duration-1000 z-10">
                        <img src="{{ asset('vendor/adminlte/dist/img/cio/sw440.png') }}" alt="Sterilwave 440" class="slideshow-image absolute w-full h-full object-contain p-4 opacity-0 transition-opacity duration-1000 z-10">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Section -->
    <section id="services" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-4">Our Services</h2>
            <p class="text-gray-600 text-center mb-12 max-w-3xl mx-auto">Comprehensive medical supply solutions for healthcare institutions.</p>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:transform hover:scale-105">
                    <div class="p-6">
                        <i class="fas fa-hospital text-blue-600 text-3xl mb-4"></i>
                        <h3 class="text-xl font-semibold mb-2">Medical Equipment</h3>
                        <p class="text-gray-600">Comprehensive range of medical equipment.</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:transform hover:scale-105">
                    <div class="p-6">
                        <i class="fas fa-truck text-blue-600 text-3xl mb-4"></i>
                        <h3 class="text-xl font-semibold mb-2">Distribution Services</h3>
                        <p class="text-gray-600">Reliable delivery and supply chain solutions.</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:transform hover:scale-105">
                    <div class="p-6">
                        <i class="fas fa-comments text-blue-600 text-3xl mb-4"></i>
                        <h3 class="text-xl font-semibold mb-2">Consultation Services</h3>
                        <p class="text-gray-600">Expert guidance on medical equipment solutions.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- Featured Products Section -->
<section id="products" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-4">Featured Products</h2>
            <p class="text-gray-600 text-center mb-12 max-w-3xl mx-auto">Discover our comprehensive range of medical equipment and supplies, designed to meet the highest standards of quality and performance.</p>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:transform hover:scale-105 hover:shadow-2xl">
                    <img src="{{ asset('vendor/adminlte/dist/img/cio/medicalequipment1.png') }}" alt="Medical Supplies" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Medical Equipment</h3>
                        <p class="text-gray-600 mb-4">Comprehensive range of medical equipment and supplies.</p>
                        <a href="{{ route('medical_equipment') }}" class="mt-4 w-full bg-gray-50 text-blue-600 font-medium py-2 rounded-lg hover:bg-blue-600 hover:text-white transition duration-300 inline-block text-center">
                            Learn More →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold mb-6">Our Legacy</h2>
                    <div class="space-y-4">
                        <p class="text-gray-600">Cebic Trading is the pioneering business that established the foundation for MHR Properties Conglomerate, Inc. (MHRPCI). Since our inception, we have been a leading provider of hospital and office medical supplies in the region.</p>
                        <p class="text-gray-600">What began as a focused medical supply business has evolved into a comprehensive solution provider, expanding our product range while maintaining our core commitment to serving healthcare institutions with excellence.</p>
                        <p class="text-gray-600">Our longstanding relationships with healthcare providers and commitment to quality have made us a trusted name in the industry. We continue to grow and adapt to meet the evolving needs of our clients while maintaining the highest standards of service and reliability.</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <img src="{{ asset('vendor/adminlte/dist/img/frontmhrhci.jpg') }}" alt="Our Facility" class="rounded-lg shadow-lg">
                    <img src="{{ asset('vendor/adminlte/dist/img/hci/medicalequipment1.png') }}" alt="Our Warehouse" class="rounded-lg shadow-lg mt-8">
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">About Us</h3>
                    <p class="text-gray-400">Leading provider of medical supplies and healthcare solutions, serving the community since 1995.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#services" class="hover:text-white">Services</a></li>
                        <li><a href="#about" class="hover:text-white">About Us</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-map-marker-alt w-6"></i> Cebu City, Philippines</li>
                        <li><i class="fas fa-phone w-6"></i> Contact MHRPCI</li>
                        <li><i class="fas fa-envelope w-6"></i> info@mhrpci.ph</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Cebic Industries. All rights reserved.</p>
                <p class="mt-2 text-sm">A subsidiary of MHR Properties Conglomerate, Inc.</p>
            </div>
        </div>
    </footer>

<!-- Add this script at the end of the body tag -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all navigation links
        const navLinks = document.querySelectorAll('nav a');
        
        // Add click handler for smooth scrolling
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Highlight active section on scroll
        window.addEventListener('scroll', function() {
            let current = '';
            const sections = document.querySelectorAll('section, #home');
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= (sectionTop - 200)) {
                    current = '#' + section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('text-blue-600');
                if (link.getAttribute('href') === current) {
                    link.classList.add('text-blue-600');
                }
            });
        });

        // Add reveal on scroll functionality
        function reveal() {
            const reveals = document.querySelectorAll('.reveal');
            
            reveals.forEach(element => {
                const windowHeight = window.innerHeight;
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;
                
                if (elementTop < windowHeight - elementVisible) {
                    element.classList.add('active');
                }
            });
        }

        // Add reveal class to sections you want to animate
        document.querySelectorAll('section').forEach(section => {
            section.classList.add('reveal');
        });

        // Listen for scroll events
        window.addEventListener('scroll', reveal);
        
        // Trigger initial reveal
        reveal();
    });
    </script>

    <!-- Modal -->
    <div id="learnMoreModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <!-- Modal backdrop -->
            <div class="fixed inset-0 bg-black opacity-50" onclick="closeModal()"></div>
            
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 animate-modal">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-2xl font-bold text-gray-900">About Cebic Trading</h2>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="prose max-w-none">
                        <p class="text-gray-600 leading-relaxed mb-4">
                            As the original business of MHRPCI, Cebic Trading has built a strong reputation in medical supplies distribution since its establishment.
                        </p>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            With decades of experience in healthcare supplies, our team provides expert guidance and reliable service to medical institutions.
                        </p>
                        <p class="text-gray-600 leading-relaxed">
                            We maintain a strong presence in the healthcare supply chain, serving hospitals, clinics, and medical offices throughout the region.
                        </p>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 rounded-b-lg">
                    <button onclick="closeModal()" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .animate-modal {
            animation: modalFade 0.3s ease-out;
        }
        
        @keyframes modalFade {
            from {
                opacity: 0;
                transform: translateY(-1rem);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Add new scroll reveal animations */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        html {
            scroll-behavior: smooth;
        }

        /* Add new slideshow styles */
        .slideshow-image {
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .slideshow-image.active {
            opacity: 1;
        }
    </style>

    <script>
        function openModal() {
            document.getElementById('learnMoreModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('learnMoreModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
        }

        // Close mobile menu when clicking on a link
        document.querySelectorAll('#mobileMenu a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobileMenu').classList.add('hidden');
            });
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            const mobileMenu = document.getElementById('mobileMenu');
            const hamburgerButton = document.querySelector('button[onclick="toggleMobileMenu()"]');
            
            if (!mobileMenu.contains(e.target) && !hamburgerButton.contains(e.target)) {
                mobileMenu.classList.add('hidden');
            }
        });

        function startSlideshow() {
            const images = document.querySelectorAll('.slideshow-image');
            let currentImageIndex = 0;
            
            // Show first image immediately
            images[0].classList.add('active');

            setInterval(() => {
                // Remove active class from current image
                images[currentImageIndex].classList.remove('active');
                
                // Move to next image
                currentImageIndex = (currentImageIndex + 1) % images.length;
                
                // Add active class to next image
                images[currentImageIndex].classList.add('active');
            }, 5000); // Change image every 5 seconds to allow for smooth transitions
        }

        // Start the slideshow when the page loads
        document.addEventListener('DOMContentLoaded', startSlideshow);
    </script>
</body>
</html>
