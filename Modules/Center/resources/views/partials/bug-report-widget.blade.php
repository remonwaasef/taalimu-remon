{{-- Bug Report Floating Widget (Beta Feedback System) --}}
{{-- Uses dom-to-image-more for auto-screenshots (proper Arabic font support) --}}
{{-- Also supports paste/drag/upload as manual alternatives --}}

<!-- dom-to-image-more: Handles Arabic fonts correctly by embedding them as base64 -->
<script src="https://cdn.jsdelivr.net/npm/dom-to-image-more@3/dist/dom-to-image-more.min.js"></script>

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

    /* Screenshot Container Styles */
    .screenshot-box {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        background: #f8fafc;
        position: relative;
    }

    .screenshot-box.has-image {
        border-color: #059669;
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
                        <textarea name="description" class="form-control" rows="3" placeholder="{{ __('center::bug_report.report_description_placeholder') }}" required maxlength="5000" id="bugDescription"></textarea>
                    </div>

                    {{-- Auto Screenshot Box --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small">
                            <i class="fas fa-camera me-1 text-success"></i>
                            @if(app()->getLocale() == 'ar') لقطة الشاشة
                            @elseif(app()->getLocale() == 'fr') Capture d'écran
                            @else Screenshot @endif
                        </label>
                        <div class="screenshot-box" id="screenshotBox">
                            {{-- Loading state --}}
                            <div id="screenshotLoading" class="screenshot-loading">
                                <div><i class="fas fa-spinner fa-spin"></i></div>
                                <small>
                                    @if(app()->getLocale() == 'ar') جاري التقاط الشاشة...
                                    @elseif(app()->getLocale() == 'fr') Capture en cours...
                                    @else Capturing screenshot... @endif
                                </small>
                            </div>
                            {{-- Image preview --}}
                            <img id="screenshotPreview" class="screenshot-img d-none" src="" alt="Screenshot">
                            {{-- Action buttons --}}
                            <div class="screenshot-actions d-none" id="screenshotActions">
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeScreenshot()">
                                    <i class="fas fa-trash me-1"></i>
                                    @if(app()->getLocale() == 'ar') حذف @elseif(app()->getLocale() == 'fr') Supprimer @else Remove @endif
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('bugScreenshot').click()">
                                    <i class="fas fa-exchange-alt me-1"></i>
                                    @if(app()->getLocale() == 'ar') تغيير @elseif(app()->getLocale() == 'fr') Changer @else Change @endif
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="retakeScreenshot()">
                                    <i class="fas fa-redo me-1"></i>
                                    @if(app()->getLocale() == 'ar') إعادة التقاط @elseif(app()->getLocale() == 'fr') Reprendre @else Retake @endif
                                </button>
                            </div>
                        </div>
                        <input type="file" name="screenshot" class="d-none" accept="image/*" id="bugScreenshot">
                    </div>

                    {{-- Auto-info Notice --}}
                    <div class="bug-info-badge mb-3">
                        <i class="fas fa-info-circle me-1"></i>
                        {{ __('center::bug_report.auto_info_notice') }}
                    </div>

                    {{-- Hidden Fields --}}
                    <input type="hidden" name="page_url" id="bugPageUrl">
                    <input type="hidden" name="browser_info" id="bugBrowserInfo">
                    <input type="hidden" name="auto_screenshot" id="autoScreenshotValue">

                    {{-- Submit --}}
                    <button type="submit" class="btn btn-success w-100 rounded-pill py-2 fw-bold" id="bugSubmitBtn">
                        <i class="fas fa-paper-plane me-2"></i>{{ __('center::bug_report.submit_report') }}
                    </button>
                </form>
            </div>

            {{-- Success Body --}}
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
// ========== Screenshot Capture ==========

function captureScreenshot() {
    const loading = document.getElementById('screenshotLoading');
    const preview = document.getElementById('screenshotPreview');
    const actions = document.getElementById('screenshotActions');
    const box = document.getElementById('screenshotBox');

    // Show loading
    loading.classList.remove('d-none');
    preview.classList.add('d-none');
    actions.classList.add('d-none');
    box.classList.remove('has-image');

    // Hide the FAB and modal before capturing
    const fab = document.getElementById('bugReportFab');
    const modal = document.getElementById('bugReportModal');
    const backdrops = document.querySelectorAll('.modal-backdrop');
    
    fab.style.display = 'none';
    modal.style.display = 'none';
    backdrops.forEach(b => b.style.display = 'none');
    document.body.classList.remove('modal-open');

    // Wait a frame for DOM to update, then capture
    requestAnimationFrame(() => {
        setTimeout(() => {
            domtoimage.toJpeg(document.body, { 
                quality: 0.75,
                bgcolor: '#ffffff',
                style: {
                    'overflow': 'visible'
                },
                filter: (node) => {
                    if (!node.classList) return true;
                    return !node.classList.contains('modal-backdrop');
                }
            })
            .then(function(dataUrl) {
                setScreenshot(dataUrl);
            })
            .catch(function(error) {
                console.error('Screenshot capture failed:', error);
                // Show the box without an image
                loading.classList.add('d-none');
            })
            .finally(function() {
                // Restore modal visibility
                fab.style.display = '';
                modal.style.display = '';
                backdrops.forEach(b => b.style.display = '');
                document.body.classList.add('modal-open');
                
                // Re-show the modal
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    if (!bsModal) {
                        new bootstrap.Modal(modal).show();
                    }
                }
            });
        }, 100);
    });
}

function setScreenshot(dataUrl) {
    const preview = document.getElementById('screenshotPreview');
    const actions = document.getElementById('screenshotActions');
    const loading = document.getElementById('screenshotLoading');
    const box = document.getElementById('screenshotBox');

    // Compress and resize the image before storing
    const img = new Image();
    img.onload = function() {
        const maxWidth = 1280;
        const maxHeight = 900;
        let width = img.width;
        let height = img.height;

        // Scale down if too large
        if (width > maxWidth) {
            height = Math.round(height * maxWidth / width);
            width = maxWidth;
        }
        if (height > maxHeight) {
            width = Math.round(width * maxHeight / height);
            height = maxHeight;
        }

        const canvas = document.createElement('canvas');
        canvas.width = width;
        canvas.height = height;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0, width, height);

        const compressedDataUrl = canvas.toDataURL('image/jpeg', 0.5);
        document.getElementById('autoScreenshotValue').value = compressedDataUrl;
        preview.src = compressedDataUrl;
        preview.classList.remove('d-none');
        actions.classList.remove('d-none');
        loading.classList.add('d-none');
        box.classList.add('has-image');
    };
    img.onerror = function() {
        // Fallback: use original data
        document.getElementById('autoScreenshotValue').value = dataUrl;
        preview.src = dataUrl;
        preview.classList.remove('d-none');
        actions.classList.remove('d-none');
        loading.classList.add('d-none');
        box.classList.add('has-image');
    };
    img.src = dataUrl;
}

function removeScreenshot() {
    const preview = document.getElementById('screenshotPreview');
    const actions = document.getElementById('screenshotActions');
    const loading = document.getElementById('screenshotLoading');
    const box = document.getElementById('screenshotBox');

    document.getElementById('autoScreenshotValue').value = '';
    document.getElementById('bugScreenshot').value = '';
    preview.src = '';
    preview.classList.add('d-none');
    actions.classList.add('d-none');
    loading.classList.add('d-none');
    box.classList.remove('has-image');

    // Show a simple "no screenshot" placeholder
    loading.innerHTML = '<div><i class="fas fa-camera" style="opacity:0.3"></i></div><small style="color:#94a3b8">{{ app()->getLocale() == "ar" ? "لم يتم إرفاق صورة" : "No screenshot attached" }}</small>';
    loading.classList.remove('d-none');
}

function retakeScreenshot() {
    captureScreenshot();
}

// ========== Modal Controls ==========

function openBugReportModal() {
    const btn = document.getElementById('bugReportFab');
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size: 20px;"></i>';
    btn.style.pointerEvents = 'none';

    // First capture the screenshot (while modal is hidden)
    const fab = btn;
    fab.style.display = 'none';

    // Small delay to hide the button first
    setTimeout(() => {
        domtoimage.toJpeg(document.body, { 
            quality: 0.75,
            bgcolor: '#ffffff',
            filter: (node) => {
                if (!node.classList) return true;
                return !node.classList.contains('bug-report-fab') && 
                       !node.classList.contains('modal-backdrop');
            }
        })
        .then(function(dataUrl) {
            // Show the modal with the captured screenshot
            fab.style.display = '';
            btn.innerHTML = originalContent;
            btn.style.pointerEvents = 'auto';
            showModalWithScreenshot(dataUrl);
        })
        .catch(function(error) {
            console.error('Auto screenshot failed:', error);
            fab.style.display = '';
            btn.innerHTML = originalContent;
            btn.style.pointerEvents = 'auto';
            showModalWithScreenshot(null);
        });
    }, 150);
}

function showModalWithScreenshot(dataUrl) {
    const modalEl = document.getElementById('bugReportModal');
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    } else {
        modalEl.classList.add('show');
        modalEl.style.display = 'block';
        document.body.classList.add('modal-open');
    }

    if (dataUrl) {
        setScreenshot(dataUrl);
    } else {
        document.getElementById('screenshotLoading').classList.add('d-none');
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
    }
}

