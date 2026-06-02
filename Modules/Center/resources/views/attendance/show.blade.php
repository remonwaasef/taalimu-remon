@extends('center::layouts.hope-master')

@section('content')
    <div class="mb-4">
        <h2 class="fw-bold text-dark">{{ __('center::attendance.sheet_title', ['title' => $schedule->course->title]) }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('center.attendance.index') }}">{{ __('center::attendance.history') }}</a></li>
                <li class="breadcrumb-item active">{{ __('center::attendance.details') }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1"><i class="bi bi-people me-2"></i>{{ __('center::attendance.enrolled_list') }}</h5>
                        <p class="text-muted small mb-0">{{ __('center::attendance.session_at', ['time' => \Carbon\Carbon::parse($schedule->start_time)->format('h:i A')]) }} - {{ __('center::attendance.classroom_label') }} {{ $schedule->classroom->name ?? __('center::schedules.classroom') }}</p>
                    </div>
                    <div class="text-end d-flex align-items-center gap-2">
                        <!-- Scan Button -->
                        <button type="button" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#scanQrModal">
                            <i class="bi bi-qr-code-scan me-1"></i>{{ __('center::attendance.scan_qr_btn') }}</button>

                        @php
                            $isEnded = now()->isAfter(\Carbon\Carbon::parse($schedule->end_time));
                            $hasUnrecorded = $schedule->course->enrollments->count() > $attendances->count();
                        @endphp
                        @if($isEnded && $hasUnrecorded)
                            <form action="{{ route('center.attendance.bulkAbsent', $schedule) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm">
                                    <i class="bi bi-person-x-fill me-1"></i>{{ __('center::attendance.mark_all_absent') }}</button>
                            </form>
                        @endif
                        <span class="badge bg-primary px-3 rounded-pill">{{ today()->format('Y-m-d') }}</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-start">{{ __('center::attendance.student_name') }}</th>
                                    <th class="border-0">{{ __('center::attendance.student_code') }}</th>
                                    <th class="border-0 text-center">{{ __('center::attendance.status') }}</th>
                                    <th class="border-0 rounded-end text-center">{{ __('center::attendance.record_attendance') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedule->course->enrollments as $enrollment)
                                    @php
                                        $student = $enrollment->user->student ?? null;
                                        $attendance = $student ? $attendances->get($student->id) : null;
                                    @endphp
                                    @if($student)
                                    @php
                                        $isAdmin = auth()->user()->hasRole('center_admin');
                                        $lockPresent = ($attendance && !$isAdmin) || ($isEnded && !$isAdmin && (!$attendance || $attendance->status !== 'present'));
                                        $lockLate = ($attendance && !$isAdmin) || ($isEnded && !$isAdmin && (!$attendance || $attendance->status !== 'late'));
                                        $lockAbsent = ($attendance && !$isAdmin) || ($isEnded && !$isAdmin && (!$attendance || $attendance->status !== 'absent'));
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $student->name }}</div>
                                            <small class="text-muted">{{ $enrollment->user->email }}</small>
                                        </td>
                                        <td><code class="text-primary fw-bold">#{{ $student->id }}</code></td>
                                        <td class="text-center">
                                            @if($attendance)
                                                <span class="badge bg-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'late' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'late' ? 'warning' : 'danger') }} rounded-pill px-3">
                                                    @if($attendance->status == 'late')
                                                    {{ __('center::attendance.late') }} ({{ $attendance->late_minutes }} {{ __('center::attendance.minutes') }})
                                                    @else
                                                        {{ $attendance->status == 'present' ? __('center::attendance.present') : __('center::attendance.absent') }}
                                                    @endif
                                                    <small class="d-block text-muted" style="font-size: 0.6rem;">{{ $attendance->check_in_time->format('h:i A') }}</small>
                                                </span>
                                            @elseif($isEnded)
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">{{ __('center::attendance.absent') }}</span>
                                            @else
                                                <span class="text-muted small">{{ __('center::attendance.not_recorded') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <form action="{{ route('center.attendance.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                    <input type="hidden" name="course_id" value="{{ $schedule->course_id }}">
                                                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                                    <input type="hidden" name="session_date" value="{{ today()->format('Y-m-d') }}">
                                                    <input type="hidden" name="status" value="present">
                                                    <button type="submit" class="btn btn-sm btn-{{ $attendance && $attendance->status == 'present' ? 'success' : 'outline-success' }} rounded-pill px-3" {{ $lockPresent ? 'disabled' : '' }}>{{ __('center::attendance.present') }}</button>
                                                </form>
                                                
                                                <!-- Smart Late Button (Triggers Modal) -->
                                                <button type="button" class="btn btn-sm btn-{{ $attendance && $attendance->status == 'late' ? 'warning' : 'outline-warning' }} rounded-pill px-3" onclick="openLateModal({{ $student->id }}, '{{ $schedule->course_id }}', '{{ $schedule->id }}', '{{ e($student->name) }}')" {{ $lockLate ? 'disabled' : '' }}>{{ __('center::attendance.late') }}</button>

                                                <form action="{{ route('center.attendance.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                    <input type="hidden" name="course_id" value="{{ $schedule->course_id }}">
                                                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                                    <input type="hidden" name="session_date" value="{{ today()->format('Y-m-d') }}">
                                                    <input type="hidden" name="status" value="absent">
                                                    <button type="submit" class="btn btn-sm btn-{{ $attendance && $attendance->status == 'absent' ? 'danger' : 'outline-danger' }} rounded-pill px-3" {{ $lockAbsent ? 'disabled' : '' }}>{{ __('center::attendance.absent') }}</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">{{ __('center::attendance.no_students_enrolled') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Smart Late Modal -->
<div class="modal fade" id="lateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h6 class="modal-title fw-bold" id="lateModalTitle">تسجيل تأخير</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('center.attendance.store') }}" method="POST">
                @csrf
                <div class="modal-body text-center pt-2">
                    <p class="text-muted small mb-3">طالب: <strong id="lateModalStudentName" class="text-dark"></strong></p>
                    
                    <input type="hidden" name="student_id" id="lateModalStudentId">
                    <input type="hidden" name="course_id" id="lateModalCourseId">
                    <input type="hidden" name="schedule_id" id="lateModalScheduleId">
                    <input type="hidden" name="session_date" value="{{ today()->format('Y-m-d') }}">
                    <input type="hidden" name="status" value="late">
                    
                    <label class="form-label fw-bold">كم دقيقة تأخير؟</label>
                    <div class="input-group input-group-lg mb-2">
                        <input type="number" name="late_minutes" id="lateModalMinutes" class="form-control text-center fw-bold" required min="1" value="">
                        <span class="input-group-text bg-light">دقيقة</span>
                    </div>
                    <small class="text-success d-block mb-3" style="font-size: 0.75rem;"><i class="bi bi-robot"></i> تم الحساب آلياً بناءً على وقت الحصة</small>
                </div>
                <div class="modal-footer border-top-0 pt-0 justify-content-center">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal" onclick="bootstrap.Modal.getInstance(document.getElementById('lateModal'))?.hide()">إلغاء</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">حفظ التأخير</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scan QR Modal -->
<div class="modal fade" id="scanQrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content overflow-hidden rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-qr-code-scan me-2"></i>{{ __('center::attendance.scan_qr_modal_title') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center bg-dark position-relative">
                <div id="reader" style="width: 100%; min-height: 300px;"></div>
                <div id="scan-result" class="position-absolute bottom-0 start-0 w-100 p-3 bg-white bg-opacity-90 text-dark fw-bold d-none">{{ __('center::attendance.verifying') }}</div>
            </div>
            <div class="modal-footer border-0 bg-light justify-content-center">
                <small class="text-muted">{{ __('center::attendance.facing_camera_hint') }}</small>
            </div>
        </div>
    </div>
</div>

@feature('offline_attendance')
<div id="offlineStatusBar" class="position-fixed bottom-0 start-0 w-100 d-none" style="z-index: 9999;">
    <div class="d-flex align-items-center justify-content-between px-4 py-2" id="offlineBarInner">
        <div class="d-flex align-items-center">
            <span id="offlineIcon" class="me-2"></span>
            <span id="offlineText" class="fw-bold small"></span>
        </div>
        <div id="offlinePending" class="small"></div>
    </div>
</div>
@endfeature

@feature('offline_attendance')
<style>
    #offlineStatusBar .bar-offline {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }
    #offlineStatusBar .bar-syncing {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        color: #333;
    }
    #offlineStatusBar .bar-online {
        background: linear-gradient(135deg, #28a745, #218838);
        color: white;
    }
    .offline-saved-badge {
        display: inline-block;
        font-size: 0.65rem;
        padding: 2px 8px;
        border-radius: 20px;
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffc107;
        animation: pulse-badge 1.5s infinite;
    }
    @keyframes pulse-badge {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }
</style>
@endfeature

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
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

        const modalEl = document.getElementById('lateModal');
        if (modalEl.parentNode !== document.body) {
            document.body.appendChild(modalEl);
        }

        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
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
        document.getElementById('scan-result').classList.add('d-none');

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
        resultDiv.classList.remove('d-none');
        resultDiv.innerHTML = message;
        const colorMap = { success: 'text-success', danger: 'text-danger', warning: 'text-warning', primary: 'text-primary' };
        resultDiv.className = 'position-absolute bottom-0 start-0 w-100 p-3 bg-white bg-opacity-95 fw-bold ' + (colorMap[type] || 'text-dark');
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

        // QR Scanner modal lifecycle
        const scanModal = document.getElementById('scanQrModal');
        if (scanModal) {
            scanModal.addEventListener('shown.bs.modal', () => startScanner());
            scanModal.addEventListener('hidden.bs.modal', () => stopScanner());
        }
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

<style>
    @keyframes slideDown { from { transform: translateX(-50%) translateY(-100%); opacity: 0; } to { transform: translateX(-50%) translateY(0); opacity: 1; } }
    @keyframes slideUp { from { transform: translateX(-50%) translateY(0); opacity: 1; } to { transform: translateX(-50%) translateY(-100%); opacity: 0; } }
    .spin-icon { animation: spin 1s linear infinite; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>
@endpush
@endsection
