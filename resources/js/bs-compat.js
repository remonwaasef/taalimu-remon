/**
 * Taalimu Bootstrap Compatibility Layer
 * ---------------------------------------------------------------------------
 * Minimal re-implementation of the Bootstrap JS API surface (Modal, Tab,
 * Collapse, Dropdown) plus the data-bs-* declarative behaviors.
 *
 * Purpose: allows legacy views built on Bootstrap markup/classes to keep
 * working after the real bootstrap package was removed from the bundle,
 * while the UI is progressively migrated to Tailwind + Alpine.js.
 *
 * Remove this file once no view references data-bs-* attributes or
 * window.bootstrap anymore.
 */
(function () {
    'use strict';

    var OPEN_CLASS = 'show';
    var BODY_MODAL = 'modal-open';

    function qs(target, root) {
        if (!target) return null;
        if (typeof target === 'string') return document.querySelector(target);
        return target;
    }

    function fire(el, type) {
        el.dispatchEvent(new CustomEvent(type, { bubbles: true }));
    }

    /* ─── Modal ─────────────────────────────────────────────── */

    function Modal(element) {
        this._element = element;
    }

    Modal.prototype.show = function () {
        var el = this._element;
        el.classList.add(OPEN_CLASS);
        el.style.display = '';
        document.body.classList.add(BODY_MODAL);
        fire(el, 'shown.bs.modal');
    };

    Modal.prototype.hide = function () {
        var el = this._element;
        el.classList.remove(OPEN_CLASS);
        document.body.classList.remove(BODY_MODAL);
        fire(el, 'hidden.bs.modal');
    };

    Modal.prototype.toggle = function () {
        this._element.classList.contains(OPEN_CLASS) ? this.hide() : this.show();
    };

    Modal.prototype.dispose = function () {
        this._element._bsInstance = null;
    };

    Modal.getInstance = function (element) {
        return element && element._bsInstance ? element._bsInstance : null;
    };

    Modal.getOrCreateInstance = function (element) {
        if (!element._bsInstance) element._bsInstance = new Modal(element);
        return element._bsInstance;
    };

    /* ─── Tab / Pill ────────────────────────────────────────── */

    function Tab(trigger) {
        this._trigger = trigger;
    }

    Tab.prototype.show = function () {
        activateTab(this._trigger);
    };

    Tab.prototype.dispose = function () {};

    Tab.getInstance = function (element) {
        return element && element._bsInstance ? element._bsInstance : null;
    };

    Tab.getOrCreateInstance = function (element) {
        if (!element._bsInstance) element._bsInstance = new Tab(element);
        return element._bsInstance;
    };

    function activateTab(trigger) {
        var pane = null;
        var targetSel = trigger.getAttribute('data-bs-target') || trigger.getAttribute('href');
        if (targetSel && targetSel.charAt(0) === '#') pane = document.querySelector(targetSel);

        var nav = trigger.closest('[role="tablist"]');
        if (nav) {
            nav.querySelectorAll('[data-bs-toggle="tab"], [data-bs-toggle="pill"]').forEach(function (b) {
                b.classList.remove('active');
                b.setAttribute('aria-selected', 'false');
            });
        }
        trigger.classList.add('active');
        trigger.setAttribute('aria-selected', 'true');

        if (pane) {
            var content = pane.closest('.tab-content');
            if (content) {
                content.querySelectorAll(':scope > .tab-pane').forEach(function (p) {
                    p.classList.remove('active', OPEN_CLASS);
                    fire(p, 'hidden.bs.tab');
                });
            }
            pane.classList.add('active', OPEN_CLASS);
            fire(pane, 'shown.bs.tab');
        }
    }

    /* ─── Collapse ──────────────────────────────────────────── */

    function Collapse(element) {
        this._element = element;
    }

    Collapse.prototype.show = function () {
        this._element.classList.add(OPEN_CLASS);
        syncCollapseTrigger(this._element, true);
    };

    Collapse.prototype.hide = function () {
        this._element.classList.remove(OPEN_CLASS);
        syncCollapseTrigger(this._element, false);
    };

    Collapse.prototype.toggle = function () {
        this._element.classList.contains(OPEN_CLASS) ? this.hide() : this.show();
    };

    Collapse.prototype.dispose = function () {};

    Collapse.getInstance = function (element) {
        return element && element._bsInstance ? element._bsInstance : null;
    };

    Collapse.getOrCreateInstance = function (element) {
        if (!element._bsInstance) element._bsInstance = new Collapse(element);
        return element._bsInstance;
    };

    function collapseTargetFor(trigger) {
        var sel = trigger.getAttribute('data-bs-target') || trigger.getAttribute('href');
        return sel ? document.querySelector(sel) : null;
    }

    function syncCollapseTrigger(target, isOpen) {
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function (t) {
            if (collapseTargetFor(t) === target) {
                t.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                t.classList.toggle('collapsed', !isOpen);
            }
        });
    }

    /* ─── Dropdown ──────────────────────────────────────────── */

    function Dropdown(toggleEl) {
        this._element = toggleEl;
        this._menu = findMenu(toggleEl);
    }

    function findMenu(toggleEl) {
        var parent = toggleEl.closest('.dropdown');
        if (parent) return parent.querySelector('.dropdown-menu');
        var next = toggleEl.nextElementSibling;
        return next && next.classList.contains('dropdown-menu') ? next : null;
    }

    Dropdown.prototype.show = function () {
        closeAllDropdowns(this._element);
        if (this._menu) this._menu.classList.add(OPEN_CLASS);
        this._element.setAttribute('aria-expanded', 'true');
    };

    Dropdown.prototype.hide = function () {
        if (this._menu) this._menu.classList.remove(OPEN_CLASS);
        this._element.setAttribute('aria-expanded', 'false');
    };

    Dropdown.prototype.toggle = function () {
        this._menu && this._menu.classList.contains(OPEN_CLASS) ? this.hide() : this.show();
    };

    Dropdown.prototype.dispose = function () {};

    Dropdown.getInstance = function (element) {
        return element && element._bsInstance ? element._bsInstance : null;
    };

    Dropdown.getOrCreateInstance = function (element) {
        if (!element._bsInstance) element._bsInstance = new Dropdown(element);
        return element._bsInstance;
    };

    function closeAllDropdowns(exceptToggle) {
        document.querySelectorAll('.dropdown-menu.' + OPEN_CLASS).forEach(function (menu) {
            menu.classList.remove(OPEN_CLASS);
        });
        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function (t) {
            if (t !== exceptToggle) t.setAttribute('aria-expanded', 'false');
        });
    }

    /* ─── Public API (window.bootstrap) ─────────────────────── */

    window.bootstrap = {
        Modal: Modal,
        Tab: Tab,
        Collapse: Collapse,
        Dropdown: Dropdown,
    };

    /* ─── Declarative behaviors (data-bs-*) ─────────────────── */

    document.addEventListener('click', function (e) {
        var dismissAlert = e.target.closest('[data-bs-dismiss="alert"]');
        if (dismissAlert) {
            var alertEl = dismissAlert.closest('.alert');
            if (alertEl) alertEl.remove();
            return;
        }

        var dismissModal = e.target.closest('[data-bs-dismiss="modal"]');
        if (dismissModal) {
            var modalToHide = dismissModal.closest('.modal');
            if (modalToHide) Modal.getOrCreateInstance(modalToHide).hide();
            return;
        }

        var modalTrigger = e.target.closest('[data-bs-toggle="modal"]');
        if (modalTrigger) {
            e.preventDefault();
            var modalSel = modalTrigger.getAttribute('data-bs-target') || modalTrigger.getAttribute('href');
            var modalEl = qs(modalSel);
            if (modalEl) Modal.getOrCreateInstance(modalEl).show();
            return;
        }

        var tabTrigger = e.target.closest('[data-bs-toggle="tab"], [data-bs-toggle="pill"]');
        if (tabTrigger) {
            e.preventDefault();
            activateTab(tabTrigger);
            return;
        }

        var collapseTrigger = e.target.closest('[data-bs-toggle="collapse"]');
        if (collapseTrigger) {
            e.preventDefault();
            var collapseEl = collapseTargetFor(collapseTrigger);
            if (collapseEl) Collapse.getOrCreateInstance(collapseEl).toggle();
            return;
        }

        var ddTrigger = e.target.closest('[data-bs-toggle="dropdown"]');
        if (ddTrigger) {
            e.preventDefault();
            e.stopPropagation();
            Dropdown.getOrCreateInstance(ddTrigger).toggle();
            return;
        }
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.dropdown')) closeAllDropdowns(null);
    });

    // Escape closes open modal / dropdowns
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        var openModal = document.querySelector('.modal.' + OPEN_CLASS);
        if (openModal) Modal.getOrCreateInstance(openModal).hide();
        closeAllDropdowns(null);
    });
})();
