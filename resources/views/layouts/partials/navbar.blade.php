<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>

                <li class="nav-item d-none d-sm-inline-block">
                    {{-- <a href="{{ url('/') }}" class="nav-link">Home</a> --}}
                </li>
                <!-- Add more nav items here -->
            </ul>

            <!-- Right navbar links --> 
            <ul class="navbar-nav ml-auto">
                <!-- Add the tour guide button before notifications -->
                @if(auth()->check() && (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Admin') || auth()->user()->hasRole('VP Finance')))
                <li class="nav-item">
                    <button id="startTour" class="nav-link btn btn-link" data-tooltip="Start App Tour">
                        <i class="fas fa-route"></i>
                        <span class="d-none d-md-inline ml-1">Tour Guide</span>
                    </button>
                </li>
                @endif
                @if(auth()->check() && (auth()->user()->hasRole('HR ComBen') || auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Finance') || auth()->user()->hasRole('HR Compliance') || auth()->user()->hasRole('VP Finance')))
                <!-- Search Icon and Popup -->
                <li class="nav-item">
                    <a class="nav-link" href="#" id="search-toggle" data-tooltip="Search">
                        <i class="fas fa-search"></i>
                    </a>
                    <div id="search-popup" class="search-popup" style="display: none;">
                        <div class="search-content">
                            <div class="search-header">
                                <h5 class="mb-0">MHR Search</h5>
                                <button type="button" class="close" id="search-close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="search-input-wrapper">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" id="search-input" class="form-control" placeholder="Search employees, leaves, attendance..." autocomplete="off">
                                <div class="search-shortcuts">
                                    <span class="badge badge-light">Press <kbd>ESC</kbd> to close</span>
                                </div>
                            </div>
                            
                            <!-- Search Results Container -->
                            <div class="search-results-wrapper mt-3">
                                <div class="loading-spinner text-center" style="display: none;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                <div class="search-results">
                                    <div class="search-categories">
                                        <!-- Categories will be filled via JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @endif

                <!-- Notifications Dropdown -->
                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#" data-tooltip="Notifications" id="notifications-dropdown-toggle">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-danger navbar-badge notification-count">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right notifications-dropdown" aria-labelledby="notifications-dropdown-toggle">
                        <span class="dropdown-header"><span id="notification-header-count">0</span> Notifications</span>
                        <div class="dropdown-divider"></div>
                        
                        <!-- Notification Items Container -->
                        <div class="notifications-container">
                            <!-- Loading indicator -->
                            <div class="loading-notifications text-center p-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p class="text-muted mt-2">Loading notifications...</p>
                            </div>
                        </div>
                        
                        <div class="dropdown-divider"></div>
                        <div class="dropdown-item dropdown-actions">
                            <button class="btn btn-sm btn-outline-primary mark-all-read">
                                <i class="fas fa-check-double mr-1"></i> Mark All as Read
                            </button>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('notifications.all') }}" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>
                @endauth

                <!-- Web Push Notification Permission Button -->
                @auth
                <li class="nav-item">
                    <button id="enableNotifications" class="nav-link btn btn-link" onclick="requestNotificationPermission()" style="display: none;" data-tooltip="Enable Notifications">
                        <i class="fas fa-bell"></i>
                    </button>
                </li>
                @endauth

                <!-- Toast Container for Notifications -->
                <div class="toast-container position-fixed bottom-0 left-0 p-3"></div>

                <style>
                    .search-popup {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(9, 30, 66, 0.75);
                        backdrop-filter: blur(4px);
                        -webkit-backdrop-filter: blur(4px);
                        z-index: 9999;
                        display: flex;
                        justify-content: center;
                        align-items: flex-start;
                        padding-top: 70px;
                        opacity: 0;
                        transition: opacity 0.3s cubic-bezier(0.19, 1, 0.22, 1), backdrop-filter 0.3s ease;
                    }
                    
                    .search-popup.visible {
                        opacity: 1;
                    }
                    
                    /* Notification Dropdown Styles */
                    .notifications-dropdown {
                        padding: 0;
                        width: 320px;
                        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
                        border: none;
                        overflow: hidden;
                    }
                    
                    .dropdown-header {
                        background-color: #f8f9fa;
                        padding: 12px 15px;
                        color: #495057;
                        font-weight: 600;
                        border-bottom: 1px solid #eee;
                    }
                    
                    .notifications-container {
                        max-height: 350px;
                        overflow-y: auto;
                    }
                    
                    .notification-item {
                        padding: 12px 15px;
                        display: flex;
                        align-items: flex-start;
                        transition: background-color 0.3s;
                        position: relative;
                    }
                    
                    .notification-item.unread {
                        background-color: rgba(0, 123, 255, 0.05);
                    }
                    
                    .notification-item.unread:before {
                        content: '';
                        position: absolute;
                        left: 0;
                        top: 0;
                        bottom: 0;
                        width: 3px;
                        background-color: #007bff;
                    }
                    
                    .notification-item:hover {
                        background-color: #f8f9fa;
                    }
                    
                    .notification-icon {
                        width: 36px;
                        height: 36px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin-right: 12px;
                        flex-shrink: 0;
                    }
                    
                    .notification-icon i {
                        color: white;
                        font-size: 14px;
                    }
                    
                    .notification-content {
                        flex-grow: 1;
                        padding-right: 5px;
                    }
                    
                    .notification-text {
                        margin-bottom: 4px;
                        color: #333;
                        font-size: 14px;
                        line-height: 1.4;
                    }
                    
                    .notification-time {
                        color: #888;
                        font-size: 12px;
                        margin-bottom: 0;
                    }
                    
                    .notification-action {
                        margin-left: 5px;
                        margin-top: 5px;
                        flex-shrink: 0;
                    }
                    
                    .dropdown-actions {
                        padding: 8px 15px;
                        text-align: center;
                    }
                    
                    .dropdown-actions .btn {
                        width: 100%;
                    }
                    
                    .dropdown-footer {
                        padding: 10px 15px;
                        text-align: center;
                        background-color: #f8f9fa;
                        font-weight: 500;
                        color: #007bff;
                        border-top: 1px solid #eee;
                    }
                    
                    @media (max-width: 576px) {
                        .notifications-dropdown {
                            width: 290px;
                        }
                        
                        .notification-text {
                            font-size: 13px;
                        }
                    }
                    
                    .search-content {
                        width: 90%;
                        max-width: 780px;
                        background: white;
                        padding: 28px;
                        border-radius: 18px;
                        box-shadow: 0 10px 50px rgba(9, 30, 66, 0.25), 0 2px 5px rgba(9, 30, 66, 0.1);
                        transform: translateY(-30px);
                        opacity: 0;
                        transition: transform 0.4s cubic-bezier(0.19, 1, 0.22, 1), 
                                    opacity 0.4s cubic-bezier(0.19, 1, 0.22, 1);
                        max-height: 85vh;
                        overflow-y: auto;
                        scrollbar-width: thin;
                        scrollbar-color: #ddd #f8f8f8;
                    }
                    
                    .search-content::-webkit-scrollbar {
                        width: 6px;
                        height: 6px;
                    }
                    
                    .search-content::-webkit-scrollbar-track {
                        background: #f8f8f8;
                        border-radius: 3px;
                    }
                    
                    .search-content::-webkit-scrollbar-thumb {
                        background-color: #ddd;
                        border-radius: 3px;
                    }
                    
                    .search-popup.visible .search-content {
                        transform: translateY(0);
                        opacity: 1;
                    }
                    
                    .search-header {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin-bottom: 22px;
                        border-bottom: 1px solid #f0f0f0;
                        padding-bottom: 15px;
                    }
                    
                    .search-header h5 {
                        color: #172b4d;
                        font-weight: 700;
                        font-size: 1.35rem;
                        margin: 0;
                        letter-spacing: -0.015em;
                    }
                    
                    .search-header .close {
                        background: none;
                        border: none;
                        font-size: 20px;
                        color: #5e6c84;
                        cursor: pointer;
                        padding: 0;
                        line-height: 1;
                        width: 36px;
                        height: 36px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border-radius: 50%;
                        transition: all 0.2s ease;
                    }
                    
                    .search-header .close:hover {
                        background-color: rgba(9, 30, 66, 0.08);
                        color: #172b4d;
                    }

                    .search-input-wrapper {
                        position: relative;
                        margin-bottom: 24px;
                    }

                    .search-icon {
                        position: absolute;
                        left: 20px;
                        top: 50%;
                        transform: translateY(-50%);
                        color: #5e6c84;
                        font-size: 16px;
                        pointer-events: none;
                    }

                    #search-input {
                        width: 100%;
                        padding: 16px 20px 16px 52px;
                        font-size: 17px;
                        border: 2px solid #dfe1e6;
                        border-radius: 10px;
                        outline: none;
                        transition: all 0.3s;
                        box-shadow: 0 4px 8px rgba(9, 30, 66, 0.05);
                        font-weight: 500;
                        color: #172b4d;
                        background-color: #fafbfc;
                        letter-spacing: -0.01em;
                    }
                    
                    #search-input:focus {
                        border-color: #4c9aff;
                        box-shadow: 0 0 0 2px rgba(76, 154, 255, 0.3);
                        background-color: #fff;
                    }
                    
                    #search-input::placeholder {
                        color: #97a0af;
                        font-weight: 400;
                    }

                    .search-shortcuts {
                        position: absolute;
                        right: 18px;
                        top: 50%;
                        transform: translateY(-50%);
                        opacity: 0.7;
                        transition: opacity 0.2s;
                    }
                    
                    .search-input-wrapper:focus-within .search-shortcuts {
                        opacity: 1;
                    }

                    .search-shortcuts kbd {
                        background: #f4f5f7;
                        border: 1px solid #dfe1e6;
                        border-radius: 4px;
                        box-shadow: 0 1px 1px rgba(9, 30, 66, 0.1);
                        font-size: 11px;
                        padding: 3px 6px;
                        color: #5e6c84;
                        font-family: 'SFMono-Regular', Consolas, monospace;
                    }

                    /* Search Results Styles */
                    .search-results-wrapper {
                        margin-top: 10px;
                    }
                    
                    .search-category {
                        margin-bottom: 28px;
                        background: #fff;
                        border-radius: 10px;
                        overflow: hidden;
                        box-shadow: 0 1px 3px rgba(9, 30, 66, 0.1);
                        border: 1px solid #f0f0f0;
                        transition: transform 0.2s ease, box-shadow 0.2s ease;
                    }
                    
                    .search-category:hover {
                        box-shadow: 0 4px 15px rgba(9, 30, 66, 0.1);
                    }
                    
                    .search-results-items {
                        max-height: 370px;
                        overflow-y: auto;
                        scrollbar-width: thin;
                        scrollbar-color: #ddd #f8f8f8;
                    }
                    
                    .search-results-items::-webkit-scrollbar {
                        width: 6px;
                        height: 6px;
                    }
                    
                    .search-results-items::-webkit-scrollbar-track {
                        background: #f8f8f8;
                    }
                    
                    .search-results-items::-webkit-scrollbar-thumb {
                        background-color: #ddd;
                        border-radius: 4px;
                    }
                    
                    .search-category-title {
                        font-size: 15px;
                        font-weight: 600;
                        color: #172b4d;
                        padding: 15px 20px;
                        margin-bottom: 0;
                        background: #f4f5f7;
                        border-bottom: 1px solid #dfe1e6;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        position: sticky;
                        top: 0;
                        z-index: 2;
                    }
                    
                    .search-category-count {
                        font-size: 13px;
                        color: #5e6c84;
                        background: #fff;
                        padding: 2px 10px;
                        border-radius: 20px;
                        border: 1px solid #dfe1e6;
                        min-width: 28px;
                        text-align: center;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        font-weight: 500;
                    }
                    
                    .search-result-item {
                        display: flex;
                        align-items: flex-start;
                        padding: 16px 20px;
                        transition: all 0.2s;
                        text-decoration: none;
                        color: inherit;
                        border-bottom: 1px solid #f4f5f7;
                        position: relative;
                        overflow: hidden;
                    }
                    
                    .search-result-item:last-child {
                        border-bottom: none;
                    }
                    
                    .search-result-item:hover {
                        background-color: #f4f9ff;
                    }
                    
                    .search-result-item:active {
                        background-color: #e9f2ff;
                    }
                    
                    .search-result-item::after {
                        content: '\f054';
                        font-family: 'Font Awesome 5 Free';
                        font-weight: 900;
                        position: absolute;
                        right: 20px;
                        top: 50%;
                        transform: translateY(-50%);
                        color: #c1c7d0;
                        transition: transform 0.2s ease, color 0.2s ease;
                        font-size: 12px;
                        opacity: 0.7;
                    }
                    
                    .search-result-item:hover::after {
                        transform: translate(4px, -50%);
                        color: #4c9aff;
                        opacity: 1;
                    }
                    
                    .search-result-icon {
                        width: 48px;
                        height: 48px;
                        border-radius: 50%;
                        margin-right: 16px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        flex-shrink: 0;
                        font-size: 18px;
                        color: white;
                        box-shadow: 0 3px 10px rgba(9, 30, 66, 0.15);
                        background-position: center;
                        background-size: cover;
                        position: relative;
                        border: 2px solid rgba(255, 255, 255, 0.95);
                        transition: transform 0.2s ease, box-shadow 0.2s ease;
                    }
                    
                    .search-result-item:hover .search-result-icon {
                        transform: scale(1.05);
                        box-shadow: 0 4px 12px rgba(9, 30, 66, 0.2);
                    }
                    
                    .search-result-info {
                        flex-grow: 1;
                        min-width: 0; /* Allows text to truncate properly */
                        padding-right: 25px; /* Space for the arrow */
                    }
                    
                    .search-result-title {
                        font-weight: 600;
                        margin-bottom: 5px;
                        font-size: 16px;
                        display: flex;
                        align-items: center;
                        flex-wrap: wrap;
                        color: #172b4d;
                        letter-spacing: -0.01em;
                    }
                    
                    .search-result-subtitle {
                        color: #42526e;
                        font-size: 14px;
                        margin-bottom: 5px;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        font-weight: 500;
                    }
                    
                    .search-result-desc {
                        color: #5e6c84;
                        font-size: 13px;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        margin-bottom: 2px;
                    }
                    
                    .search-result-meta {
                        margin-top: 8px;
                        display: flex;
                        flex-wrap: wrap;
                        gap: 8px;
                    }
                    
                    .search-result-meta-item {
                        font-size: 12px;
                        color: #5e6c84;
                        background: #f4f5f7;
                        padding: 3px 8px;
                        border-radius: 4px;
                        white-space: nowrap;
                        display: inline-flex;
                        align-items: center;
                        border: 1px solid #ebecf0;
                        transition: background-color 0.2s ease, border-color 0.2s ease;
                    }
                    
                    .search-result-item:hover .search-result-meta-item {
                        background: #e9f2ff;
                        border-color: #deebff;
                    }
                    
                    .search-result-meta-item i {
                        margin-right: 5px;
                        font-size: 11px;
                        opacity: 0.8;
                    }
                    
                    .search-result-badge {
                        padding: 3px 10px;
                        border-radius: 12px;
                        font-size: 11px;
                        color: white;
                        margin-left: 10px;
                        text-transform: capitalize;
                        white-space: nowrap;
                        font-weight: 500;
                        letter-spacing: 0.01em;
                        box-shadow: 0 1px 2px rgba(9, 30, 66, 0.1);
                    }
                    
                    .bg-success {
                        background-color: #36b37e !important;
                    }
                    
                    .bg-danger {
                        background-color: #ff5630 !important;
                    }
                    
                    .bg-warning {
                        background-color: #ffab00 !important;
                        color: #172b4d !important;
                    }
                    
                    .bg-info {
                        background-color: #0065ff !important;
                    }
                    
                    .bg-primary {
                        background-color: #0052cc !important;
                    }
                    
                    .bg-secondary {
                        background-color: #6b778c !important;
                    }
                    
                    .no-results-found {
                        text-align: center;
                        padding: 60px 0;
                        color: #5e6c84;
                    }
                    
                    .no-results-found i {
                        font-size: 52px;
                        color: #dfe1e6;
                        margin-bottom: 20px;
                    }
                    
                    .no-results-found p {
                        font-size: 18px;
                        margin-bottom: 10px;
                        color: #172b4d;
                        font-weight: 500;
                    }
                    
                    .no-results-found .hint {
                        font-size: 14px;
                        color: #5e6c84;
                        max-width: 400px;
                        margin: 0 auto;
                    }
                    
                    .search-empty-state {
                        text-align: center;
                        padding: 60px 20px;
                        color: #5e6c84;
                    }
                    
                    .search-empty-state i {
                        font-size: 60px;
                        color: #dfe1e6;
                        margin-bottom: 25px;
                    }
                    
                    .search-empty-state p {
                        margin-bottom: 10px;
                        font-size: 20px;
                        color: #172b4d;
                        font-weight: 500;
                    }
                    
                    .search-empty-state .hint {
                        font-size: 15px;
                        color: #5e6c84;
                        max-width: 450px;
                        margin: 0 auto;
                    }
                    
                    .loading-spinner {
                        padding: 60px 0;
                    }
                    
                    .loading-spinner .spinner-border {
                        width: 42px;
                        height: 42px;
                        color: #0052cc;
                    }
                    
                    .loading-spinner .searching-text {
                        font-size: 16px;
                        font-weight: 500;
                        color: #5e6c84;
                        margin-top: 15px;
                    }

                    /* Animated loading dots for "Searching..." text */
                    .searching-text:after {
                        content: '...';
                        animation: dots 1.5s steps(4, end) infinite;
                        display: inline-block;
                        width: 20px;
                        text-align: left;
                    }

                    @keyframes dots {
                        0%, 20% { content: '.'; }
                        40% { content: '..'; }
                        60% { content: '...'; }
                        80%, 100% { content: ''; }
                    }
                    
                    /* Mini highlight animation */
                    @keyframes highlight-pulse {
                        0% { background-color: rgba(76, 154, 255, 0); }
                        50% { background-color: rgba(76, 154, 255, 0.15); }
                        100% { background-color: rgba(76, 154, 255, 0); }
                    }
                    
                    .search-result-highlight {
                        animation: highlight-pulse 1.5s ease-in-out;
                    }

                    /* Mobile responsive styles */
                    @media (max-width: 767px) {
                        .search-popup {
                            padding-top: 30px;
                        }
                        
                        .search-content {
                            width: 95%;
                            padding: 20px;
                            max-height: 90vh;
                            border-radius: 14px;
                        }
                        
                        #search-input {
                            padding: 14px 15px 14px 45px;
                            font-size: 16px;
                        }
                        
                        .search-category-title {
                            font-size: 14px;
                            padding: 12px 15px;
                        }
                        
                        .search-result-icon {
                            width: 42px;
                            height: 42px;
                            margin-right: 14px;
                        }
                        
                        .search-result-item {
                            padding: 14px 15px;
                        }
                        
                        .search-header h5 {
                            font-size: 1.1rem;
                        }
                        
                        .search-shortcuts {
                            display: none;
                        }
                        
                        .search-result-title {
                            font-size: 15px;
                        }
                        
                        .search-result-subtitle {
                            font-size: 13px;
                        }
                        
                        .search-result-meta {
                            margin-top: 6px;
                            gap: 6px;
                        }
                        
                        .search-empty-state p,
                        .no-results-found p {
                            font-size: 16px;
                        }
                    }
                    
                    /* Mini tablets and small screens */
                    @media (min-width: 768px) and (max-width: 991px) {
                        .search-content {
                            width: 85%;
                            max-width: 650px;
                        }
                        
                        .search-result-item::after {
                            right: 15px;
                        }
                    }
                    
                    /* Reduced motion preference */
                    @media (prefers-reduced-motion: reduce) {
                        .search-content, 
                        .search-popup,
                        .search-result-item::after {
                            transition: none;
                        }
                        
                        .search-result-item:hover .search-result-icon {
                            transform: none;
                        }
                        
                        .search-result-highlight {
                            animation: none;
                        }
                    }
                </style>

                <script>
                    $(document).ready(function() {
                        // Global notification variables
                        let latestNotificationTimestamp = 0;
                        let notificationCheckInterval = null;
                        let initialLoad = true;
                        let notificationsData = [];
                        
                        // Function to load notifications
                        function loadNotifications() {
                            $.ajax({
                                url: '{{ route("notifications.get") }}',
                                method: 'GET',
                                cache: false,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Cache-Control': 'no-cache, no-store, must-revalidate'
                                },
                                success: function(response) {
                                    updateNotifications(response);
                            
                                    // Store the latest notification timestamp for future checks
                                    if (response.notifications && response.notifications.length > 0) {
                                        notificationsData = response.notifications;
                                        const timestamps = response.notifications.map(notification => 
                                            new Date(notification.created_at).getTime()
                                        );
                                        latestNotificationTimestamp = Math.max(...timestamps);
                                    }
                                    
                                    // If this is the first load, start checking for updates
                                    if (initialLoad) {
                                        initialLoad = false;
                                        startNotificationUpdateChecks();
                                    }
                                },
                                error: function(xhr) {
                                    console.error('Error loading notifications:', xhr);
                                    $('.loading-notifications').html('<div class="text-danger p-3 text-center">Error loading notifications. Please try again.</div>');
                            }
                        });
                        }
                        
                        // Update the notification UI
                        function updateNotifications(data) {
                            // Update notification count
                            const count = data.unread_count || 0;
                            $('.notification-count').text(count > 99 ? '99+' : count);
                            $('#notification-header-count').text(count);
                            
                            // Update notification list
                            if (data.notifications && data.notifications.length > 0) {
                                renderNotifications(data.notifications);
                            } else {
                                $('.notifications-container').html('<div class="text-muted p-3 text-center">No notifications</div>');
                            }
                        }

                        // Render notifications in the dropdown
                        function renderNotifications(notifications) {
                            const container = $('.notifications-container');
                            container.empty();
                        
                            // Only display the most recent 5 notifications in the dropdown
                            const recentNotifications = notifications.slice(0, 5);
                            
                            recentNotifications.forEach(notification => {
                                const createdDate = new Date(notification.created_at);
                                const formattedTime = formatNotificationTime(createdDate);
                                
                                let notificationHTML = `
                                    <div class="notification-item ${notification.read_at ? '' : 'unread'}" data-id="${notification.id}">
                                        <div class="notification-icon ${getNotificationColor(notification.type)}">
                                            <i class="${notification.icon}"></i>
                                        </div>
                                        <div class="notification-content">
                                            <div class="notification-text">${notification.title}</div>
                                            <div class="notification-description">${notification.message}</div>
                                            <div class="notification-time">${formattedTime}</div>
                                        </div>
                                        <div class="notification-action">
                                            <button class="btn btn-sm btn-link mark-read-btn" data-id="${notification.id}">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </div>
                                    </div>
                                `;
                                
                                container.append(notificationHTML);
                            });
                            
                            // Add event handlers for mark as read buttons
                            $('.mark-read-btn').on('click', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                const id = $(this).data('id');
                                markAsRead(id);
                        });

                            // Make the notification items clickable to view details
                            $('.notification-item').on('click', function() {
                                const id = $(this).data('id');
                                const notification = notifications.find(n => n.id === id);
                                if (notification) {
                                    markAsRead(id);
                                    window.location.href = getNotificationUrl(notification);
                            }
                        });
                        }
                        
                        // Get notification background color based on type
                        function getNotificationColor(type) {
                            switch(type) {
                                case 'leave':
                                    return 'bg-info';
                                case 'cash_advance':
                                    return 'bg-warning';
                                case 'overtime':
                                    return 'bg-success';
                                case 'night_premium':
                                    return 'bg-primary';
                                default:
                                    return 'bg-secondary';
                            }
                        }
                        
                        // Format notification time to be more readable
                        function formatNotificationTime(date) {
                            const now = new Date();
                            const diff = Math.floor((now - date) / 1000); // seconds
                            
                            if (diff < 60) {
                                return 'Just now';
                            } else if (diff < 3600) {
                                const minutes = Math.floor(diff / 60);
                                return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
                            } else if (diff < 86400) {
                                const hours = Math.floor(diff / 3600);
                                return `${hours} hour${hours > 1 ? 's' : ''} ago`;
                            } else if (diff < 604800) {
                                const days = Math.floor(diff / 86400);
                                return `${days} day${days > 1 ? 's' : ''} ago`;
                            } else {
                                return date.toLocaleDateString();
                            }
                        }
                        
                        // Get appropriate URL for a notification
                        function getNotificationUrl(notification) {
                            const type = notification.type;
                            const details = notification.details;
                            
                            if (type.includes('leave')) {
                                return '{{ url("leaves") }}/' + (details.id || '');
                            } else if (type.includes('cash_advance')) {
                                return '{{ url("cash_advances") }}/' + (details.id || '');
                            } else if (type.includes('overtime')) {
                                return '{{ url("overtime") }}/' + (details.id || '');
                            } else if (type.includes('night_premium')) {
                                return '{{ url("night-premium") }}/' + (details.id || '');
                            }
                            
                            return '{{ route("notifications.all") }}';
                                        }
                                        
                        // Mark a notification as read
                        function markAsRead(id) {
                            $.ajax({
                                url: '{{ route("notifications.mark-read") }}',
                                method: 'POST',
                                data: { id: id },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    // Refresh notifications after marking as read
                                    loadNotifications();
                                },
                                error: function(xhr) {
                                    console.error('Error marking notification as read:', xhr);
                                }
                            });
                        }
                        
                        // Mark all notifications as read
                        $('.mark-all-read').on('click', function() {
                            $.ajax({
                                url: '{{ route("notifications.mark-all-read") }}',
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    // Refresh notifications after marking all as read
                                    loadNotifications();
                                
                                    // Show success message
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'All notifications marked as read',
                                        toast: true,
                                        position: 'bottom-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true
                                    });
                                },
                                error: function(xhr) {
                                    console.error('Error marking all notifications as read:', xhr);
                                    
                                    // Show error message
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Failed to mark notifications as read',
                                        toast: true,
                                        position: 'bottom-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true
                                    });
                                }
                            });
                        });
                        
                        // Check for notification updates
                        function checkForUpdates() {
                            $.ajax({
                                url: '{{ route("notifications.check-updates") }}',
                                method: 'GET',
                                data: { 
                                    timestamp: latestNotificationTimestamp 
                                },
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Cache-Control': 'no-cache, no-store, must-revalidate'
                                },
                                success: function(response) {
                                    if (response.has_updates) {
                                        // Reload notifications if there are updates
                                        loadNotifications();
                                        
                                        // Show notification toast if we have new ones
                                        if (response.new_count > 0) {
                                            Swal.fire({
                                                icon: 'info',
                                                title: 'New Notifications',
                                                text: `You have ${response.new_count} new notification${response.new_count !== 1 ? 's' : ''}`,
                                                toast: true,
                                                position: 'bottom-end',
                                                showConfirmButton: false,
                                                timer: 5000,
                                                timerProgressBar: true
                                            });
                                        }
                                    }
                                },
                                error: function(xhr) {
                                    console.error('Error checking for notification updates:', xhr);
                                }
                            });
                        }
                        
                        // Start periodic checks for new notifications
                        function startNotificationUpdateChecks() {
                            // Clear any existing interval
                            if (notificationCheckInterval) {
                                clearInterval(notificationCheckInterval);
                            }
                            
                            // Check for updates every 30 seconds
                            notificationCheckInterval = setInterval(checkForUpdates, 30000);
                        }
                        
                        // Initial load of notifications
                        loadNotifications();
                        
                        // Add specific event for opening the dropdown to refresh notifications
                        $('#notifications-dropdown-toggle').on('click', function() {
                            loadNotifications();
                        });

                        // Add desktop notification support when supported
                        if ('Notification' in window) {
                            // Handle notification permission
                            if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
                                // We'll request permission when the user interacts with notifications
                                $('#notifications-dropdown-toggle').on('click', function() {
                                    Notification.requestPermission();
                                });
                            }
                        }
                    });
                </script>

                <!-- System Updates Icon -->
                @if(isset($systemUpdates))
                <li class="nav-item">
                    <a class="nav-link system-updates-icon" href="#" data-toggle="modal" data-target="#systemUpdatesModal" title="System Updates">
                        <i class="fas fa-sync-alt"></i>
                        @if($systemUpdates['hasUnreadUpdates'])
                            <span class="badge badge-danger navbar-badge updates-count">{{ $systemUpdates['updates']->count() }}</span>
                        @endif
                    </a>
                </li>
                @endif


                @canany(['admin', 'super-admin', 'hrcomben', 'hrcompliance', 'hrpolicy','vpfinance-admin'])
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#" data-tooltip="Announcements and Holidays">
                        <i class="fas fa-bullhorn"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" aria-labelledby="announcements-dropdown">
                        @canany(['super-admin'])
                        <a href="{{ url('types') }}" class="dropdown-item">
                            <i class="fas fa-folder mr-2"></i> Leave Type
                        </a>
                        @endcanany
                        @canany(['admin', 'super-admin', 'hrcompliance', 'hrpolicy','vpfinance-admin'])
                        <a href="{{ url('posts') }}" class="dropdown-item">
                            <i class="fas fa-bullhorn mr-2"></i> Announcement
                        </a>
                        @endcanany
                        @canany(['admin', 'super-admin', 'supervisor','vpfinance-admin'])
                        <a href="{{ url('tasks') }}" class="dropdown-item">
                            <i class="fas fa-tasks mr-2"></i> Send Task
                        </a>
                        @endcanany
                        @canany(['admin', 'super-admin', 'hrcomben'])
                        <a href="{{ url('holidays') }}" class="dropdown-item">
                            <i class="fas fa-calendar-alt mr-2"></i> Holiday
                        </a>
                        @endcanany
                        @can('system-admin')
                        <a href="{{ url('system-updates') }}" class="dropdown-item">
                            <i class="fas fa-sync-alt mr-2"></i> System Updates
                        </a>
                        @endcan
                    </div>
                </li>
                @endcanany

                @canany(['admin', 'super-admin', 'hrcomben'])
                <li class="nav-item">
                    <a class="nav-link contribution-notify-btn" href="#" data-toggle="modal" data-target="#contributeNotifyModal" data-tooltip="Send Contribution Notifications">
                        <i class="fas fa-money-bill-wave"></i>
                        <span class="badge badge-warning navbar-badge notification-badge"><i class="fas fa-bell fa-xs"></i></span>
                    </a>
                </li>
                @endcanany

                @canany(['admin', 'super-admin', 'supervisor'])
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#" data-tooltip="User Management">
                        <i class="fas fa-users"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" aria-labelledby="user-management-dropdown">
                        @canany(['admin', 'super-admin', 'vpfinance-admin'])
                        <a href="{{ url('users') }}" class="dropdown-item">
                            <i class="fas fa-user-cog mr-2"></i> User Management
                        </a>
                        @endcanany
                        @if(auth()->check() && auth()->user()->hasRole('Supervisor'))
                        <a href="{{ route('activity-logs.index') }}" class="dropdown-item">
                            <i class="fas fa-history mr-2"></i> Departmental User Activity
                        </a>
                        @endif
                        @canany(['admin', 'super-admin', 'vpfinance-admin'])
                        <a href="{{ url('/user-activity') }}" class="dropdown-item">
                            <i class="fas fa-history mr-2"></i> User General Logs
                        </a>
                        @endcanany
                        @canany(['super-admin'])
                        <a href="{{ route('database.backups') }}" class="dropdown-item">
                            <i class="fas fa-database mr-2"></i> Database Backups
                        </a>
                        @endcanany
                    </div>
                </li>
                @endcanany


                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/register') }}">Register</a>
                    </li>
                @else
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" data-tooltip="Profile Management" id="profile-dropdown">
                            @if(Auth::user()->adminlte_image())
                                <img src="{{ Auth::user()->adminlte_image() }}" class="user-image img-circle elevation-1" alt="User Image">
                                <span class="d-none d-md-inline">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</span>
                            @else
                                <div class="user-image img-circle elevation-1 d-flex justify-content-center align-items-center">
                                    {{ strtoupper(substr(Auth::user()->first_name, 0, 1) . substr(Auth::user()->last_name, 0, 1)) }}
                                </div>
                                <span class="d-none d-md-inline">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-user" aria-labelledby="profile-dropdown">
                            <div class="user-header">
                                @if(Auth::user()->adminlte_image())
                                    <img src="{{ Auth::user()->adminlte_image() }}" class="img-circle elevation-2" alt="User Image">
                                @else
                                    <div class="img-circle elevation-2 d-flex justify-content-center align-items-center mx-auto">
                                        {{ strtoupper(substr(Auth::user()->first_name, 0, 1) . substr(Auth::user()->last_name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="user-info">
                                    <div class="user-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                                    <span class="user-role">{{ Auth::user()->roles->first()->name ?? 'User' }}</span>
                                </div>
                            </div>
                            
                            <div class="dropdown-menu-content">
                                <a href="/profile/details" class="dropdown-item">
                                    <i class="fas fa-user"></i>
                                    My Profile
                                </a>

                                <!-- Account Management Section -->
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Account Management</h6>
                                
                                <!-- Linked Accounts -->
                                <div class="linked-accounts px-3 py-2">
                                    @foreach(Auth::user()->linkedAccounts as $linkedAccount)
                                        <div class="linked-account d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                                <i class="fas fa-user-circle"></i>
                                                {{ Str::limit($linkedAccount->email, 15) }}
                                            </div>
                                            <div class="btn-group">
                                                <form action="{{ route('account.switch', $linkedAccount->id) }}" method="POST" class="d-inline switch-form">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-exchange-alt"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('account.unlink', $linkedAccount->id) }}" method="POST" class="d-inline unlink-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-outline-danger unlink-btn">
                                                        <i class="fas fa-unlink"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <script>
                                    $(document).ready(function() {
                                        // Handle unlink button click
                                        $('.unlink-btn').on('click', function(e) {
                                            e.preventDefault();
                                            const $form = $(this).closest('form');
                                            const email = $(this).closest('.linked-account').find('.email-text').text().trim();

                                            Swal.fire({
                                                title: 'Are you sure?',
                                                text: "This will unlink the account. This action cannot be undone!",
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#dc3545',
                                                cancelButtonColor: '#6c757d',
                                                confirmButtonText: 'Yes, unlink it!',
                                                cancelButtonText: 'Cancel',
                                                customClass: {
                                                    popup: 'animated fadeInDown faster'
                                                }
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    $.ajax({
                                                        url: $form.attr('action'),
                                                        method: 'POST',
                                                        data: $form.serialize(),
                                                        success: function(response) {
                                                            // Show success Swal
                                                            Swal.fire({
                                                                title: 'Unlinked!',
                                                                text: 'The account has been unlinked successfully.',
                                                                icon: 'success',
                                                                timer: 2000,
                                                                timerProgressBar: true,
                                                                showConfirmButton: false,
                                                                customClass: {
                                                                    popup: 'animated fadeInDown faster'
                                                                }
                                                            });

                                                            // Show success toast
                                                            const toast = `
                                                                <div class="toast success" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
                                                                    <div class="toast-header bg-success text-white">
                                                                        <i class="fas fa-check-circle mr-2"></i>
                                                                        <strong class="mr-auto">Success</strong>
                                                                        <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="toast-body">
                                                                        Account unlinked successfully!
                                                                    </div>
                                                                    <div class="toast-progress"></div>
                                                                </div>
                                                            `;
                                                            
                                                            $('.toast-container').append(toast);
                                                            $('.toast').toast('show');

                                                            // Reload page after short delay
                                                            setTimeout(() => {
                                                                window.location.reload();
                                                            }, 2000);
                                                        },
                                                        error: function(xhr) {
                                                            const message = xhr.responseJSON?.message || 'An error occurred while unlinking the account.';
                                                            
                                                            // Show error Swal
                                                            Swal.fire({
                                                                title: 'Error!',
                                                                text: message,
                                                                icon: 'error',
                                                                confirmButtonText: 'OK',
                                                                customClass: {
                                                                    popup: 'animated shake faster'
                                                                }
                                                            });

                                                            // Show error toast
                                                            const toast = `
                                                                <div class="toast error" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
                                                                    <div class="toast-header bg-danger text-white">
                                                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                                                        <strong class="mr-auto">Error</strong>
                                                                        <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="toast-body">
                                                                        ${message}
                                                                    </div>
                                                                    <div class="toast-progress"></div>
                                                                </div>
                                                            `;
                                                            
                                                            $('.toast-container').append(toast);
                                                            $('.toast').toast('show');
                                                        }
                                                    });
                                                }
                                            });
                                        });
                                    });
                                </script>

                                <!-- Link New Account -->
                                <a href="#" class="dropdown-item" data-toggle="modal" data-target="#linkAccountModal">
                                    <i class="fas fa-link"></i>
                                    Link Another Account
                                </a>
                                @if(auth()->check() && auth()->user()->hasRole('Super Admin'))
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('route-management.index') }}" class="dropdown-item">
                                    <i class="fas fa-route"></i>
                                    Route Management
                                </a>
                                @endif
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('login.history') }}" class="dropdown-item logout-item">
                                    <i class="fas fa-history"></i>
                                    Recent Logins
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout-item">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button" data-tooltip="Settings">
                            <i class="fas fa-cog"></i>
                        </a>
                    </li>
                @endguest
            </ul>
        </nav>

