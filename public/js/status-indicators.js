/**
 * ============================================================
 * Taalimu Status Indicators — Clear Visual Feedback
 * ============================================================
 * يعرض حالة النظام بشكل واضح:
 * ✅ تم الحفظ | 🔄 جاري المزامنة | ⚠️ بدون إنترنت | 💾 جاري الحفظ
 * ============================================================
 */

(function() {
    'use strict';

    const STATUS_TYPES = {
        saved: {
            icon: 'fas fa-check-circle',
            text: 'تم الحفظ',
            textEn: 'Saved',
            color: '#10b981',
            bg: '#E6F4F3',
            border: '#B2DDD9'
        },
        saving: {
            icon: 'fas fa-spinner fa-spin',
            text: 'جاري الحفظ...',
            textEn: 'Saving...',
            color: '#3b82f6',
            bg: '#eff6ff',
            border: '#93c5fd'
        },
        syncing: {
            icon: 'fas fa-sync-alt fa-spin',
            text: 'جاري المزامنة...',
            textEn: 'Syncing...',
            color: '#8b5cf6',
            bg: '#f5f3ff',
            border: '#c4b5fd'
        },
        offline: {
            icon: 'fas fa-wifi-slash',
            text: 'بدون إنترنت',
            textEn: 'Offline',
            color: '#ef4444',
            bg: '#fef2f2',
            border: '#fca5a5'
        },
        error: {
            icon: 'fas fa-exclamation-triangle',
            text: 'خطأ في الحفظ',
            textEn: 'Save Error',
            color: '#f59e0b',
            bg: '#fffbeb',
            border: '#fcd34d'
        },
        restored: {
            icon: 'fas fa-undo-alt',
            text: 'تم استعادة المسودة',
            textEn: 'Draft Restored',
            color: '#06b6d4',
            bg: '#ecfeff',
            border: '#67e8f9'
        }
    };

    class StatusIndicator {
        constructor() {
            this.badge = null;
            this.hideTimer = null;
            this.init();
        }

        init() {
            this.createBadge();
            this.interceptFormSubmissions();
            this.watchConnectivity();
        }

        createBadge() {
            const badge = document.createElement('div');
            badge.id = 'taalimu-status-badge';
            badge.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 9998;
                padding: 8px 16px;
                border-radius: 50px;
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 0.78rem;
                font-weight: 600;
                font-family: 'Cairo', 'Segoe UI', sans-serif;
                box-shadow: 0 4px 15px rgba(0,0,0,0.08);
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                transform: translateY(80px);
                opacity: 0;
                pointer-events: none;
                backdrop-filter: blur(10px);
            `;
            document.body.appendChild(badge);
            this.badge = badge;
        }

        /**
         * Show a status indicator
         * @param {string} type - One of: saved, saving, syncing, offline, error, restored
         * @param {number} autoHideMs - Auto-hide after ms (0 = persistent)
         */
        show(type, autoHideMs = 2500) {
            const config = STATUS_TYPES[type];
            if (!config || !this.badge) return;

            // Clear any pending hide
            if (this.hideTimer) {
                clearTimeout(this.hideTimer);
                this.hideTimer = null;
            }

            const isRTL = document.documentElement.dir === 'rtl';
            const text = isRTL ? config.text : config.textEn;

            this.badge.innerHTML = `
                <i class="${config.icon}" style="color: ${config.color}; font-size: 0.85rem;"></i>
                <span style="color: ${config.color};">${text}</span>
            `;
            this.badge.style.backgroundColor = config.bg;
            this.badge.style.border = `1px solid ${config.border}`;
            this.badge.style.transform = 'translateY(0)';
            this.badge.style.opacity = '1';

            if (autoHideMs > 0) {
                this.hideTimer = setTimeout(() => this.hide(), autoHideMs);
            }
        }

        hide() {
            if (!this.badge) return;
            this.badge.style.transform = 'translateY(80px)';
            this.badge.style.opacity = '0';
        }

        /**
         * Intercept form submissions to show saving/saved indicators
         */
        interceptFormSubmissions() {
            document.addEventListener('submit', (e) => {
                if (e.defaultPrevented) return;
                const form = e.target;
                if (!form || form.tagName !== 'FORM') return;

                // Skip delete forms and search forms
                if (form.classList.contains('delete-student-form')) return;
                if (form.method?.toLowerCase() === 'get') return;
                if (!form.checkValidity()) return;

                setTimeout(() => {
                    if (e.defaultPrevented) return;
                    this.show('saving', 0);
                    // Hide after 6s if page did not navigate away
                    setTimeout(() => {
                        this.hide();
                    }, 6000);
                }, 100);
            });
        }

        /**
         * Watch for connectivity changes
         */
        watchConnectivity() {
            window.addEventListener('offline', () => {
                this.show('offline', 0); // Persistent until online
            });

            window.addEventListener('online', () => {
                this.show('syncing', 0);
                // After sync animation, show saved
                setTimeout(() => {
                    this.show('saved', 3000);
                }, 2000);
            });
        }
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        window.taalimuStatus = new StatusIndicator();
    });

    // Global API for other scripts to use
    window.showStatus = function(type, duration) {
        if (window.taalimuStatus) {
            window.taalimuStatus.show(type, duration);
        }
    };
})();
