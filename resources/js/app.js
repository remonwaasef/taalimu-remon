import './bootstrap';
// NOTE: bs-compat.js removed — Bootstrap 5 is already fully loaded via
// libs.min.js (assets/hope-ui/js/libs.min.js). Keeping both caused
// duplicate event listeners for dropdowns, modals, tabs & collapse,
// resulting in double-toggle (open → immediately close) on all
// data-bs-toggle elements.
