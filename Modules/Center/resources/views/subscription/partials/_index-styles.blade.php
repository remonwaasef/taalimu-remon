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
    .contact-card {
        background: linear-gradient(135deg, #2E8B83 0%, #10b981 100%);
        border-radius: 1rem;
        color: #fff;
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
