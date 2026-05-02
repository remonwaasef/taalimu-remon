{{-- Bug Report Floating Widget (Beta Feedback System) --}}
{{-- This widget appears on every page to allow centers to report issues --}}

<!-- Include html2canvas for automatic screenshots -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

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
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 20px rgba(5, 150, 105, 0.4);
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
        box-shadow: 0 6px 28px rgba(5, 150, 105, 0.5);
        animation: none;
    }

    @keyframes bug-fab-pulse {
        0%, 100% { box-shadow: 0 4px 20px rgba(5, 150, 105, 0.4); }
        50% { box-shadow: 0 4px 28px rgba(5, 150, 105, 0.6); }
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

    /* Modal Overrides for Bug Report */
    #bugReportModal .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
    }

    #bugReportModal .modal-header {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: white;
        border: none;
        padding: 20px 24px;
    }

    #bugReportModal .modal-header .btn-close {
        filter: brightness(0) invert(1);
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
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
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

    .screenshot-preview {
        max-height: 100px;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        margin-top: 8px;
    }

    .auto-screenshot-container {
        position: relative;
        display: inline-block;
    }
    
    .auto-screenshot-container img {
        max-height: 120px;
        border-radius: 8px;
        border: 2px solid #059669;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .auto-screenshot-container .badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #059669;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
    }

    .bug-info-badge {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 12px;
    }
</style>

{{-- Floating Button --}}
<button type="button" class="bug-report-fab" onclick="openBugReportModal()" id="bugReportFab">
    🐛
    <span class="fab-tooltip">{{ __('center::bug_report.report_bug') }}</span>
</button>

