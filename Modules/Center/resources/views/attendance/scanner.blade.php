@extends('center::layouts.master')

@section('content')
<div class="row">
    <div class="col-12 col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-primary text-white p-4">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                        <i class="fas fa-qrcode fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold">ماسح الحضور الذكي</h4>
                        <p class="mb-0 opacity-75">قم بتوجيه الكاميرا نحو كود الطالب لتسجيل الحضور</p>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4">
                <!-- Scanner UI -->
                <div id="reader-wrapper" class="position-relative bg-light rounded-4 overflow-hidden mb-4" style="min-height: 300px;">
                    <div id="reader"></div>
                    
                    <!-- Overlay for scan status -->
                    <div id="scan-overlay" class="position-absolute top-0 start-0 w-100 h-100 d-none flex-column align-items-center justify-content-center bg-white bg-opacity-90 z-index-10">
                        <div class="spinner-border text-primary mb-3" role="status"></div>
                        <p class="fw-bold text-primary">جاري معالجة الكود...</p>
                    </div>
                </div>

                <!-- Result Card -->
                <div id="scan-result" class="d-none">
                    <div class="alert alert-success border-0 rounded-4 p-4 shadow-sm animate__animated animate__fadeIn">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success text-white rounded-circle p-2 me-3">
                                <i class="fas fa-check"></i>
                            </div>
                            <h5 class="mb-0 fw-bold" id="result-message">تم تسجيل الحضور بنجاح!</h5>
                        </div>
                        
                        <div class="bg-white bg-opacity-50 rounded-3 p-3">
                            <div class="row g-3">
                                <div class="col-6">
                                    <small class="text-muted d-block mb-1">اسم الطالب</small>
                                    <span class="fw-bold" id="student-name">---</span>
                                </div>
                                <div class="col-6 text-end">
                                    <small class="text-muted d-block mb-1">حالة المديونية</small>
                                    <span class="badge" id="student-debt-badge">---</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button onclick="resumeScanning()" class="btn btn-primary rounded-pill w-100 py-2 fw-bold">
                                <i class="fas fa-sync-alt me-2"></i> مسح كود آخر
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Helper info -->
                <div class="bg-light rounded-4 p-3 border border-dashed text-center">
                    <p class="small text-muted mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        تأكد من وجود إضاءة كافية للحصول على أفضل النتائج.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let html5QrCode;
    const scannerId = "reader";
    const scanOverlay = document.getElementById('scan-overlay');
    const scanResult = document.getElementById('scan-result');
    const resultMessage = document.getElementById('result-message');
    const studentNameEl = document.getElementById('student-name');
    const studentDebtBadge = document.getElementById('student-debt-badge');

    function onScanSuccess(decodedText, decodedResult) {
        // Pause scanning
        html5QrCode.pause();
        
        // Show overlay
        scanOverlay.classList.remove('d-none');
        scanOverlay.classList.add('d-flex');

        // Process with server
        processScan(decodedText);
    }

    function processScan(qrId) {
        fetch("{{ route('center.attendance.processScan', ['tenant' => app('tenant')->domain]) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ qr_identifier: qrId })
        })
        .then(response => response.json())
        .then(data => {
            scanOverlay.classList.add('d-none');
            scanOverlay.classList.remove('d-flex');

            if (data.success) {
                showSuccess(data);
                // Play success sound if you have one
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            scanOverlay.classList.add('d-none');
            scanOverlay.classList.remove('d-flex');
            showError('حدث خطأ أثناء الاتصال بالخادم.');
            console.error(error);
        });
    }

    function showSuccess(data) {
        resultMessage.innerText = data.message;
        studentNameEl.innerText = data.student.name;
        
        const debt = data.student.debt;
        if (debt > 0) {
            studentDebtBadge.className = 'badge bg-danger';
            studentDebtBadge.innerText = 'مستحق: ' + data.student.debt_formatted;
        } else {
            studentDebtBadge.className = 'badge bg-success';
            studentDebtBadge.innerText = 'لا يوجد مديونية';
        }

        scanResult.classList.remove('d-none');
        document.getElementById('reader-wrapper').classList.add('d-none');
    }

    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'عذراً',
            text: message,
            confirmButtonText: 'حاول مرة أخرى',
            confirmButtonColor: '#435ebe'
        }).then(() => {
            resumeScanning();
        });
    }

    function resumeScanning() {
        scanResult.classList.add('d-none');
        document.getElementById('reader-wrapper').classList.remove('d-none');
        html5QrCode.resume();
    }

    function startScanner() {
        html5QrCode = new Html5Qrcode(scannerId);
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'warning',
                title: 'الكاميرا غير متوفرة',
                text: 'يرجى السماح بالوصول للكاميرا أو التأكد من توصيلها.'
            });
        });
    }

    document.addEventListener('DOMContentLoaded', startScanner);
</script>
@endpush
@endsection