// ========== Event Listeners ==========

document.addEventListener('DOMContentLoaded', function() {
    // Collect browser info
    const browserInfo = {
        browser: navigator.userAgent.match(/(Chrome|Firefox|Safari|Edge|Opera)[\/\s](\d+)/)?.[0] || navigator.userAgent.substring(0, 80),
        os: navigator.platform,
        language: navigator.language,
        screen: window.innerWidth + 'x' + window.innerHeight,
        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
    };
    document.getElementById('bugBrowserInfo').value = JSON.stringify(browserInfo);
    document.getElementById('bugPageUrl').value = window.location.href;

    // Handle paste (Ctrl+V) inside the modal
    document.getElementById('bugReportModal').addEventListener('paste', function(e) {
        const items = e.clipboardData?.items;
        if (!items) return;
        for (let i = 0; i < items.length; i++) {
            if (items[i].type.indexOf('image') !== -1) {
                e.preventDefault();
                const blob = items[i].getAsFile();
                const reader = new FileReader();
                reader.onload = function(ev) {
                    setScreenshot(ev.target.result);
                };
                reader.readAsDataURL(blob);
                break;
            }
        }
    });

    // Handle file input change
    document.getElementById('bugScreenshot').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                setScreenshot(ev.target.result);
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Capture JS errors
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

    // Form submission
    document.getElementById('bugReportForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const btn = document.getElementById('bugSubmitBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("center::bug_report.submitting") }}';
        btn.disabled = true;

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
        .then(response => {
            if (!response.ok) {
                // Server returned an error status (413, 422, 500, etc.)
                return response.text().then(text => {
                    let errorMsg = 'Server Error: ' + response.status;
                    try {
                        const json = JSON.parse(text);
                        errorMsg = json.message || json.error || errorMsg;
                    } catch(e) {
                        // If response is HTML (like 413), extract a short message
                        if (response.status === 413) {
                            errorMsg = 'الصورة كبيرة جداً. حاول بدون صورة أو بصورة أصغر.';
                        }
                    }
                    throw new Error(errorMsg);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                document.getElementById('bugReportFormBody').classList.add('d-none');
                document.getElementById('bugReportSuccessBody').classList.remove('d-none');
                document.getElementById('bugReportForm').reset();
                removeScreenshot();
            } else {
                throw new Error(data.message || 'Unknown error');
            }
        })
        .catch(error => {
            console.error('Bug report submit error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: error.message || '{{ __("center::bug_report.submit_error") }}',
                confirmButtonColor: '#059669',
            });
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    });

    // Reset modal state when closed
    document.getElementById('bugReportModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('bugReportFormBody').classList.remove('d-none');
        document.getElementById('bugReportSuccessBody').classList.add('d-none');
        document.getElementById('bugPageUrl').value = window.location.href;
    });
});
</script>
