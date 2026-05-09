/**
 * Taalimu Offline Sync Logic
 * Stores requests in IndexedDB when offline and replays them when online.
 */

const DB_NAME = 'taalimu_offline_db';
const STORE_NAME = 'sync_queue';

// Initialize IndexedDB
function initDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(DB_NAME, 1);
        request.onupgradeneeded = (event) => {
            const db = event.target.result;
            if (!db.objectStoreNames.contains(STORE_NAME)) {
                db.createObjectStore(STORE_NAME, { keyPath: 'id', autoIncrement: true });
            }
        };
        request.onsuccess = () => resolve(request.result);
        request.onerror = () => reject(request.error);
    });
}

// Save request to queue
async function saveToQueue(url, method, headers, body) {
    const db = await initDB();
    const transaction = db.transaction(STORE_NAME, 'readwrite');
    const store = transaction.objectStore(STORE_NAME);
    store.add({ url, method, headers, body, timestamp: Date.now() });
}

// Sync all queued requests to the server
async function syncOfflineData() {
    const db = await initDB();
    const transaction = db.transaction(STORE_NAME, 'readonly');
    const store = transaction.objectStore(STORE_NAME);
    const request = store.getAll();

    request.onsuccess = async () => {
        const queuedItems = request.result;
        if (queuedItems.length === 0) return;

        console.log(`[PWA] Syncing ${queuedItems.length} offline actions...`);

        for (const item of queuedItems) {
            try {
                const response = await fetch(item.url, {
                    method: item.method,
                    headers: item.headers,
                    body: item.body,
                });

                if (response.ok) {
                    // Remove successfully sent request from queue
                    const deleteTx = db.transaction(STORE_NAME, 'readwrite');
                    deleteTx.objectStore(STORE_NAME).delete(item.id);
                }
            } catch (error) {
                console.error('[PWA] Sync failed for item:', item, error);
                // Keep it in the queue for the next sync attempt
            }
        }
    };
}

// Listen for network status changes
window.addEventListener('online', () => {
    console.log('[PWA] Connection restored! Starting sync...');
    syncOfflineData();
});

// Expose a safeFetch wrapper for the app to use
window.safeFetch = async (url, options) => {
    if (navigator.onLine) {
        return fetch(url, options).catch((err) => {
            // Fallback if network drops exactly during fetch
            if (!navigator.onLine && options.method !== 'GET') {
                saveToQueue(url, options.method, options.headers, options.body);
            }
            throw err;
        });
    } else {
        // App is offline, queue mutation requests
        if (options.method !== 'GET') {
            console.log('[PWA] Offline mode: Action queued.');
            await saveToQueue(url, options.method, options.headers, options.body);
            return new Response(JSON.stringify({ offline: true, message: 'Action saved offline.' }), { status: 202 });
        }
        throw new Error('Offline: Cannot fetch new data.');
    }
};

// Initial sync on page load (in case they just reloaded while online)
if (navigator.onLine) {
    syncOfflineData();
}
