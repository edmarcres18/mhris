// public/js/loader.js
window.addEventListener('load', function() {
    const loader = document.getElementById('loader');
    setTimeout(function() {
        loader.style.opacity = '0';
        setTimeout(function() {
            loader.style.display = 'none';
        }, 500); // Fade out transition time
    }, 2000); // Display loader for 2 seconds
});
