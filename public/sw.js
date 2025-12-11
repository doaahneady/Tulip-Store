// Service Worker for background GPS tracking
const CACHE_NAME = 'tulip-driver-tracking-v1';

// Install event
self.addEventListener('install', (event) => {
    console.log('Service Worker installed');
    self.skipWaiting();
});

// Activate event
self.addEventListener('activate', (event) => {
    console.log('Service Worker activated');
    event.waitUntil(clients.claim());
});

// Background sync for location updates
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-location') {
        event.waitUntil(syncLocation());
    }
});

// Sync location data
async function syncLocation() {
    // This will be called when connection is restored
    console.log('Syncing location data');
}

// Keep service worker alive
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'KEEP_ALIVE') {
        event.ports[0].postMessage({ status: 'alive' });
    }
});
