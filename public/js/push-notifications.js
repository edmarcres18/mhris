// public/js/push-notifications.js

if ('serviceWorker' in navigator && 'PushManager' in window) {
    navigator.serviceWorker.register('/service-worker.js')
    .then(function(swReg) {
        console.log('Service Worker is registered', swReg);

        swReg.pushManager.getSubscription()
        .then(function(subscription) {
            if (subscription === null) {
                // Subscribe the user
                swReg.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: urlBase64ToUint8Array('YOUR_PUBLIC_VAPID_KEY')
                })
                .then(function(subscription) {
                    console.log('User is subscribed:', subscription);

                    // Send subscription to the server
                    fetch('/subscribe', {
                        method: 'POST',
                        body: JSON.stringify(subscription),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });
                })
                .catch(function(err) {
                    console.log('Failed to subscribe the user: ', err);
                });
            } else {
                console.log('User is already subscribed:', subscription);
            }
        });
    })
    .catch(function(error) {
        console.error('Service Worker Error', error);
    });
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}
