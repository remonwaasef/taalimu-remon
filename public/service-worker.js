const CACHE_NAME = 'taalimu-v5';

self.addEventListener('install', () => {
    console.log('[Taalimu SW] Installing v5...');
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    console.log('[Taalimu SW] Activating v5...');
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k))
            );
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') return;

    const url = new URL(event.request.url);
    const isSameOrigin = url.origin === self.location.origin;

    // Do NOT handle external requests like Cloudflare or Google Analytics
    if (!isSameOrigin) return;

    event.respondWith((async () => {
        try {
            const response = await fetch(event.request);

            // Only cache successful same-origin responses
            if (response && response.status === 200) {
                const cache = await caches.open(CACHE_NAME);
                cache.put(event.request, response.clone());
            }
            return response;
        } catch (error) {
            const cachedResponse = await caches.match(event.request);
            if (cachedResponse) return cachedResponse;

            // Offline HTML fallback
            if (event.request.headers.get('accept')?.includes('text/html')) {
                return new Response(
                    '<html dir="rtl"><body style="display:flex;justify-content:center;align-items:center;height:100vh;font-family:Cairo,sans-serif;background:#f0f2f5;"><div style="text-align:center;"><h1>📡 لا يوجد اتصال</h1><p>يرجى التحقق من اتصالك بالإنترنت</p></div></body></html>',
                    { headers: { 'Content-Type': 'text/html; charset=utf-8' } }
                );
            }

            // Final fallback for other assets
            return new Response('', { status: 408, statusText: 'Network Error' });
        }
    })());
});
