@extends('center::layouts.master')

@section('content')
    <div class="mb-4">
        <h2 class="fw-bold text-dark">تسجيل الحضور: {{ $schedule->course->title }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('center.attendance.index') }}">متابعة الحضور</a></li>
                <li class="breadcrumb-item active">تحضير الطلاب</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1"><i class="bi bi-people me-2"></i>قائمة الطلاب المسجلين</h5>
                        <p class="text-muted small mb-0">الحصة: {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - القاعة: {{ $schedule->classroom->name ?? __('center::schedules.classroom') }}</p>
                    </div>
                    <div class="text-end d-flex align-items-center gap-2">
                        <!-- Scan Button -->
                        <button type="button" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#scanQrModal">
                            <i class="bi bi-qr-code-scan me-1"></i> مسح بطاقة الطالب
                        </button>

                        @php
                            $isEnded = now()->isAfter(\Carbon\Carbon::parse($schedule->end_time));
                            $hasUnrecorded = $schedule->course->enrollments->count() > $attendances->count();
                        @endphp
                        @if($isEnded && $hasUnrecorded)
                            <form action="{{ route('center.attendance.bulkAbsent', $schedule) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm">
                                    <i class="bi bi-person-x-fill me-1"></i> تسجيل الجميع غياب
                                </button>
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
                                    <th class="border-0 rounded-start">الطالب</th>
                                    <th class="border-0">كود الطالب</th>
                                    <th class="border-0 text-center">حالة الحضور اليوم</th>
                                    <th class="border-0 rounded-end text-center">تسجيل الحضور</th>
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
                                                    {{ $attendance->status == 'present' ? 'حاضر' : ($attendance->status == 'late' ? 'متأخر' : 'غائب') }}
                                                    <small class="d-block text-muted" style="font-size: 0.6rem;">{{ $attendance->check_in_time->format('h:i A') }}</small>
                                                </span>
                                            @elseif($isEnded)
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">
                                                    غائب (تلقائي)
                                                </span>
                                            @else
                                                <span class="text-muted small">لم يتم التحضير بعد</span>
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
                                                    <button type="submit" class="btn btn-sm btn-{{ $attendance && $attendance->status == 'present' ? 'success' : 'outline-success' }} rounded-pill px-3" {{ $isEnded && (!$attendance || $attendance->status !== 'present') ? 'disabled' : '' }}>حاضر</button>
                                                </form>
                                                
                                                <form action="{{ route('center.attendance.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                    <input type="hidden" name="course_id" value="{{ $schedule->course_id }}">
                                                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                                    <input type="hidden" name="session_date" value="{{ today()->format('Y-m-d') }}">
                                                    <input type="hidden" name="status" value="late">
                                                    <button type="submit" class="btn btn-sm btn-{{ $attendance && $attendance->status == 'late' ? 'warning' : 'outline-warning' }} rounded-pill px-3" {{ $isEnded && (!$attendance || $attendance->status !== 'late') ? 'disabled' : '' }}>متأخر</button>
                                                </form>

                                                <form action="{{ route('center.attendance.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                    <input type="hidden" name="course_id" value="{{ $schedule->course_id }}">
                                                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                                    <input type="hidden" name="session_date" value="{{ today()->format('Y-m-d') }}">
                                                    <input type="hidden" name="status" value="absent">
                                                    <button type="submit" class="btn btn-sm btn-{{ $attendance && $attendance->status == 'absent' ? 'danger' : 'outline-danger' }} rounded-pill px-3">غائب</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">لا يوجد طلاب مسجلين في هذه الدورة</td>
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
                <h5 class="modal-title fw-bold"><i class="bi bi-qr-code-scan me-2"></i>مسح بطاقة الطالب</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center bg-dark position-relative">
                <div id="reader" style="width: 100%; min-height: 300px;"></div>
                <div id="scan-result" class="position-absolute bottom-0 start-0 w-100 p-3 bg-white bg-opacity-90 text-dark fw-bold d-none">
                    جاري المعالجة...
                </div>
            </div>
            <div class="modal-footer border-0 bg-light justify-content-center">
                <small class="text-muted">وجه الكاميرا نحو رمز QR في بطاقة الطالب</small>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    let html5QrcodeScanner;

    function onScanSuccess(decodedText, decodedResult) {
        // Stop scanning temporarily
        if (html5QrcodeScanner) {
            html5QrcodeScanner.pause();
        }

        const resultDiv = document.getElementById('scan-result');
        resultDiv.classList.remove('d-none');
        resultDiv.innerHTML = '<div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div> جاري تسجيل الحضور...';
        resultDiv.className = 'position-absolute bottom-0 start-0 w-100 p-3 bg-white bg-opacity-90 text-primary fw-bold';

        // Extract Student ID from URL
        // Expected format: .../magic-login/{id}?signature=...
        const match = decodedText.match(/magic-login\/(\d+)/);
        
        if (match && match[1]) {
            const studentId = match[1];
            markAttendance(studentId);
        } else {
            showResult('رمز QR غير صالح. تأكد من استخدام بطاقة الطالب مع الرابط الجديد.', 'danger');
            setTimeout(() => {
                 if(html5QrcodeScanner.getState() === Html5QrcodeScannerState.PAUSED) {
                    html5QrcodeScanner.resume();
                 }
            }, 2000);
        }
    }

    function markAttendance(studentId) {
        fetch('{{ route("center.attendance.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                student_id: studentId,
                course_id: '{{ $schedule->course_id }}',
                schedule_id: '{{ $schedule->id }}', 
                session_date: '{{ today()->format("Y-m-d") }}',
                status: 'present'
            })
        })
        .then(response => {
            if (response.headers.get("content-type") && response.headers.get("content-type").indexOf("application/json") !== -1) {
                 return response.json().then(data => ({ status: response.status, body: data }));
            } else {
                 // Even if it redirects back success, the status is usually 200/302.
                 // If it returns HTML (like a redirect to the same page), we consider it success if status is ok.
                 return { status: response.status, body: {} };
            }
        })
        .then(({ status, body }) => {
            if (status >= 200 && status < 300) {
                 showResult('✅ تم تسجيل الحضور للطالب بنجاح!', 'success');
                 setTimeout(() => location.reload(), 1000);
            } else {
                 showResult('❌ ' + (body.message || 'فشل التسجيل (قد يكون مسجلاً بالفعل أو انتهى الوقت)'), 'danger');
                 setTimeout(() => {
                     if(html5QrcodeScanner.getState() === Html5QrcodeScannerState.PAUSED) {
                        html5QrcodeScanner.resume();
                     }
                 }, 2000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showResult('❌ حدث خطأ في الاتصال بالسيرفر', 'danger');
            setTimeout(() => {
                 if(html5QrcodeScanner.getState() === Html5QrcodeScannerState.PAUSED) {
                    html5QrcodeScanner.resume();
                 }
            }, 2000);
        });
    }

    function showResult(message, type) {
        const resultDiv = document.getElementById('scan-result');
        resultDiv.classList.remove('d-none');
        resultDiv.innerHTML = message;
        
        const textClass = type === 'success' ? 'text-success' : (type === 'warning' ? 'text-warning' : 'text-danger');
        resultDiv.className = `position-absolute bottom-0 start-0 w-100 p-3 bg-white bg-opacity-95 fw-bold ${textClass}`;
    }

    // Initialize Modal Events
    const scanModal = document.getElementById('scanQrModal');
    if (scanModal) {
        scanModal.addEventListener('shown.bs.modal', function () {
            html5QrcodeScanner = new Html5Qrcode("reader");
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };
            
            html5QrcodeScanner.start({ facingMode: "environment" }, config, onScanSuccess)
            .catch(err => {
                console.error("Error starting scanner", err);
                alert("فشل تشغيل الكاميرا: " + err);
                document.getElementById('reader').innerHTML = '<div class="alert alert-danger m-3">فشل في تشغيل الكاميرا. يرجى التأكد من استخدام HTTPS والسماح للكاميرا.</div>';
            });
        });

        scanModal.addEventListener('hidden.bs.modal', function () {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    html5QrcodeScanner.clear();
                }).catch(err => console.error("Failed to stop scanner", err));
            }
        });
    }
</script>
@endpush
@endsection
