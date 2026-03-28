@extends('center::layouts.hope-master')

@section('content')
<div class="container text-center mt-5">
    <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width: 500px;">
        <div class="card-header bg-primary text-white p-4">
            <h3 class="fw-bold mb-1"><i class="bi bi-qr-code-scan me-2"></i>{{ __('center::attendance.scan_attendance_code') }}</h3>
            <p class="mb-0">{{ $schedule->course->title }}</p>
            <small class="opacity-75">{{ __('center::attendance.session_at', ['time' => \Carbon\Carbon::parse($schedule->start_time)->format('h:i A')]) }}</small>
        </div>
        <div class="card-body p-5">
            <div id="qrcode" class="d-flex justify-content-center my-4"></div>
            
            <div class="alert alert-light border-0 small text-muted">
                <i class="bi bi-info-circle me-1"></i>{{ __('center::attendance.qr_refresh_msg') }}</div>
            
            <div class="mt-3">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 rounded-pill">{{ __('center::attendance.today_date', ['date' => now()->format('Y-m-d')]) }}</span>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    // Initial QR Generation
    new QRCode(document.getElementById("qrcode"), {
        text: "{!! $url !!}",
        width: 256,
        height: 256
    });

    // Auto-refresh page to get new signed URL
    setTimeout(function() {
        window.location.reload();
    }, 60000);
</script>
@endsection
