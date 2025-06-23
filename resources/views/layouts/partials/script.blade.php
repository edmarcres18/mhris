<script>
    $(document).ready(function() {
        // Preloader
        $(window).on('load', function() {
            $('#loader').fadeOut('slow', function() {
                $(this).remove();
            });
        });

        // If the page takes too long to load, hide the preloader after 1 second
        setTimeout(function() {
            $('#loader').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 1000);

        // Theme customization
        function applyTheme(navbarClass, sidebarClass, brandClass) {
            // Apply navbar theme
            $('.main-header').attr('class', 'main-header navbar navbar-expand ' + navbarClass);

            // Apply sidebar theme
            $('.main-sidebar').attr('class', 'main-sidebar ' + sidebarClass);

            // Apply brand theme
            $('.brand-link').attr('class', 'brand-link ' + brandClass);

            // Update active links color in sidebar
            $('.nav-sidebar .nav-link.active').css('background-color', getComputedStyle(document.documentElement).getPropertyValue('--' + sidebarClass.split('-')[2] + '-color'));

            // Update navbar text and icon colors
            updateNavbarColors(navbarClass);

            // Save theme preferences
            localStorage.setItem('navbarVariant', navbarClass);
            localStorage.setItem('sidebarVariant', sidebarClass);
            localStorage.setItem('brandVariant', brandClass);

            // Update select values
            $('#theme-select').val(navbarClass.split('-')[1]);
        }

        // Function to update navbar text and icon colors
        function updateNavbarColors(navbarClass) {
            var isDark = navbarClass.includes('navbar-dark');
            var textColor = isDark ? '#ffffff' : '#000000';
            var iconColor = isDark ? '#ffffff' : '#000000';

            $('.main-header .nav-link').css('color', textColor);
            $('.main-header .nav-link i').css('color', iconColor);

            // Adjust dropdown text colors
            $('.main-header .dropdown-menu a').css('color', '#212529');

            // Adjust navbar brand text color
            $('.main-header .navbar-brand').css('color', textColor);
        }

        // Theme change event handler
        $('.theme-option').on('click', function() {
            var selectedTheme = $(this).data('theme');
            var navbarClass = 'navbar-' + selectedTheme + ' ' + (isLightColor(selectedTheme) ? 'navbar-light' : 'navbar-dark');
            var sidebarClass = 'sidebar-dark-' + selectedTheme;
            var brandClass = 'bg-' + selectedTheme;

            applyTheme(navbarClass, sidebarClass, brandClass);

            // Update active state
            $('.theme-option').removeClass('active');
            $(this).addClass('active');
        });

        // Function to determine if a color is light
        function isLightColor(color) {
            var lightColors = ['light', 'warning', 'white', 'orange', 'lime', 'teal', 'cyan'];
            return lightColors.includes(color);
        }

        // Load saved theme
        function loadSavedTheme() {
            var navbarVariant = localStorage.getItem('navbarVariant') || 'navbar-dark navbar-primary';
            var sidebarVariant = localStorage.getItem('sidebarVariant') || 'sidebar-dark-primary';
            var brandVariant = localStorage.getItem('brandVariant') || 'bg-primary';

            applyTheme(navbarVariant, sidebarVariant, brandVariant);

            // Set active state on the correct theme option
            var activeTheme = navbarVariant.split('-')[2] || 'primary';
            $('.theme-option[data-theme="' + activeTheme + '"]').addClass('active');
        }

        // Call this function on page load
        loadSavedTheme();

        // Navbar Position Functionality
        function applyNavbarPosition(position) {
            const $body = $('body');
            const $navbar = $('.main-header');

            // Remove existing classes
            $body.removeClass('layout-navbar-fixed layout-navbar-not-fixed');
            $navbar.removeClass('fixed-top sticky-top');

            switch (position) {
                case 'fixed':
                    $body.addClass('layout-navbar-fixed');
                    $navbar.addClass('fixed-top');
                    break;
                case 'sticky':
                    $navbar.addClass('sticky-top');
                    break;
                default: // 'static'
                    $body.addClass('layout-navbar-not-fixed');
                    break;
            }

            // Save preference
            localStorage.setItem('navbarPosition', position);
        }

        // Navbar position change event handler
        $('#navbar-position-select').on('change', function() {
            const selectedPosition = $(this).val();
            applyNavbarPosition(selectedPosition);
        });

        // Load saved navbar position
        function loadSavedNavbarPosition() {
            const savedPosition = localStorage.getItem('navbarPosition') || 'static';
            $('#navbar-position-select').val(savedPosition);
            applyNavbarPosition(savedPosition);
        }

        // Call this function on page load
        loadSavedNavbarPosition();

        // Notifications handling
        function loadNotifications() {
            $.ajax({
                url: '/notifications/get',
                method: 'GET',
                success: function(response) {
                    updateNotifications(response);
                },
                error: function(xhr) {
                    console.error('Error loading notifications:', xhr);
                }
            });
        }

        function updateNotifications(data) {
            $('.notification-count').text(data.count);
            $('.notifications-list').html(data.notifications);
            
            if (data.toast && data.toast.message) {
                showToast(data.toast);
            }
        }

        function showToast(toastData) {
            const toast = `
                <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
                    <div class="toast-header">
                        <i class="${toastData.icon} mr-2"></i>
                        <strong class="mr-auto">${toastData.title}</strong>
                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="toast-body">
                        ${toastData.message}
                    </div>
                    <div class="toast-progress"></div>
                </div>
            `;

            // Create toast container if it doesn't exist
            if (!$('.toast-container').length) {
                $('body').append('<div class="toast-container"></div>');
            }

            // Add toast to container and show it
            const $toast = $(toast);
            $('.toast-container').append($toast);
            $toast.toast('show');

            // Remove toast after it's hidden
            $toast.on('hidden.bs.toast', function() {
                $(this).remove();
            });
        }

        // Load notifications on page load
        loadNotifications();

        // Set up Echo to listen for new notifications
        window.Echo.channel('notifications')
            .listen('NewNotification', (e) => {
                updateNotifications(e);
            });

        // No need for setInterval as we're using real-time updates
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function checkAndShowCelebrants() {
            fetch('/api/today-celebrants', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.celebrants && data.celebrants.length > 0 && !data.userDismissed) {
                    // Update modal content
                    const modalBody = document.getElementById('celebrantsModalBody');
                    modalBody.innerHTML = data.celebrants.map(celebrant => `
                        <div class="d-flex align-items-center mb-3">
                            <div class="mr-3">
                                ${celebrant.profile_picture ? 
                                    `<img src="${celebrant.profile_picture}" class="rounded-circle" width="50" height="50" alt="${celebrant.name}">` :
                                    `<div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px">
                                        ${celebrant.name.split(' ').map(n => n[0]).join('')}
                                    </div>`
                                }
                            </div>
                            <div>
                                <h6 class="mb-0">${celebrant.name}</h6>
                                <small class="text-muted">${celebrant.department}</small>
                            </div>
                        </div>
                    `).join('');

                    // Show modal
                    $('#celebrantsModal').modal('show');
                }
            })
            .catch(error => {
                console.error('Error fetching celebrants:', error);
            });
        }

        // Handle checkbox change
        document.getElementById('dontShowToday').addEventListener('change', function(e) {
            if (e.target.checked) {
                fetch('/api/dismiss-celebrants', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .catch(error => {
                    console.error('Error dismissing celebrants:', error);
                });
            }
        });

        // Check for celebrants when page loads
        checkAndShowCelebrants();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quickActionsToggle = document.getElementById('quickActionsToggle');
        const quickActionsCard = document.getElementById('quickActionsCard');
        
        // Toggle quick actions card
        quickActionsToggle.addEventListener('click', function() {
            quickActionsCard.classList.toggle('show');
            this.classList.toggle('active');
        });
        
        // Close quick actions when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInside = quickActionsToggle.contains(event.target) || 
                                quickActionsCard.contains(event.target);
            
            if (!isClickInside && quickActionsCard.classList.contains('show')) {
                quickActionsCard.classList.remove('show');
                quickActionsToggle.classList.remove('active');
            }
        });
        
        // Prevent closing when clicking inside the card
        quickActionsCard.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    });
