const CACHE_NAME = 'taalimu-sw-v5';

self.addEventListener('install', () => {
    console.log('[ServiceWorker] Installing v5...');
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    console.log('[ServiceWorker] Activating v5...');
    event.waitUntil(
        caches.keys().then((keyList) => {
            return Promise.all(keyList.map((key) => {
                if (key !== CACHE_NAME) {
                    return caches.delete(key);
                }
            }));
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') return;

    const url = new URL(event.request.url);
    if (url.origin !== self.location.origin) return;

    event.respondWith((async () => {
        try {
            const response = await fetch(event.request);
            if (response && response.status === 200) {
                const cache = await caches.open(CACHE_NAME);
                cache.put(event.request, response.clone());
            }
            return response;
        } catch (error) {
            const cachedResponse = await caches.match(event.request);
            if (cachedResponse) return cachedResponse;

            if (event.request.headers.get('accept')?.includes('text/html')) {
                return new Response(
                    '<html dir="rtl"><body style="display:flex;justify-content:center;align-items:center;height:100vh;font-family:Cairo,sans-serif;background:#f0f2f5;"><div style="text-align:center;"><h1>📡 لا يوجد اتصال</h1><p>يرجى التحقق من اتصالك بالإنترنت</p></div></body></html>',
                    { headers: { 'Content-Type': 'text/html; charset=utf-8' } }
                );
            }
            return new Response('', { status: 408, statusText: 'Network Error' });
        }
    })());
});
