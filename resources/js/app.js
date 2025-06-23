import './bootstrap';
import '../css/app.css';

if ('serviceWorker' in navigator && 'PushManager' in window) {
    navigator.serviceWorker.register('/service-worker.js')
    .then(function(registration) {
        return registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array('BEVq1eiyhw-3VzECWiQDDKDLL5b2bzmkh6Pw1AQt-k6Xv0YD5hCsTobhlH-YURsAApNZjJPIlmbUJ-g4Z0qqN2M') // Replace with your public key
        });
    })
    .then(function(subscription) {
        // Send subscription details to your server
        console.log('User is subscribed:', subscription);
    })
    .catch(function(error) {
        console.error('Subscription failed:', error);
    });
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding).replace(/\-/g, '+').replace(/\_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

// Import Echo for real-time messaging
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Initialize Laravel Echo
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

window.Echo.channel('notifications')
    .listen('NotificationsGenerated', (e) => {
        console.log(e.notifications);
        // Update your UI with the new notifications
        updateNotificationsUI(e.notifications);
    });

function updateNotificationsUI(notifications) {
    const notificationMenu = document.getElementById('notification-menu');
    notificationMenu.innerHTML = ''; // Clear existing notifications

    notifications.forEach(notification => {
        const notificationItem = document.createElement('a');
        notificationItem.href = '#';
        notificationItem.classList.add('dropdown-item');
        notificationItem.innerHTML = `
            <i class="mr-2 ${notification.icon}"></i> ${notification.text}
            <span class="float-right text-muted text-sm">${notification.time}</span>
        `;
        notificationMenu.appendChild(notificationItem);
    });
    // Update the notification count
    const notificationCount = document.getElementById('notification-count');
    notificationCount.textContent = notifications.length;
}