<style>
    /* Custom tooltip styles */
    .tooltip {
        z-index: 9999;
        pointer-events: none;
    }
    
    .tooltip .tooltip-inner {
        background-color: rgba(0, 0, 0, 0.8);
        color: #fff;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        max-width: 200px;
    }
    
    .tooltip.bs-tooltip-top .arrow::before {
        border-top-color: rgba(0, 0, 0, 0.8);
    }
    
    .tooltip.bs-tooltip-bottom .arrow::before {
        border-bottom-color: rgba(0, 0, 0, 0.8);
    }
    
    /* Toast notification styles */
    .toast-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
    }
    
    .toast {
        min-width: 250px;
        margin-bottom: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        opacity: 1 !important;
    }
    
    .toast .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: rgba(255, 255, 255, 0.7);
        width: 0;
        animation: toast-progress 3s linear forwards;
    }
    
    @keyframes toast-progress {
        from { width: 100%; }
        to { width: 0%; }
    }

    /* Additional mobile responsiveness for dropdowns */
    @media (max-width: 767.98px) {
        /* Profile name display */
        .nav-link .d-md-inline {
            display: none;
        }
        
        /* Ensure dropdown toggle is properly sized */
        .dropdown-toggle {
            padding-right: 1.5rem;
        }
        
        /* Fix button touch targets */
        .navbar-nav .nav-link {
            padding: 0.75rem 1rem;
        }
        
        /* Improve dropdown item touch targets */
        .dropdown-item {
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }
    }
