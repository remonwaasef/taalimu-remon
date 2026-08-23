@extends('campus::layouts.master')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h5 class="fw-bold mb-1" style="color: var(--primary-color);">{{ $class->title }}</h5>
            <div class="text-muted small">
                <i class="fas fa-book me-1"></i> {{ $class->course->title }} |
                <i class="fas fa-user-tie me-1 ms-3"></i> {{ $class->instructor->name ?? '' }}
            </div>
        </div>
        <span class="badge bg-danger rounded-pill px-3 animate__animated animate__pulse">
            <i class="fas fa-circle me-1"></i> {{ __('campus::classes.live') }}
        </span>
    </div>

    {{-- Watermark Layer --}}
    <div id="watermark-layer" class="pointer-events-none" style="position: fixed; inset: 0; z-index: 1050; overflow: hidden;"></div>

    {{-- Video Canvas --}}
    <div class="card border-0 shadow-sm rounded-4" style="background:#000; aspect-ratio: 16/9; max-width: 100%;">
        <div class="card-body p-0 d-flex align-items-center justify-content-center" style="min-height: 400px;">
            @if($joinContext)
                <div id="zmmtg-root" style="width:100%; height:100%;"></div>
            @else
                <div class="d-flex align-items-center justify-content-center h-100 text-white">
                    <div class="text-center">
                        <i class="fas fa-video-slash fa-3x text-muted mb-3"></i>
                        <h5>{{ __('campus::classes.cannot_load') }}</h5>
                        <p class="text-muted">{{ __('campus::classes.contact_instructor') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Info Bar --}}
    <div class="card border-0 shadow-sm rounded-4 mt-3">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="fw-bold" style="color: var(--primary-color);">{{ $class->title }}</div>
                    <span class="badge bg-info text-white">{{ $class->duration_minutes }} {{ __('campus::classes.minutes') }}</span>
                </div>
                <a href="{{ route('campus.classes.index') }}" class="btn btn-light rounded-pill px-4">
                    <i class="fas fa-arrow-right me-1"></i> {{ __('campus::classes.leave') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://source.zoom.us/2.18.0/zoom-meeting-2.18.0.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const joinContext = @json($joinContext ?? null);
    let zmClient = null;

    async function initZoom() {
        if (!joinContext) {
            console.warn('No Zoom join context');
            return;
        }

        if (typeof ZoomMtg === 'undefined') {
            console.error('Zoom SDK not loaded');
            return;
        }

        ZoomMtg.setZoomJSLib('https://source.zoom.us/2.18.0/lib', '/av');
        ZoomMtg.preLoadWasm();
        ZoomMtg.prepareJssdk();

        ZoomMtg.init({
            leaveUrl: '{{ route("campus.classes.index") }}',
            isSupportAV: true,
            success: () => joinMeeting(),
            error: (res) => showError('فشل تهيئة Zoom SDK: ' + JSON.stringify(res)),
        });
    }

    function joinMeeting() {
        ZoomMtg.join({
            signature: joinContext.signature,
            meetingNumber: joinContext.meeting_number,
            sdkKey: joinContext.sdk_key,
            userName: '{{ auth()->user()->name }} (طالب) ',
            userEmail: '{{ auth()->user()->email }}',
            passWord: '',
            role: joinContext.role, // 0 = attendee
            success: (res) => console.log('Join success', res),
            error: (res) => showError('فشل الانضمام: ' + JSON.stringify(res)),
        });
    }

    function showError(msg) {
        const root = document.getElementById('zmmtg-root');
        if (root) {
            root.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-white"><div class="text-center"><i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i><h5>تعذر الدخول للحصة</h5><p class="text-muted">' + msg + '</p></div></div>';
        }
    }

    // Watermark (same as recordings)
    const studentName = '{{ auth()->user()->name }}';
    const studentCode = '{{ auth()->user()->student?->code ?? auth()->id() }}';
    const maskedCode = studentCode.length > 4 ? '****' + studentCode.slice(-4) : studentCode;
    const watermarkLayer = document.getElementById('watermark-layer');
    const positions = [
        { top: '5%', right: '5%' },
        { bottom: '5%', left: '5%' },
        { top: '15%', left: '50%', transform: 'translateX(-50%)' },
        { bottom: '15%', right: '50%', transform: 'translateX(50%)' },
        { top: '50%', left: '5%', transform: 'translateY(-50%)' },
        { top: '50%', right: '5%', transform: 'translateY(-50%)' },
    ];
    let wmIdx = 0;

    function createWm() {
        const now = new Date();
        const timeStr = now.toLocaleString('ar-EG', { hour: '2-digit', minute: '2-digit', second: '2-digit', day: '2-digit', month: '2-digit', year: 'numeric' });
        const el = document.createElement('div');
        el.className = 'watermark-item';
        el.style.cssText = `position:absolute;pointer-events:none;user-select:none;font-family:'Cairo',sans-serif;font-size:0.85rem;font-weight:600;color:rgba(255,255,255,0.35);text-shadow:0 0 8px rgba(0,0,0,0.8);white-space:nowrap;line-height:1.4;`;
        el.innerHTML = `<div>${studentName}</div><div>ID: ${maskedCode}</div><div>Taalimu</div><div>${timeStr}</div>`;
        return el;
    }

    function placeWm() {
        watermarkLayer.innerHTML = '';
        const el = createWm();
        const pos = positions[wmIdx % positions.length];
        Object.assign(el.style, pos);
        if (pos.transform) el.style.transform = pos.transform;
        watermarkLayer.appendChild(el);
        wmIdx = (wmIdx + 1) % positions.length;
    }

    if (joinContext) {
        initZoom();
        placeWm();
        setInterval(placeWm, 8000);
    }

    // Prevent right-click
    document.addEventListener('contextmenu', e => e.preventDefault());
});
</script>

<style>
.watermark-item {
    filter: drop-shadow(0 0 4px rgba(0,0,0,0.7));
    animation: watermark-fade 0.5s ease-in-out;
}
@keyframes watermark-fade {
    from { opacity: 0; transform: translateY(-10px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
</style>
@endpush