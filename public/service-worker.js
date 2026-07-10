/**
 * ============================================================
 * Taalimu Service Worker v7 — Network-Aware Caching
 * ============================================================
 * Strategy: Network-First with intelligent cache fallback.
 * Integrates with NetworkMonitor for coordinated offline UX.
 *
 * Features:
 * - Network-first for HTML (always fresh content)
 * - Cache-first for static assets (CSS, JS, images, fonts)
 * - Background sync notification to main thread
 * - Smart cache cleanup with size limits
 * - Health-check requests are never cached
 * ============================================================
 */

const CACHE_NAME = 'taalimu-v11';
const STATIC_CACHE = 'taalimu-static-v11';
const MAX_CACHE_SIZE = 100; // Maximum cached entries

// Static assets to pre-cache on install
const PRECACHE_URLS = [
    '/css/network-monitor.css',
    '/js/network-monitor.js',
    '/manifest.json',
];

// Patterns that should NEVER be cached
const NO_CACHE_PATTERNS = [
    '/api/health-check',
    '/livewire/',
    '/broadcasting/',
    '/api/',
    'chrome-extension://',
];

// ─── Install ─────────────────────────────────────────────
self.addEventListener('install', (event) => {
    console.log('[Taalimu SW v7] Installing...');
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then(cache => cache.addAll(PRECACHE_URLS))
            .then(() => self.skipWaiting())
    );
});

// ─── Activate ────────────────────────────────────────────
self.addEventListener('activate', (event) => {
    console.log('[Taalimu SW v7] Activating...');
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(
                keys
                    .filter(k => k !== CACHE_NAME && k !== STATIC_CACHE)
                    .map(k => caches.delete(k))
            )
        ).then(() => self.clients.claim())
    );
});

// ─── Fetch Strategy ──────────────────────────────────────
self.addEventListener('fetch', (event) => {
    const { request } = event;

    // Skip non-GET requests
    if (request.method !== 'GET') return;

    const url = new URL(request.url);

    // Skip external requests
    if (url.origin !== self.location.origin) return;

    // Skip no-cache patterns (health-check, APIs, etc.)
    if (NO_CACHE_PATTERNS.some(pattern => url.pathname.includes(pattern))) return;

    // Determine strategy based on request type
    if (isStaticAsset(url)) {
        // Cache-first for static assets
        event.respondWith(cacheFirst(request));
    } else if (isHTMLRequest(request)) {
        // Network-first for HTML pages
        event.respondWith(networkFirst(request));
    } else {
        // Stale-while-revalidate for everything else
        event.respondWith(staleWhileRevalidate(request));
    }
});

// ─── Cache-First Strategy (for static assets) ───────────
async function cacheFirst(request) {
    const cached = await caches.match(request);
    if (cached) return cached;

    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(STATIC_CACHE);
            cache.put(request, response.clone());
        }
        return response;
    } catch (error) {
        return new Response('', { status: 408, statusText: 'Offline' });
    }
}

// ─── Network-First Strategy (for HTML pages) ────────────
async function networkFirst(request) {
    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, response.clone());
            trimCache(CACHE_NAME, MAX_CACHE_SIZE);
        }
        return response;
    } catch (error) {
        // Network failed — try cache
        const cached = await caches.match(request);
        if (cached) {
            // Notify main thread we're serving cached content
            notifyClients({ type: 'SERVING_CACHED', url: request.url });
            return cached;
        }

        // No cache — return offline fallback
        return offlineFallback();
    }
}

// ─── Stale-While-Revalidate Strategy ────────────────────
async function staleWhileRevalidate(request) {
    const cached = await caches.match(request);

    const networkFetch = fetch(request).then(response => {
        if (response.ok) {
            const cache = caches.open(CACHE_NAME);
            cache.then(c => c.put(request, response.clone()));
        }
        return response;
    }).catch(() => cached);

    return cached || networkFetch;
}

// ─── Helpers ─────────────────────────────────────────────
function isStaticAsset(url) {
    return /\.(css|js|png|jpg|jpeg|gif|webp|svg|woff|woff2|ttf|ico)(\?.*)?$/i.test(url.pathname);
}

function isHTMLRequest(request) {
    return request.headers.get('accept')?.includes('text/html');
}

function offlineFallback() {
    const isRTL = true; // Default to RTL for Taalimu
    return new Response(
        `<!DOCTYPE html>
        <html dir="${isRTL ? 'rtl' : 'ltr'}">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width,initial-scale=1">
            <title>Taalimu — Offline</title>
            <link rel="stylesheet" href="/css/network-monitor.css">
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body {
                    min-height: 100vh; display: flex; align-items: center; justify-content: center;
                    font-family: 'Cairo', sans-serif; background: linear-gradient(135deg, #0f172a, #1e293b);
                    color: #e2e8f0;
                }
                .offline-card {
                    text-align: center; padding: 3rem 2rem; max-width: 420px;
                    background: rgba(255,255,255,0.05); border-radius: 24px;
                    border: 1px solid rgba(255,255,255,0.08);
                    backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
                }
                .offline-icon {
                    font-size: 64px; margin-bottom: 1.5rem;
                    animation: float 3s ease-in-out infinite;
                }
                h1 { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.75rem; }
                p { font-size: 0.95rem; opacity: 0.7; line-height: 1.6; margin-bottom: 2rem; }
                .retry-btn {
                    display: inline-flex; align-items: center; gap: 8px;
                    padding: 12px 32px; border: none; border-radius: 12px; cursor: pointer;
                    font-family: inherit; font-size: 1rem; font-weight: 600;
                    background: linear-gradient(135deg, #059669, #047857); color: white;
                    transition: all 0.3s ease; box-shadow: 0 4px 16px rgba(5,150,105,0.3);
                }
                .retry-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(5,150,105,0.4); }
                @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
            </style>
        </head>
        <body>
            <div class="offline-card">
                <div class="offline-icon">📡</div>
                <h1>${isRTL ? 'لا يوجد اتصال بالإنترنت' : 'No Internet Connection'}</h1>
                <p>${isRTL ? 'يبدو أن جهازك غير متصل بالإنترنت. تم حفظ جميع عملياتك وسيتم مزامنتها تلقائياً عند استعادة الاتصال.' : 'Your device appears to be offline. All your actions have been saved and will sync automatically when connection is restored.'}</p>
                <button class="retry-btn" onclick="location.reload()">
                    🔄 ${isRTL ? 'إعادة المحاولة' : 'Try Again'}
                </button>
            </div>
            <script>window.addEventListener('online', () => location.reload());</script>
        </body>
        </html>`,
        { headers: { 'Content-Type': 'text/html; charset=utf-8' } }
    );
}

async function trimCache(cacheName, maxItems) {
    const cache = await caches.open(cacheName);
    const keys = await cache.keys();
    if (keys.length > maxItems) {
        await cache.delete(keys[0]);
        trimCache(cacheName, maxItems);
    }
}

function notifyClients(message) {
    self.clients.matchAll().then(clients => {
        clients.forEach(client => client.postMessage(message));
    });
}

// ─── Listen for messages from main thread ────────────────
self.addEventListener('message', (event) => {
    if (event.data === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});