</script>

<!-- Theme Management System -->
<script>
const ThemeManager = {
    themes: {
        primary: {
            navbar: 'navbar-dark navbar-primary',
            sidebar: 'sidebar-dark-primary',
            brand: 'bg-primary'
        },
        secondary: {
            navbar: 'navbar-dark navbar-secondary',
            sidebar: 'sidebar-dark-secondary',
            brand: 'bg-secondary'
        },
        success: {
            navbar: 'navbar-dark navbar-success',
            sidebar: 'sidebar-dark-success',
            brand: 'bg-success'
        },
        danger: {
            navbar: 'navbar-dark navbar-danger',
            sidebar: 'sidebar-dark-danger',
            brand: 'bg-danger'
        },
        warning: {
            navbar: 'navbar-light navbar-warning',
            sidebar: 'sidebar-dark-warning',
            brand: 'bg-warning'
        },
        info: {
            navbar: 'navbar-dark navbar-info',
            sidebar: 'sidebar-dark-info',
            brand: 'bg-info'
        },
        dark: {
            navbar: 'navbar-dark navbar-dark',
            sidebar: 'sidebar-dark-dark',
            brand: 'bg-dark'
        },
        purple: {
            navbar: 'navbar-dark navbar-purple',
            sidebar: 'sidebar-dark-purple',
            brand: 'bg-purple'
        },
        indigo: {
            navbar: 'navbar-dark navbar-indigo',
            sidebar: 'sidebar-dark-indigo',
            brand: 'bg-indigo'
        },
        pink: {
            navbar: 'navbar-dark navbar-pink',
            sidebar: 'sidebar-dark-pink',
            brand: 'bg-pink'
        }
    },

    themeColors: {
        primary: '#007bff',
        secondary: '#6c757d',
        success: '#28a745',
        danger: '#dc3545',
        warning: '#ffc107',
        info: '#17a2b8',
        dark: '#343a40',
        purple: '#6f42c1',
        indigo: '#6610f2',
        pink: '#e83e8c'
    },

    init() {
        this.loadSavedTheme();
        this.bindEvents();
    },

    bindEvents() {
        $('.theme-option').on('click', (e) => {
            const theme = $(e.currentTarget).data('theme');
            this.applyTheme(theme);
            this.saveTheme(theme);
            this.updateActiveState(e.currentTarget);
        });
    },

    applyTheme(themeName) {
        const theme = this.themes[themeName];
        const themeColor = this.themeColors[themeName];
        if (!theme) return;

        // Existing theme applications
        $('.main-header')
            .removeClass(Object.values(this.themes).map(t => t.navbar).join(' '))
            .addClass(theme.navbar);

        $('.main-sidebar')
            .removeClass(Object.values(this.themes).map(t => t.sidebar).join(' '))
            .addClass(theme.sidebar);

        $('.brand-link')
            .removeClass(Object.values(this.themes).map(t => t.brand).join(' '))
            .addClass(theme.brand);

        // Apply theme to Quick Actions
        $('.quick-actions-button').css('background-color', themeColor);
        $('.quick-actions-header').css('background-color', themeColor);

        // Update hover effect for quick actions button
        const darkerColor = this.adjustColor(themeColor, -20); // Darken by 20%
        const style = document.createElement('style');
        style.textContent = `
            .quick-actions-button:hover {
                background-color: ${darkerColor} !important;
                transform: scale(1.1);
            }
        `;
        // Remove any previous dynamic styles
        document.querySelectorAll('style[data-theme-style]').forEach(el => el.remove());
        style.setAttribute('data-theme-style', 'true');
        document.head.appendChild(style);

        // Update navbar colors
        this.updateNavbarColors(theme.navbar);
    },

    updateNavbarColors(navbarClass) {
        const isDark = navbarClass.includes('navbar-dark');
        const textColor = isDark ? '#ffffff' : '#000000';
        
        $('.main-header .nav-link').css('color', textColor);
        $('.main-header .navbar-brand').css('color', textColor);
        
        // Preserve dropdown text colors
        $('.main-header .dropdown-menu a').css('color', '#212529');
    },

    saveTheme(theme) {
        localStorage.setItem('selectedTheme', theme);
    },

    loadSavedTheme() {
        const savedTheme = localStorage.getItem('selectedTheme') || 'primary';
        this.applyTheme(savedTheme);
        this.updateActiveState($(`.theme-option[data-theme="${savedTheme}"]`));
    },

    updateActiveState(element) {
        $('.theme-option').removeClass('active');
        $(element).addClass('active');
    },

    // Helper function to darken/lighten colors
    adjustColor(color, percent) {
        const num = parseInt(color.replace('#', ''), 16);
        const amt = Math.round(2.55 * percent);
        const R = (num >> 16) + amt;
        const G = (num >> 8 & 0x00FF) + amt;
        const B = (num & 0x0000FF) + amt;
        return '#' + (
            0x1000000 +
            (R < 255 ? (R < 1 ? 0 : R) : 255) * 0x10000 +
            (G < 255 ? (G < 1 ? 0 : G) : 255) * 0x100 +
            (B < 255 ? (B < 1 ? 0 : B) : 255)
        ).toString(16).slice(1);
    }
};

