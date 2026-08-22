<style>
    /* Floating Bug Report Button */
    .bug-report-fab {
        position: fixed;
        bottom: 24px;
        {{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 24px;
        z-index: 9990;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4F46E5 0%, #4338CA 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 20px rgba(79, 70, 229, 0.4);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        animation: bug-fab-pulse 3s ease-in-out infinite;
    }

    .bug-report-fab:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 28px rgba(79, 70, 229, 0.5);
        animation: none;
    }

    @keyframes bug-fab-pulse {
        0%, 100% { box-shadow: 0 4px 20px rgba(79, 70, 229, 0.4); }
        50% { box-shadow: 0 4px 28px rgba(79, 70, 229, 0.6); }
    }

    .bug-report-fab .fab-tooltip {
        position: absolute;
        {{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: -999px;
        bottom: 50%;
        transform: translateY(50%);
        background: #1e293b;
        color: white;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 13px;
        white-space: nowrap;
        opacity: 0;
        transition: all 0.3s;
        pointer-events: none;
    }

    .bug-report-fab:hover .fab-tooltip {
        {{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: calc(100% + 12px);
        opacity: 1;
    }

    /* Modal Base (Bootstrap-free) */
    #bugReportModal {
        position: fixed;
        inset: 0;
        z-index: 9995;
        display: none;
        overflow-y: auto;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(2px);
        padding: 1rem;
    }

    #bugReportModal.show { display: block; }

    #bugReportModal .modal-dialog {
        max-width: 500px;
        margin: 3rem auto;
    }

    #bugReportModal .modal-content {
        background: white;
        color: #1e293b;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    /* Scoped visibility helper (Bootstrap-free) */
    #bugReportModal .d-none { display: none !important; }

    #bugReportModal .btn-close {
        background: transparent;
        border: none;
        color: white;
        font-size: 20px;
        line-height: 1;
        cursor: pointer;
        opacity: 0.9;
        padding: 0;
    }

    #bugReportModal .btn-close:hover { opacity: 1; }

    /* Modal Overrides for Bug Report */
    #bugReportModal .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
    }

    #bugReportModal .modal-header {
        background: linear-gradient(135deg, #4F46E5 0%, #4338CA 100%);
        color: white;
        border: none;
        padding: 20px 24px;
    }

    #bugReportModal .modal-body {
        padding: 24px;
    }

    #bugReportModal .form-control,
    #bugReportModal .form-select {
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        padding: 10px 14px;
        transition: border-color 0.2s;
    }

    #bugReportModal .form-control:focus,
    #bugReportModal .form-select:focus {
        border-color: #4F46E5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .bug-report-success {
        text-align: center;
        padding: 30px 20px;
    }

    .bug-report-success .success-icon {
        font-size: 64px;
        animation: bug-success-bounce 0.6s ease;
    }

    @keyframes bug-success-bounce {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    /* Screenshot Container Styles */
    .screenshot-box {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        background: #f8fafc;
        position: relative;
    }

    .screenshot-box.has-image {
        border-color: #4F46E5;
    }

    .screenshot-box .screenshot-img {
        max-height: 140px;
        width: 100%;
        object-fit: contain;
        display: block;
    }

    .screenshot-box .screenshot-loading {
        padding: 20px;
        text-align: center;
        color: #94a3b8;
    }

    .screenshot-box .screenshot-loading i {
        font-size: 24px;
        margin-bottom: 6px;
    }

    .screenshot-box .screenshot-actions {
        display: flex;
        gap: 6px;
        padding: 8px;
        background: rgba(248, 250, 252, 0.95);
        border-top: 1px solid #e2e8f0;
        justify-content: center;
    }

    .screenshot-box .screenshot-actions .btn {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 8px;
    }

    .bug-info-badge {
        background: #EEF2FF;
        border: 1px solid #C7D2FE;
        color: #3730A3;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 12px;
    }
</style>