</style><script>
    $(document).ready(function() {
        // Initialize tooltips with custom configuration
        $('[data-tooltip]').tooltip({
            trigger: 'hover',
            placement: 'bottom',
            container: 'body',
            boundary: 'window',
            title: function() {
                return $(this).data('tooltip');
            },
            delay: {
                show: 200,
                hide: 0
            }
        });

        // Hide tooltips when dropdown menus are shown
        $('.dropdown').on('show.bs.dropdown', function () {
            $('[data-tooltip]').tooltip('hide');
        });

        // Hide tooltips when search popup is shown
        $('#search-toggle').on('click', function() {
            $('[data-tooltip]').tooltip('hide');
        });

        // Destroy tooltips before showing modals
        $('[data-toggle="modal"]').on('click', function() {
            $('[data-tooltip]').tooltip('hide');
        });
        
        // Cleanup tooltips when elements are removed from DOM
        $(document).on('remove', '[data-tooltip]', function() {
            $(this).tooltip('dispose');
        });
    });
</script>

<!-- Search Functionality Script -->
<script>
    $(document).ready(function() {
        // Show search popup with animations
        $('#search-toggle').click(function(e) {
            e.preventDefault();
            $('#search-popup').css('display', 'flex').addClass('visible');
            setTimeout(function() {
                $('#search-input').focus();
            }, 100);
            
            // Show empty state initially
            showEmptyState();
        });

        // Close search popup with animations
        $('#search-close').click(function() {
            closeSearchPopup();
        });

        // Close search popup when clicking outside
        $(document).click(function(e) {
            if ($('#search-popup').is(':visible') && !$(e.target).closest('#search-popup, #search-toggle').length) {
                closeSearchPopup();
            }
        });
        
        // Function to close search popup with animation
        function closeSearchPopup() {
            $('#search-popup').removeClass('visible');
            setTimeout(function() {
                $('#search-popup').css('display', 'none');
            }, 300);
        }

        // Variable to track the timeout for debouncing
        let searchTimeout = null;
        let lastQuery = '';
        
        // Handle search input with debounce (250ms for faster responses)
        $('#search-input').on('keyup', function(e) {
            const query = $(this).val().trim();
            
            // Clear any existing timeout
            clearTimeout(searchTimeout);
            
            // If query is empty, show empty state
            if (query === '') {
                showEmptyState();
                return;
            }
            
            // Don't search again if query hasn't changed
            if (query === lastQuery) {
                return;
            }
            
            // Show loading state
            showSearchingState(query);
            
            // Set a new timeout for improved performance
            searchTimeout = setTimeout(function() {
                lastQuery = query;
                performSearch(query);
            }, 250);
        });

        // Close on escape key
        $(document).keyup(function(e) {
            if (e.key === "Escape" && $('#search-popup').is(':visible')) {
                closeSearchPopup();
            }
        });
        
        // Function to perform search
        function performSearch(query) {
            $.ajax({
                url: '{{ route("global.search") }}',
                type: 'GET',
                data: {
                    query: query,
                    limit: 10 // Show more results per category for better usability
                },
                dataType: 'json',
                success: function(response) {
                    // Hide loading spinner
                    $('.loading-spinner').hide();
                    
                    if (response.success) {
                        const results = response.results;
                        
                        // Check if we have any results
                        if (results.total_count === 0) {
                            showNoResults(query);
                            return;
                        }
                        
                        // Clear previous results
                        $('.search-categories').empty();
                        
                        // Render employees with staggered animation
                        if (results.employees && results.employees.length > 0) {
                            renderResultsCategory('Employees', results.employees, 'bg-primary', 'fas fa-user');
                        }
                        
                        // Render leaves
                        if (results.leaves && results.leaves.length > 0) {
                            renderResultsCategory('Leave Requests', results.leaves, 'bg-success', 'fas fa-calendar-alt');
                        }
                        
                        // Render attendance
                        if (results.attendances && results.attendances.length > 0) {
                            renderResultsCategory('Attendance Records', results.attendances, 'bg-info', 'fas fa-clock');
                        }
                    } else {
                        showSearchError(response.message || 'An error occurred while searching');
                    }
                },
                error: function(xhr) {
                    // Hide loading spinner
                    $('.loading-spinner').hide();
                    
                    showSearchError('An error occurred while searching. Please try again.');
                    console.error('Search error:', xhr.responseText);
                }
            });
        }
        
        // Function to render a category of results
        function renderResultsCategory(title, items, iconClass, iconName) {
            const $category = $('<div class="search-category"></div>');
            const $title = $('<div class="search-category-title"></div>')
                .text(title)
                .append($('<span class="search-category-count"></span>').text(items.length));
            
            $category.append($title);
            
            const $itemsContainer = $('<div class="search-results-items"></div>');
            
            items.forEach(function(item, index) {
                const $item = $('<a></a>')
                    .addClass('search-result-item')
                    .attr('href', item.url)
                    .attr('title', 'View details for ' + item.title);
                
                // Determine icon and color based on type and status
                let icon = iconName;
                let bgClass = item.status_color ? 'bg-' + item.status_color : iconClass;
                
                // Create icon element
                let $icon;
                if (item.image) {
                    $icon = $('<div class="search-result-icon" style="background-image: url(' + item.image + ');"></div>');
                } else {
                    $icon = $('<div class="search-result-icon ' + bgClass + '"><i class="' + icon + '"></i></div>');
                }
                
                // Create status badge if applicable
                let statusBadge = '';
                if (item.status) {
                    const badgeClass = item.status_color ? 'bg-' + item.status_color : 'bg-secondary';
                    statusBadge = '<span class="search-result-badge ' + badgeClass + '">' + 
                        (item.status.charAt(0).toUpperCase() + item.status.slice(1)) + '</span>';
                }
                
                // Create content
                const $content = $('<div class="search-result-info"></div>');
                $content.append('<div class="search-result-title">' + item.title + statusBadge + '</div>');
                $content.append('<div class="search-result-subtitle">' + item.subtitle + '</div>');
                $content.append('<div class="search-result-desc">' + item.description + '</div>');
                
                // Add metadata if available
                if (item.meta) {
                    const $meta = $('<div class="search-result-meta"></div>');
                    
                    if (item.type === 'employee') {
                        // Employee-specific metadata
                        if (item.meta.company_id) {
                            $meta.append('<span class="search-result-meta-item"><i class="fas fa-id-badge"></i> ' + item.meta.company_id + '</span>');
                        }
                        if (item.meta.email) {
                            $meta.append('<span class="search-result-meta-item"><i class="fas fa-envelope"></i> ' + item.meta.email + '</span>');
                        }
                        if (item.meta.date_hired) {
                            $meta.append('<span class="search-result-meta-item"><i class="fas fa-calendar-check"></i> Hired: ' + item.meta.date_hired + '</span>');
                        }
                    } else if (item.type === 'leave') {
                        // Leave-specific metadata
                        if (item.meta.days) {
                            $meta.append('<span class="search-result-meta-item"><i class="fas fa-calendar-day"></i> ' + item.meta.days + ' day(s)</span>');
                        }
                        if (item.meta.payment_status) {
                            $meta.append('<span class="search-result-meta-item"><i class="fas fa-money-bill-wave"></i> ' + item.meta.payment_status + '</span>');
                        }
                    } else if (item.type === 'attendance') {
                        // Attendance-specific metadata
                        if (item.meta.time_in && item.meta.time_in !== 'N/A') {
                            $meta.append('<span class="search-result-meta-item"><i class="fas fa-sign-in-alt"></i> In: ' + item.meta.time_in + '</span>');
                        }
                        if (item.meta.time_out && item.meta.time_out !== 'N/A') {
                            $meta.append('<span class="search-result-meta-item"><i class="fas fa-sign-out-alt"></i> Out: ' + item.meta.time_out + '</span>');
                        }
                        if (item.meta.hours_worked) {
                            $meta.append('<span class="search-result-meta-item"><i class="fas fa-hourglass-half"></i> ' + item.meta.hours_worked + '</span>');
                        }
                    }
                    
                    $content.append($meta);
                }
                
                $item.append($icon).append($content);
                $itemsContainer.append($item);
            });
            
            $category.append($itemsContainer);
            $('.search-categories').append($category);
        }
        
        // Function to show searching state
        function showSearchingState(query) {
            $('.loading-spinner').show().html(
                '<div class="text-center">' +
                '<div class="spinner-border text-primary mb-2" role="status">' +
                '<span class="sr-only">Loading...</span>' +
                '</div>' +
                '<p class="searching-text mb-0">Searching</p>' +
                '</div>'
            );
            $('.search-categories').empty();
        }
        
        // Function to show empty state
        function showEmptyState() {
            $('.loading-spinner').hide();
            $('.search-categories').html(
                '<div class="search-empty-state">' +
                '<i class="fas fa-search"></i>' +
                '<p>Start typing to search</p>' +
                '<div class="hint">Find employees, leave requests, and attendance records</div>' +
                '</div>'
            );
        }
        
        // Function to show no results message
        function showNoResults(query) {
            $('.search-categories').html(
                '<div class="no-results-found">' +
                '<i class="fas fa-search"></i>' +
                '<p>No results found for "' + query + '"</p>' +
                '<div class="hint">Try different keywords or check your spelling</div>' +
                '</div>'
            );
        }
        
        // Function to show search error
        function showSearchError(message) {
            $('.search-categories').html(
                '<div class="no-results-found">' +
                '<i class="fas fa-exclamation-circle text-danger"></i>' +
                '<p>' + message + '</p>' +
                '<div class="hint">Please try again or contact support if the issue persists</div>' +
                '</div>'
            );
        }
    });
</script>

