/**
 * Responsive Dropdowns Enhancement
 * Makes dropdowns more touch-friendly and responsive across all devices
 */
(function() {
  'use strict';
  
  document.addEventListener('DOMContentLoaded', function() {
    // Detect if device is mobile
    const isMobile = window.matchMedia('(max-width: 767.98px)').matches;
    
    // Get all dropdown toggles in the navbar
    const dropdownToggles = document.querySelectorAll('.navbar-nav .dropdown-toggle');
    
    // Keep track of open dropdown
    let currentOpenDropdown = null;
    
    // Add click outside listener for mobile
    if (isMobile) {
      document.addEventListener('click', function(event) {
        if (currentOpenDropdown && !currentOpenDropdown.contains(event.target)) {
          const dropdownMenu = currentOpenDropdown.querySelector('.dropdown-menu');
          if (dropdownMenu && dropdownMenu.classList.contains('show')) {
            // Create close animation
            dropdownMenu.style.animation = 'slide-down 0.3s ease-out forwards';
            
            // Remove dropdown after animation
            setTimeout(() => {
              $(currentOpenDropdown).dropdown('hide');
              dropdownMenu.style.animation = '';
            }, 300);
          }
        }
      });
      
      // Add touch swipe down to close functionality
      dropdownToggles.forEach(toggle => {
        const dropdown = toggle.closest('.dropdown');
        const menu = dropdown.querySelector('.dropdown-menu');
        
        if (menu) {
          let startY, moveY;
          
          menu.addEventListener('touchstart', function(e) {
            startY = e.touches[0].clientY;
          }, { passive: true });
          
          menu.addEventListener('touchmove', function(e) {
            moveY = e.touches[0].clientY;
            
            // Calculate distance moved
            const diff = moveY - startY;
            
            // If swiping down more than 50px
            if (diff > 50) {
              // Create close animation and hide dropdown
              menu.style.animation = 'slide-down 0.3s ease-out forwards';
              
              // Remove dropdown after animation
              setTimeout(() => {
                $(dropdown).dropdown('hide');
                menu.style.animation = '';
              }, 300);
              
              startY = null;
            }
          }, { passive: true });
        }
      });
    }
    
    // Enhance dropdowns for all devices
    dropdownToggles.forEach(toggle => {
      const dropdown = toggle.closest('.dropdown');
      
      // Track the current open dropdown
      dropdown.addEventListener('show.bs.dropdown', function() {
        if (currentOpenDropdown && currentOpenDropdown !== dropdown) {
          $(currentOpenDropdown).dropdown('hide');
        }
        currentOpenDropdown = dropdown;
        
        // Add animation class
        setTimeout(() => {
          const menu = dropdown.querySelector('.dropdown-menu');
          if (menu) {
            menu.classList.add('show-animated');
          }
        }, 0);
      });
      
      // Clean up on hide
      dropdown.addEventListener('hide.bs.dropdown', function() {
        if (currentOpenDropdown === dropdown) {
          currentOpenDropdown = null;
        }
        
        // Remove animation class
        const menu = dropdown.querySelector('.dropdown-menu');
        if (menu) {
          menu.classList.remove('show-animated');
        }
      });
    });
    
    // Fix dropdown position for mobile devices
    if (isMobile) {
      window.adjustDropdownPositions = function() {
        const dropdownMenus = document.querySelectorAll('.dropdown-menu.show');
        
        dropdownMenus.forEach(menu => {
          // Ensure proper positioning for bottom sheet style on mobile
          menu.style.top = 'auto';
          menu.style.transform = 'none';
          
          // Add bottom safe area for iOS devices
          const bottomPadding = 'env(safe-area-inset-bottom)';
          menu.style.paddingBottom = 
            menu.style.paddingBottom ? 
            `calc(${menu.style.paddingBottom} + ${bottomPadding})` : 
            bottomPadding;
        });
      };
      
      // Call on dropdown shown
      document.querySelectorAll('.dropdown').forEach(dropdown => {
        dropdown.addEventListener('shown.bs.dropdown', window.adjustDropdownPositions);
      });
      
      // Handle orientation change
      window.addEventListener('orientationchange', function() {
        setTimeout(window.adjustDropdownPositions, 200);
      });
    }
    
    // Keydown events for accessibility
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && currentOpenDropdown) {
        $(currentOpenDropdown).dropdown('hide');
      }
    });
    
    // Add slide-down animation keyframes
    if (!document.getElementById('responsive-dropdown-keyframes')) {
      const style = document.createElement('style');
      style.id = 'responsive-dropdown-keyframes';
      style.textContent = `
        @keyframes slide-down {
          from {
            transform: translateY(0);
          }
          to {
            transform: translateY(100%);
          }
        }
      `;
      document.head.appendChild(style);
    }
  });
})(); 