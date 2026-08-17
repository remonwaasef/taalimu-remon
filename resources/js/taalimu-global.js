/**
 * ==========================================================================
 * TAALIMU GLOBAL INTERACTIONS (التفاعلات العالمية الموحدة)
 * --------------------------------------------------------------------------
 * Central JavaScript file loaded on every page.
 * Provides reusable UI patterns: delete confirmations, toasts, double-submit
 * prevention, clipboard copy, and feature gate dialogs.
 *
 * Usage is via HTML data-attributes — zero per-page JavaScript needed.
 * ==========================================================================
 */

(function () {
    'use strict';

    /* ------------------------------------------------------------------
       Helper: Detect dark mode from <html> class
       ------------------------------------------------------------------ */
    function isDarkMode() {
        return document.documentElement.classList.contains('dark') ||
               document.body.classList.contains('dark');
    }

    function swalDarkOptions() {
        if (!isDarkMode()) return {};
        return {
            background: '#1E293B',
            color: '#F8FAFC',
            confirmButtonColor: '#2E8B83',
        };
    }

    /* ------------------------------------------------------------------
       1. TaalimuToast — Global Toast Notification System
       ------------------------------------------------------------------
       Usage (JS):
         TaalimuToast.success('تم الحفظ بنجاح');
         TaalimuToast.error('حدث خطأ');
         TaalimuToast.warning('تحذير');
         TaalimuToast.info('ملاحظة');
       ------------------------------------------------------------------ */
    window.TaalimuToast = {
        _fire: function (icon, title) {
            if (typeof Swal === 'undefined') {
                alert(title);
                return;
            }
            var dark = isDarkMode();
            Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                background: dark ? '#1E293B' : '#ffffff',
                color: dark ? '#F8FAFC' : '#1E293B',
                didOpen: function (toast) {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            }).fire({ icon: icon, title: title });
        },
        success: function (msg) { this._fire('success', msg); },
        error:   function (msg) { this._fire('error', msg); },
        warning: function (msg) { this._fire('warning', msg); },
        info:    function (msg) { this._fire('info', msg); },
    };

    /* ------------------------------------------------------------------
       2. Global Delete Confirmation (data-confirm-delete)
       ------------------------------------------------------------------
       Usage (HTML):
         <form id="delete-form-{{ $id }}" action="..." method="POST">
             @csrf @method('DELETE')
         </form>
         <button data-confirm-delete
                 data-form="delete-form-{{ $id }}"
                 data-title="هل أنت متأكد؟"
                 data-text="لن تتمكن من التراجع"
                 data-confirm="نعم، احذف"
                 data-cancel="إلغاء">
             حذف
         </button>

       All data-* attributes are optional except data-form.
       ------------------------------------------------------------------ */
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-confirm-delete]');
        if (!btn) return;

        e.preventDefault();
        e.stopPropagation();

        if (typeof Swal === 'undefined') {
            if (confirm(btn.dataset.title || 'هل أنت متأكد؟')) {
                var form = document.getElementById(btn.dataset.form);
                if (form) form.submit();
            }
            return;
        }

        var options = Object.assign({
            title: btn.dataset.title || 'هل أنت متأكد؟',
            text: btn.dataset.text || 'لن تتمكن من التراجع عن هذا الإجراء',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: btn.dataset.confirm || 'نعم، احذف',
            cancelButtonText: btn.dataset.cancel || 'إلغاء',
            reverseButtons: document.dir === 'rtl' || document.documentElement.dir === 'rtl',
        }, swalDarkOptions());

        Swal.fire(options).then(function (result) {
            if (result.isConfirmed) {
                var targetForm = document.getElementById(btn.dataset.form);
                if (targetForm) {
                    targetForm.submit();
                } else if (btn.dataset.url) {
                    window.location.href = btn.dataset.url;
                }
            }
        });
    });

    /* ------------------------------------------------------------------
       3. Global Confirmation Dialog (data-confirm-action)
       ------------------------------------------------------------------
       Usage (HTML) — for non-delete confirmations (archive, toggle, etc.):
         <button data-confirm-action
                 data-form="archive-form-5"
                 data-title="تأكيد الأرشفة"
                 data-text="سيتم نقل العنصر للأرشيف"
                 data-icon="question"
                 data-confirm="نعم، أرشف"
                 data-confirm-color="#2E8B83">
             أرشفة
         </button>
       ------------------------------------------------------------------ */
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-confirm-action]');
        if (!btn) return;

        e.preventDefault();
        e.stopPropagation();

        if (typeof Swal === 'undefined') {
            if (confirm(btn.dataset.title || 'هل أنت متأكد؟')) {
                var form = document.getElementById(btn.dataset.form);
                if (form) form.submit();
            }
            return;
        }

        var options = Object.assign({
            title: btn.dataset.title || 'تأكيد',
            text: btn.dataset.text || '',
            icon: btn.dataset.icon || 'question',
            showCancelButton: true,
            confirmButtonColor: btn.dataset.confirmColor || '#2E8B83',
            cancelButtonColor: '#6B7280',
            confirmButtonText: btn.dataset.confirm || 'نعم',
            cancelButtonText: btn.dataset.cancel || 'إلغاء',
            reverseButtons: document.dir === 'rtl' || document.documentElement.dir === 'rtl',
        }, swalDarkOptions());

        Swal.fire(options).then(function (result) {
            if (result.isConfirmed) {
                var targetForm = document.getElementById(btn.dataset.form);
                if (targetForm) {
                    targetForm.submit();
                } else if (btn.dataset.url) {
                    window.location.href = btn.dataset.url;
                }
            }
        });
    });

    /* ------------------------------------------------------------------
       4. Prevent Double Submit (data-prevent-double)
       ------------------------------------------------------------------
       Usage (HTML):
         <form data-prevent-double action="..." method="POST">
             <button type="submit">حفظ</button>
         </form>

       On submit, the button is disabled and shows a spinner.
       ------------------------------------------------------------------ */
    document.addEventListener('submit', function (e) {
        var form = e.target.closest('form[data-prevent-double]');
        if (!form) return;

        var btn = form.querySelector('[type="submit"]');
        if (!btn || btn.disabled) {
            e.preventDefault();
            return;
        }

        btn.disabled = true;
        var originalHTML = btn.innerHTML;
        btn.dataset.originalHtml = originalHTML;
        var loadingText = btn.dataset.loadingText || 'جارٍ...';
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> ' + loadingText;

        // Re-enable after 10 seconds as safety net (e.g. validation failure)
        setTimeout(function () {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        }, 10000);
    });

    /* ------------------------------------------------------------------
       5. Clipboard Copy (data-clipboard)
       ------------------------------------------------------------------
       Usage (HTML):
         <button data-clipboard="https://mysite.taalimu.com">نسخ الرابط</button>
         OR
         <button data-clipboard-target="#someInput">نسخ</button>
       ------------------------------------------------------------------ */
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-clipboard], [data-clipboard-target]');
        if (!btn) return;

        e.preventDefault();
        var text = btn.dataset.clipboard;
        if (!text && btn.dataset.clipboardTarget) {
            var target = document.querySelector(btn.dataset.clipboardTarget);
            text = target ? (target.value || target.textContent) : '';
        }
        if (!text) return;

        navigator.clipboard.writeText(text).then(function () {
            TaalimuToast.success(btn.dataset.successMsg || 'تم النسخ بنجاح!');

            // Visual feedback on button
            var originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check me-1"></i> ' + (btn.dataset.copiedText || 'تم النسخ');
            btn.classList.add('btn-success');
            setTimeout(function () {
                btn.innerHTML = originalHTML;
                btn.classList.remove('btn-success');
            }, 2000);
        }).catch(function () {
            TaalimuToast.error('فشل النسخ، حاول مرة أخرى');
        });
    });

    /* ------------------------------------------------------------------
       6. Feature Gate (data-feature-gate)
       ------------------------------------------------------------------
       Usage (HTML):
         <button data-feature-gate
                 data-feature-name="الاختبارات الإلكترونية"
                 data-upgrade-url="/subscription">
             إنشاء اختبار
         </button>

       Shows a styled SweetAlert upgrade prompt instead of performing
       the action, when the feature is not available in the current plan.
       ------------------------------------------------------------------ */
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-feature-gate]');
        if (!btn) return;

        e.preventDefault();
        e.stopPropagation();

        if (typeof Swal === 'undefined') {
            alert('هذه الميزة غير متوفرة في باقتك الحالية');
            return;
        }

        var featureName = btn.dataset.featureName || 'هذه الميزة';
        var upgradeUrl = btn.dataset.upgradeUrl || '/subscription';

        var options = Object.assign({
            title: 'ترقية مطلوبة',
            html: '<div style="font-size:1rem;line-height:1.8">' +
                  '<i class="fas fa-lock" style="font-size:2.5rem;color:#F59E0B;margin-bottom:12px;display:block"></i>' +
                  '<strong>' + featureName + '</strong> غير متوفرة في باقتك الحالية.<br>' +
                  'قم بالترقية للاستفادة من جميع المزايا.</div>',
            icon: null,
            showCancelButton: true,
            confirmButtonColor: '#2E8B83',
            confirmButtonText: '<i class="fas fa-arrow-up me-1"></i> ترقية الآن',
            cancelButtonText: 'لاحقاً',
            reverseButtons: document.dir === 'rtl' || document.documentElement.dir === 'rtl',
        }, swalDarkOptions());

        Swal.fire(options).then(function (result) {
            if (result.isConfirmed) {
                window.location.href = upgradeUrl;
            }
        });
    });

    /* ------------------------------------------------------------------
       7. Loading Overlay for Links (data-loading)
       ------------------------------------------------------------------
       Usage (HTML):
         <a href="/reports/generate" data-loading data-loading-text="جارٍ إنشاء التقرير...">
             إنشاء تقرير
         </a>

       Shows a full-page loading overlay when navigating to heavy pages.
       ------------------------------------------------------------------ */
    document.addEventListener('click', function (e) {
        var link = e.target.closest('a[data-loading]');
        if (!link || !link.href) return;

        // Don't intercept if opening in new tab
        if (e.ctrlKey || e.metaKey || link.target === '_blank') return;

        var text = link.dataset.loadingText || 'جارٍ التحميل...';

        // Create overlay
        var overlay = document.createElement('div');
        overlay.id = 'taalimu-loading-overlay';
        overlay.style.cssText = 'position:fixed;inset:0;z-index:99999;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.4);backdrop-filter:blur(4px)';
        overlay.innerHTML = '<div style="background:' + (isDarkMode() ? '#1E293B' : '#fff') +
            ';padding:2rem 3rem;border-radius:1rem;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,0.2)">' +
            '<i class="fas fa-spinner fa-spin" style="font-size:2rem;color:#2E8B83;margin-bottom:1rem;display:block"></i>' +
            '<div style="font-size:1rem;font-weight:600;color:' + (isDarkMode() ? '#F8FAFC' : '#1E293B') + '">' + text + '</div></div>';
        document.body.appendChild(overlay);

        // Auto-remove after 15 seconds as safety net
        setTimeout(function () {
            var el = document.getElementById('taalimu-loading-overlay');
            if (el) el.remove();
        }, 15000);
    });

