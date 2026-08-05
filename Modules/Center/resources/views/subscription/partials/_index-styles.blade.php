<style>
    .subscription-hero {
        background: #fff;
        border-radius: 1.25rem;
        color: var(--bs-dark);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .progress-bar-custom {
        height: 8px;
        border-radius: 999px;
        background: rgba(255,255,255,0.2);
        overflow: hidden;
    }
    .progress-bar-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, #7ecbff, #fff);
        transition: width 1s ease;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
    }
    .status-active   { background: rgba(34,197,94,0.15); color: #16a34a; }
    .status-trial    { background: rgba(251,191,36,0.15); color: #d97706; }
    .status-expired  { background: rgba(239,68,68,0.15);  color: #dc2626; }
    .plan-card {
        border: 2px solid #e5e7eb;
        border-radius: 1rem;
        transition: all 0.3s ease;
        background: #fff;
        cursor: pointer;
    }
    .plan-card:hover          { border-color: #10b981; transform: translateY(-4px); box-shadow: 0 12px 30px rgba(16,185,129,0.12); }
    .plan-card.current-plan   { border-color: #10b981; background: linear-gradient(135deg, #f0fdf4, #f8fafc); }
    .plan-card.featured-plan  { border-color: #10b981; }
    .feature-check { color: #10b981; }
    .feature-x     { color: #d1d5db; }
    .info-tile {
        background: rgba(0,0,0,0.03);
        border-radius: 0.75rem;
        padding: 1rem 1.25rem;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .pulse-dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        background: #10b981;
        animation: pulse-green 2s infinite;
    }
    @keyframes pulse-green {
        0%   { box-shadow: 0 0 0 0 rgba(16,185,129,0.6); }
        70%  { box-shadow: 0 0 0 8px rgba(16,185,129,0); }
        100% { box-shadow: 0 0 0 0 rgba(16,185,129,0); }
    }
    .contact-card {
        background: linear-gradient(135deg, #2E8B83 0%, #10b981 100%);
        border-radius: 1rem;
        color: #fff;
    }
    /* Redesigned Toggle Styles - Upgraded to 3 choices */
    .billing-toggle {
        display: inline-flex;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 999px;
        padding: 5px;
        position: relative;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        direction: ltr !important; 
    }
    .billing-toggle label {
        cursor: pointer;
        padding: 10px 18px;
        font-weight: 800;
        border-radius: 999px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1;
        font-size: 0.85rem;
        color: #64748b;
        position: relative;
        min-width: 100px;
        text-align: center;
    }
    .billing-toggle input[type="radio"]:checked + label {
        color: #fff !important;
    }
    .billing-toggle input[type="radio"] {
        display: none;
    }
    .toggle-slider {
        position: absolute;
        top: 4px;
        bottom: 4px;
        left: 4px; 
        width: calc(33.33% - 5px);
        background: #10b981;
        border-radius: 999px;
        transition: transform 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
        z-index: 0;
        box-shadow: 0 4px 12px rgba(16,185,129, 0.3);
    }
    .dual-toggle .toggle-slider {
        width: calc(50% - 6px);
    }
    .billing-toggle input[type="radio"]:nth-of-type(2):checked ~ .toggle-slider {
        transform: translateX(100%);
    }
    .dual-toggle input[type="radio"]:nth-of-type(2):checked ~ .toggle-slider {
        transform: translateX(100%);
    }
    .billing-toggle input[type="radio"]:nth-of-type(3):checked ~ .toggle-slider {
        transform: translateX(200%);
    }
    .save-badge {
        position: absolute;
        top: -15px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.75rem;
        padding: 4px 8px;
        white-space: nowrap;
        background: #f59e0b !important;
        color: #fff !important;
        border-radius: 6px;
        box-shadow: 0 4px 10px rgba(245,158,11,0.3);
        z-index: 10;
        border: 1px solid rgba(255,255,255,0.3) !important;
        animation: pulse-orange 2.5s infinite;
        font-weight: 800;
    }
    @keyframes pulse-orange {
        0%   { box-shadow: 0 0 0 0 rgba(245,158,11,0.5); transform: translateX(-50%) scale(1); }
        70%  { box-shadow: 0 0 0 8px rgba(245,158,11,0); transform: translateX(-50%) scale(1.05); }
        100% { box-shadow: 0 0 0 0 rgba(245,158,11,0); transform: translateX(-50%) scale(1); }
    }
</style>