// Initialize Theme Manager
$(document).ready(() => {
    ThemeManager.init();
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltip
    $('#startTour').tooltip();

    // Configure tour
    const tour = new Shepherd.Tour({
        useModalOverlay: true,
        defaultStepOptions: {
            classes: 'shadow-md bg-purple-dark',
            scrollTo: true,
            cancelIcon: {
                enabled: true
            }
        },
        tourName: 'app-tour'
    });

    // Define tour steps
    tour.addStep({
        id: 'welcome',
        text: `
            <div class="text-center">
                <h3 class="font-weight-bold mb-3">Welcome to MHRPCI-HRIS! 👋</h3>
                <p>Let's take a quick tour of the main features.</p>
            </div>
        `,
        buttons: [
            {
                text: 'Skip Tour',
                action: tour.complete,
                classes: 'btn btn-secondary'
            },
            {
                text: 'Start Tour',
                action: tour.next,
                classes: 'btn btn-primary'
            }
        ]
    });

    // Sidebar navigation
    tour.addStep({
        id: 'sidebar',
        text: 'The sidebar contains all main navigation items. Click items to access different sections.',
        attachTo: {
            element: '.main-sidebar',
            on: 'right'
        },
        buttons: [
            {
                text: 'Back',
                action: tour.back,
                classes: 'btn btn-secondary'
            },
            {
                text: 'Next',
                action: tour.next,
                classes: 'btn btn-primary'
            }
        ]
    });

    // Quick actions
    if (document.querySelector('.quick-actions-fab')) {
        tour.addStep({
            id: 'quick-actions',
            text: 'Access common actions quickly from this floating button.',
            attachTo: {
                element: '.quick-actions-fab',
                on: 'left'
            },
            buttons: [
                {
                    text: 'Back',
                    action: tour.back,
                    classes: 'btn btn-secondary'
                },
                {
                    text: 'Next',
                    action: tour.next,
                    classes: 'btn btn-primary'
                }
            ]
        });
    }

    // Notifications
    tour.addStep({
        id: 'notifications',
        text: 'Check your notifications here. The badge shows unread notifications.',
        attachTo: {
            element: '#notifications-dropdown',
            on: 'bottom'
        },
        buttons: [
            {
                text: 'Back',
                action: tour.back,
                classes: 'btn btn-secondary'
            },
            {
                text: 'Next',
                action: tour.next,
                classes: 'btn btn-primary'
            }
        ]
    });

    // Theme customization
    tour.addStep({
        id: 'theme',
        text: 'Customize the app appearance using the theme settings.',
        attachTo: {
            element: '[data-widget="control-sidebar"]',
            on: 'left'
        },
        buttons: [
            {
                text: 'Back',
                action: tour.back,
                classes: 'btn btn-secondary'
            },
            {
                text: 'Next',
                action: tour.next,
                classes: 'btn btn-primary'
            }
        ]
    });

    // User menu
    tour.addStep({
        id: 'user-menu',
        text: 'Access your profile and account settings here.',
        attachTo: {
            element: '.user-menu',
            on: 'bottom'
        },
        buttons: [
            {
                text: 'Back',
                action: tour.back,
                classes: 'btn btn-secondary'
            },
            {
                text: 'Finish',
                action: tour.complete,
                classes: 'btn btn-primary'
            }
        ]
    });

    // Handle tour button click
    document.getElementById('startTour').addEventListener('click', function() {
        // Check if tour was completed before
        const tourCompleted = localStorage.getItem('tourCompleted');
        
        if (!tourCompleted) {
            tour.start();
        } else {
            // Ask if user wants to take the tour again
            if (confirm('You have already completed the tour. Would you like to take it again?')) {
                tour.start();
            }
        }
    });

    // Mark tour as completed when finished
    tour.on('complete', function() {
        localStorage.setItem('tourCompleted', 'true');
    });

    // Add responsive handling
    function updateTourAttachment() {
        if (window.innerWidth < 768) {
            // Adjust attachments for mobile
            tour.steps.forEach(step => {
                if (step.options.attachTo) {
                    step.options.attachTo.on = 'bottom';
                }
            });
        }
    }

    // Update on resize
    window.addEventListener('resize', updateTourAttachment);
    updateTourAttachment();

    // Add tour button animation
    const tourButton = document.getElementById('startTour');
    if (!localStorage.getItem('tourCompleted')) {
        tourButton.classList.add('pulse-animation');
    }
});
</script>
<script>
    $(document).ready(function() {
        // Handle link account form submission
        $('#linkAccountForm').on('submit', function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const $submitBtn = $('#linkAccountBtn');
            const $error = $('#linkAccountError');
            
            // Show loading state
            $submitBtn.prop('disabled', true);
            $submitBtn.find('.normal-text').hide();
            $submitBtn.find('.loading-text').show();
            $error.hide();

            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: $form.serialize(),
                success: function(response) {
                    // Hide modal
                    $('#linkAccountModal').modal('hide');
                    
                    // Show success Swal
                    Swal.fire({
                        title: 'Success!',
                        text: 'Account linked successfully!',
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
                                Account linked successfully!
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
                    // Show error message
                    const message = xhr.responseJSON?.message || 'An error occurred while linking the account.';
                    $error.html(message).show();
                    
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
                },
                complete: function() {
                    // Reset button state
                    $submitBtn.prop('disabled', false);
                    $submitBtn.find('.loading-text').hide();
                    $submitBtn.find('.normal-text').show();
                }
            });
        });

        // Remove toasts when hidden
        $(document).on('hidden.bs.toast', '.toast', function() {
            $(this).remove();
        });

        // Reset form when modal is closed
        $('#linkAccountModal').on('hidden.bs.modal', function() {
            $('#linkAccountForm')[0].reset();
            $('#linkAccountError').hide();
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Password toggle functionality
        $('#togglePassword').click(function() {
            const passwordInput = $('#password');
            const icon = $(this).find('i');
            
            // Toggle password visibility
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        // Reset password visibility when modal is closed
        $('#linkAccountModal').on('hidden.bs.modal', function() {
            $('#password').attr('type', 'password');
            $('#togglePassword i').removeClass('fa-eye-slash').addClass('fa-eye');
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const accountSwitchToast = document.querySelector('.account-switch-toast');
        if (accountSwitchToast) {
            $(accountSwitchToast).toast('show');
        }
    });
</script>

<!-- System Updates Handler -->
<script>
$(document).ready(function() {
    // Initialize tooltip for system updates icon
    $('.system-updates-icon').tooltip();

    // Handle system updates icon click
    $('.system-updates-icon').on('click', function(e) {
        const today = new Date().toDateString();
        const updatesModalShown = localStorage.getItem('updates_modal_shown');

        // Check if updates should be shown
        if (!updatesModalShown || updatesModalShown !== today) {
            $('#systemUpdatesModal').modal('show');
        }
    });

    // Handle "Don't show again" checkbox
    $('#dontShowUpdatesAgain').on('change', function() {
        const today = new Date().toDateString();
        const confirmationDiv = $(this).closest('.dont-show-again').find('.confirmation-message');
        
        if (this.checked) {
            localStorage.setItem('updates_modal_shown', today);
            confirmationDiv.text("Update notifications won't show again today.").show();
        } else {
            localStorage.removeItem('updates_modal_shown');
            confirmationDiv.hide();
        }
    });

    // Theme-aware updates icon
    function updateSystemUpdatesIconTheme() {
        const isDarkMode = $('body').hasClass('dark-mode');
        const $icon = $('.system-updates-icon');
        
        if (isDarkMode) {
            $icon.addClass('text-light').removeClass('text-dark');
        } else {
            $icon.addClass('text-dark').removeClass('text-light');
        }
    }

    // Update theme when dark mode changes
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                updateSystemUpdatesIconTheme();
            }
        });
    });

    observer.observe(document.body, {
        attributes: true
    });

    // Initial theme setup
    updateSystemUpdatesIconTheme();
});
</script>