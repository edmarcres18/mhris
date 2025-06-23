<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MHR Property Conglomerate Inc. (MHRPCI) - A diverse group of businesses operating across healthcare, fuel distribution, construction, and hospitality sectors.">
    <meta name="keywords" content="MHRPCI, MHR Property Conglomerate, healthcare, fuel distribution, construction, hospitality, Cebu business">
    <title>MHR Property Conglomerate Inc. | MHRPCI</title>
    <link rel="icon" type="image/png" href="{{ asset('vendor/adminlte/dist/img/LOGO4.png') }}">
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
                        <img src="{{ asset('vendor/adminlte/dist/img/LOGO4.png') }}" alt="MHRPCI Logo" class="h-12 w-auto hover:opacity-90 transition-opacity duration-300">
                        <span class="font-bold text-2xl text-purple-600 hover:text-purple-700 transition-colors duration-300">MHRPCI</span>
                    </div>
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="#home" class="group relative inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-purple-600">Home</a>
                        <a href="#profile" class="group relative inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-purple-600">Company Profile</a>
                        <a href="#about" class="group relative inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-purple-600">About</a>
                        <a href="#brand" class="group relative inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-purple-600">Our Brand</a>
                        <a href="#milestones" class="group relative inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-purple-600">Milestones</a>
                    </div>
                </div>
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button type="button" onclick="toggleMobileMenu()" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-purple-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden hidden" id="mobileMenu">
            <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t">
                <a href="#home" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-purple-600">Home</a>
                <a href="#profile" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-purple-600">Company Profile</a>
                <a href="#about" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-purple-600">About</a>
                <a href="#brand" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-purple-600">Our Brand</a>
                <a href="#milestones" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-purple-600">Milestones</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div id="home" class="pt-16">
        <div class="relative bg-gradient-to-r from-purple-600 to-purple-900 text-white py-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold mb-6">MHR Property Conglomerate Inc.</h1>
                        <p class="text-xl mb-8">Transforming Industries, Empowering Growth</p>
                        <div class="flex space-x-4">
                            <button onclick="openModal()" class="border-2 border-white text-white px-6 py-3 rounded-lg hover:bg-white hover:text-purple-600 transition duration-300">
                                Learn More
                            </button>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <img src="{{ asset('vendor/adminlte/dist/img/whiteLOGO4.png') }}" alt="MHRPCI" class="rounded-lg shadow-xl">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Company Profile Section -->
    <section id="profile" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-4">Company Profile</h2>
            <p class="text-gray-600 text-center mb-12 max-w-3xl mx-auto">
                MHRPCI is the parent company of a diverse group of businesses operating across several industries, including healthcare, fuel distribution, construction, and hospitality.
            </p>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-shadow duration-300">
                    <div class="text-purple-600 mb-4">
                        <i class="fas fa-hospital text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Healthcare</h3>
                    <p class="text-gray-600">Leading provider of medical supplies and equipment through MHRHCI.</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-shadow duration-300">
                    <div class="text-purple-600 mb-4">
                        <i class="fas fa-gas-pump text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Fuel Distribution</h3>
                    <p class="text-gray-600">Efficient fuel distribution services across the Philippines.</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-shadow duration-300">
                    <div class="text-purple-600 mb-4">
                        <i class="fas fa-building text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Construction</h3>
                    <p class="text-gray-600">Quality construction and development projects.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission, Vision, Goals Section -->
    <section class="py-20 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-purple-600 mb-4">Our Mission</h3>
                    <p class="text-gray-600">To deliver excellence across industries through innovative solutions and sustainable practices, while creating value for our stakeholders and communities.</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-purple-600 mb-4">Our Vision</h3>
                    <p class="text-gray-600">To be a leading conglomerate that transforms industries and empowers growth across the Philippines and beyond.</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-purple-600 mb-4">Our Goals</h3>
                    <ul class="text-gray-600 space-y-2">
                        <li>• Sustainable business growth</li>
                        <li>• Excellence in service delivery</li>
                        <li>• Innovation leadership</li>
                        <li>• Community development</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold mb-6">About MHRPCI</h2>
                    <p class="text-gray-600 mb-4">
                        MHR Property Conglomerate Inc. (MHRPCI) began in 2000 with the establishment of Cebic Trading, starting with just a 20,000-peso capital in hospital and office medical supplies.
                    </p>
                    <p class="text-gray-600 mb-4">
                        In 2003, we expanded operations in Cebu by forming Medical & Hospital Resources Health Care, Inc. (MHRHCI) to focus on medical supplies and forge international partnerships.
                    </p>
                    <p class="text-gray-600">
                        Today, MHRPCI has grown into a conglomerate with 10 companies working in synergy across various industries.
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="relative w-full" style="padding-top: 56.25%;">
                        <iframe 
                            class="absolute top-0 left-0 w-full h-full rounded-lg shadow-lg"
                            src="https://www.youtube.com/embed/4DRktuQ5tno"
                            title="Company History"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>
                    <img src="{{ asset('vendor/adminlte/dist/img/companies.png') }}" alt="Company Growth" class="rounded-lg shadow-lg mt-8">
                </div>
            </div>
        </div>
    </section>

    <!-- Key Milestones Section -->
    <section id="milestones" class="py-20 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Key Milestones</h2>
            <div class="relative">
                <!-- Timeline items -->
                <div class="space-y-8">
                    <div class="flex items-center">
                        <div class="bg-purple-600 text-white px-4 py-2 rounded-lg">2000</div>
                        <div class="ml-4">
                            <h3 class="text-xl font-semibold">Establishment of Cebic Trading</h3>
                            <p class="text-gray-600">Started with medical supplies distribution</p>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="bg-purple-600 text-white px-4 py-2 rounded-lg">2003</div>
                        <div class="ml-4">
                            <h3 class="text-xl font-semibold">Formation of MHRHCI</h3>
                            <p class="text-gray-600">Expanded into healthcare sector</p>
                        </div>
                    </div>

                    <!-- Add more milestone items as needed -->
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
                    <p class="text-gray-400">Leading conglomerate operating across healthcare, fuel distribution, construction, and hospitality sectors.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#profile" class="hover:text-white">Company Profile</a></li>
                        <li><a href="#about" class="hover:text-white">About Us</a></li>
                        <li><a href="#brand" class="hover:text-white">Our Brand</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Our Companies</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('mhrhci') }}" class="hover:text-white">MHRHCI</a></li>
                        <li><a href="{{ route('bgpdi') }}" class="hover:text-white">Bay Gas</a></li>
                        <li><a href="{{ route('cio') }}" class="hover:text-white">Cebic Industries</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Connect With Us</h3>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/mhrpciofficial/" target="_blank" class="text-gray-400 hover:text-white">
                            <i class="fab fa-facebook"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} MHR Property Conglomerate Inc. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Modal -->
    <div id="learnMoreModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <!-- Modal backdrop -->
            <div class="fixed inset-0 bg-black opacity-50" onclick="closeModal()"></div>
            
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 animate-modal">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-2xl font-bold text-gray-900">About MHRPCI</h2>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="prose max-w-none">
                        <p class="text-gray-600 leading-relaxed mb-4">
                            MHR Property Conglomerate Inc. (MHRPCI) is a diverse group of businesses operating across several industries, including healthcare, fuel distribution, construction, and hospitality sectors.
                        </p>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Starting from humble beginnings in 2000 with Cebic Trading, we have grown into a conglomerate of 10 companies working in synergy to deliver excellence across various industries.
                        </p>
                        <p class="text-gray-600 leading-relaxed">
                            Our commitment to innovation, sustainable practices, and community development has established us as a leading conglomerate in the Philippines.
                        </p>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 rounded-b-lg">
                    <button onclick="closeModal()" class="w-full bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition duration-300">
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

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
        }

        document.querySelectorAll('#mobileMenu a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobileMenu').classList.add('hidden');
            });
        });

        document.addEventListener('click', (e) => {
            const mobileMenu = document.getElementById('mobileMenu');
            const hamburgerButton = document.querySelector('button[onclick="toggleMobileMenu()"]');
            
            if (!mobileMenu.contains(e.target) && !hamburgerButton.contains(e.target)) {
                mobileMenu.classList.add('hidden');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('nav a');
            
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
                    link.classList.remove('text-purple-600');
                    if (link.getAttribute('href') === current) {
                        link.classList.add('text-purple-600');
                    }
                });
            });

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

            document.querySelectorAll('section').forEach(section => {
                section.classList.add('reveal');
            });

            window.addEventListener('scroll', reveal);
            reveal();
        });
    </script>
</body>
</html>