{{-- Bug Report Modal --}}
<div class="modal fade" id="bugReportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            {{-- Header --}}
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-bug me-2"></i>{{ __('center::bug_report.report_bug') }}
                </h5>
                <button type="button" class="btn-close" onclick="closeBugReportModal()" aria-label="Close"></button>
            </div>

            {{-- Form Body --}}
            <div class="modal-body" id="bugReportFormBody">
                <form id="bugReportForm" enctype="multipart/form-data">
                    @csrf

                    {{-- Category --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small">{{ __('center::bug_report.report_category') }}</label>
                        <select name="category" class="form-select" required id="bugCategory">
                            <option value="bug">🐛 {{ __('center::bug_report.category_bug') }}</option>
                            <option value="suggestion">💡 {{ __('center::bug_report.category_suggestion') }}</option>
                            <option value="ui_issue">🎨 {{ __('center::bug_report.category_ui_issue') }}</option>
                            <option value="performance">⚡ {{ __('center::bug_report.category_performance') }}</option>
                            <option value="other">📝 {{ __('center::bug_report.category_other') }}</option>
                        </select>
                    </div>

                    {{-- Title --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small">{{ __('center::bug_report.report_title') }}</label>
                        <input type="text" name="title" class="form-control" placeholder="{{ __('center::bug_report.report_title_placeholder') }}" required maxlength="255" id="bugTitle">
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small">{{ __('center::bug_report.report_description') }}</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="{{ __('center::bug_report.report_description_placeholder') }}" required maxlength="5000" id="bugDescription"></textarea>
                    </div>

                    {{-- Auto Captured Screenshot --}}
                    <div class="mb-3 d-none" id="autoScreenshotContainer">
                        <label class="form-label fw-bold small">لقطة الشاشة التلقائية:</label>
                        <div class="auto-screenshot-container">
                            <img id="autoScreenshotPreview" src="" alt="Auto Captured Screenshot">
                            <span class="badge"><i class="fas fa-camera"></i></span>
                        </div>
                        <div class="small text-muted mt-1">تم التقاط صورة للشاشة تلقائياً. يمكنك اختيار صورة أخرى إذا أردت.</div>
                    </div>

                    {{-- Custom Screenshot --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small">تغيير الصورة المرفقة (اختياري)</label>
                        <input type="file" name="screenshot" class="form-control" accept="image/*" id="bugScreenshot">
                        <img id="screenshotPreview" class="screenshot-preview d-none" alt="preview">
                    </div>

                    {{-- Auto-info Notice --}}
                    <div class="bug-info-badge mb-3">
                        <i class="fas fa-info-circle me-1"></i>
                        {{ __('center::bug_report.auto_info_notice') }}
                    </div>

                    {{-- Hidden Fields (auto-filled by JS) --}}
                    <input type="hidden" name="page_url" id="bugPageUrl">
                    <input type="hidden" name="browser_info" id="bugBrowserInfo">
                    <input type="hidden" name="auto_screenshot" id="autoScreenshotValue">

                    {{-- Submit --}}
                    <button type="submit" class="btn btn-success w-100 rounded-pill py-2 fw-bold" id="bugSubmitBtn">
                        <i class="fas fa-paper-plane me-2"></i>{{ __('center::bug_report.submit_report') }}
                    </button>
                </form>
            </div>

            {{-- Success Body (hidden initially) --}}
            <div class="modal-body d-none" id="bugReportSuccessBody">
                <div class="bug-report-success">
                    <div class="success-icon">✅</div>
                    <h4 class="fw-bold mt-3">{{ __('center::bug_report.thank_you_title') }}</h4>
                    <p class="text-muted">{{ __('center::bug_report.thank_you_message') }}</p>
                    <button type="button" class="btn btn-outline-success rounded-pill px-4 mt-2" onclick="closeBugReportModal()">
                        {{ __('center::bug_report.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Open modal with auto screenshot
function openBugReportModal() {
    const btn = document.getElementById('bugReportFab');
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size: 20px;"></i>';
    btn.style.pointerEvents = 'none';

    // Capture screenshot (excluding the modal and button)
    html2canvas(document.body, {
        logging: false,
        useCORS: true,
        ignoreElements: (node) => {
            return node.id === 'bugReportFab' || node.id === 'bugReportModal' || node.classList.contains('modal-backdrop');
        }
    }).then(canvas => {
        const base64image = canvas.toDataURL("image/jpeg", 0.6);
        document.getElementById('autoScreenshotValue').value = base64image;
        
        // Show preview
        const preview = document.getElementById('autoScreenshotPreview');
        preview.src = base64image;
        document.getElementById('autoScreenshotContainer').classList.remove('d-none');

        showModal();
    }).catch(err => {
        console.error("Screenshot capture failed", err);
        showModal(); // Show anyway
    }).finally(() => {
        btn.innerHTML = originalContent;
        btn.style.pointerEvents = 'auto';
    });

    function showModal() {
        const modalEl = document.getElementById('bugReportModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        } else {
            // Fallback for older environments
            modalEl.classList.add('show');
            modalEl.style.display = 'block';
            document.body.classList.add('modal-open');
            if (!document.querySelector('.modal-backdrop')) {
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                document.body.appendChild(backdrop);
            }
        }
    }
}

function closeBugReportModal() {
    const modalEl = document.getElementById('bugReportModal');
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        modal.hide();
    } else {
        modalEl.classList.remove('show');
        modalEl.style.display = 'none';
        document.body.classList.remove('modal-open');
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) backdrop.remove();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Collect browser info automatically
    const browserInfo = {
        browser: navigator.userAgent.match(/(Chrome|Firefox|Safari|Edge|Opera)[\/\s](\d+)/)?.[0] || navigator.userAgent.substring(0, 80),
        os: navigator.platform,
        language: navigator.language,
        screen: window.innerWidth + 'x' + window.innerHeight,
        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
    };

    document.getElementById('bugBrowserInfo').value = JSON.stringify(browserInfo);
    document.getElementById('bugPageUrl').value = window.location.href;

    // Screenshot preview
    document.getElementById('bugScreenshot').addEventListener('change', function(e) {
        const preview = document.getElementById('screenshotPreview');
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                preview.src = ev.target.result;
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(this.files[0]);
        } else {
            preview.classList.add('d-none');
        }
    });

    // Capture JS errors to include in report
    window._jsErrors = [];
    window.addEventListener('error', function(e) {
        if (window._jsErrors.length < 5) {
            window._jsErrors.push({
                message: e.message,
                source: e.filename,
                line: e.lineno,
                time: new Date().toISOString()
            });
        }
    });

    // Form submission via AJAX
    document.getElementById('bugReportForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const btn = document.getElementById('bugSubmitBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("center::bug_report.submitting") }}';
        btn.disabled = true;

        // Add JS errors to browser info
        const infoField = document.getElementById('bugBrowserInfo');
        const info = JSON.parse(infoField.value);
        info.js_errors = window._jsErrors;
        info.page_url_at_submit = window.location.href;
        infoField.value = JSON.stringify(info);

        const formData = new FormData(this);

        fetch('{{ route("center.bug-report.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success state
                document.getElementById('bugReportFormBody').classList.add('d-none');
                document.getElementById('bugReportSuccessBody').classList.remove('d-none');
                // Reset form
                document.getElementById('bugReportForm').reset();
                document.getElementById('screenshotPreview').classList.add('d-none');
            } else {
                throw new Error(data.message || 'Unknown error');
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{{ __("center::bug_report.submit_error") }}',
                confirmButtonColor: '#059669',
            });
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    });

    // Reset modal state when closed
    const modal = document.getElementById('bugReportModal');
    modal.addEventListener('hidden.bs.modal', function() {
        document.getElementById('bugReportFormBody').classList.remove('d-none');
        document.getElementById('bugReportSuccessBody').classList.add('d-none');
        document.getElementById('bugPageUrl').value = window.location.href;
    });
});
</script>