/* ------------------------------------------------------------------
        9. Taalimu.notify — Global Notification Pulse
        ------------------------------------------------------------------
        Plays the notification-bell swing + glow, pops the unread badge,
        and optionally shows a toast. Dispatch-based so any backend can
        trigger it without touching the navbars:
          Taalimu.notify('تمت إضافة طالب جديد');
        Listens: window 'new-notification' event.
        ------------------------------------------------------------------ */
    window.Taalimu = window.Taalimu || {};
    window.Taalimu.notify = function (message, type) {
        type = type || 'success';
        if (message && typeof window.TaalimuToast !== 'undefined' && window.TaalimuToast[type]) {
            window.TaalimuToast[type](message);
        }
        window.dispatchEvent(new CustomEvent('new-notification'));
    };

    window.addEventListener('new-notification', function () {
        var bells = document.querySelectorAll('[data-bell-swing]');
        bells.forEach(function (bell) {
            bell.click(); // triggers the Alpine ping() handler
        });
    });

    /* ------------------------------------------------------------------
        10. Auto-dismiss Flash Alerts
        ------------------------------------------------------------------
        Automatically hides .flash-messages-container alerts after 6 seconds
        with a smooth fade-out animation.
        ------------------------------------------------------------------ */
    document.addEventListener('DOMContentLoaded', function () {
        var flashAlerts = document.querySelectorAll('.flash-messages-container .alert');
        flashAlerts.forEach(function (alert) {
            setTimeout(function () {
                alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(function () { alert.remove(); }, 500);
            }, 6000);
        });
    });

    /* ------------------------------------------------------------------
        11. Responsive Card-Mode Tables
        ------------------------------------------------------------------
        For any wrapper carrying [data-mobile-cards], copy the header
        labels onto each cell (data-label) so CSS can render rows as
        stacked cards on small screens. Cells without a header (actions,
        checkboxes) and colspan cells (empty states) get dedicated classes.
        ------------------------------------------------------------------ */
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-mobile-cards] table').forEach(function (table) {
            var headerCells = table.querySelectorAll('thead th');
            if (!headerCells.length) return;

            var labels = Array.prototype.map.call(headerCells, function (th) {
                var hide = th.classList.contains('d-none') || th.classList.contains('hidden');
                return hide ? '' : (th.textContent || '').trim().replace(/[:\u200f\u200e]+$/g, '');
            });

            table.querySelectorAll('tbody tr').forEach(function (row) {
                row.querySelectorAll('td').forEach(function (td, index) {
                    if (td.hasAttribute('colspan')) {
                        td.classList.add('td-full');
                        return;
                    }
                    var label = labels[index] || '';
                    if (label) {
                        td.setAttribute('data-label', label);
                    } else {
                        td.classList.add('td-actions');
                    }
                });
            });
        });
    });

})();
