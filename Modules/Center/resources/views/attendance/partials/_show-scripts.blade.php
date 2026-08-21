<script>
    // ═══════════════════════════════════════════════════════════════
    // OFFLINE ATTENDANCE SYSTEM
    // ═══════════════════════════════════════════════════════════════
    @feature('offline_attendance')
    const OFFLINE_ENABLED = true;
    @else
    const OFFLINE_ENABLED = false;
    @endfeature

    const OFFLINE_STORAGE_KEY = 'taalimu_offline_attendance';
    const SYNC_URL = '{{ route("center.attendance.offlineSync") }}';
    const STORE_URL = '{{ route("center.attendance.store") }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
    const sessionStartTimeRaw = "{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}";

    // ─── LocalStorage Helpers ───
    function getOfflineRecords() {
        try {
            return JSON.parse(localStorage.getItem(OFFLINE_STORAGE_KEY) || '[]');
        } catch (e) {
            return [];
        }
    }

    function saveOfflineRecord(record) {
        const records = getOfflineRecords();
        // Prevent duplicate (same student + schedule + date)
        const exists = records.findIndex(r =>
            r.student_id == record.student_id &&
            r.schedule_id == record.schedule_id &&
            r.session_date == record.session_date
        );
        if (exists >= 0) {
            records[exists] = record; // Update existing
        } else {
            records.push(record);
        }
        localStorage.setItem(OFFLINE_STORAGE_KEY, JSON.stringify(records));
        updateOfflineBar();
    }

    function clearSyncedRecords() {
        localStorage.removeItem(OFFLINE_STORAGE_KEY);
        updateOfflineBar();
    }

    // ─── Connection Status UI ───
    function updateOfflineBar() {
        const bar = document.getElementById('offlineStatusBar');
        const inner = document.getElementById('offlineBarInner');
        const icon = document.getElementById('offlineIcon');
        const text = document.getElementById('offlineText');
        const pending = document.getElementById('offlinePending');
        const records = getOfflineRecords();

        if (!navigator.onLine) {
            bar.classList.remove('d-none');
            inner.className = 'd-flex align-items-center justify-content-between px-4 py-2 bar-offline';
            icon.innerHTML = '<i class="bi bi-wifi-off fs-5"></i>';
            text.textContent = '⚡ وضع أوفلاين - التحضير يتم حفظه محلياً';
            pending.textContent = records.length > 0 ? `📦 ${records.length} سجل في الانتظار` : '';
        } else if (records.length > 0) {
            bar.classList.remove('d-none');
            inner.className = 'd-flex align-items-center justify-content-between px-4 py-2 bar-syncing';
            icon.innerHTML = '<i class="bi bi-arrow-repeat fs-5 spin-icon"></i>';
            text.textContent = '🔄 جاري المزامنة...';
            pending.textContent = `📦 ${records.length} سجل`;
        } else {
            // Hide after a short delay to show success
            setTimeout(() => bar.classList.add('d-none'), 3000);
            inner.className = 'd-flex align-items-center justify-content-between px-4 py-2 bar-online';
            icon.innerHTML = '<i class="bi bi-wifi fs-5"></i>';
            text.textContent = '✅ متصل - جميع البيانات محدثة';
            pending.textContent = '';
        }
    }

    // ─── Sync Logic ───
    let isSyncing = false;

    async function syncOfflineAttendance() {
        if (isSyncing) return;
        const records = getOfflineRecords();
        if (records.length === 0) return;
        if (!navigator.onLine) return;

        isSyncing = true;
        updateOfflineBar();

        try {
            const response = await fetch(SYNC_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ records: records })
            });

            if (response.ok) {
                const data = await response.json();
                console.log('[OfflineSync] ✅ Synced:', data);
                clearSyncedRecords();

                // Show success toast
                showOfflineToast(`✅ تمت مزامنة ${data.synced} سجل حضور بنجاح!`, 'success');

                // Reload page to reflect synced data
                setTimeout(() => location.reload(), 2000);
            } else {
                console.error('[OfflineSync] ❌ Server error:', response.status);
                showOfflineToast('❌ فشل في المزامنة. سيتم المحاولة لاحقاً.', 'danger');
            }
        } catch (error) {
            console.error('[OfflineSync] ❌ Network error:', error);
        } finally {
            isSyncing = false;
            updateOfflineBar();
        }
    }

    // ─── Toast Notification ───
    function showOfflineToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} position-fixed shadow-lg border-0 rounded-3 px-4 py-3 fw-bold`;
        toast.style.cssText = 'top: 20px; left: 50%; transform: translateX(-50%); z-index: 99999; min-width: 300px; text-align: center; animation: slideDown 0.3s ease;';
        toast.innerHTML = message;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.animation = 'slideUp 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    // ─── Intercept Attendance Forms ───
    function interceptAttendanceForms() {
        // Intercept all attendance forms (Present / Absent buttons)
        document.querySelectorAll('form[action="{{ route("center.attendance.store") }}"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const payload = {};
                formData.forEach((value, key) => {
                    if (key !== '_token') payload[key] = value;
                });
                payload.offline_timestamp = new Date().toISOString();

                if (navigator.onLine) {
                    // Online: Send normally via AJAX
                    submitOnline(payload, this);
                } else {
                    // Offline: Save to LocalStorage
                    saveOfflineRecord(payload);
                    markRowAsOfflineSaved(payload.student_id, payload.status);
                    showOfflineToast('📦 تم الحفظ محلياً - ستتم المزامنة عند عودة الإنترنت', 'warning');
                }
            });
        });
    }

    function submitOnline(payload, formEl) {
        const btn = formEl.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        btn.disabled = true;

        fetch(STORE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.ok ? res.json() : Promise.reject(res))
        .then(data => {
            showOfflineToast('✅ ' + (data.message || 'تم تسجيل الحضور بنجاح!'), 'success');
            setTimeout(() => location.reload(), 1000);
        })
        .catch(error => {
            console.warn('[Attendance] Online failed, saving offline...', error);
            // Fallback to offline save
            saveOfflineRecord(payload);
            markRowAsOfflineSaved(payload.student_id, payload.status);
            showOfflineToast('📦 فشل الاتصال - تم الحفظ محلياً للمزامنة لاحقاً', 'warning');
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    // ─── Update UI for Offline Saved Records ───
    function markRowAsOfflineSaved(studentId, status) {
        // Find the row with this student
        const rows = document.querySelectorAll('table tbody tr');
        rows.forEach(row => {
            const studentInput = row.querySelector(`input[name="student_id"][value="${studentId}"]`);
            if (studentInput) {
                const statusCell = row.querySelector('td:nth-child(3)');
                if (statusCell) {
                    const statusLabels = {
                        'present': '{{ __("center::attendance.present") }}',
                        'late': '{{ __("center::attendance.late") }}',
                        'absent': '{{ __("center::attendance.absent") }}'
                    };
                    statusCell.innerHTML = `
                        <span class="badge bg-${status === 'present' ? 'success' : (status === 'late' ? 'warning' : 'danger')} bg-opacity-10 text-${status === 'present' ? 'success' : (status === 'late' ? 'warning' : 'danger')} rounded-pill px-3">
                            ${statusLabels[status] || status}
                        </span>
                        <br><span class="offline-saved-badge mt-1">📦 محفوظ أوفلاين</span>
                    `;
                }
                // Disable buttons for this row
                row.querySelectorAll('button[type="submit"], button[type="button"]').forEach(b => b.disabled = true);
            }
        });
    }

    // ─── Restore Offline-Saved UI on Page Load ───
    function restoreOfflineUI() {
        const records = getOfflineRecords();
        const scheduleId = '{{ $schedule->id }}';
        const today = '{{ today()->format("Y-m-d") }}';

        records.forEach(record => {
            if (record.schedule_id == scheduleId && record.session_date == today) {
                markRowAsOfflineSaved(record.student_id, record.status);
            }
        });
    }

    // ─── Late Modal (unchanged logic, adapted for offline) ───
    function openLateModal(studentId, courseId, scheduleId, studentName) {
        document.getElementById('lateModalStudentId').value = studentId;
        document.getElementById('lateModalCourseId').value = courseId;
        document.getElementById('lateModalScheduleId').value = scheduleId;
        document.getElementById('lateModalStudentName').innerText = studentName;

        // Smart Calculation
        const now = new Date();
        const sessionTime = new Date();
        const [hours, minutes] = sessionStartTimeRaw.split(':');
        sessionTime.setHours(parseInt(hours), parseInt(minutes), 0, 0);

        let diffMinutes = Math.floor((now - sessionTime) / 60000);
        if (diffMinutes <= 0 || diffMinutes > 300) {
            diffMinutes = 15;
        }

        document.getElementById('lateModalMinutes').value = diffMinutes;

        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'lateModal' }));
    }

    // ─── QR Scanner ───
    let html5QrScanner = null;
    let scannerRunning = false;
    const scanConfig = { fps: 10, qrbox: { width: 250, height: 250 } };

    function onScanSuccess(decodedText) {
        console.log('[QR] Scanned:', decodedText);
        stopScanner();

        const urlMatch = decodedText.match(/magic-login\/(\d+)/);
        if (urlMatch && urlMatch[1]) {
            markAttendance(urlMatch[1], null);
            return;
        }

        if (/^\d+$/.test(decodedText.trim())) {
            markAttendance(decodedText.trim(), null);
            return;
        }

        if (decodedText && decodedText.trim().length > 0) {
            markAttendance(null, decodedText.trim());
            return;
        }

        showResult("{{ __('center::attendance.qr_invalid') }}", 'danger');
        setTimeout(startScanner, 3000);
    }

    function startScanner() {
        const readerEl = document.getElementById('reader');
        if (!readerEl) return;
        readerEl.innerHTML = '';
        document.getElementById('scan-result').classList.add('hidden');

        html5QrScanner = new Html5Qrcode("reader");

        html5QrScanner.start(
            { facingMode: "environment" },
            scanConfig,
            onScanSuccess,
            () => {}
        ).then(() => {
            scannerRunning = true;
        }).catch(err => {
            scannerRunning = false;
            readerEl.innerHTML = '<div class="alert alert-danger m-3">' +
                '<i class="bi bi-camera-video-off me-2"></i>' +
                '{{ __("center::attendance.camera_access_error") }}<br>' +
                '<small class="text-muted">{{ __("center::attendance.ensure_that") }}<br>• استخدام HTTPS<br>• السماح بالوصول للكاميرا من إعدادات المتصفح</small></div>';
        });
    }

    function stopScanner() {
        if (html5QrScanner && scannerRunning) {
            html5QrScanner.stop().then(() => {
                html5QrScanner.clear();
                scannerRunning = false;
            }).catch(() => { scannerRunning = false; });
        }
    }

    function markAttendance(studentId, studentCode) {
        showResult('<div class="spinner-border spinner-border-sm me-2"></div> ' + "{{ __('center::attendance.marking_attendance') }}", 'primary');

        const payload = {
            course_id: '{{ $schedule->course_id }}',
            schedule_id: '{{ $schedule->id }}',
            session_date: '{{ today()->format("Y-m-d") }}',
            status: 'present',
            offline_timestamp: new Date().toISOString()
        };
        if (studentId) payload.student_id = studentId;
        if (studentCode) payload.student_code = studentCode;

        if (!navigator.onLine) {
            // Offline: Save QR scan locally
            saveOfflineRecord(payload);
            if (studentId) markRowAsOfflineSaved(studentId, 'present');
            showResult('📦 تم الحفظ محلياً - ستتم المزامنة عند عودة الإنترنت', 'warning');
            setTimeout(startScanner, 3000);
            return;
        }

        fetch(STORE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(response => {
            const contentType = response.headers.get("content-type") || '';
            if (contentType.includes("application/json")) {
                return response.json().then(data => ({ ok: response.ok, body: data }));
            }
            return { ok: response.ok, body: { message: response.ok ? 'تم التسجيل' : 'حدث خطأ' } };
        })
        .then(({ ok, body }) => {
            if (ok) {
                showResult('✅ ' + (body.message || 'تم تسجيل الحضور بنجاح!'), 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showResult('❌ ' + (body.message || 'فشل التسجيل'), 'danger');
                setTimeout(startScanner, 3000);
            }
        })
        .catch(error => {
            console.warn('[QR] Network error, saving offline:', error);
            saveOfflineRecord(payload);
            if (studentId) markRowAsOfflineSaved(studentId, 'present');
            showResult('📦 فشل الاتصال - تم الحفظ محلياً', 'warning');
            setTimeout(startScanner, 3000);
        });
    }

    function showResult(message, type) {
        const resultDiv = document.getElementById('scan-result');
        const colorMap = { success: 'text-green-600', danger: 'text-red-600', warning: 'text-amber-600', primary: 'text-brand-primary' };
        resultDiv.className = 'absolute bottom-0 start-0 w-full p-3 bg-white/95 font-bold text-center rounded-b-xl ' + (colorMap[type] || 'text-slate-900');
        resultDiv.classList.remove('hidden');
        resultDiv.innerHTML = message;
    }

    // ─── Initialize Everything ───
    document.addEventListener('DOMContentLoaded', function() {
        if (OFFLINE_ENABLED) {
            // Intercept forms for offline support
            interceptAttendanceForms();

            // Restore offline UI for previously saved records
            restoreOfflineUI();

            // Show connection status
            updateOfflineBar();

            // Try to sync on page load
            syncOfflineAttendance();
        }

        // QR Scanner modal lifecycle (Alpine open-modal/close-modal events)
        window.addEventListener('open-modal', function(e) {
            if (e.detail === 'scanQrModal') {
                // Wait for Alpine to render the modal before starting the camera
                setTimeout(startScanner, 150);
            }
        });
        window.addEventListener('close-modal', function(e) {
            if (e.detail === 'scanQrModal') stopScanner();
        });
    });

    // ─── Network Event Listeners ───
    window.addEventListener('online', function() {
        if (OFFLINE_ENABLED) {
            console.log('[Network] 🟢 Back online!');
            updateOfflineBar();
            syncOfflineAttendance();
        }
    });

    window.addEventListener('offline', function() {
        if (OFFLINE_ENABLED) {
            console.log('[Network] 🔴 Gone offline');
            updateOfflineBar();
        }
    });

    // ─── Periodic Sync (every 30 seconds if online) ───
    setInterval(() => {
        if (OFFLINE_ENABLED && navigator.onLine && getOfflineRecords().length > 0) {
            syncOfflineAttendance();
        }
    }, 30000);
</script>
