<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical & Hospital Resources Health Care, Inc. | Welcome to MHRHCI</title>
    <meta name="description" content="Medical & Hospital Resources Health Care, Inc. (MHRHCI) - A leading distributor of medical and hospital supplies in the Philippines, serving healthcare facilities across the nation with high-quality medical products and equipment.">
    <meta name="keywords" content="MHRHCI, Medical & Hospital Resources Health Care, Inc., MHRHCI, Medical & Hospital Resources Health Care, Inc.">
    <link rel="icon" type="image/png" href="{{ asset('vendor/adminlte/dist/img/mhrhci.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center space-x-3">
                        <img src="{{ asset('vendor/adminlte/dist/img/mhrhci.png') }}" alt="MHRHCI Logo" class="h-12 w-auto hover:opacity-90 transition-opacity duration-300">
                        <span class="font-bold text-2xl text-blue-600 hover:text-blue-700 transition-colors duration-300">MHRHCI</span>
                    </div>
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="#home" class="group relative inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors duration-300">
                            <span>Home</span>
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
                        <a href="#contact" class="group relative inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors duration-300">
                            <span>Contact</span>
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                        </a>
                    </div>
                </div>
                <div class="md:hidden flex items-center">
                    <button type="button" onclick="toggleMobileMenu()" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-blue-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-600">
                        <span class="sr-only">Open main menu</span>
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden hidden" id="mobileMenu">
            <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t">
                <a href="#home" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Home</a>
                <a href="#products" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Products</a>
                <a href="#about" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">About</a>
                <a href="#contact" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div id="home" class="pt-16">
        <div class="relative bg-gradient-to-r from-blue-600 to-blue-800 text-white py-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold mb-6">Medical & Hospital Resources Health Care, Inc.</h1>
                        <p class="text-xl mb-8">A leading distributor of medical and hospital supplies across the Philippines, serving healthcare facilities in Cebu, Bicol, Iloilo, and Manila.</p>
                        <div class="flex space-x-4">
                            <button onclick="openModal()" class="border-2 border-white text-white px-6 py-3 rounded-lg hover:bg-white hover:text-blue-600 transition duration-300">
                                Learn More
                            </button>
                        </div>
                    </div>
                    <div class="hidden md:block relative h-96">
                        <!-- Background overlay image -->
                        <img src="{{ asset('vendor/adminlte/dist/img/mhrhci.png') }}" alt="Background Overlay" class="absolute w-full h-full object-cover opacity-10 z-0">
                        <!-- Slideshow images -->
                        @foreach($featuredProducts as $product)
                            <img src="{{ Storage::url($product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="slideshow-image absolute w-full h-full object-contain {{ $loop->first ? 'opacity-100' : 'opacity-0' }} transition-opacity duration-1000 z-10"
                                 title="{{ $product->name }} - {{ $product->category->name }}">
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-b from-transparent to-white opacity-20"></div>
        </div>
    </div>

    <!-- Featured Products Section -->
    <section id="products" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-4">Featured Products</h2>
            <p class="text-gray-600 text-center mb-12 max-w-3xl mx-auto">Discover our comprehensive range of medical equipment and supplies, designed to meet the highest standards of quality and performance.</p>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:transform hover:scale-105 hover:shadow-2xl">
                    <img src="{{ asset('vendor/adminlte/dist/img/img/medicalproducts1.png') }}" alt="Medical Supplies" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Medical Products</h3>
                        <p class="text-gray-600 mb-4">Essential medical products including PPE, wound care products, and disposable medical items.</p>
                        <a href="{{ route('medical_products') }}" class="mt-4 w-full bg-gray-50 text-blue-600 font-medium py-2 rounded-lg hover:bg-blue-600 hover:text-white transition duration-300 inline-block text-center">
                            View Products →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold mb-6">Why Choose MHRHCI?</h2>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold mb-2">Nationwide Network</h3>
                                <p class="text-gray-600">Extensive coverage across the Philippines with presence in Cebu, Bicol, Iloilo, and Manila.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <i class="fas fa-warehouse text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold mb-2">Modern Facilities</h3>
                                <p class="text-gray-600">State-of-the-art warehousing facilities ensuring product quality and efficient distribution.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <i class="fas fa-heart text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold mb-2">Dedicated Service</h3>
                                <p class="text-gray-600">Committed team providing excellent service to healthcare institutions nationwide.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <img src="{{ asset('vendor/adminlte/dist/img/frontmhrhci.jpg') }}" alt="Medical Facility" class="rounded-lg shadow-lg">
                    <img src="{{ asset('vendor/adminlte/dist/img/img/medicalproducts1.png') }}" alt="Medical Products" class="rounded-lg shadow-lg mt-8">
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-blue-600 text-white p-12">
                    <h2 class="text-3xl font-bold mb-6">Get in Touch</h2>
                    <p class="mb-8">Need assistance with medical equipment or supplies? Our team is here to help.</p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt w-6"></i>
                            <span class="ml-4">MHR Building: Jose L. Briones St., NRA, Cebu City, Philippines, 6000</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone w-6"></i>
                            <span class="ml-4">+63-933-816-8717</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope w-6"></i>
                            <span class="ml-4">csr.mhrhealthcare@gmail.com</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">About Us</h3>
                    <p class="text-gray-400">Leading provider of medical equipment and supplies, serving healthcare facilities worldwide.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#products" class="hover:text-white">Products</a></li>
                        <li><a href="#about" class="hover:text-white">About Us</a></li>
                        <li><a href="#contact" class="hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Products</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('medical_products') }}" class="hover:text-white">Medical Supplies</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Connect With Us</h3>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/mhrhci" target="_blank" class="text-gray-400 hover:text-white"><i class="fab fa-facebook"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Medical & Hospital Resources Health Care, Inc. All rights reserved.</p>
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
                        <h2 class="text-2xl font-bold text-gray-900">About MHRHCI</h2>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="prose max-w-none">
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Medical & Hospital Resources Health Care, Inc. (MHRHCI) is a leading distributor of medical and hospital supplies in the Philippines. With our extensive network spanning Cebu, Bicol, Iloilo, and Manila, we serve healthcare facilities across the nation with high-quality medical products and equipment.
                        </p>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Since our establishment, we have grown into a reliable partner in the healthcare industry, serving hospitals, clinics, and medical facilities. Our commitment to quality and reliability has made us the preferred choice for healthcare institutions seeking dependable medical supply solutions.
                        </p>
                        <p class="text-gray-600 leading-relaxed">
                            With our modern warehousing facilities and dedicated team, we maintain the highest standards of product quality and service excellence. Our passion for healthcare and commitment to patient care has helped us build a strong reputation in the medical supply industry.
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
