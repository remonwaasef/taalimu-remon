/**
 * ============================================================
 * Taalimu Service Worker — Offline Page Cache
 * ============================================================
 * 
 * ما يفعله:
 * 1. يحفظ الصفحات المهمة في Cache عند أول زيارة
 * 2. لو الإنترنت فصل → يعرض الصفحة من الكاش
 * 3. لو الصفحة مش في الكاش → يعرض صفحة "أنت أوفلاين" جميلة
 * 4. يحدّث الكاش في الخلفية (Stale While Revalidate)
 * 
 * ============================================================
 */

const CACHE_VERSION = 'taalimu-v1';
const OFFLINE_PAGE = '/offline.html';

// الملفات الأساسية اللي لازم تتحفظ فوراً
const PRECACHE_ASSETS = [
    OFFLINE_PAGE,
    '/css/network-monitor.css',
    '/js/network-monitor.js',
    '/favicon.ico',
];

// أنماط URLs اللي نحفظها (Network First + Cache Fallback)
const CACHEABLE_PATTERNS = [
    /\.(css|js|woff2?|ttf|eot)(\?.*)?$/,   // Static assets
    /\.(png|jpg|jpeg|gif|svg|ico|webp)$/,    // Images
];

// أنماط URLs اللي ما نحفظهاش أبداً
const NEVER_CACHE = [
    /\/api\//,              // API calls
    /\/health-check/,       // Health check endpoint
    /\/broadcasting\//,     // WebSocket
    /\/livewire\//,         // Livewire
    /\/_debugbar/,          // Debug bar
    /\/horizon/,            // Horizon
    /chrome-extension/,     // Browser extensions
];

// ─── Install Event ──────────────────────────────────────
self.addEventListener('install', (event) => {
    console.log('[ServiceWorker] Installing...');
    event.waitUntil(
        caches.open(CACHE_VERSION)
            .then((cache) => {
                console.log('[ServiceWorker] Pre-caching offline assets');
                return cache.addAll(PRECACHE_ASSETS);
            })
            .then(() => self.skipWaiting()) // Activate immediately
    );
});

// ─── Activate Event ─────────────────────────────────────
self.addEventListener('activate', (event) => {
    console.log('[ServiceWorker] Activating...');
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((name) => name !== CACHE_VERSION)
                    .map((name) => {
                        console.log('[ServiceWorker] Deleting old cache:', name);
                        return caches.delete(name);
                    })
            );
        }).then(() => self.clients.claim()) // Take control of all pages
    );
});

// ─── Fetch Event ────────────────────────────────────────
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET requests (POST, PUT, DELETE handled by IndexedDB in network-monitor.js)
    if (request.method !== 'GET') return;

    // Skip never-cache patterns
    if (NEVER_CACHE.some((pattern) => pattern.test(url.pathname))) return;

    // Skip cross-origin requests (CDNs, fonts.googleapis, etc.)
    if (url.origin !== self.location.origin) return;

    // Static assets: Cache First (fast!)
    if (CACHEABLE_PATTERNS.some((pattern) => pattern.test(url.pathname))) {
        event.respondWith(cacheFirst(request));
        return;
    }

    // HTML pages: Network First + Cache Fallback + Offline Page
    if (request.headers.get('accept')?.includes('text/html')) {
        event.respondWith(networkFirstWithOffline(request));
        return;
    }

    // Everything else: Network with Cache Fallback
    event.respondWith(networkFirst(request));
});

// ─── Strategies ─────────────────────────────────────────

/**
 * Cache First: For static assets (CSS, JS, images)
 * Fast response from cache, update in background
 */
async function cacheFirst(request) {
    const cached = await caches.match(request);
    if (cached) {
        // Update cache in background (Stale While Revalidate)
        fetchAndCache(request).catch(() => {});
        return cached;
    }
    return fetchAndCache(request);
}

/**
 * Network First: Try network, fallback to cache
 */
async function networkFirst(request) {
    try {
        const response = await fetchWithTimeout(request, 8000);
        if (response.ok) {
            const cache = await caches.open(CACHE_VERSION);
            cache.put(request, response.clone());
        }
        return response;
    } catch (_error) {
        const cached = await caches.match(request);
        if (cached) return cached;
        throw _error;
    }
}

/**
 * Network First with Offline Page Fallback
 * For HTML pages — shows cached version or offline page
 */
async function networkFirstWithOffline(request) {
    try {
        const response = await fetchWithTimeout(request, 8000);
        if (response.ok) {
            const cache = await caches.open(CACHE_VERSION);
            cache.put(request, response.clone());
        }
        return response;
    } catch (_error) {
        // Try cached version of THIS page
        const cached = await caches.match(request);
        if (cached) {
            console.log('[ServiceWorker] Serving cached page:', request.url);
            return cached;
        }

        // No cached version → Show offline page
        console.log('[ServiceWorker] No cache, serving offline page');
        const offlinePage = await caches.match(OFFLINE_PAGE);
        if (offlinePage) return offlinePage;

        // Last resort
        return new Response('أنت غير متصل بالإنترنت', {
            status: 503,
            headers: { 'Content-Type': 'text/plain; charset=utf-8' },
        });
    }
}

/**
 * Fetch and cache helper
 */
async function fetchAndCache(request) {
    const response = await fetch(request);
    if (response.ok) {
        const cache = await caches.open(CACHE_VERSION);
        cache.put(request, response.clone());
    }
    return response;
}

/**
 * Fetch with timeout (prevents hanging on slow connections)
 */
function fetchWithTimeout(request, timeoutMs) {
    return new Promise((resolve, reject) => {
        const timer = setTimeout(() => reject(new Error('Timeout')), timeoutMs);
        fetch(request)
            .then((response) => {
                clearTimeout(timer);
                resolve(response);
            })
            .catch((error) => {
                clearTimeout(timer);
                reject(error);
            });
    });
}

// ─── Background Sync (for offline queue) ────────────────
self.addEventListener('sync', (event) => {
    if (event.tag === 'taalimu-offline-sync') {
        console.log('[ServiceWorker] Background sync triggered');
        event.waitUntil(
            self.clients.matchAll().then((clients) => {
                clients.forEach((client) => {
                    client.postMessage({ type: 'SYNC_OFFLINE_QUEUE' });
                });
            })
        );
    }
});

// ─── Push Notifications (future) ────────────────────────
self.addEventListener('push', (event) => {
    if (!event.data) return;

    try {
        const data = event.data.json();
        event.waitUntil(
            self.registration.showNotification(data.title || 'Taalimu', {
                body: data.body || '',
                icon: '/images/icons/icon-192x192.png',
                badge: '/images/icons/icon-72x72.png',
                dir: 'rtl',
                lang: 'ar',
                data: data.url ? { url: data.url } : {},
            })
        );
    } catch (_e) {}
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const url = event.notification.data?.url;
    if (url) {
        event.waitUntil(self.clients.openWindow(url));
    }
});
