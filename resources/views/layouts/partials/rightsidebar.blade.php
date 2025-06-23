<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3 control-sidebar-content">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="text-light m-0">
                <i class="fas fa-paint-brush mr-2"></i>
                Customize Theme
            </h5>
            <button type="button" class="close text-light" data-widget="control-sidebar" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        
        <div class="theme-divider mb-4"></div>
        
        <!-- Theme Color Options -->
        <div class="theme-option-wrapper mb-4">
            <label class="control-sidebar-label">
                <i class="fas fa-palette mr-2"></i>
                Select Theme Color
                <small class="d-block text-muted mt-1">Choose your preferred color scheme</small>
            </label>
            <div class="theme-options mt-3">
                <div class="theme-option bg-primary" data-theme="primary" data-toggle="tooltip" title="Primary"></div>
                <div class="theme-option bg-secondary" data-theme="secondary" data-toggle="tooltip" title="Secondary"></div>
                <div class="theme-option bg-info" data-theme="info" data-toggle="tooltip" title="Info"></div>
                <div class="theme-option bg-success" data-theme="success" data-toggle="tooltip" title="Success"></div>
                <div class="theme-option bg-danger" data-theme="danger" data-toggle="tooltip" title="Danger"></div>
                <div class="theme-option bg-indigo" data-theme="indigo" data-toggle="tooltip" title="Indigo"></div>
                <div class="theme-option bg-purple" data-theme="purple" data-toggle="tooltip" title="Purple"></div>
                <div class="theme-option bg-pink" data-theme="pink" data-toggle="tooltip" title="Pink"></div>
                <div class="theme-option bg-dark" data-theme="dark" data-toggle="tooltip" title="Dark"></div>
            </div>
        </div>
        
        <div class="theme-divider mb-4"></div>
        
        <!-- Navbar Position Options -->
        <div class="navbar-position-wrapper mb-4">
            <label class="control-sidebar-label d-flex align-items-center">
                <i class="fas fa-arrows-alt mr-2"></i>
                <div>
                    Navbar Position
                    <small class="d-block text-muted mt-1">Select how the navigation bar behaves</small>
                </div>
            </label>
            
            <!-- Desktop Radio Button View -->
            <div class="position-options-desktop mt-3 d-none d-lg-block">
                <div class="custom-control custom-radio mb-2">
                    <input type="radio" id="position-static-desktop" name="navbar-position" class="custom-control-input" value="static" checked>
                    <label class="custom-control-label d-flex align-items-center" for="position-static-desktop">
                        <i class="fas fa-thumbtack fa-sm mr-2"></i>
                        <div>
                            <span class="d-block font-weight-medium">Static</span>
                            <small class="text-muted">Default position</small>
                        </div>
                    </label>
                </div>
                
                <div class="custom-control custom-radio mb-2">
                    <input type="radio" id="position-fixed-desktop" name="navbar-position" class="custom-control-input" value="fixed">
                    <label class="custom-control-label d-flex align-items-center" for="position-fixed-desktop">
                        <i class="fas fa-lock fa-sm mr-2"></i>
                        <div>
                            <span class="d-block font-weight-medium">Fixed Top</span>
                            <small class="text-muted">Always visible at top</small>
                        </div>
                    </label>
                </div>
                
                <div class="custom-control custom-radio">
                    <input type="radio" id="position-sticky-desktop" name="navbar-position" class="custom-control-input" value="sticky">
                    <label class="custom-control-label d-flex align-items-center" for="position-sticky-desktop">
                        <i class="fas fa-magnet fa-sm mr-2"></i>
                        <div>
                            <span class="d-block font-weight-medium">Sticky Top</span>
                            <small class="text-muted">Sticks when scrolling</small>
                        </div>
                    </label>
                </div>
            </div>
            
            <!-- Mobile Card View -->
            <div class="position-options mt-3 d-lg-none">
                <!-- Existing mobile cards structure -->
                <div class="position-option" data-position="static">
                    <div class="position-preview">
                        <div class="preview-navbar"></div>
                        <div class="preview-content">
                            <div class="preview-line"></div>
                            <div class="preview-line"></div>
                        </div>
                    </div>
                    <div class="position-label">
                        <span class="position-name">
                            <i class="fas fa-thumbtack fa-sm mr-1"></i>
                            Static
                        </span>
                        <small class="position-desc">Default position</small>
                    </div>
                    <div class="position-check">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
                <div class="position-option" data-position="fixed">
                    <div class="position-preview">
                        <div class="preview-navbar fixed"></div>
                        <div class="preview-content">
                            <div class="preview-line"></div>
                            <div class="preview-line"></div>
                        </div>
                    </div>
                    <div class="position-label">
                        <span class="position-name">
                            <i class="fas fa-lock fa-sm mr-1"></i>
                            Fixed Top
                        </span>
                        <small class="position-desc">Always visible at top</small>
                    </div>
                    <div class="position-check">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
                <div class="position-option" data-position="sticky">
                    <div class="position-preview">
                        <div class="preview-navbar sticky"></div>
                        <div class="preview-content">
                            <div class="preview-line"></div>
                            <div class="preview-line"></div>
                        </div>
                    </div>
                    <div class="position-label">
                        <span class="position-name">
                            <i class="fas fa-magnet fa-sm mr-1"></i>
                            Sticky Top
                        </span>
                        <small class="position-desc">Sticks when scrolling</small>
                    </div>
                    <div class="position-check">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional customization options can be added here -->
        
        <div class="theme-divider mb-4"></div>
    </div>
