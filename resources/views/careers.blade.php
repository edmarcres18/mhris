<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Careers | MHR Property Conglomerate Inc.</title>
    <meta name="description" content="Explore exciting career opportunities at MHR Property Conglomerate Inc. (MHRPCI) - Join our diverse team and help shape the future.">
    <meta name="keywords" content="MHRPCI careers, MHR jobs, employment opportunities, Cebu jobs, Philippines careers">
    
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

    <!-- Hero Section -->
    <section class="hero-gradient pt-32 pb-20 md:pt-40 md:pb-32">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-white text-4xl md:text-5xl font-bold leading-tight mb-6" data-aos="fade-up">Join Our Team</h2>
                <p class="text-indigo-100 text-lg mb-8 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                    Discover exciting career opportunities and help shape the future of MHR Property Conglomerates, Inc.
                </p>
                <div class="flex justify-center" data-aos="fade-up" data-aos-delay="300">
                    <a href="#openings" class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition duration-300 inline-flex items-center">
                        View Open Positions <i class="fas fa-arrow-down ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section id="openings" class="py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative mb-6" role="alert" data-aos="fade-up">
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3 close-alert">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded relative mb-6" role="alert" data-aos="fade-up">
                    <span class="block sm:inline">{{ session('error') }}</span>
                    <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3 close-alert">
                        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                    </button>
                </div>
            @endif

            <!-- Search Box -->
            <div class="max-w-2xl mx-auto mb-12">
                <div class="relative" data-aos="fade-up">
                    <input 
                        type="text" 
                        id="careerSearch" 
                        class="w-full py-4 px-6 bg-gray-50 rounded-xl shadow-sm border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                        placeholder="Search for positions..."
                    >
                    <div class="absolute right-4 top-4 text-gray-400">
                        <i class="fas fa-search text-xl"></i>
                    </div>
                </div>
            </div>
            
            <!-- Job Listings -->
            <div class="text-center mb-12">
                <h3 class="text-3xl font-bold mb-4" data-aos="fade-up">Open Positions</h3>
                <div class="w-20 h-1 bg-indigo-600 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                    Take the next step in your career with MHR Property Conglomerates, Inc. Browse our current openings and find the perfect role for you.
                </p>
            </div>

            @if($hirings->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6" id="careerList" data-aos="fade-up" data-aos-delay="300">
                    @foreach($hirings as $hiring)
                        <div class="job-card career-item-container bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300">
                            <div class="p-6">
                                <h4 class="text-xl font-semibold mb-3 text-indigo-700">{{ $hiring->position }}</h4>
                                <p class="text-gray-600 mb-4 text-sm line-clamp-3">{{ Str::limit($hiring->description, 100) }}</p>
                                
                                <div class="flex flex-wrap gap-2 mb-5">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        <i class="fas fa-map-marker-alt mr-1"></i> {{ Str::limit($hiring->location, 45) }}
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        <i class="fas fa-clock mr-1"></i> {{ $hiring->employment_type }}
                                    </span>
                                </div>
                                
                                <div class="flex space-x-3">
                                    @if(Auth::guard('google')->check())
                                        <a href="{{ route('careers.show', $hiring->slug) }}" class="flex-1 px-4 py-2 text-center text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition duration-300">
                                            <i class="fas fa-info-circle mr-1"></i> Details
                                        </a>
                                        <button 
                                            class="flex-1 px-4 py-2 text-center text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition duration-300"
                                            onclick="openModal('applyModal'); document.getElementById('hiringIdInput').value='{{ $hiring->id }}';"
                                        >
                                            <i class="fas fa-paper-plane mr-1"></i> Apply
                                        </button>
                                    @else
                                        <a href="#" class="flex-1 px-4 py-2 text-center text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition duration-300" onclick="openModal('loginModal'); return false;">
                                            <i class="fas fa-info-circle mr-1"></i> Details
                                        </a>
                                        <button class="flex-1 px-4 py-2 text-center text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition duration-300" onclick="openModal('loginModal');">
                                            <i class="fas fa-paper-plane mr-1"></i> Apply
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- No Results Message (Hidden by default) -->
                <div id="noResultsMessage" class="hidden mt-8 p-8 text-center bg-gray-50 rounded-xl" data-aos="fade-up">
                    <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                    <h4 class="text-xl font-semibold text-gray-700">No positions found</h4>
                    <p class="text-gray-500">Try adjusting your search criteria</p>
                </div>
            @else
                <div class="mt-8 p-8 text-center bg-gray-50 rounded-xl" data-aos="fade-up">
                    <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-4"></i>
                    <h4 class="text-xl font-semibold text-gray-700">No open positions</h4>
                    <p class="text-gray-500">There are currently no open positions. Please check back later.</p>
                </div>
            @endif
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
                            Apply for Position
                        </h3>
                        <div class="mt-2">
                            <form action="{{ route('careers.apply') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <input type="hidden" name="hiring_id" id="hiringIdInput">
                                
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
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-500 transition-colors" id="resumeDropArea">
                                        <div class="space-y-1 text-center" id="resumeUploadContainer">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600 justify-center">
                                                <label for="resume" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                                    <span>Upload a file</span>
                                                    <input id="resume" name="resume" type="file" class="sr-only" accept=".pdf,.doc,.docx" required>
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500" id="fileTypeHelp">PDF, DOC, or DOCX up to 10MB</p>
                                        </div>
                                        
                                        <!-- File Preview (Hidden by default) -->
                                        <div class="hidden w-full" id="resumePreview">
                                            <div class="flex items-center p-2 bg-indigo-50 rounded-md">
                                                <div class="mr-3 flex-shrink-0">
                                                    <svg class="h-10 w-10 text-indigo-500" id="fileIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                                    <button type="button" class="text-indigo-600 hover:text-indigo-900" id="removeFile">
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
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
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
            <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom bg-white rounded-lg shadow-xl transition-all transform sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6 modal-content">
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
                            <div class="rounded-md bg-indigo-50 p-4 mb-6">
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
                        <li><a href="{{ route('careers') }}" class="hover:text-indigo-400 transition duration-300">MHR Careers</a></li>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- AOS Animation Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS animations
            AOS.init({
                duration: 800,
                once: true
            });
            
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
            
            // Handle alert closings
            const closeButtons = document.querySelectorAll('.close-alert');
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    this.parentElement.remove();
                });
            });
            
            /**********************************************
             * CUSTOM MODAL FUNCTIONALITY
             * This replaces Bootstrap's modal handling
             **********************************************/
            window.openModal = function(modalId) {
                console.log("Opening modal: " + modalId);
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                    console.log("Modal opened successfully");
                    return true;
                }
                console.log("Modal not found");
                return false;
            };
            
            window.closeModal = function(modalId) {
                console.log("Closing modal: " + modalId);
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    console.log("Modal closed successfully");
                    return true;
                }
                console.log("Modal not found");
                return false;
            };
            
            // Add universal click handlers for all modal close buttons
            document.querySelectorAll('.modal-close').forEach(function(button) {
                console.log("Adding click handler to modal close button");
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Find the closest modal parent
                    const modal = this.closest('.modal-container');
                    if (modal) {
                        modal.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                        console.log("Modal closed via button click");
                    }
                });
            });
            
            // Close modals when clicking on backdrop
            document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
                backdrop.addEventListener('click', function(e) {
                    // Only if clicking directly on the backdrop
                    if (e.target === backdrop) {
                        const modal = backdrop.closest('.modal-container');
                        if (modal) {
                            modal.classList.add('hidden');
                            document.body.classList.remove('overflow-hidden');
                            console.log("Modal closed via backdrop click");
                        }
                    }
                });
            });
            
            // Close modals with escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const visibleModal = document.querySelector('.modal-container:not(.hidden)');
                    if (visibleModal) {
                        visibleModal.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                        console.log("Modal closed via Escape key");
                    }
                }
            });
            
            // Direct references to specific modal elements for existing code
            const applyModal = document.getElementById('applyModal');
            const loginModal = document.getElementById('loginModal');
            const applyModalClose = document.getElementById('applyModalClose');
            const loginModalClose = document.getElementById('loginModalClose');
            
            // ADDITIONAL DIRECT BINDINGS FOR THE APPLY MODAL CLOSE BUTTON
            if (applyModalClose) {
                console.log("Setting up direct binding for apply modal close");
                applyModalClose.onclick = function(e) {
                    if (e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    if (applyModal) {
                        applyModal.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                        console.log("Apply modal closed via direct binding");
                    }
                    return false;
                };
            }
            
            // ADDITIONAL DIRECT BINDINGS FOR THE LOGIN MODAL CLOSE BUTTON
            if (loginModalClose) {
                console.log("Setting up direct binding for login modal close");
                loginModalClose.onclick = function(e) {
                    if (e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    if (loginModal) {
                        loginModal.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                        console.log("Login modal closed via direct binding");
                    }
                    return false;
                };
            }

            /********************************************
             * FIXED SEARCH FUNCTIONALITY
             * Improved search for job listings
             ********************************************/
            
            const searchInput = document.getElementById('careerSearch');
            const careerList = document.getElementById('careerList');
            const noResultsMessage = document.getElementById('noResultsMessage');
            
            if (searchInput && careerList) {
                // Debounce function to prevent excess searching
                function debounce(func, wait) {
                    let timeout;
                    return function() {
                        const context = this;
                        const args = arguments;
                        clearTimeout(timeout);
                        timeout = setTimeout(() => func.apply(context, args), wait);
                    };
                }
                
                // Search function
                const performSearch = debounce(function() {
                    const searchTerm = searchInput.value.toLowerCase().trim();
                    const careerItems = careerList.querySelectorAll('.career-item-container');
                    let visibleCount = 0;
                    
                    careerItems.forEach(item => {
                        const title = item.querySelector('h4')?.textContent.toLowerCase() || '';
                        const description = item.querySelector('p')?.textContent.toLowerCase() || '';
                        const location = item.querySelector('.inline-flex:first-of-type')?.textContent.toLowerCase() || '';
                        
                        // More comprehensive search across fields
                        if (title.includes(searchTerm) || 
                            description.includes(searchTerm) || 
                            location.includes(searchTerm)) {
                            item.style.display = '';
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });
                    
                    // Show/hide no results message
                    if (noResultsMessage) {
                        if (visibleCount === 0 && searchTerm.length > 0) {
                            noResultsMessage.classList.remove('hidden');
                        } else {
                            noResultsMessage.classList.add('hidden');
                        }
                    }
                }, 300); // 300ms debounce
                
                // Add event listener
                searchInput.addEventListener('input', performSearch);
                
                // Clear button functionality
                const searchContainer = searchInput.closest('.relative');
                if (searchContainer) {
                    const clearButton = document.createElement('button');
                    clearButton.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`;
                    clearButton.className = 'absolute right-12 top-4 text-gray-400 hover:text-gray-600 focus:outline-none hidden';
                    clearButton.id = 'clearSearch';
                    clearButton.setAttribute('type', 'button');
                    searchContainer.appendChild(clearButton);
                    
                    // Show/hide clear button
                    searchInput.addEventListener('input', function() {
                        if (this.value.length > 0) {
                            clearButton.classList.remove('hidden');
                        } else {
                            clearButton.classList.add('hidden');
                        }
                    });
                    
                    // Clear search when button is clicked
                    clearButton.addEventListener('click', function() {
                        searchInput.value = '';
                        searchInput.focus();
                        performSearch();
                        this.classList.add('hidden');
                    });
                }
            }
            
            /********************************************
             * FIXED RESUME UPLOAD FUNCTIONALITY
             * Improved file selection and preview
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
                const maxSizeMB = 10;
                
                // Handle file selection
                resumeInput.addEventListener('change', function(e) {
                    validateAndDisplayFile(this.files);
                });
                
                // Handle drag and drop
                if (resumeDropArea) {
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
                    }
                    
                    function unhighlight() {
                        resumeDropArea.classList.remove('border-indigo-500', 'bg-indigo-50');
                    }
                    
                    resumeDropArea.addEventListener('drop', function(e) {
                        validateAndDisplayFile(e.dataTransfer.files);
                    });
                }
                
                // Handle remove file button
                if (removeFile) {
                    removeFile.addEventListener('click', function() {
                        resumeInput.value = '';
                        if (resumeUploadContainer) resumeUploadContainer.classList.remove('hidden');
                        if (resumePreview) resumePreview.classList.add('hidden');
                    });
                }
                
                // Improved file validation and display
                function validateAndDisplayFile(files) {
                    if (!files || files.length === 0) return;
                    
                    const file = files[0];
                    const fileExt = '.' + file.name.split('.').pop().toLowerCase();
                    
                    // Validate file type
                    if (!validTypes.includes(fileExt)) {
                        alert(`Invalid file type. Please upload ${validTypes.join(', ')} files only.`);
                        return;
                    }
                    
                    // Validate file size
                    const fileSizeMB = file.size / (1024 * 1024);
                    if (fileSizeMB > maxSizeMB) {
                        alert(`File too large. Maximum size is ${maxSizeMB}MB.`);
                        return;
                    }
                    
                    // Update file icon based on extension
                    updateFileIcon(fileExt);
                    
                    // Display file information
                    if (fileName) fileName.textContent = file.name;
                    if (fileSize) fileSize.textContent = `Size: ${fileSizeMB.toFixed(2)} MB`;
                    
                    // Show file preview, hide upload container
                    if (resumeUploadContainer) resumeUploadContainer.classList.add('hidden');
                    if (resumePreview) resumePreview.classList.remove('hidden');
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
            
            // Ensure all checkboxes have proper event handling
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                // Ensure label click works
                const label = checkbox.closest('label') || document.querySelector(`label[for="${checkbox.id}"]`);
                if (label) {
                    label.addEventListener('click', function(e) {
                        e.stopPropagation(); // Prevent duplicate events
                    });
                }
                
                // Fix any validation issues
                if (checkbox.hasAttribute('required')) {
                    const form = checkbox.closest('form');
                    if (form) {
                        form.addEventListener('submit', function(e) {
                            if (!checkbox.checked) {
                                e.preventDefault();
                                alert('Please agree to the terms and conditions before submitting.');
                            }
                        });
                    }
                }
            });
            
            // Safe content loading protection
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
            });
        });
    </script>
</body>
</html>
