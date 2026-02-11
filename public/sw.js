const CACHE_NAME = 'taalimu-v2';

self.addEventListener('install', (event) => {
    console.log('[ServiceWorker] Installing v2...');
    // Skip pre-caching to avoid installation failures
    // Assets will be cached on-the-fly instead
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    console.log('[ServiceWorker] Activating v2...');
    event.waitUntil(
        caches.keys().then((keyList) => {
            return Promise.all(keyList.map((key) => {
                if (key !== CACHE_NAME) {
                    console.log('[ServiceWorker] Removing old cache:', key);
                    return caches.delete(key);
                }
            }));
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') return;

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                // Cache successful responses for offline use
                if (response.status === 200) {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseClone);
                    });
                }
                return response;
            })
            .catch(() => {
                return caches.match(event.request)
                    .then((response) => {
                        if (response) return response;
                        // Return a simple offline message for HTML pages
                        if (event.request.headers.get('accept') &&
                            event.request.headers.get('accept').includes('text/html')) {
                            return new Response(
                                '<html dir="rtl"><body style="display:flex;justify-content:center;align-items:center;height:100vh;font-family:Cairo,sans-serif;background:#f0f2f5;"><div style="text-align:center;"><h1>📡 لا يوجد اتصال</h1><p>يرجى التحقق من اتصالك بالإنترنت</p></div></body></html>',
                                { headers: { 'Content-Type': 'text/html; charset=utf-8' } }
                            );
                        }
                    });
            })
    );
});
