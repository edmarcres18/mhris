<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#3390ec">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <title>{{ config('app.name', 'MHRIS') }} - Messaging</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#edf4fc',
                            100: '#d1e3f9',
                            200: '#a3c7f3',
                            300: '#75aaed',
                            400: '#4794e6',
                            500: '#3390ec',
                            600: '#2379d8',
                            700: '#1366c5',
                            800: '#0f53a0',
                            900: '#0b3f7a'
                        },
                        gray: {
                            50: '#f9fafb',
                            100: '#f4f4f5',
                            200: '#e5e7eb',
                            300: '#d1d5db',
                            400: '#9ca3af',
                            500: '#6b7280',
                            600: '#4b5563',
                            700: '#374151',
                            800: '#1f2937',
                            900: '#111827'
                        }
                    },
                    boxShadow: {
                        'message': '0 1px 2px rgba(16, 35, 47, 0.07)',
                        'card': '0 2px 10px rgba(0, 0, 0, 0.05)',
                        'dropdown': '0 4px 20px rgba(0, 0, 0, 0.08)'
                    },
                    transitionProperty: {
                        'height': 'height',
                        'spacing': 'margin, padding',
                        'width': 'width'
                    },
                    borderRadius: {
                        'message': '18px'
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.2s ease-in-out',
                        'slide-in': 'slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1)',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideIn: {
                            '0%': { transform: 'translateY(10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        }
                    }
                }
            },
            variants: {
                extend: {
                    opacity: ['group-hover'],
                    display: ['group-hover'],
                    transform: ['hover', 'focus'],
                    scale: ['hover', 'active'],
                }
            }
        }
    </script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">

    <style>
        :root {
            --primary-color: #3390ec;
            --primary-light: #edf4fc;
            --primary-dark: #2379d8;
            --gray-light: #f4f4f5;
            --bubble-shadow: 0 1px 2px rgba(16, 35, 47, 0.07);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            overscroll-behavior: none;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            height: 100vh;
            height: calc(var(--vh, 1vh) * 100);
            touch-action: manipulation;
        }

        /* Custom scrollbar styling */
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: rgba(140, 140, 140, 0.35);
            border-radius: 4px;
            transition: background-color 0.2s ease;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: rgba(140, 140, 140, 0.5);
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        /* Message bubbles */
        .message-bubble-mine {
            background-color: var(--primary-light);
            color: #000;
            border-radius: 18px 18px 4px 18px;
            box-shadow: var(--bubble-shadow);
            position: relative;
            transition: all 0.15s ease-out;
            word-break: break-word;
        }

        .message-bubble-mine:hover {
            background-color: #e6f0fa;
        }

        .message-bubble-other {
            background-color: white;
            border-radius: 18px 18px 18px 4px;
            box-shadow: var(--bubble-shadow);
            position: relative;
            transition: all 0.15s ease-out;
            word-break: break-word;
        }

        .message-bubble-other:hover {
            background-color: #fafafa;
        }

        /* Quoted message styling */
        .quoted-message {
            border-radius: 8px;
            position: relative;
            transition: all 0.15s ease-out;
            font-size: 0.85em;
            overflow: hidden;
            max-height: 100px;
        }

        /* Reply preview */
        #reply-preview {
            animation: slideDown 0.2s ease-out;
            border-left: 3px solid var(--primary-color);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
                max-height: 0;
            }
            to {
                opacity: 1;
                transform: translateY(0);
                max-height: 100px;
            }
        }

        /* Message actions menu */
        #message-actions-menu {
            animation-duration: 0.2s;
            animation-timing-function: cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            max-width: 90vw;
        }

        .message-action-btn {
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        /* Typing indicator animation */
        .typing-indicator {
            padding: 8px 16px;
            background-color: white;
            border-radius: 18px 18px 18px 4px;
            box-shadow: var(--bubble-shadow);
            width: fit-content;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            position: relative;
        }

        .typing-dot {
            height: 8px;
            width: 8px;
            margin: 0 2px;
            background-color: var(--primary-color);
            display: block;
            border-radius: 50%;
            opacity: 0.4;
            transform: translateY(0);
            will-change: transform, opacity;
        }

        .typing-dot:nth-of-type(1) {
            animation: 1s typingBounce infinite 0.1s;
        }

        .typing-dot:nth-of-type(2) {
            animation: 1s typingBounce infinite 0.3s;
        }

        .typing-dot:nth-of-type(3) {
            animation: 1s typingBounce infinite 0.5s;
        }

        .typing-name {
            font-style: italic;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 120px;
            animation: fadeIn 0.5s ease;
        }

        @keyframes typingBounce {
            0%, 100% {
                transform: translateY(0);
                opacity: 0.4;
            }
            50% {
                transform: translateY(-5px);
                opacity: 0.8;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 0.75; }
        }

        /* Transitions */
        .sidebar-transition {
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1),
                        margin-left 0.3s cubic-bezier(0.16, 1, 0.3, 1),
                        left 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform, margin-left;
        }

        .fade-transition {
            transition: opacity 0.25s ease-in-out;
            will-change: opacity;
        }

        /* Menu drawer */
        #main-menu {
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
            z-index: 40;
            will-change: transform;
        }

        #main-menu.open {
            transform: translateX(0);
        }

        /* Badge styles */
        .unread-badge {
            background-color: var(--primary-color);
            transform-origin: center;
            box-shadow: 0 2px 4px rgba(51, 144, 236, 0.3);
        }

        /* Skeleton loaders */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e6e6e6 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }

        /* Online status */
        .online-status {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 12px;
            height: 12px;
            background-color: #4caf50;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.3);
        }

        /* Toast notification */
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 16px;
            background-color: #333;
            color: white;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
            z-index: 100;
            opacity: 0;
            transform: translateY(-20px);
            transition: opacity 0.3s, transform 0.3s;
            max-width: 80%;
            backdrop-filter: blur(8px);
        }

        .toast-notification.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Ripple effect */
        .ripple {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.4);
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }

        /* Message day separator */
        .day-separator {
            position: relative;
            text-align: center;
            margin: 1.5rem 0;
        }

        .day-separator::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background-color: #e5e7eb;
            z-index: -1;
        }

        /* System message styling */
        .system-message {
            background-color: #f8f9fa;
            color: #6c757d;
            font-size: 0.75rem;
            padding: 5px 12px;
            border-radius: 15px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            animation: fadeInUp 0.3s ease;
        }

        .system-message i {
            opacity: 0.8;
            margin-right: 4px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive fixes */
        @media (max-width: 1024px) {
            #sidebar {
                width: 320px;
            }

            #chat-container {
                margin-left: 0;
            }

            .lg\:ml-80 {
                margin-left: 0 !important;
            }
        }

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .message-bubble-mine, .message-bubble-other {
                max-width: 85%;
                padding-left: 12px;
                padding-right: 12px;
                padding-top: 8px;
                padding-bottom: 8px;
                font-size: 0.9375rem;
            }

            .toast-notification {
                left: 20px;
                right: 20px;
                max-width: none;
            }

            #main-menu {
                width: 60px;
            }

            #sidebar {
                width: 100%;
                max-width: 100%;
            }

            .contact-item {
                padding: 10px;
            }

            .chat-header-name {
                max-width: 180px;
            }
        }

        /* Touch device optimizations */
        @media (hover: none) {
            .message-bubble-mine:hover,
            .message-bubble-other:hover {
                background-color: inherit;
            }

            .hover\:bg-gray-50:hover {
                background-color: inherit;
            }

            .hover\:bg-gray-100:hover {
                background-color: inherit;
            }

            /* Make touch targets larger */
            button {
                min-height: 44px;
                min-width: 44px;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 h-screen flex flex-col overflow-hidden text-gray-800">
    <!-- Toast Container -->
    <div id="toast-container" class="z-50"></div>

    <div class="flex flex-col h-full">
        <!-- Main Content - 3-column layout -->
        <div class="flex flex-1 h-full relative three-column-layout">
            <!-- Left Menu Panel -->
            <div id="main-menu" class="fixed top-0 left-0 h-full w-20 bg-white border-r border-gray-200 flex flex-col items-center py-6 shadow-sm">
                <div class="mt-auto">
                </div>
            </div>

            <!-- Contact Sidebar -->
            <div id="sidebar" class="fixed top-0 left-0 h-full bg-white border-r border-gray-200 w-80 z-30 sidebar-transition shadow-md">
                @yield('sidebar')
            </div>

            <!-- Content Area (Chat) -->
            <div id="chat-container" class="flex-1 h-full ml-0 lg:ml-80 sidebar-transition">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.iife.js"></script>

    <script>
        // Set correct viewport height for mobile browsers
        const setVhProperty = () => {
            const vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        };

        window.addEventListener('resize', setVhProperty);
        window.addEventListener('orientationchange', setVhProperty);
        setVhProperty();

        // Initialize Laravel Echo for real-time communication
        window.Pusher = Pusher;

        try {
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: '{{ env("PUSHER_APP_KEY") }}',
                cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
                forceTLS: true,
                enabledTransports: ['ws', 'wss'],
                disableStats: true,
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }
            });

            window.Echo.connector.pusher.connection.bind('error', function(error) {
                console.log('Pusher connection error:', error);
                showToast('Connection issue. Switching to polling mode.');
                setupPollingFallback();
            });
        } catch (error) {
            console.log('Echo initialization error:', error);
            setupPollingFallback();
        }

        // Toast notification function
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            toast.innerHTML = message;

            if (type === 'error') toast.style.backgroundColor = '#e53935';
            if (type === 'success') toast.style.backgroundColor = '#43a047';

            document.getElementById('toast-container').appendChild(toast);

            setTimeout(() => {
                toast.classList.add('show');
            }, 10);

            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }

        // Fallback polling function
        function setupPollingFallback() {
            console.log('Setting up polling fallback for messages');
            if (window.location.pathname.includes('/messaging/')) {
                setInterval(function() {
                    const pathParts = window.location.pathname.split('/');
                    if (pathParts.length > 2 && pathParts[1] === 'messaging') {
                        const conversationId = pathParts[2];
                        if (!isNaN(conversationId)) {
                            fetchMessages(conversationId);
                        }
                    }
                }, 5000);
            }
        }

        // Fetch messages function for polling fallback
        function fetchMessages(conversationId) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Get timestamp of last message for incremental updates
            let lastMessageTime = localStorage.getItem(`last_message_time_${conversationId}`) || 0;

            fetch(`/api/messages/${conversationId}?after=${lastMessageTime}`, {
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(messages => {
                if (window.messagingApp && messages.length > 0) {
                    console.log(`Received ${messages.length} messages via polling`);

                    // Update last message time
                    const latestMessage = messages[messages.length - 1];
                    if (latestMessage) {
                        const messageTime = new Date(latestMessage.created_at).getTime();
                        localStorage.setItem(`last_message_time_${conversationId}`, messageTime);
                    }

                    // Only render messages that aren't from current user
                    const userId = document.body.dataset.userId;
                    const newMessages = messages.filter(message => message.user_id != userId);

                    // Append new messages to chat
                    newMessages.forEach(message => {
                        if (window.messagingApp.appendMessage) {
                            const messageElement = document.querySelector(`[data-message-id="${message.id}"]`);
                            if (!messageElement) {
                                window.messagingApp.appendMessage(message);
                            }
                        }
                    });

                    // Mark new messages as read
                    if (newMessages.length > 0 && window.messagingApp.markMessagesAsRead) {
                        const messageIds = newMessages.map(message => message.id);
                        window.messagingApp.markMessagesAsRead(messageIds);
                    }
                }
            })
            .catch(error => {
                console.error('Polling error:', error);
                showToast('Could not update messages. Please refresh.', 'error');
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const mainMenu = document.getElementById('main-menu');
            const sidebar = document.getElementById('sidebar');
            const chatContainer = document.getElementById('chat-container');

            // Menu toggle (mobile)
            const menuToggle = document.getElementById('menu-toggle');
            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    mainMenu.classList.toggle('open');
                });

                // Close menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (mainMenu.classList.contains('open') &&
                        !mainMenu.contains(e.target) &&
                        !menuToggle.contains(e.target)) {
                        mainMenu.classList.remove('open');
                    }
                });
            }

            // Handle responsive behavior
            const handleResize = () => {
                const isMobile = window.innerWidth < 768;
                const isTablet = window.innerWidth >= 768 && window.innerWidth < 1024;

                if (window.innerWidth < 1024) {
                    mainMenu.classList.remove('open');

                    if (isMobile) {
                        // Check if we're in a conversation and adjust accordingly
                        const pathParts = window.location.pathname.split('/');
                        if (pathParts.length > 2 && pathParts[1] === 'messaging' && !isNaN(pathParts[2])) {
                            sidebar.style.transform = 'translateX(-100%)';
                            chatContainer.style.marginLeft = '0';
                        } else {
                            sidebar.style.transform = '';
                            chatContainer.style.marginLeft = '';
                        }
                    }
                } else {
                    sidebar.style.transform = '';
                    chatContainer.style.marginLeft = '';
                }

                // Update chat messages container size for better display
                const chatMessages = document.getElementById('chat-messages');
                if (chatMessages) {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            };

            window.addEventListener('resize', handleResize);
            handleResize();

            // Mobile chat view handling
            const contactItems = document.querySelectorAll('.contact-item');
            contactItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    if (window.innerWidth < 768) {
                        // Prevent default only if needed for SPA behavior
                        // e.preventDefault();
                        sidebar.style.transform = 'translateX(-100%)';
                        chatContainer.style.marginLeft = '0';
                    }
                });
            });

            // Back button in chat (mobile)
            const backButtons = document.querySelectorAll('.chat-back-button');
            backButtons.forEach(button => {
                button.addEventListener('click', function() {
                    sidebar.style.transform = '';
                    chatContainer.style.marginLeft = '';
                });
            });

            // Add ripple effect to buttons
            document.querySelectorAll('button').forEach(button => {
                button.addEventListener('click', function(e) {
                    const rect = button.getBoundingClientRect();
                    const size = Math.max(button.offsetWidth, button.offsetHeight);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    const ripple = document.createElement('span');
                    ripple.className = 'ripple';
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';

                    button.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });

            // Handle orientation change for mobile
            window.addEventListener('orientationchange', function() {
                setTimeout(() => {
                    setVhProperty();
                    handleResize();
                }, 150);
            });

            // Optimize scrolling performance
            document.querySelectorAll('.overflow-y-auto').forEach(element => {
                element.style.willChange = 'scroll-position';

                let ticking = false;
                element.addEventListener('scroll', function() {
                    if (!ticking) {
                        window.requestAnimationFrame(function() {
                            ticking = false;
                        });
                        ticking = true;
                    }
                }, { passive: true });
            });

            // Fix iOS input focus issues
            const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
            if (isIOS) {
                document.querySelectorAll('input, textarea').forEach(input => {
                    input.addEventListener('focus', function() {
                        setTimeout(() => {
                            const chatMessages = document.getElementById('chat-messages');
                            if (chatMessages) {
                                chatMessages.scrollTop = chatMessages.scrollHeight;
                            }
                        }, 300);
                    });
                });
            }

            // Add smooth transitions when switching conversations
            const updateActiveConversation = () => {
                const currentPath = window.location.pathname;
                const conversationLinks = document.querySelectorAll('.contact-item');

                conversationLinks.forEach(link => {
                    if (link.getAttribute('href') === currentPath) {
                        link.classList.add('bg-primary-50');
                    } else {
                        link.classList.remove('bg-primary-50');
                    }
                });
            };

            // Call on load
            updateActiveConversation();

            // Initialize tooltips for menu items
            const menuButtons = document.querySelectorAll('#main-menu button');

            // Optimize images
            document.querySelectorAll('img').forEach(img => {
                img.setAttribute('loading', 'lazy');
                img.setAttribute('decoding', 'async');
            });
        });
    </script>

    @stack('scripts')

    <script src="{{ asset('js/messaging.js') }}"></script>
    <script>
        // Set user ID on body
        document.body.dataset.userId = "{{ auth()->id() }}";

        // Messaging Sync Manager - Ensures real-time sync between conversation list and active chat
        class MessagingSyncManager {
            constructor() {
                this.userId = parseInt(document.body.dataset.userId);
                this.activeConversationId = null;
                this.lastSyncTime = Date.now();
                this.listeners = new Map();
                this.conversationData = new Map();

                // Get active conversation ID if available
                const activeConversationEl = document.querySelector('[data-active="true"]');
                if (activeConversationEl) {
                    this.activeConversationId = activeConversationEl.dataset.conversationId;
                }

                this.initializeListeners();
            }

            // Initialize real-time listening for both active chat and sidebar
            initializeListeners() {
                if (typeof window.Echo === 'undefined') {
                    console.log('Echo not available, falling back to polling');
                    this.setupPollingSync();
                    return;
                }

                // Listen to user's private channel for notifications
                window.Echo.private(`user.${this.userId}`)
                    .listen('.message.sent', data => {
                        this.handleNewMessage(data);
                    })
                    .listen('.conversation.updated', data => {
                        this.handleConversationUpdate(data);
                    });

                // Listen to active conversation if any
                if (this.activeConversationId) {
                    this.joinConversation(this.activeConversationId);
                }

                // Set up periodic sync to ensure consistency
                setInterval(() => this.syncConversationList(), 30000);
            }

            // Join a specific conversation channel
            joinConversation(conversationId) {
                if (!conversationId) return;

                // Leave previous conversation if any
                if (this.activeConversationId &&
                    this.activeConversationId !== conversationId &&
                    window.Echo) {
                    window.Echo.leave(`chat.${this.activeConversationId}`);
                }

                this.activeConversationId = conversationId;

                if (window.Echo) {
                    window.Echo.private(`chat.${conversationId}`)
                        .listen('.message.sent', data => {
                            this.handleNewChatMessage(data);
                        })
                        .listen('.message.read', data => {
                            this.handleMessageRead(data);
                        })
                        .listen('.user.typing', data => {
                            this.handleUserTyping(data);
                        });
                }

                // Mark all as read when joining a conversation
                this.markConversationAsRead(conversationId);
            }

            // Handle new message in any conversation (for updating sidebar)
            handleNewMessage(data) {
                // Update conversation in sidebar
                this.updateConversationInSidebar(data.conversation_id, {
                    lastMessage: data,
                    unreadCount: data.user_id !== this.userId ? 1 : 0,
                    increment: true
                });

                // If message is in active conversation, no need to show notification
                if (this.activeConversationId === data.conversation_id) {
                    return;
                }

                // Show notification for new message
                this.showNotification(data.user_name, data.body || 'New message', data.conversation_id);
            }

            // Handle message in active conversation
            handleNewChatMessage(data) {
                // If message is not from current user, mark as read
                if (data.user_id !== this.userId) {
                    this.markMessageAsRead(data.id);
                }

                // Update conversation in sidebar
                this.updateConversationInSidebar(data.conversation_id, {
                    lastMessage: data,
                    unreadCount: 0 // Reset unread in active conversation
                });

                // Append to chat if messagingApp is available
                if (window.messagingApp && window.messagingApp.appendMessage) {
                    const messageElement = document.querySelector(`[data-message-id="${data.id}"]`);
                    if (!messageElement) { // Avoid duplicates
                        window.messagingApp.appendMessage(data);
                    }
                }
            }

            // Handle conversation update events
            handleConversationUpdate(data) {
                // Update sidebar to reflect changes in participants, name, etc.
                this.syncConversationList();
            }

            // Handle message read receipts
            handleMessageRead(data) {
                const messageElements = document.querySelectorAll(`[data-message-id="${data.message_id}"]`);
                messageElements.forEach(element => {
                    const readIndicator = element.querySelector('.fa-check');
                    if (readIndicator) {
                        readIndicator.classList.add('fa-check-double', 'text-primary-500');
                        readIndicator.classList.remove('fa-check');
                    }
                });
            }

            // Update a conversation item in the sidebar
            updateConversationInSidebar(conversationId, { lastMessage, unreadCount, increment = false } = {}) {
                const conversationItem = document.querySelector(`.contact-item[data-conversation-id="${conversationId}"]`);
                if (!conversationItem) return;

                // Update last message preview if provided
                if (lastMessage) {
                    const preview = conversationItem.querySelector('p.text-xs.truncate');
                    if (preview) {
                        // Create preview text
                        let previewText = '';

                        // Add "You: " prefix for own messages
                        if (lastMessage.user_id === this.userId) {
                            previewText += '<span class="font-medium text-gray-600">You: </span>';
                        }
                        // Add sender name for group chats
                        else if (conversationItem.dataset.isGroup === 'true') {
                            previewText += `<span class="font-medium">${lastMessage.user_name.split(' ')[0]}: </span>`;
                        }

                        // Add attachment icon or message body
                        if (lastMessage.attachment) {
                            previewText += '<i class="fas fa-paperclip mr-1"></i> ';
                            previewText += lastMessage.attachment_name || 'Attachment';
                        } else if (lastMessage.is_system) {
                            if (lastMessage.system_action === 'user_added') {
                                previewText += `${lastMessage.user_name} added a user`;
                            } else if (lastMessage.system_action === 'group_created') {
                                previewText += 'Group created';
                            } else {
                                previewText += lastMessage.body || 'System message';
                            }
                        } else {
                            previewText += lastMessage.body || '';
                        }

                        preview.innerHTML = previewText;

                        // Update timestamp
                        const timestamp = conversationItem.querySelector('.text-xs.text-gray-500');
                        if (timestamp) {
                            const messageDate = new Date(lastMessage.created_at);
                            timestamp.textContent = messageDate.toLocaleTimeString([],
                                {hour: 'numeric', minute:'2-digit'});
                        }
                    }
                }

                // Update unread badge
                const badgeContainer = conversationItem.querySelector('.unread-badge');
                if (unreadCount !== undefined) {
                    // If there's a specific count
                    if (badgeContainer) {
                        if (unreadCount > 0) {
                            // If incrementing, take current value
                            const currentCount = increment
                                ? parseInt(badgeContainer.textContent || '0')
                                : 0;

                            badgeContainer.textContent = increment
                                ? currentCount + unreadCount
                                : unreadCount;
                            badgeContainer.parentElement.style.display = 'flex';
                        } else {
                            // Hide badge if count is 0
                            badgeContainer.parentElement.style.display = 'none';
                        }
                    }
                }

                // Move conversation to top of list if it has a new message
                if (lastMessage) {
                    const contactList = conversationItem.parentElement;
                    if (contactList) {
                        // Remove from current position
                        conversationItem.remove();
                        // Add to top of list
                        if (contactList.firstChild) {
                            contactList.insertBefore(conversationItem, contactList.firstChild);
                        } else {
                            contactList.appendChild(conversationItem);
                        }
                    }
                }
            }

            // Mark a message as read
            markMessageAsRead(messageId) {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/api/messages/mark-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message_ids: [messageId] })
                }).catch(error => console.error('Error marking message as read:', error));
            }

            // Mark all messages in a conversation as read
            markConversationAsRead(conversationId) {
                if (!conversationId) return;

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/messaging/${conversationId}/mark-all-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                })
                .then(() => {
                    // Update unread badge in sidebar after marking as read
                    this.updateConversationInSidebar(conversationId, { unreadCount: 0 });
                })
                .catch(error => console.error('Error marking conversation as read:', error));
            }

            // Show browser notification for new messages
            showNotification(title, body, conversationId) {
                // Only show notifications if page is not visible
                if (document.visibilityState === 'visible') return;

                // Check if notifications are supported and permitted
                if (!("Notification" in window)) return;

                if (Notification.permission === "granted") {
                    const notification = new Notification(title, {
                        body: body,
                        icon: '/images/logo.png'
                    });

                    notification.onclick = function() {
                        window.focus();
                        window.location.href = `/messaging/${conversationId}`;
                    };
                }
                else if (Notification.permission !== "denied") {
                    Notification.requestPermission();
                }
            }

            // Sync the conversation list from the server
            syncConversationList() {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/api/conversations', {
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(conversations => {
                    // Update each conversation in sidebar
                    conversations.forEach(conversation => {
                        this.updateConversationInSidebar(conversation.id, {
                            lastMessage: conversation.last_message,
                            unreadCount: conversation.unread_count
                        });
                    });
                })
                .catch(error => console.error('Error syncing conversations:', error));
            }

            // Set up polling fallback if real-time is not available
            setupPollingSync() {
                // Poll for new messages in active conversation
                if (this.activeConversationId) {
                    setInterval(() => {
                        this.pollForNewMessages(this.activeConversationId);
                    }, 3000);
                }

                // Poll for conversation updates less frequently
                setInterval(() => {
                    this.syncConversationList();
                }, 5000);
            }

            // Poll for new messages in a conversation
            pollForNewMessages(conversationId) {
                if (!conversationId) return;

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const lastMsgTime = localStorage.getItem(`last_message_time_${conversationId}`) || 0;

                fetch(`/api/messages/${conversationId}?after=${lastMsgTime}`, {
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(messages => {
                    if (messages && messages.length > 0) {
                        // Process new messages
                        messages.forEach(message => {
                            this.handleNewChatMessage(message);
                        });

                        // Update last message time
                        const latestMsg = messages[messages.length - 1];
                        if (latestMsg) {
                            const msgTime = new Date(latestMsg.created_at).getTime();
                            localStorage.setItem(`last_message_time_${conversationId}`, msgTime);
                        }
                    }
                })
                .catch(error => console.error('Error polling for messages:', error));
            }
        }

        // Initialize the sync manager when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize global synchronization manager
            window.messagingSync = new MessagingSyncManager();

            // Pass existing messagingApp functionality to the sync manager
            if (window.messagingApp) {
                window.messagingApp.syncManager = window.messagingSync;

                // Enhance the original markAllAsRead to update the sidebar
                const originalMarkAllAsRead = window.messagingApp.markAllAsRead;
                window.messagingApp.markAllAsRead = function(conversationId) {
                    if (originalMarkAllAsRead) {
                        originalMarkAllAsRead(conversationId);
                    }

                    // Also update sidebar
                    if (window.messagingSync) {
                        window.messagingSync.updateConversationInSidebar(conversationId, { unreadCount: 0 });
                    }
                };
            }
        });

        // Detect mobile devices
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            document.body.classList.add('mobile-device');
        }

        // Handle page visibility for notifications
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'visible') {
                // Update unread messages
                const activeConversationEl = document.querySelector('[data-active="true"]');
                if (activeConversationEl) {
                    const conversationId = activeConversationEl.dataset.conversationId;
                    if (conversationId && window.messagingSync) {
                        window.messagingSync.markConversationAsRead(conversationId);
                    }
                }
            }
        });
    </script>
</body>
</html>
