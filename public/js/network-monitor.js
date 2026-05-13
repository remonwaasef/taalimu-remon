/**
 * ============================================================
 * Taalimu Network Monitor — Professional Connectivity System
 * ============================================================
 * Inspired by: flutter_network_monitor (abdelrhman-mahrous)
 *
 * Architecture:
 * - Real connectivity validation (not just navigator.onLine)
 * - Singleton pattern (one instance across the entire app)
 * - Reactive stream-based (events + polling)
 * - Auto-retry with exponential backoff
 * - Offline queue for mutations (POST/PUT/DELETE)
 * - Beautiful UI banner with animations
 *
 * Equivalent to NetworkInfo (abstract), NetworkMonitor (impl),
 * and NetworkAware (mixin) from the Flutter version.
 * ============================================================
 */

(function () {
    'use strict';

    // ─────────────────────────────────────────────────────────
    // Configuration
    // ─────────────────────────────────────────────────────────
    const CONFIG = {
        // Health-check endpoint (lightweight, same-origin)
        healthCheckUrl: '/api/health-check',
        // Fallback endpoints if primary fails
        fallbackUrls: [
            '/favicon.ico',
            '/manifest.json',
        ],
        // Timeout for health check requests (ms)
        timeout: 5000,
        // Polling interval when online (ms) — less aggressive
        onlinePollInterval: 30000,
        // Polling interval when offline (ms) — more aggressive
        offlinePollInterval: 5000,
        // Maximum retry delay (ms)
        maxRetryDelay: 60000,
        // Debounce delay for events (ms)
        debounceDelay: 1000,
        // IndexedDB settings
        dbName: 'taalimu_network_db',
        storeName: 'offline_queue',
        // Auto-dismiss banner delay when back online (ms)
        bannerDismissDelay: 3000,
    };

    // ─────────────────────────────────────────────────────────
    // NetworkMonitor Singleton
    // ─────────────────────────────────────────────────────────
    class NetworkMonitor {
        // Singleton instance
        static _instance = null;

        static getInstance() {
            if (!NetworkMonitor._instance) {
                NetworkMonitor._instance = new NetworkMonitor();
            }
            return NetworkMonitor._instance;
        }

        constructor() {
            if (NetworkMonitor._instance) {
                return NetworkMonitor._instance;
            }

            // State
            this._isConnected = navigator.onLine;
            this._lastKnownState = navigator.onLine;
            this._connectionType = this._detectConnectionType();
            this._listeners = new Set();
            this._pollTimer = null;
            this._retryCount = 0;
            this._bannerElement = null;
            this._db = null;

            // Initialize
            this._init();
        }

        // ─── Public API (equivalent to NetworkInfo abstract) ───

        /** Check if there is real internet connectivity */
        get isConnected() {
            return this._isConnected;
        }

        /** Get last known state (cached) */
        get lastKnownState() {
            return this._lastKnownState;
        }

        /** Get connection type: wifi, cellular, ethernet, unknown, none */
        get connectionType() {
            return this._connectionType;
        }

        /** Async check — validates real internet (like DNS lookup in Flutter) */
        async checkConnectivity() {
            // Step 1: Check network interface (like hasNetwork in Flutter)
            if (!navigator.onLine) {
                this._updateState(false);
                return false;
            }

            // Step 2: Validate real internet (like isConnected in Flutter)
            const hasRealInternet = await this._validateRealConnectivity();
            this._updateState(hasRealInternet);
            return hasRealInternet;
        }

        /** Subscribe to connectivity changes (like connectivityStream) */
        onStatusChange(callback) {
            this._listeners.add(callback);
            // Return unsubscribe function
            return () => this._listeners.delete(callback);
        }

        /** Save action to offline queue */
        async queueOfflineAction(url, method, headers, body) {
            try {
                const db = await this._getDB();
                const tx = db.transaction(CONFIG.storeName, 'readwrite');
                const store = tx.objectStore(CONFIG.storeName);
                await store.add({
                    url,
                    method,
                    headers: JSON.parse(JSON.stringify(headers || {})),
                    body,
                    timestamp: Date.now(),
                });
                console.log('[NetworkMonitor] Action queued for offline sync');
            } catch (error) {
                console.error('[NetworkMonitor] Failed to queue action:', error);
            }
        }

        /** Sync all queued offline actions */
        async syncOfflineQueue() {
            try {
                const db = await this._getDB();
                const tx = db.transaction(CONFIG.storeName, 'readonly');
                const store = tx.objectStore(CONFIG.storeName);
                const items = await this._idbRequest(store.getAll());

                if (!items || items.length === 0) return;

                console.log(`[NetworkMonitor] Syncing ${items.length} offline actions...`);

                for (const item of items) {
                    try {
                        const response = await fetch(item.url, {
                            method: item.method,
                            headers: item.headers,
                            body: item.body,
                        });

                        if (response.ok) {
                            const deleteTx = db.transaction(CONFIG.storeName, 'readwrite');
                            const deleteStore = deleteTx.objectStore(CONFIG.storeName);
                            deleteStore.delete(item.id);
                            console.log(`[NetworkMonitor] Synced action: ${item.method} ${item.url}`);
                        }
                    } catch (syncError) {
                        console.warn('[NetworkMonitor] Sync failed for item:', item.url, syncError);
                    }
                }
            } catch (error) {
                console.error('[NetworkMonitor] Sync queue error:', error);
            }
        }

        /** Safe fetch wrapper — queues mutations when offline */
        async safeFetch(url, options = {}) {
            if (this._isConnected) {
                try {
                    return await fetch(url, options);
                } catch (fetchError) {
                    // Connection dropped during fetch
                    await this.checkConnectivity();
                    if (!this._isConnected && options.method && options.method !== 'GET') {
                        await this.queueOfflineAction(url, options.method, options.headers, options.body);
                        return new Response(JSON.stringify({
                            offline: true,
                            message: 'Action saved for offline sync'
                        }), { status: 202 });
                    }
                    throw fetchError;
                }
            } else {
                if (options.method && options.method !== 'GET') {
                    await this.queueOfflineAction(url, options.method, options.headers, options.body);
                    return new Response(JSON.stringify({
                        offline: true,
                        message: 'Action saved for offline sync'
                    }), { status: 202 });
                }
                throw new Error('NetworkMonitor: No internet connection');
            }
        }

        /** Cleanup resources */
        dispose() {
            if (this._pollTimer) clearInterval(this._pollTimer);
            window.removeEventListener('online', this._boundOnlineHandler);
            window.removeEventListener('offline', this._boundOfflineHandler);
            this._listeners.clear();
            if (this._db) this._db.close();
        }

        // ─── Private Methods ──────────────────────────────────

        _init() {
            // Bind event handlers
            this._boundOnlineHandler = this._debounce(() => this._onNetworkChange(), CONFIG.debounceDelay);
            this._boundOfflineHandler = this._debounce(() => this._onNetworkChange(), CONFIG.debounceDelay);

            // Listen to browser connectivity events
            window.addEventListener('online', this._boundOnlineHandler);
            window.addEventListener('offline', this._boundOfflineHandler);

            // Create the UI banner
            this._createBanner();

            // Initial connectivity check
            this.checkConnectivity().then(() => {
                this._startPolling();
            });

            // Listen to visibility changes (user switches tab back)
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) {
                    this.checkConnectivity();
                }
            });

            console.log('[NetworkMonitor] Initialized — Real connectivity monitoring active');
        }

        async _onNetworkChange() {
            const wasConnected = this._isConnected;
            await this.checkConnectivity();

            // Adjust polling frequency based on state
            this._startPolling();

            // If just came back online, sync offline queue
            if (!wasConnected && this._isConnected) {
                this._retryCount = 0;
                setTimeout(() => this.syncOfflineQueue(), 2000);
            }
        }

        /** Validate real connectivity — equivalent to DNS lookup in Flutter */
        async _validateRealConnectivity() {
            // Try primary health check
            try {
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), CONFIG.timeout);

                const response = await fetch(CONFIG.healthCheckUrl, {
                    method: 'HEAD',
                    cache: 'no-store',
                    signal: controller.signal,
                    headers: {
                        'X-Network-Check': '1',
                        'Cache-Control': 'no-cache',
                    },
                });

                clearTimeout(timeoutId);
                return response.ok;
            } catch (_primaryError) {
                // Primary failed — try fallback URLs
                for (const fallbackUrl of CONFIG.fallbackUrls) {
                    try {
                        const controller = new AbortController();
                        const timeoutId = setTimeout(() => controller.abort(), CONFIG.timeout);

                        const response = await fetch(fallbackUrl, {
                            method: 'HEAD',
                            cache: 'no-store',
                            signal: controller.signal,
                        });

                        clearTimeout(timeoutId);
                        if (response.ok || response.status === 304) return true;
                    } catch (_fallbackError) {
                        continue;
                    }
                }
                return false;
            }
        }

        _updateState(isConnected) {
            const previousState = this._isConnected;
            this._isConnected = isConnected;
            this._lastKnownState = isConnected;
            this._connectionType = this._detectConnectionType();

            // Only notify if state actually changed (like .distinct() in Flutter)
            if (previousState !== isConnected) {
                console.log(`[NetworkMonitor] State changed: ${previousState ? 'Online' : 'Offline'} → ${isConnected ? 'Online' : 'Offline'}`);
                this._notifyListeners(isConnected);
                this._updateBanner(isConnected);

                // Dispatch custom event for other scripts
                window.dispatchEvent(new CustomEvent('taalimu:connectivity', {
                    detail: {
                        isConnected,
                        connectionType: this._connectionType,
                        timestamp: Date.now(),
                    }
                }));
            }
        }

        _notifyListeners(isConnected) {
            this._listeners.forEach(callback => {
                try {
                    callback(isConnected, this._connectionType);
                } catch (error) {
                    console.error('[NetworkMonitor] Listener error:', error);
                }
            });
        }

        _startPolling() {
            if (this._pollTimer) clearInterval(this._pollTimer);

            const interval = this._isConnected
                ? CONFIG.onlinePollInterval
                : Math.min(
                    CONFIG.offlinePollInterval * Math.pow(1.5, this._retryCount),
                    CONFIG.maxRetryDelay
                );

            this._pollTimer = setInterval(() => {
                if (!this._isConnected) this._retryCount++;
                this.checkConnectivity();
            }, interval);
        }

        _detectConnectionType() {
            if (!navigator.onLine) return 'none';
            const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
            if (!connection) return 'unknown';

            const type = connection.type || connection.effectiveType;
            if (type === 'wifi') return 'wifi';
            if (['cellular', '2g', '3g', '4g', '5g'].includes(type)) return 'cellular';
            if (type === 'ethernet') return 'ethernet';
            return 'unknown';
        }

        // ─── UI Banner ───────────────────────────────────────

        _createBanner() {
            // Don't create duplicate banners
            if (document.getElementById('taalimu-network-banner')) return;

            const isRTL = document.documentElement.dir === 'rtl' ||
                          document.documentElement.lang === 'ar';

            const banner = document.createElement('div');
            banner.id = 'taalimu-network-banner';
            banner.className = 'taalimu-network-banner';
            banner.setAttribute('role', 'alert');
            banner.setAttribute('aria-live', 'assertive');

            banner.innerHTML = `
                <div class="taalimu-network-banner__content">
                    <div class="taalimu-network-banner__icon-wrap">
                        <span class="taalimu-network-banner__icon taalimu-network-banner__icon--offline">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                                <path d="M16.72 11.06A10.94 10.94 0 0 1 19 12.55"></path>
                                <path d="M5 12.55a10.94 10.94 0 0 1 5.17-2.39"></path>
                                <path d="M10.71 5.05A16 16 0 0 1 22.56 9"></path>
                                <path d="M1.42 9a15.91 15.91 0 0 1 4.7-2.88"></path>
                                <path d="M8.53 16.11a6 6 0 0 1 6.95 0"></path>
                                <line x1="12" y1="20" x2="12.01" y2="20"></line>
                            </svg>
                        </span>
                        <span class="taalimu-network-banner__icon taalimu-network-banner__icon--online">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12.55a11 11 0 0 1 14.08 0"></path>
                                <path d="M1.42 9a16 16 0 0 1 21.16 0"></path>
                                <path d="M8.53 16.11a6 6 0 0 1 6.95 0"></path>
                                <line x1="12" y1="20" x2="12.01" y2="20"></line>
                            </svg>
                        </span>
                    </div>
                    <div class="taalimu-network-banner__text">
                        <span class="taalimu-network-banner__text--offline">
                            ${isRTL ? 'لا يوجد اتصال بالإنترنت — سيتم حفظ عملياتك ومزامنتها تلقائياً' : 'No internet connection — Your actions will be saved and synced automatically'}
                        </span>
                        <span class="taalimu-network-banner__text--online">
                            ${isRTL ? 'تم استعادة الاتصال — جاري المزامنة...' : 'Connection restored — Syncing your data...'}
                        </span>
                    </div>
                    <div class="taalimu-network-banner__actions">
                        <button class="taalimu-network-banner__retry" onclick="window.TaalimuNetwork.checkConnectivity()" title="${isRTL ? 'إعادة المحاولة' : 'Retry'}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="23 4 23 10 17 10"></polyline>
                                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                            </svg>
                        </button>
                        <button class="taalimu-network-banner__close" onclick="document.getElementById('taalimu-network-banner').classList.remove('is-visible')" title="${isRTL ? 'إغلاق' : 'Close'}">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="taalimu-network-banner__progress"></div>
            `;

            document.body.appendChild(banner);
            this._bannerElement = banner;
        }

        _updateBanner(isConnected) {
            if (!this._bannerElement) return;

            if (!isConnected) {
                // Show offline banner
                this._bannerElement.classList.add('is-visible', 'is-offline');
                this._bannerElement.classList.remove('is-online');
            } else {
                // Show "back online" briefly, then hide
                this._bannerElement.classList.remove('is-offline');
                this._bannerElement.classList.add('is-visible', 'is-online');

                setTimeout(() => {
                    this._bannerElement.classList.remove('is-visible', 'is-online');
                }, CONFIG.bannerDismissDelay);

                // Trigger page data refresh
                this._triggerPageRefresh();
            }
        }

        _triggerPageRefresh() {
            // Dispatch event for app-level refresh handling
            window.dispatchEvent(new CustomEvent('taalimu:back-online'));

            // If there are any forms with data, don't reload
            const hasActiveForm = document.querySelector('form:focus-within, form [data-dirty="true"]');
            if (!hasActiveForm) {
                // Soft refresh: reload AJAX-driven content without full page reload
                const ajaxContainers = document.querySelectorAll('[data-auto-refresh]');
                if (ajaxContainers.length > 0) {
                    ajaxContainers.forEach(container => {
                        const refreshUrl = container.dataset.autoRefresh;
                        if (refreshUrl) {
                            fetch(refreshUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                                .then(res => res.text())
                                .then(html => { container.innerHTML = html; })
                                .catch(() => {});
                        }
                    });
                }
            }
        }

        // ─── IndexedDB Helpers ────────────────────────────────

        _getDB() {
            return new Promise((resolve, reject) => {
                if (this._db) {
                    resolve(this._db);
                    return;
                }
                const request = indexedDB.open(CONFIG.dbName, 1);
                request.onupgradeneeded = (event) => {
                    const db = event.target.result;
                    if (!db.objectStoreNames.contains(CONFIG.storeName)) {
                        db.createObjectStore(CONFIG.storeName, { keyPath: 'id', autoIncrement: true });
                    }
                };
                request.onsuccess = () => {
                    this._db = request.result;
                    resolve(this._db);
                };
                request.onerror = () => reject(request.error);
            });
        }

        _idbRequest(request) {
            return new Promise((resolve, reject) => {
                request.onsuccess = () => resolve(request.result);
                request.onerror = () => reject(request.error);
            });
        }

        // ─── Utility ─────────────────────────────────────────

        _debounce(fn, delay) {
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => fn.apply(this, args), delay);
            };
        }
    }

    // ─────────────────────────────────────────────────────────
    // Initialize and expose globally
    // ─────────────────────────────────────────────────────────
    const monitor = NetworkMonitor.getInstance();

    // Global API (like NetworkAware mixin)
    window.TaalimuNetwork = monitor;

    // Override the old safeFetch if it exists
    window.safeFetch = (url, options) => monitor.safeFetch(url, options);

    // Log initial state
    console.log(`[NetworkMonitor] Initial state: ${monitor.isConnected ? '🟢 Online' : '🔴 Offline'} (${monitor.connectionType})`);

    // ─────────────────────────────────────────────────────────
    // Register Service Worker (Offline Page Cache + PWA)
    // ─────────────────────────────────────────────────────────
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js', { scope: '/' })
                .then((registration) => {
                    console.log('[ServiceWorker] Registered successfully, scope:', registration.scope);

                    // Register Background Sync if supported
                    if ('sync' in registration) {
                        monitor.onStatusChange((isConnected) => {
                            if (isConnected) {
                                registration.sync.register('taalimu-offline-sync')
                                    .catch(() => {});
                            }
                        });
                    }
                })
                .catch((error) => {
                    console.warn('[ServiceWorker] Registration failed:', error.message);
                });

            // Listen for SW messages (Background Sync trigger)
            navigator.serviceWorker.addEventListener('message', (event) => {
                if (event.data?.type === 'SYNC_OFFLINE_QUEUE') {
                    monitor.syncOfflineQueue();
                }
            });
        });
    }

})();