</aside>

<style>
/* Control Sidebar Enhanced Styles */
.control-sidebar {
    transition: right 0.3s ease-in-out;
}

.control-sidebar-dark {
    background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
}

.control-sidebar-content {
    height: 100%;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,0.2) transparent;
}

.control-sidebar-content::-webkit-scrollbar {
    width: 6px;
}

.control-sidebar-content::-webkit-scrollbar-track {
    background: transparent;
}

.control-sidebar-content::-webkit-scrollbar-thumb {
    background-color: rgba(255,255,255,0.2);
    border-radius: 3px;
}

.theme-divider {
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(255,255,255,0.1), transparent);
}

.control-sidebar-label {
    color: #e9ecef;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.theme-options {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
}

.theme-option {
    aspect-ratio: 1;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 2px solid transparent;
    position: relative;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.theme-option:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.theme-option.active {
    border-color: rgba(255,255,255,0.9);
}

.theme-option.active::after {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #fff;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

/* Navbar Position Options Styling */
.position-options {
    display: grid;
    gap: 1rem;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
}

.position-option {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    padding: 1.25rem;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    position: relative;
    overflow: hidden;
}

.position-option:hover {
    background: rgba(255, 255, 255, 0.08);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.position-option.active {
    border-color: var(--primary, #007bff);
    background: rgba(255, 255, 255, 0.12);
}

.position-preview {
    height: 120px;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    margin-bottom: 1rem;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.preview-navbar {
    height: 24px;
    background: rgba(255, 255, 255, 0.3);
    margin-bottom: 4px;
    transition: all 0.3s ease;
}

.preview-content {
    height: 140px;
    padding: 0.5rem;
}

.preview-line {
    height: 8px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
    margin-bottom: 8px;
    width: 100%;
}

.preview-line:nth-child(2) {
    width: 70%;
}

.position-label {
    display: flex;
    flex-direction: column;
    position: relative;
    z-index: 2;
}

.position-name {
    font-weight: 600;
    color: #fff;
    margin-bottom: 0.25rem;
    font-size: 1rem;
    display: flex;
    align-items: center;
}

.position-desc {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.6);
    line-height: 1.4;
}

.position-check {
    position: absolute;
    top: 1.25rem;
    right: 1.25rem;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: var(--primary, #007bff);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transform: scale(0.8);
    transition: all 0.3s ease;
}

.position-option.active .position-check {
    opacity: 1;
    transform: scale(1);
}

.position-check i {
    color: #fff;
    font-size: 0.8rem;
}

/* Responsive adjustments */
@media (min-width: 992px) {
    .position-options {
        display: none;
    }
}

@media (min-width: 768px) and (max-width: 991px) {
    .position-options {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 767px) {
    .position-options {
        grid-template-columns: 1fr;
    }
    
    .position-option {
        padding: 1rem;
    }
    
    .position-preview {
        height: 100px;
    }
    
    .preview-navbar {
        height: 20px;
    }
    
    .position-name {
        font-size: 0.95rem;
    }
    
    .position-desc {
        font-size: 0.8rem;
    }
}

@media (max-width: 480px) {
    .navbar-position-wrapper {
        margin: 0 -0.5rem;
    }
    
    .position-option {
        border-radius: 8px;
        margin: 0 0.5rem;
    }
}

/* Desktop Radio Button Styles */
.position-options-desktop .custom-control-label {
    cursor: pointer;
    padding: 0.5rem 0;
    width: 100%;
    color: rgba(255, 255, 255, 0.8);
}

.position-options-desktop .custom-control-label::before {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
}

.position-options-desktop .custom-control-input:checked ~ .custom-control-label::before {
    background-color: var(--primary, #007bff);
    border-color: var(--primary, #007bff);
}

.position-options-desktop .custom-control-label:hover {
    color: #fff;
}

.position-options-desktop .custom-control {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    padding: 0.5rem 1rem;
    margin-bottom: 0.5rem;
    transition: all 0.2s ease;
}

.position-options-desktop .custom-control:hover {
    background: rgba(255, 255, 255, 0.08);
}

.position-options-desktop .custom-control-input:checked ~ .custom-control-label {
    color: #fff;
}

.position-options-desktop .custom-control-label div {
    margin-top: 2px;
}

.position-options-desktop .custom-control-label small {
    font-size: 0.8rem;
    opacity: 0.7;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Theme option selection
    const themeOptions = document.querySelectorAll('.theme-option');
    themeOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all options
            themeOptions.forEach(opt => opt.classList.remove('active'));
            // Add active class to selected option
            this.classList.add('active');
            // Get selected theme
            const theme = this.dataset.theme;
            // Apply theme (implement your theme switching logic here)
            console.log('Selected theme:', theme);
        });
    });
    
    // Navbar position selection - Updated for both desktop and mobile
    const positionOptions = document.querySelectorAll('.position-option');
    const positionRadios = document.querySelectorAll('input[name="navbar-position"]');
    const navbar = document.querySelector('.main-header');
    let currentPosition = localStorage.getItem('navbarPosition') || 'static';
    
    // Set initial position
    setNavbarPosition(currentPosition);
    activatePositionOption(currentPosition);
    
    // Desktop radio button handlers
    positionRadios.forEach(radio => {
        if(radio.value === currentPosition) {
            radio.checked = true;
        }
        
        radio.addEventListener('change', function() {
            const position = this.value;
            setNavbarPosition(position);
            localStorage.setItem('navbarPosition', position);
            showPositionChangeToast(position);
            
            // Sync with mobile view
            activatePositionOption(position);
        });
    });
    
    // Mobile card handlers
    positionOptions.forEach(option => {
        option.addEventListener('click', function() {
            const position = this.dataset.position;
            
            // Remove active class from all options
            positionOptions.forEach(opt => opt.classList.remove('active'));
            // Add active class to selected option
            this.classList.add('active');
            
            // Sync with desktop radio buttons
            const radio = document.querySelector(`input[name="navbar-position"][value="${position}"]`);
            if(radio) radio.checked = true;
            
            // Apply navbar position
            setNavbarPosition(position);
            
            // Save preference
            localStorage.setItem('navbarPosition', position);
            
            // Show feedback toast
            showPositionChangeToast(position);
        });
    });
    
    function setNavbarPosition(position) {
        // Remove all position classes
        navbar.classList.remove('position-static', 'fixed-top', 'sticky-top');
        
        // Add appropriate class based on position
        switch(position) {
            case 'fixed':
                navbar.classList.add('fixed-top');
                document.body.style.paddingTop = navbar.offsetHeight + 'px';
                break;
            case 'sticky':
                navbar.classList.add('sticky-top');
                document.body.style.paddingTop = '0';
                break;
            default: // static
                navbar.classList.add('position-static');
                document.body.style.paddingTop = '0';
                break;
        }
    }
    
    function activatePositionOption(position) {
        positionOptions.forEach(opt => {
            if (opt.dataset.position === position) {
                opt.classList.add('active');
            } else {
                opt.classList.remove('active');
            }
        });
    }
    
    function showPositionChangeToast(position) {
        const positions = {
            static: 'Static (Default)',
            fixed: 'Fixed Top',
            sticky: 'Sticky Top'
        };
        
        const toast = `
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000">
                <div class="toast-header">
                    <i class="fas fa-arrows-alt mr-2"></i>
                    <strong class="mr-auto">Navbar Position</strong>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    Navbar position changed to: ${positions[position]}
                </div>
            </div>
        `;
        
        const toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed bottom-0 right-0 p-3';
        toastContainer.innerHTML = toast;
        document.body.appendChild(toastContainer);
        
        $('.toast').toast('show');
        
        // Remove toast container after it's hidden
        $('.toast').on('hidden.bs.toast', function() {
            toastContainer.remove();
        });
    }
    
    // Reset button
    const resetButton = document.querySelector('.btn-outline-light');
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            // Reset navbar position to static
            setNavbarPosition('static');
            activatePositionOption('static');
            localStorage.removeItem('navbarPosition');
            showPositionChangeToast('static');
        });
    }
});
</script>