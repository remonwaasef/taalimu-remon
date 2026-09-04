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
    
    fab.style.display = 'none';
    modal.style.visibility = 'hidden';

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
                showBugReportModal();
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
    loading.textContent = '';
    const div = document.createElement('div');
    const icon = document.createElement('i');
    icon.className = 'fas fa-camera';
    icon.style.opacity = '0.3';
    div.appendChild(icon);
    
    const small = document.createElement('small');
    small.style.color = '#94a3b8';
    small.textContent = '{{ __('center::messages.bug_report.no_screenshot') }}';
    
    loading.appendChild(div);
    loading.appendChild(small);
    loading.classList.remove('d-none');
}

function retakeScreenshot() {
    captureScreenshot();
}

// ========== Modal Controls ==========

function openBugReportModal() {
    const btn = document.getElementById('bugReportFab');
    const originalNodes = Array.from(btn.childNodes); // Save original nodes
    btn.textContent = '';
    const spinner = document.createElement('i');
    spinner.className = 'fas fa-spinner fa-spin';
    spinner.style.fontSize = '20px';
    btn.appendChild(spinner);
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
            btn.textContent = '';
            originalNodes.forEach(node => btn.appendChild(node));
            btn.style.pointerEvents = 'auto';
            showModalWithScreenshot(dataUrl);
        })
        .catch(function(error) {
            console.error('Auto screenshot failed:', error);
            fab.style.display = '';
            btn.textContent = '';
            originalNodes.forEach(node => btn.appendChild(node));
            btn.style.pointerEvents = 'auto';
            showModalWithScreenshot(null);
        });
    }, 150);
}

function showBugReportModal() {
    const modalEl = document.getElementById('bugReportModal');
    modalEl.classList.add('show');
    modalEl.style.visibility = '';
    document.body.classList.add('modal-open');
}

function showModalWithScreenshot(dataUrl) {
    showBugReportModal();

    if (dataUrl) {
        setScreenshot(dataUrl);
    } else {
        document.getElementById('screenshotLoading').classList.add('d-none');
    }
}

function closeBugReportModal() {
    const modalEl = document.getElementById('bugReportModal');
    modalEl.classList.remove('show');
    document.body.classList.remove('modal-open');

    // Reset modal state (was previously handled via hidden.bs.modal)
    document.getElementById('bugReportFormBody').classList.remove('d-none');
    document.getElementById('bugReportSuccessBody').classList.add('d-none');
    document.getElementById('bugPageUrl').value = window.location.href;
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

    // Reset page URL whenever the modal opens (state reset happens on close)
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
        const originalNodes = Array.from(btn.childNodes);
        btn.textContent = '{{ __("center::bug_report.submitting") }}';
        const spinner = document.createElement('i');
        spinner.className = 'fas fa-spinner fa-spin me-2';
        btn.prepend(spinner);
        btn.disabled = true;

        const infoField = document.getElementById('bugBrowserInfo');
        const info = JSON.parse(infoField.value);
        info.js_errors = window._jsErrors;
        info.page_url_at_submit = window.location.href;
        infoField.value = JSON.stringify(info);

        const formData = new FormData(this);

        fetch('{{ tenant_route("center.bug-report.store") }}', {
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
                confirmButtonColor: '#2E8B83',
            });
        })
        .finally(() => {
            btn.textContent = '';
            originalNodes.forEach(node => btn.appendChild(node));
            btn.disabled = false;
        });
    });

    // Refresh page URL on open
    document.getElementById('bugReportModal').addEventListener('transitionend', function() {
        if (this.classList.contains('show')) {
            document.getElementById('bugPageUrl').value = window.location.href;
        }
    });
});
</script>
