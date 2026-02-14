const CACHE_NAME = 'taalimu-v3';

self.addEventListener('install', () => {
    console.log('[Taalimu SW] Installing v3...');
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    console.log('[Taalimu SW] Activating v3...');
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

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                if (response.status === 200) {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, clone);
                    });
                }
                return response;
            })
            .catch(() => {
                return caches.match(event.request).then((r) => {
                    if (r) return r;
                    if (event.request.headers.get('accept') &&
                        event.request.headers.get('accept').includes('text/html')) {
                        return new Response(
                            '<html dir="rtl"><body style="display:flex;justify-content:center;align-items:center;height:100vh;font-family:Cairo,sans-serif;background:#f0f2f5;"><div style="text-align:center;"><h1>📡 لا يوجد اتصال</h1><p>يرجى التحقق من اتصالك بالإنترنت</p></div></body></html>',
                            { headers: { 'Content-Type': 'text/html; charset=utf-8' } }
                        );
                    }
                    // Return a basic error response for non-HTML requests if not in cache
                    return new Response('', { status: 408, statusText: 'Network Error' });
                });
            })
    );
});
