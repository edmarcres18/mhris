// Listen for messages from service worker
navigator.serviceWorker.addEventListener('message', function(event) {
    if (event.data && event.data.action === 'PLAY_NOTIFICATION_SOUND') {
        const audio = new Audio(event.data.soundUrl);
        audio.play().catch(error => {
            console.error('Failed to play notification sound:', error);
        });
    }
}); 