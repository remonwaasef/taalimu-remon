/**
 * ============================================================
 * Taalimu Keyboard Shortcuts — Power User Navigation
 * ============================================================
 * اختصارات لوحة المفاتيح للتنقل السريع بين أقسام المنصة
 * 
 * Ctrl+Shift+S → الطلاب
 * Ctrl+Shift+D → الداشبورد
 * Ctrl+Shift+C → الدورات
 * Ctrl+Shift+F → البحث الفوري (Focus)
 * Ctrl+Shift+N → إنشاء طالب جديد
 * Ctrl+Shift+? → عرض قائمة الاختصارات
 * Escape → إغلاق أي Modal
 * ============================================================
 */

(function() {
    'use strict';

    const SHORTCUTS = [
        { keys: ['ctrl', 'shift', 'd'], action: 'dashboard', label: 'لوحة التحكم', icon: 'fas fa-tachometer-alt' },
        { keys: ['ctrl', 'shift', 's'], action: 'students', label: 'إدارة الطلاب', icon: 'fas fa-user-graduate' },
        { keys: ['ctrl', 'shift', 'c'], action: 'courses', label: 'إدارة الدورات', icon: 'fas fa-book' },
        { keys: ['ctrl', 'shift', 'n'], action: 'new-student', label: 'إضافة طالب', icon: 'fas fa-plus' },
        { keys: ['ctrl', 'shift', 'f'], action: 'focus-search', label: 'البحث الفوري', icon: 'fas fa-search' },
        { keys: ['ctrl', 'shift', '/'], action: 'show-shortcuts', label: 'عرض الاختصارات', icon: 'fas fa-keyboard' },
    ];

    // URL patterns for navigation shortcuts, preferring dynamic ones from window
    const ROUTES = window.AppRoutes || {
        'dashboard': '/',
        'students': '/students',
        'courses': '/courses',
        'new-student': '/students/create',
    };

    function init() {
        document.addEventListener('keydown', handleKeydown);
    }

    function handleKeydown(e) {
        // Don't trigger in input/textarea/contenteditable fields
        const tag = e.target.tagName.toLowerCase();
        if (tag === 'input' || tag === 'textarea' || tag === 'select' || e.target.isContentEditable) {
            // Exception: Escape should always work
            if (e.key !== 'Escape') return;
        }

        // Handle Escape — close any open modal
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal.show');
            if (openModal) {
                const modalInstance = bootstrap.Modal.getInstance(openModal);
                if (modalInstance) modalInstance.hide();
            }
            // Close any SweetAlert
            if (typeof Swal !== 'undefined') {
                Swal.close();
            }
            return;
        }

        // Only process Ctrl+Shift combinations
        if (!e.ctrlKey || !e.shiftKey) return;

        const key = e.key.toLowerCase();

        for (const shortcut of SHORTCUTS) {
            const shortcutKey = shortcut.keys[shortcut.keys.length - 1];
            if (key === shortcutKey) {
                e.preventDefault();
                executeAction(shortcut.action);
                return;
            }
        }
    }

    function executeAction(action) {
        switch (action) {
            case 'focus-search':
                focusSearch();
                break;
            case 'show-shortcuts':
                showShortcutsModal();
                break;
            default:
                navigateTo(action);
        }
    }

    function navigateTo(action) {
        const path = ROUTES[action];
        if (!path) return;

        // Build the full URL using the current tenant prefix
        const currentPath = window.location.pathname;
        // Extract tenant prefix (e.g., /c/demo or subdomain handling)
        const tenantMatch = currentPath.match(/^(\/c\/[^/]+)/);
        const prefix = tenantMatch ? tenantMatch[1] : '';

        const fullPath = prefix + path;

        // Show visual feedback
        showNavigationToast(action);

        setTimeout(() => {
            window.location.href = fullPath;
        }, 200);
    }

    function focusSearch() {
        // Look for the search input
        const searchInput = document.querySelector('[data-instant-search]') 
            || document.querySelector('input[name="search"]')
            || document.querySelector('.search-input');
        
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
            
            // Visual highlight
            searchInput.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.3)';
            setTimeout(() => {
                searchInput.style.boxShadow = '';
            }, 2000);
        }
    }

    function showNavigationToast(action) {
        const shortcut = SHORTCUTS.find(s => s.action === action);
        if (!shortcut) return;

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: shortcut.label,
                icon: 'info',
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 800,
                customClass: { popup: 'rounded-4' }
            });
        }
    }

    function showShortcutsModal() {
        if (typeof Swal === 'undefined') return;

        let html = `<div class="text-start" dir="rtl">`;
        html += `<table class="table table-borderless mb-0" style="font-size: 0.85rem;">`;
        html += `<tbody>`;

        SHORTCUTS.forEach(shortcut => {
            const keyCombination = shortcut.keys.map(k => {
                if (k === 'ctrl') return 'Ctrl';
                if (k === 'shift') return 'Shift';
                return k.toUpperCase();
            }).join(' + ');

            html += `
                <tr>
                    <td class="py-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="${shortcut.icon} text-primary opacity-75" style="width: 20px;"></i>
                            <span class="fw-bold">${shortcut.label}</span>
                        </div>
                    </td>
                    <td class="py-2 text-end">
                        <kbd class="bg-light text-dark border px-2 py-1 rounded" style="font-size: 0.75rem;">${keyCombination}</kbd>
                    </td>
                </tr>
            `;
        });

        // Add Escape shortcut
        html += `
            <tr>
                <td class="py-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-times text-danger opacity-75" style="width: 20px;"></i>
                        <span class="fw-bold">إغلاق النافذة</span>
                    </div>
                </td>
                <td class="py-2 text-end">
                    <kbd class="bg-light text-dark border px-2 py-1 rounded" style="font-size: 0.75rem;">Esc</kbd>
                </td>
            </tr>
        `;

        html += `</tbody></table></div>`;

        Swal.fire({
            title: '⌨️ اختصارات لوحة المفاتيح',
            html: html,
            showConfirmButton: false,
            showCloseButton: true,
            width: 480,
            customClass: { popup: 'rounded-4' }
        });
    }

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
