{{-- Bug Report Floating Widget (Beta Feedback System) --}}
{{-- This widget appears on every page to allow centers to report issues --}}

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
        max-height: 120px;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        margin-top: 8px;
    }

    /* Paste Zone Styles */
    .paste-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8fafc;
        position: relative;
    }

    .paste-zone:hover,
    .paste-zone.drag-over {
        border-color: #059669;
        background: #f0fdf4;
    }

    .paste-zone.has-image {
        border-color: #059669;
        border-style: solid;
        background: #f0fdf4;
        padding: 10px;
    }

    .paste-zone .paste-icon {
        font-size: 28px;
        color: #94a3b8;
        margin-bottom: 8px;
    }

    .paste-zone:hover .paste-icon {
        color: #059669;
    }

    .paste-zone .paste-text {
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
    }

    .paste-zone .paste-text kbd {
        background: #e2e8f0;
        border-radius: 4px;
        padding: 2px 6px;
        font-size: 11px;
        color: #334155;
    }

    .paste-zone img {
        max-height: 150px;
        max-width: 100%;
        border-radius: 8px;
        border: 2px solid #059669;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .paste-zone .remove-btn {
        position: absolute;
        top: 5px;
        {{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 5px;
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 5;
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

                    {{-- Screenshot Paste/Upload Zone --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small">
                            <i class="fas fa-camera me-1 text-success"></i>
                            {{ app()->getLocale() == 'ar' ? 'لقطة الشاشة' : (app()->getLocale() == 'fr' ? "Capture d'écran" : 'Screenshot') }}
                        </label>
                        <div class="paste-zone" id="pasteZone" tabindex="0">
                            <div id="pasteZonePlaceholder">
                                <div class="paste-icon"><i class="fas fa-paste"></i></div>
                                <div class="paste-text">
                                    @if(app()->getLocale() == 'ar')
                                        اضغط <kbd>Print Screen</kbd> ثم <kbd>Ctrl+V</kbd> هنا للصق لقطة الشاشة
                                        <br><span class="text-muted">أو اسحب صورة هنا أو اضغط لاختيار ملف</span>
                                    @elseif(app()->getLocale() == 'fr')
                                        Appuyez sur <kbd>Print Screen</kbd> puis <kbd>Ctrl+V</kbd> ici
                                        <br><span class="text-muted">ou glissez une image ici ou cliquez pour choisir</span>
                                    @else
                                        Press <kbd>Print Screen</kbd> then <kbd>Ctrl+V</kbd> here
                                        <br><span class="text-muted">or drag an image here or click to choose a file</span>
                                    @endif
                                </div>
                            </div>
                            <div id="pasteZonePreview" class="d-none">
                                <img id="pastedScreenshot" src="" alt="Screenshot">
                                <button type="button" class="remove-btn" onclick="removePastedImage(event)">
                                    <i class="fas fa-times"></i>
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
// Open modal (no more html2canvas - just open directly)
function openBugReportModal() {
    const modalEl = document.getElementById('bugReportModal');
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    } else {
        modalEl.classList.add('show');
        modalEl.style.display = 'block';
        document.body.classList.add('modal-open');
    }
    // Focus the paste zone so user can immediately paste
    setTimeout(() => {
        document.getElementById('pasteZone').focus();
    }, 500);
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

// Handle pasted image from clipboard
function handlePastedImage(dataUrl) {
    document.getElementById('autoScreenshotValue').value = dataUrl;
    document.getElementById('pastedScreenshot').src = dataUrl;
    document.getElementById('pasteZonePlaceholder').classList.add('d-none');
    document.getElementById('pasteZonePreview').classList.remove('d-none');
    document.getElementById('pasteZone').classList.add('has-image');
}

// Remove pasted image
function removePastedImage(event) {
    event.stopPropagation();
    document.getElementById('autoScreenshotValue').value = '';
    document.getElementById('pastedScreenshot').src = '';
    document.getElementById('pasteZonePlaceholder').classList.remove('d-none');
    document.getElementById('pasteZonePreview').classList.add('d-none');
    document.getElementById('pasteZone').classList.remove('has-image');
    document.getElementById('bugScreenshot').value = '';
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

    const pasteZone = document.getElementById('pasteZone');
    const fileInput = document.getElementById('bugScreenshot');

    // 1. Handle paste (Ctrl+V) anywhere in the modal
    document.getElementById('bugReportModal').addEventListener('paste', function(e) {
        const items = e.clipboardData?.items;
        if (!items) return;

        for (let i = 0; i < items.length; i++) {
            if (items[i].type.indexOf('image') !== -1) {
                e.preventDefault();
                const blob = items[i].getAsFile();
                const reader = new FileReader();
                reader.onload = function(ev) {
                    handlePastedImage(ev.target.result);
                };
                reader.readAsDataURL(blob);
                break;
            }
        }
    });

    // 2. Handle drag & drop on paste zone
    pasteZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        pasteZone.classList.add('drag-over');
    });

    pasteZone.addEventListener('dragleave', function(e) {
        pasteZone.classList.remove('drag-over');
    });

    pasteZone.addEventListener('drop', function(e) {
        e.preventDefault();
        pasteZone.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                handlePastedImage(ev.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    // 3. Handle click to open file picker
    pasteZone.addEventListener('click', function() {
        if (!pasteZone.classList.contains('has-image')) {
            fileInput.click();
        }
    });

    // 4. Handle file input change
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                handlePastedImage(ev.target.result);
            };
            reader.readAsDataURL(this.files[0]);
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
                document.getElementById('bugReportFormBody').classList.add('d-none');
                document.getElementById('bugReportSuccessBody').classList.remove('d-none');
                document.getElementById('bugReportForm').reset();
                removePastedImage(new Event('reset'));
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
