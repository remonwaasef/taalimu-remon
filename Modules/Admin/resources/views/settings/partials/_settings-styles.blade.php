<style>
    /* Custom Tab Styling for Settings */
    #settingsTabs .nav-link {
        color: var(--text-muted);
        font-weight: 600;
        transition: all 0.3s ease;
    }

    #settingsTabs .nav-link.active {
        color: var(--primary-purple) !important;
        background: transparent !important;
    }

    #settingsTabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--gradient-primary);
        border-radius: 3px 3px 0 0;
    }

    .premium-card {
        border-radius: 20px;
        border: none;
        box-shadow: var(--shadow-md);
        background: white;
        overflow: hidden;
    }

    .form-control:focus {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.1);
    }

    .form-switch .form-check-input:checked {
        background-color: var(--primary-purple);
        border-color: var(--primary-purple);
    }

    /* Premium Plans Styling */
    .premium-plan-card {
        border-radius: 20px;
        position: relative;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .premium-plan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
    }

    .plan-accent-bar {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 8px;
        background: var(--gradient-primary);
    }

    .plan-pro .plan-accent-bar { background: linear-gradient(90deg, #3A0CA3, #4361EE); }
    .plan-basic .plan-accent-bar { background: linear-gradient(90deg, #4361EE, #5BE7C4); }
    .plan-free .plan-accent-bar { background: linear-gradient(90deg, #cbd5e1, #94a3b8); }

    .plan-pro .plan-name { color: #3A0CA3; }
    .plan-basic .plan-name { color: #4361EE; }
    
    .fw-black { font-weight: 900; }

    .premium-accordion .accordion-button {
        box-shadow: none !important;
        border-radius: 12px !important;
        transition: all 0.2s ease;
    }

    .premium-accordion .accordion-button:not(.collapsed) {
        background: rgba(42, 77, 255, 0.05) !important;
        color: var(--primary-purple) !important;
    }

    .premium-accordion .accordion-button::after {
        background-size: 1rem;
    }

    .premium-accordion .accordion-item {
        background: transparent;
    }

    .font-monospace {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace !important;
    }

    /* Premium Switches */
    .premium-switch {
        cursor: pointer;
        width: 3.2em !important;
        height: 1.6em !important;
        background-color: #e2e8f0;
        border-color: #cbd5e1;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
    }

    .premium-switch:checked {
        background-color: #10b981 !important; /* Success Green */
        border-color: #059669 !important;
        background-position: right center;
        box-shadow: 0 0 10px rgba(16, 185, 129, 0.4) !important;
    }

    .premium-switch:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.1) !important;
    }

    .toggle-label {
        transition: color 0.3s ease;
        color: #94a3b8;
    }

    .premium-switch:checked + .toggle-label {
        color: #10b981 !important;
        font-weight: 700 !important;
    }

    [dir="rtl"] .premium-switch:checked {
        background-position: left center;
    }

    /* RTL Switches Fix */
    [dir="rtl"] .form-switch .premium-switch {
        margin-right: -2.5em;
        margin-left: 0;
    }
</style>
