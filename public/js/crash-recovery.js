/**
 * ============================================================
 * Taalimu Crash Recovery — Never Lose Your Work
 * ============================================================
 * يحفظ حالة الصفحة الحالية (URL + بيانات الفورم) في sessionStorage
 * وعند إعادة فتح الصفحة يعرض إشعار لاستعادة آخر جلسة عمل
 * ============================================================
 */

(function() {
    'use strict';

    const RECOVERY_KEY = 'taalimu_crash_recovery';
    const RECOVERY_FORM_KEY = 'taalimu_crash_form';
    const SESSION_ALIVE_KEY = 'taalimu_session_alive';
    const HEARTBEAT_INTERVAL = 5000; // 5 seconds

    /**
     * Initialize crash recovery system
     */
    function init() {
        // Check if previous session crashed
        checkForCrash();

        // Mark session as alive
        markAlive();

        // Start heartbeat
        setInterval(markAlive, HEARTBEAT_INTERVAL);

        // Save state on page unload
        window.addEventListener('beforeunload', saveState);

        // Handle page visibility changes
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                saveState();
            }
        });

        // Track form state periodically for recovery
        setInterval(saveFormState, 10000); // Every 10 seconds
    }

    /**
     * Check if the previous session ended unexpectedly
     */
    function checkForCrash() {
        try {
            const wasAlive = localStorage.getItem(SESSION_ALIVE_KEY);
            const savedState = localStorage.getItem(RECOVERY_KEY);

            if (wasAlive === 'true' && savedState) {
                const state = JSON.parse(savedState);
                const currentUrl = window.location.pathname + window.location.search;

                // Only offer recovery if we're on a different page
                // or if the saved state has form data
                const savedFormData = localStorage.getItem(RECOVERY_FORM_KEY);
                const hasFormData = savedFormData && Object.keys(JSON.parse(savedFormData)).length > 0;

                if (state.url !== currentUrl || hasFormData) {
                    const savedAt = new Date(state.savedAt);
                    const minutesAgo = Math.floor((Date.now() - savedAt.getTime()) / 60000);

                    // Only show recovery for recent sessions (within 30 minutes)
                    if (minutesAgo <= 30) {
                        showRecoveryPrompt(state, minutesAgo);
                    }
                }
            }

            // Clear the alive flag — it will be set by heartbeat
            localStorage.setItem(SESSION_ALIVE_KEY, 'false');
        } catch (e) {
            console.warn('[CrashRecovery] Check failed:', e);
        }
    }

    /**
     * Mark the session as alive (heartbeat)
     */
    function markAlive() {
        try {
            localStorage.setItem(SESSION_ALIVE_KEY, 'true');
        } catch (e) {
            // Silently fail
        }
    }

    /**
     * Save current page state
     */
    function saveState() {
        try {
            const state = {
                url: window.location.pathname + window.location.search,
                title: document.title,
                scrollY: window.scrollY,
                savedAt: new Date().toISOString()
            };

            localStorage.setItem(RECOVERY_KEY, JSON.stringify(state));
            localStorage.setItem(SESSION_ALIVE_KEY, 'false');
        } catch (e) {
            // Silently fail
        }
    }

    /**
     * Save form state for crash recovery
     */
    function saveFormState() {
        try {
            const activeForms = document.querySelectorAll('form[data-autosave]');
            if (!activeForms.length) return;

            const formSnapshots = {};
            activeForms.forEach(form => {
                const formId = form.getAttribute('data-autosave') || 'default';
                const formData = new FormData(form);
                const data = {};

                for (const [key, value] of formData.entries()) {
                    if (key === '_token' || key === '_method') continue;
                    if (value instanceof File) continue;
                    if (value && value.toString().trim()) {
                        data[key] = value;
                    }
                }

                if (Object.keys(data).length > 0) {
                    formSnapshots[formId] = data;
                }
            });

            if (Object.keys(formSnapshots).length > 0) {
                localStorage.setItem(RECOVERY_FORM_KEY, JSON.stringify(formSnapshots));
            }
        } catch (e) {
            // Silently fail
        }
    }

    /**
     * Show recovery prompt to user
     */
    function showRecoveryPrompt(state, minutesAgo) {
        const timeText = minutesAgo < 1 ? 'لحظات' : minutesAgo + ' دقيقة';

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '🔄 استعادة الجلسة السابقة',
                html: `
                    <div class="text-start" style="font-size: 0.9rem;">
                        <p class="text-muted mb-2">يبدو أن جلستك السابقة انتهت بشكل غير متوقع.</p>
                        <div class="bg-light rounded-3 p-3 mb-2">
                            <div class="d-flex align-items-center mb-1">
                                <i class="fas fa-file-alt text-primary me-2"></i>
                                <small class="text-dark fw-bold">${state.title || 'صفحة غير معروفة'}</small>
                            </div>
                            <small class="text-muted">منذ ${timeText}</small>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '↩️ استعادة',
                cancelButtonText: 'تجاهل',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#94a3b8',
                customClass: {
                    popup: 'rounded-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Navigate to the saved URL
                    window.location.href = state.url;
                }
                // Clear recovery data
                clearRecoveryData();
            });
        }
    }

    /**
     * Clear all recovery data
     */
    function clearRecoveryData() {
        localStorage.removeItem(RECOVERY_KEY);
        localStorage.removeItem(RECOVERY_FORM_KEY);
    }

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
