<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bay Gas Petroleum Distribution Inc.</title>
    <link rel="icon" type="image/png" href="{{ asset('vendor/adminlte/dist/img/bgpdi.png') }}">
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
                        <img src="{{ asset('vendor/adminlte/dist/img/bgpdi.png') }}" alt="BGPDI Logo" class="h-12 w-auto hover:opacity-90 transition-opacity duration-300">
                        <span class="font-bold text-2xl text-blue-600 hover:text-blue-700 transition-colors duration-300">BGPDI</span>
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
        <div class="relative bg-gradient-to-r from-blue-600 to-yellow-300 text-white py-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold mb-6">Bay Gas Petroleum Distribution Inc.</h1>
                        <p class="text-xl mb-8">Your trusted fuel station in Brgy. Cansaga, Consolacion, Cebu. Providing quality petroleum products and convenience store services 24/7.</p>
                        <div class="flex space-x-4">
                            <button onclick="openModal()" class="border-2 border-white text-white px-6 py-3 rounded-lg hover:bg-white hover:text-blue-600 transition duration-300">
                                Learn More
                            </button>
                        </div>
                    </div>
                    <div class="hidden md:block relative h-96">
                        <!-- Background overlay image -->
                        <img src="{{ asset('vendor/adminlte/dist/img/bgpdi.png') }}" alt="Background Overlay" class="absolute w-full h-full object-contain opacity-10 z-0 transform scale-100">
                        <!-- Slideshow images -->
                        <img src="{{ asset('vendor/adminlte/dist/img/bgpdi/station.jpg') }}" alt="Gas Station" class="slideshow-image absolute w-full h-full object-contain opacity-100 transition-opacity duration-1000 z-10">
                        <img src="{{ asset('vendor/adminlte/dist/img/bgpdi/store.jpg') }}" alt="My Store" class="slideshow-image absolute w-full h-full object-contain opacity-0 transition-opacity duration-1000 z-10">
                        <img src="{{ asset('vendor/adminlte/dist/img/bgpdi/pumps.jpg') }}" alt="Fuel Pumps" class="slideshow-image absolute w-full h-full object-contain opacity-0 transition-opacity duration-1000 z-10">
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-b from-transparent to-white opacity-20"></div>
        </div>
    </div>

    <!-- Featured Services Section -->
    <section id="products" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-4">Our Services</h2>
            <p class="text-gray-600 text-center mb-12 max-w-3xl mx-auto">Quality fuel products and convenient store services to meet all your needs.</p>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:transform hover:scale-105 hover:shadow-2xl">
                    <img src="{{ asset('vendor/adminlte/dist/img/bgpdi/fuel.jpg') }}" alt="Fuel Services" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Fuel Services</h3>
                        <p class="text-gray-600 mb-4">High-quality petroleum products for both private and commercial vehicles. 24/7 availability.</p>
                        <!-- <button class="mt-4 w-full bg-gray-50 text-blue-600 font-medium py-2 rounded-lg hover:bg-blue-600 hover:text-white transition duration-300">
                            View Products →
                        </button> -->
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:transform hover:scale-105 hover:shadow-2xl">
                    <img src="{{ asset('vendor/adminlte/dist/img/bgpdi/store.jpg') }}" alt="My Store" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">My Store</h3>
                        <p class="text-gray-600 mb-4">Your one-stop convenience store for snacks, beverages, and essential items.</p>
                        <!-- <button class="mt-4 w-full bg-gray-50 text-blue-600 font-medium py-2 rounded-lg hover:bg-blue-600 hover:text-white transition duration-300">
                            Visit Store →
                        </button> -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-yellow-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold mb-6">Why Choose BGPDI?</h2>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <i class="fas fa-gas-pump text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold mb-2">Quality Fuel Products</h3>
                                <p class="text-gray-600">We provide high-grade petroleum products ensuring optimal performance for your vehicles.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <i class="fas fa-clock text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold mb-2">24/7 Service</h3>
                                <p class="text-gray-600">Our station operates round-the-clock to serve your fuel and convenience store needs anytime.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <i class="fas fa-store text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold mb-2">Complete Convenience</h3>
                                <p class="text-gray-600">My Store offers a wide selection of snacks, beverages, and essential items for your convenience.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <img src="{{ asset('vendor/adminlte/dist/img/bgpdi/station-front.jpg') }}" alt="BGPDI Station" class="rounded-lg shadow-lg">
                    <img src="{{ asset('vendor/adminlte/dist/img/bgpdi/store-inside.jpg') }}" alt="My Store Interior" class="rounded-lg shadow-lg mt-8">
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="grid md:grid-cols-2">
                    <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white p-12">
                        <h2 class="text-3xl font-bold mb-6">Get in Touch</h2>
                        <p class="mb-8">Have questions about our fuel products or convenience store? Our team is here to assist you 24/7.</p>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt w-6"></i>
                                <span class="ml-4">Brgy. Cansaga, Consolacion, Cebu, Philippines</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone w-6"></i>
                                <span class="ml-4">(032) 419 1014</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-envelope w-6"></i>
                                <span class="ml-4">baygaspdi@mhrpci.ph</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock w-6"></i>
                                <span class="ml-4">Open 24/7</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-12">
                        @if(session('success'))
                            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form class="space-y-6" method="POST" action="{{ route('contact.sendbgpdi') }}" id="contactForm">
                            @csrf
                            <div>
                                <label class="block text-gray-700 mb-2 font-medium">Name</label>
                                <input type="text" name="name" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="Your name">
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2 font-medium">Email</label>
                                <input type="email" name="email" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="Your email">
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2 font-medium">Message</label>
                                <textarea name="message" required class="w-full p-3 border border-gray-300 rounded-lg h-32 focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="Your message"></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300 flex items-center justify-center">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Send Message
                                </button>
                                <button type="reset" class="w-full bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition duration-300">
                                    Reset Form
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">About Us</h3>
                    <p class="text-gray-400">Leading provider of fuel and convenience store, serving the community.</p>
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
                        <li><a href="#" class="hover:text-white">Fuel</a></li>
                        <li><a href="#" class="hover:text-white">Convenience Store</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Connect With Us</h3>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/bgpdiofficial" target="_blank" class="text-gray-400 hover:text-white"><i class="fab fa-facebook"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Bay Gas Petroleum Distribution Inc. All rights reserved.</p>
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
                            Bay Gas Petroleum Distribution Inc. (BGPDI) is a prominent fuel station located in Brgy. Cansaga, Consolacion, Cebu. As part of the MHR Properties Conglomerate, Inc., we have established ourselves as a reliable provider of high-quality fuel and petroleum products in the region.
                        </p>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Since our establishment in 2015, we have grown from a small fuel distribution business to a key player in the local petroleum industry. Our strategic location and commitment to service excellence have made us the preferred choice for both private and commercial customers.
                        </p>
                        <p class="text-gray-600 leading-relaxed">
                            With our team of six dedicated local staff members, we maintain 24/7 operations to serve various sectors including private vehicle owners, public transport operators, and government agencies. Our commitment to quality service and reliability has helped us maintain a strong presence in the community.
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