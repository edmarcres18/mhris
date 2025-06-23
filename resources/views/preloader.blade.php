<div class="preloader">
    <div class="loader">
        <div class="loader-circle"></div>
        <div class="loader-circle"></div>
        <div class="loader-circle"></div>
        <div class="loader-text">MHRPCI</div>
    </div>
</div>

<style>
    .preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: white;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.5s ease-in-out;
    }

    .preloader.fade-out {
        opacity: 0;
    }

    .loader {
        width: 80px;
        height: 80px;
        position: relative;
    }

    .loader-circle {
        position: absolute;
        width: 100%;
        height: 100%;
        border: 4px solid transparent;
        border-radius: 50%;
        border-top-color: var(--purple-primary);
        animation: spin 1s linear infinite;
    }

    .loader-circle:nth-child(2) {
        border-top-color: var(--purple-secondary);
        animation-delay: 0.2s;
        scale: 0.8;
    }

    .loader-circle:nth-child(3) {
        border-top-color: var(--purple-light);
        animation-delay: 0.4s;
        scale: 0.6;
    }

    .loader-text {
        position: absolute;
        top: 120%;
        left: 50%;
        transform: translateX(-50%);
        color: var(--purple-primary);
        font-weight: 600;
        white-space: nowrap;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    body.loading {
        overflow: hidden;
    }

    :root {
        --purple-primary: #6b21a8;
        --purple-secondary: #7c3aed;
        --purple-light: #8b5cf6;
        --purple-dark: #4c1d95;
        --purple-gradient: linear-gradient(45deg, #6b21a8, #7c3aed);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const preloader = document.querySelector('.preloader');
        const body = document.body;

        // Ensure all content is loaded
        window.addEventListener('load', () => {
            setTimeout(() => {
                preloader.classList.add('fade-out');
                body.classList.remove('loading');
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 500);
            }, 500); // Show preloader for at least 500ms
        });

        // Fallback: Hide preloader after 5 seconds if the page takes too long to load
        setTimeout(() => {
            preloader.classList.add('fade-out');
            body.classList.remove('loading');
            setTimeout(() => {
                preloader.style.display = 'none';
            }, 500);
        }, 5000);
    });
</script>
