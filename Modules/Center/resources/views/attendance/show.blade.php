@extends('center::layouts.hope-master')

@section('content')
    <div class="mb-4">
        <h2 class="fw-bold text-dark">{{ __('center::messages.blade_1074') }} {{ $schedule->course->title }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('center.attendance.index') }}">{{ __('center::messages.blade_0130') }}</a></li>
                <li class="breadcrumb-item active">{{ __('center::messages.blade_0131') }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1"><i class="bi bi-people me-2"></i>{{ __('center::messages.blade_0132') }}</h5>
                        <p class="text-muted small mb-0">{{ __('center::attendance.session_at', ['time' => \Carbon\Carbon::parse($schedule->start_time)->format('h:i A')]) }} - {{ __('center::messages.blade_1075') }} {{ $schedule->classroom->name ?? __('center::schedules.classroom') }}</p>
                    </div>
                    <div class="text-end d-flex align-items-center gap-2">
                        <!-- Scan Button -->
                        <button type="button" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#scanQrModal">
                            <i class="bi bi-qr-code-scan me-1"></i>{{ __('center::messages.blade_0133') }}</button>

                        @php
                            $isEnded = now()->isAfter(\Carbon\Carbon::parse($schedule->end_time));
                            $hasUnrecorded = $schedule->course->enrollments->count() > $attendances->count();
                        @endphp
                        @if($isEnded && $hasUnrecorded)
                            <form action="{{ route('center.attendance.bulkAbsent', $schedule) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm">
                                    <i class="bi bi-person-x-fill me-1"></i>{{ __('center::messages.blade_0134') }}</button>
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
                                    <th class="border-0 rounded-start">{{ __('center::messages.blade_0135') }}</th>
                                    <th class="border-0">{{ __('center::messages.blade_0136') }}</th>
                                    <th class="border-0 text-center">{{ __('center::messages.blade_0137') }}</th>
                                    <th class="border-0 rounded-end text-center">{{ __('center::messages.blade_0138') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedule->course->enrollments as $enrollment)
                                    @php
                                        $student = $enrollment->user->student ?? null;
                                        $attendance = $student ? $attendances->get($student->id) : null;
                                    @endphp
                                    @if($student)
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
                                                    {{ $attendance->late_label ?? __('center::messages.blade_0148') }} ({{ $attendance->late_minutes }} {{ __('center::messages.blade_1076') }})
                                                    @else
                                                        {{ $attendance->status == 'present' ? __('center::messages.blade_0149') : __('center::messages.blade_0150') }}
                                                    @endif
                                                    <small class="d-block text-muted" style="font-size: 0.6rem;">{{ $attendance->check_in_time->format('h:i A') }}</small>
                                                </span>
                                            @elseif($isEnded)
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">{{ __('center::messages.blade_0139') }}</span>
                                            @else
                                                <span class="text-muted small">{{ __('center::messages.blade_0140') }}</span>
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
                                                    <button type="submit" class="btn btn-sm btn-{{ $attendance && $attendance->status == 'present' ? 'success' : 'outline-success' }} rounded-pill px-3" {{ $isEnded && (!$attendance || $attendance->status !== 'present') ? 'disabled' : '' }}>{{ __('center::messages.blade_0141') }}</button>
                                                </form>
                                                
                                                <form action="{{ route('center.attendance.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                    <input type="hidden" name="course_id" value="{{ $schedule->course_id }}">
                                                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                                    <input type="hidden" name="session_date" value="{{ today()->format('Y-m-d') }}">
                                                    <input type="hidden" name="status" value="late">
                                                    <button type="submit" class="btn btn-sm btn-{{ $attendance && $attendance->status == 'late' ? 'warning' : 'outline-warning' }} rounded-pill px-3" {{ $isEnded && (!$attendance || $attendance->status !== 'late') ? 'disabled' : '' }}>{{ __('center::messages.blade_0142') }}</button>
                                                </form>

                                                <form action="{{ route('center.attendance.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                    <input type="hidden" name="course_id" value="{{ $schedule->course_id }}">
                                                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                                    <input type="hidden" name="session_date" value="{{ today()->format('Y-m-d') }}">
                                                    <input type="hidden" name="status" value="absent">
                                                    <button type="submit" class="btn btn-sm btn-{{ $attendance && $attendance->status == 'absent' ? 'danger' : 'outline-danger' }} rounded-pill px-3">{{ __('center::messages.blade_0143') }}</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">{{ __('center::messages.blade_0144') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Scan QR Modal -->
<div class="modal fade" id="scanQrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content overflow-hidden rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-qr-code-scan me-2"></i>{{ __('center::messages.blade_0145') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center bg-dark position-relative">
                <div id="reader" style="width: 100%; min-height: 300px;"></div>
                <div id="scan-result" class="position-absolute bottom-0 start-0 w-100 p-3 bg-white bg-opacity-90 text-dark fw-bold d-none">{{ __('center::messages.blade_0146') }}</div>
            </div>
            <div class="modal-footer border-0 bg-light justify-content-center">
                <small class="text-muted">{{ __('center::attendance.facing_camera_hint') }}</small>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    let html5QrScanner = null;
    let scannerRunning = false;
    const scanConfig = { fps: 10, qrbox: { width: 250, height: 250 } };

    function onScanSuccess(decodedText) {
        console.log('[QR] Scanned:', decodedText);
        
        // Stop scanner immediately
        stopScanner();
        
        // 1. Try to extract student ID from Magic Login URL
        const urlMatch = decodedText.match(/magic-login\/(\d+)/);
        if (urlMatch && urlMatch[1]) {
            console.log('[QR] Magic-login ID found:', urlMatch[1]);
            markAttendance(urlMatch[1], null);
            return;
        }

        // 2. Pure number → treat as student ID
        if (/^\d+$/.test(decodedText.trim())) {
            console.log('[QR] Numeric ID:', decodedText.trim());
            markAttendance(decodedText.trim(), null);
            return;
        }

        // 3. Any other text → treat as student code (e.g. "S-9-1001")
        if (decodedText && decodedText.trim().length > 0) {
            console.log('[QR] Student code:', decodedText.trim());
            markAttendance(null, decodedText.trim());
            return;
        }

        showResult("{{ __('center::attendance.qr_invalid') }}", 'danger');
        setTimeout(startScanner, 3000);
    }

    function startScanner() {
        const readerEl = document.getElementById('reader');
        if (!readerEl) return;
        
        // Clear previous content
        readerEl.innerHTML = '';
        document.getElementById('scan-result').classList.add('d-none');
        
        html5QrScanner = new Html5Qrcode("reader");
        
        html5QrScanner.start(
            { facingMode: "environment" },
            scanConfig,
            onScanSuccess,
            () => {} // ignore scan failures (normal while pointing camera)
        ).then(() => {
            scannerRunning = true;
            console.log('[QR] Scanner started successfully');
        }).catch(err => {
            scannerRunning = false;
            console.error('[QR] Camera error:', err);
            readerEl.innerHTML = '<div class="alert alert-danger m-3">' +
                '<i class="bi bi-camera-video-off me-2"></i>' +
                '{{ __('center::messages.blade_1077') }}<br>' +
                '<small class="text-muted">{{ __('center::messages.blade_0147') }}<br>• استخدام HTTPS<br>• السماح بالوصول للكاميرا من إعدادات المتصفح</small>' +
                '</div>';
        });
    }

    function stopScanner() {
        if (html5QrScanner && scannerRunning) {
            html5QrScanner.stop().then(() => {
                html5QrScanner.clear();
                scannerRunning = false;
                console.log('[QR] Scanner stopped');
            }).catch(err => {
                console.error('[QR] Stop error:', err);
                scannerRunning = false;
            });
        }
    }

    function markAttendance(studentId, studentCode) {
        showResult('<div class="spinner-border spinner-border-sm me-2"></div> ' + "{{ __('center::attendance.marking_attendance') }}", 'primary');
        
        const payload = {
            course_id: '{{ $schedule->course_id }}',
            schedule_id: '{{ $schedule->id }}',
            session_date: '{{ today()->format("Y-m-d") }}',
            status: 'present'
        };
        if (studentId) payload.student_id = studentId;
        if (studentCode) payload.student_code = studentCode;

        console.log('[QR] Sending attendance:', payload);

        fetch('{{ route("center.attendance.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(response => {
            const contentType = response.headers.get("content-type") || '';
            if (contentType.includes("application/json")) {
                return response.json().then(data => ({ ok: response.ok, status: response.status, body: data }));
            }
            // Non-JSON response (redirect/HTML) — treat 2xx as success
            return { ok: response.ok, status: response.status, body: { message: response.ok ? __('center::messages.blade_0151') : __('center::messages.blade_0152') } };
        })
        .then(({ ok, status, body }) => {
            if (ok) {
                showResult('✅ ' + (body.message || __('center::messages.blade_0153')), 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showResult('❌ ' + (body.message || __('center::messages.blade_0154')), 'danger');
                setTimeout(startScanner, 3000);
            }
        })
        .catch(error => {
            console.error('[QR] Network error:', error);
            showResult("❌ {{ __('center::attendance.server_connection_error') }}", 'danger');
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

    // ─── Modal Lifecycle ───
    document.addEventListener('DOMContentLoaded', function() {
        const scanModal = document.getElementById('scanQrModal');
        if (scanModal) {
            scanModal.addEventListener('shown.bs.modal', function () {
                console.log('[QR] Modal opened, starting scanner...');
                startScanner();
            });

            scanModal.addEventListener('hidden.bs.modal', function () {
                console.log('[QR] Modal closed, stopping scanner...');
                stopScanner();
            });
        }
    });
</script>
@endpush
@endsection
