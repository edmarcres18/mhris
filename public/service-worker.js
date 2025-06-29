'use strict';

const CACHE_NAME = 'hris-cache-v1';
const urlsToCache = [
  '/',
  '/css/app.css',
  '/js/app.js',
  '/sounds/notification.mp3'
];

// Install event - cache assets
self.addEventListener('install', event => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.filter(cacheName => {
          return cacheName !== CACHE_NAME;
        }).map(cacheName => {
          return caches.delete(cacheName);
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Push event - handle incoming push notifications
self.addEventListener('push', event => {
  console.log('Push event received');

  if (event.data) {
    try {
      const data = event.data.json();

      // Keep the service worker active until notification is processed
      const promiseChain = displayNotification(data)
        .then(() => playNotificationSound(data))
        .catch(error => console.error('Error handling push event:', error));

      event.waitUntil(promiseChain);
    } catch (e) {
      console.error('Error processing push data:', e);
    }
  }
});

// Function to display a notification
function displayNotification(data) {
  const title = data.title || 'HRIS Notification';
  const options = {
    body: data.body || 'You have a new notification',
    icon: data.icon || '/img/logo.png',
    badge: data.badge || '/img/badge.png',
    tag: data.tag || 'default',
    data: data,
    vibrate: [100, 50, 100, 50, 100, 50, 100],
    renotify: true,
    requireInteraction: true,
    silent: false,
    actions: data.actions || [],
    // Important options for background visibility
    importance: 'high',
    priority: 'high'
  };

  return self.registration.showNotification(title, options);
}

// Function to play notification sound via client
function playNotificationSound(data) {
  const soundUrl = data.sound || '/sounds/notification.mp3';

  return self.clients.matchAll({
    type: 'window',
    includeUncontrolled: true
  }).then(clientList => {
    // If we have active clients, send message to play sound
    if (clientList.length > 0) {
      clientList.forEach(client => {
        client.postMessage({
          action: 'PLAY_NOTIFICATION_SOUND',
          soundUrl: soundUrl
        });
      });
      return;
    }

    // If no active clients, try to open a window and play sound
    return self.clients.openWindow('/').then(client => {
      if (client) {
        setTimeout(() => {
          client.postMessage({
            action: 'PLAY_NOTIFICATION_SOUND',
            soundUrl: soundUrl
          });
        }, 1000);
      }
    });
  });
}

// Notification click event
self.addEventListener('notificationclick', event => {
  console.log('Notification clicked');
  event.notification.close();

  const urlToOpen = event.notification.data && event.notification.data.url ?
    event.notification.data.url : '/';

  const promiseChain = self.clients.matchAll({
    type: 'window',
    includeUncontrolled: true
  }).then(clientList => {
    // Check if there is already a window/tab open with the target URL
    for (let i = 0; i < clientList.length; i++) {
      const client = clientList[i];
      if (client.url === urlToOpen && 'focus' in client) {
        return client.focus();
      }
    }

    // If no window/tab is already open, open a new one
    if (self.clients.openWindow) {
      return self.clients.openWindow(urlToOpen);
    }
  });

  event.waitUntil(promiseChain);
});

// Notification close event
self.addEventListener('notificationclose', event => {
  console.log('Notification closed', event);
});

// Fetch event - network first, then cache
self.addEventListener('fetch', event => {
  event.respondWith(
    fetch(event.request)
      .then(response => {
        // Clone the response for caching and return the response
        if (event.request.method === 'GET') {
          const responseToCache = response.clone();
          caches.open(CACHE_NAME)
            .then(cache => {
              cache.put(event.request, responseToCache);
            });
        }
        return response;
      })
      .catch(() => {
        // If network fails, try to serve from cache
        return caches.match(event.request);
      })
  );
});
