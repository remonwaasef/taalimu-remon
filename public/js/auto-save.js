/**
 * ============================================================
 * Taalimu Auto-Save — Premium Draft Saving System
 * ============================================================
 * يحفظ بيانات الفورم تلقائياً في localStorage كل 30 ثانية
 * ويستعيدها عند إعادة فتح الصفحة لمنع ضياع البيانات
 * ============================================================
 */

(function() {
    'use strict';

    const AUTOSAVE_INTERVAL = 30000; // 30 seconds
    const AUTOSAVE_KEY_PREFIX = 'taalimu_draft_';
    const AUTOSAVE_EXPIRY_HOURS = 24;

    /**
     * Initialize auto-save for all forms with [data-autosave] attribute
     */
    function initAutoSave() {
        const forms = document.querySelectorAll('form[data-autosave]');
        if (!forms.length) return;

        forms.forEach(form => {
            const formId = form.getAttribute('data-autosave') || form.id || generateFormId(form);
            const storageKey = AUTOSAVE_KEY_PREFIX + formId;

            // Try to restore saved draft
            restoreDraft(form, storageKey);

            // Set up periodic saving
            let saveTimer = setInterval(() => {
                saveDraft(form, storageKey);
            }, AUTOSAVE_INTERVAL);

            // Save on input change (debounced)
            let debounceTimer;
            form.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    saveDraft(form, storageKey);
                }, 2000);
            });

            // Clear draft on successful submit
            form.addEventListener('submit', () => {
                localStorage.removeItem(storageKey);
                clearInterval(saveTimer);
            });

            // Clean up on page unload
            window.addEventListener('beforeunload', () => {
                saveDraft(form, storageKey);
            });
        });
    }

    /**
     * Save form data as a draft to localStorage
     */
    function saveDraft(form, storageKey) {
        try {
            const formData = new FormData(form);
            const data = {};

            for (const [key, value] of formData.entries()) {
                // Skip CSRF token and files
                if (key === '_token' || key === '_method') continue;
                if (value instanceof File) continue;

                data[key] = value;
            }

            // Also save checkbox states (unchecked checkboxes are not in FormData)
            form.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                data['__cb_' + cb.name] = cb.checked ? '1' : '0';
            });

            const draft = {
                data: data,
                savedAt: new Date().toISOString(),
                url: window.location.pathname,
            };

            localStorage.setItem(storageKey, JSON.stringify(draft));
            showAutoSaveIndicator('saved');
        } catch (e) {
            console.warn('Auto-save failed:', e);
        }
    }

    /**
     * Restore form data from a saved draft
     */
    function restoreDraft(form, storageKey) {
        try {
            const raw = localStorage.getItem(storageKey);
            if (!raw) return;

            const draft = JSON.parse(raw);

            // Check expiry
            const savedAt = new Date(draft.savedAt);
            const hoursAgo = (Date.now() - savedAt.getTime()) / (1000 * 60 * 60);
            if (hoursAgo > AUTOSAVE_EXPIRY_HOURS) {
                localStorage.removeItem(storageKey);
                return;
            }

            // Check URL match
            if (draft.url && draft.url !== window.location.pathname) {
                return;
            }

            // Check if form has any user data already
            const formData = new FormData(form);
            let hasData = false;
            for (const [key, value] of formData.entries()) {
                if (key === '_token' || key === '_method') continue;
                if (value && value.toString().trim()) {
                    hasData = true;
                    break;
                }
            }

            // Only restore if form is empty (new form) or ask user
            if (hasData) {
                showDraftNotification(form, storageKey, draft);
            } else {
                applyDraft(form, draft.data);
                showAutoSaveIndicator('restored');
            }
        } catch (e) {
            console.warn('Draft restore failed:', e);
            localStorage.removeItem(storageKey);
        }
    }

    /**
     * Apply draft data to form fields
     */
    function applyDraft(form, data) {
        Object.keys(data).forEach(key => {
            // Handle checkboxes
            if (key.startsWith('__cb_')) {
                const realName = key.replace('__cb_', '');
                const cb = form.querySelector(`input[type="checkbox"][name="${realName}"]`);
                if (cb) cb.checked = data[key] === '1';
                return;
            }

            const field = form.querySelector(`[name="${key}"]`);
            if (!field) return;

            if (field.tagName === 'SELECT') {
                field.value = data[key];
                // Trigger change for dynamic selects
                field.dispatchEvent(new Event('change', { bubbles: true }));
            } else if (field.type === 'radio') {
                const radio = form.querySelector(`input[name="${key}"][value="${data[key]}"]`);
                if (radio) radio.checked = true;
            } else {
                field.value = data[key];
            }
        });
    }

    /**
     * Show notification that a draft exists
     */
    function showDraftNotification(form, storageKey, draft) {
        const savedAt = new Date(draft.savedAt);
        const timeAgo = getTimeAgo(savedAt);

        // Use SweetAlert2 if available
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: getTranslation('draft_found', '📝 تم العثور على مسودة'),
                text: getTranslation('draft_restore_question', `لديك مسودة محفوظة منذ ${timeAgo}. هل تريد استعادتها؟`),
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: getTranslation('restore', '✅ استعادة'),
                cancelButtonText: getTranslation('discard', '🗑️ تجاهل'),
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#ef4444',
            }).then((result) => {
                if (result.isConfirmed) {
                    applyDraft(form, draft.data);
                    showAutoSaveIndicator('restored');
                } else {
                    localStorage.removeItem(storageKey);
                }
            });
        } else {
            if (confirm(`تم العثور على مسودة محفوظة منذ ${timeAgo}. هل تريد استعادتها؟`)) {
                applyDraft(form, draft.data);
            } else {
                localStorage.removeItem(storageKey);
            }
        }
    }

    /**
     * Show auto-save indicator in the UI
     */
    function showAutoSaveIndicator(status) {
        // Use global status indicator if available
        if (window.showStatus) {
            window.showStatus(status === 'saved' ? 'saved' : 'restored', 2500);
            return;
        }

        let indicator = document.getElementById('autosave-indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.id = 'autosave-indicator';
            indicator.style.cssText = `
                position: fixed;
                bottom: 20px;
                left: 20px;
                z-index: 9999;
                padding: 8px 16px;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 600;
                transition: all 0.4s ease;
                opacity: 0;
                transform: translateY(10px);
                font-family: 'Cairo', sans-serif;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            `;
            document.body.appendChild(indicator);
        }

        if (status === 'saved') {
            indicator.innerHTML = '💾 ' + getTranslation('draft_saved', 'تم حفظ المسودة');
            indicator.style.background = '#10b981';
            indicator.style.color = '#fff';
        } else if (status === 'restored') {
            indicator.innerHTML = '♻️ ' + getTranslation('draft_restored', 'تم استعادة المسودة');
            indicator.style.background = '#3b82f6';
            indicator.style.color = '#fff';
        }

        indicator.style.opacity = '1';
        indicator.style.transform = 'translateY(0)';

        setTimeout(() => {
            indicator.style.opacity = '0';
            indicator.style.transform = 'translateY(10px)';
        }, 2500);
    }

    /**
     * Generate a unique form ID based on URL and form position
     */
    function generateFormId(form) {
        const path = window.location.pathname.replace(/\//g, '_');
        const index = Array.from(document.querySelectorAll('form')).indexOf(form);
        return path + '_form_' + index;
    }

    /**
     * Get time ago string
     */
    function getTimeAgo(date) {
        const seconds = Math.floor((Date.now() - date.getTime()) / 1000);
        if (seconds < 60) return 'لحظات';
        const minutes = Math.floor(seconds / 60);
        if (minutes < 60) return minutes + ' دقيقة';
        const hours = Math.floor(minutes / 60);
        return hours + ' ساعة';
    }

    /**
     * Simple translation helper
     */
    function getTranslation(key, fallback) {
        if (window.taalimuTranslations && window.taalimuTranslations[key]) {
            return window.taalimuTranslations[key];
        }
        return fallback;
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAutoSave);
    } else {
        initAutoSave();
    }
})();
