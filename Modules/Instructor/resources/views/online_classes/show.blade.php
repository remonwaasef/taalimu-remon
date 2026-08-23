@extends('layouts.app-next')

@section('title', $onlineClass->title)

@section('sidebar')
    @include('instructor::partials._sidebar-next', ['active' => 'online_classes'])
@endsection

@section('content')
<div class="container-fluid px-4">
    {{-- Header with Class Info & Controls --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--primary-color);">{{ $onlineClass->title }}</h4>
            <div class="d-flex flex-wrap gap-3 text-muted small">
                <span><i class="fas fa-book me-1"></i> {{ $onlineClass->course->title ?? 'N/A' }}</span>
                <span><i class="fas fa-clock me-1"></i> {{ $onlineClass->start_time->format('Y-m-d H:i') }} ({{ $onlineClass->duration_minutes }} {{ __('instructor::online_classes.minutes') }})</span>
                <span class="badge bg-{{ $onlineClass->status === 'in_progress' ? 'danger' : ($onlineClass->status === 'scheduled' ? 'warning text-dark' : 'secondary') }} rounded-pill px-3">
                    {{ __('instructor::online_classes.'.$onlineClass->status) }}
                </span>
            </div>
        </div>
        <div class="d-flex gap-2">
            @can('start', $onlineClass)
                <button class="btn btn-success rounded-pill px-4 fw-bold" id="btnStart" onclick="startClass()" {{ $onlineClass->status === 'in_progress' ? 'disabled' : '' }}>
                    <i class="fas fa-play me-2"></i> {{ __('instructor::online_classes.start_class') }}
                </button>
            @endcan
            @can('end', $onlineClass)
                <button class="btn btn-danger rounded-pill px-4 fw-bold" id="btnEnd" onclick="endClass()" {{ $onlineClass->status !== 'in_progress' ? 'disabled' : '' }}>
                    <i class="fas fa-stop me-2"></i> {{ __('instructor::online_classes.end_class') }}
                </button>
            @endcan
        </div>
    </div>

    {{-- Main Classroom Area --}}
    <div class="row g-3">
        {{-- Video Canvas (Left) --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background:#000; min-height: 500px;">
                <div class="card-body p-0">
                    @if($joinContext && $onlineClass->status === 'in_progress')
                        {{-- Zoom Meeting SDK Embed --}}
                        <div id="zmmtg-root" style="width:100%; height:100%;"></div>
                    @elseif($onlineClass->meeting_link && $onlineClass->platform !== 'zoom')
                        {{-- Fallback: external link --}}
                        <div class="d-flex align-items-center justify-content-center h-100 text-white">
                            <div class="text-center">
                                <i class="fas fa-external-link-alt fa-4x mb-3 opacity-75"></i>
                                <h5 class="fw-bold">{{ __('instructor::online_classes.external_meeting') }}</h5>
                                <p class="text-muted">{{ __('instructor::online_classes.external_meeting_desc') }}</p>
                                <a href="{{ $onlineClass->meeting_link }}" target="_blank" class="btn btn-primary rounded-pill px-4 mt-3">
                                    <i class="fas fa-arrow-up-right-from-square me-2"></i> {{ __('instructor::online_classes.join_external') }}
                                </a>
                            </div>
                        </div>
                    @else
                        {{-- Waiting / Not Started --}}
                        <div class="d-flex align-items-center justify-content-center h-100 text-white">
                            <div class="text-center">
                                @if($onlineClass->status === 'scheduled')
                                    <i class="fas fa-clock fa-4x mb-3 text-warning"></i>
                                    <h5 class="fw-bold">{{ __('instructor::online_classes.waiting_to_start') }}</h5>
                                    <p class="text-muted">{{ $onlineClass->start_time->diffForHumans() }}</p>
                                @elseif($onlineClass->status === 'completed')
                                    <i class="fas fa-check-circle fa-4x mb-3 text-success"></i>
                                    <h5 class="fw-bold">{{ __('instructor::online_classes.class_completed') }}</h5>
                                    <p class="text-muted">{{ __('instructor::online_classes.recording_processing') }}</p>
                                @else
                                    <i class="fas fa-video-slash fa-4x mb-3 text-secondary"></i>
                                    <h5 class="fw-bold">{{ __('instructor::online_classes.not_started') }}</h5>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar: Participants & Attendance (Right) --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0"><i class="fas fa-users me-2"></i> {{ __('instructor::online_classes.participants') }} <span class="badge bg-primary ms-2">{{ $participants->count() }}</span></h6>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="attendanceAuto" checked>
                        <label class="form-check-label small" for="attendanceAuto">{{ __('instructor::online_classes.auto_mark') }}</label>
                    </div>
                </div>
                <div class="card-body p-0" style="max-height: 500px; overflow-y: auto;">
                    <div class="list-group list-group-flush" id="participantsList">
                        @foreach($participants as $p)
                            <div class="list-group-item px-3 py-2 d-flex align-items-center justify-content-between participant-row" data-user-id="{{ $p->user_id }}" data-status="{{ $p->status }}">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-circle shadow-sm" style="width:36px;height:36px;font-size:0.8rem;">
                                        {{ $p->student ? substr($p->student->name, 0, 1) : '?' }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold small">{{ $p->student->name ?? 'Unknown' }}</div>
                                        <div class="text-muted extra-small">{{ $p->student->code ?? '' }}</div>
                                    </div>
                                </div>
                                <span class="badge bg-{{ $p->status === 'joined' ? 'success' : ($p->status === 'invited' ? 'warning text-dark' : 'secondary') }} rounded-pill px-2 py-1 status-badge">
                                    {{ __('instructor::online_classes.participant_status.'.$p->status) }}
                                </span>
                            </div>
                        @empty
                            <div class="list-group-item text-center text-muted py-4">
                                {{ __('instructor::online_classes.no_participants') }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recordings Card (if any) --}}
    @if($onlineClass->readyRecording)
    <div class="card border-0 shadow-sm rounded-4 mt-3">
        <div class="card-header bg-white border-0">
            <h6 class="fw-bold mb-0"><i class="fas fa-clapperboard me-2 text-primary"></i> {{ __('instructor::online_classes.recording_ready') }}</h6>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="fw-semibold">{{ $onlineClass->readyRecording->available_at->format('Y-m-d H:i') }}</div>
                    <div class="text-muted small">{{ number_format($onlineClass->readyRecording->duration_seconds / 60, 1) }} دقائق</div>
                </div>
                <a href="{{ route('instructor.recordings.show', $onlineClass->readyRecording) }}" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-chart-line me-2"></i> {{ __('instructor::online_classes.view_analytics') }}
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://source.zoom.us/2.18.0/zoom-meeting-2.18.0.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const joinContext = @json($joinContext ?? null);
    const classId = {{ $onlineClass->id }};
    let zmClient = null;

    async function initZoom() {
        if (!joinContext) {
            console.warn('No Zoom join context available');
            return;
        }

        try {
            // Check if ZoomMtg is available
            if (typeof ZoomMtg === 'undefined') {
                console.error('Zoom SDK not loaded');
                return;
            }

            // Initialize SDK
            ZoomMtg.setZoomJSLib('https://source.zoom.us/2.18.0/lib', '/av');
            ZoomMtg.preLoadWasm();
            ZoomMtg.prepareJssdk();

            const signature = joinContext.signature;
            const meetingNumber = joinContext.meeting_number;
            const sdkKey = joinContext.sdk_key;
            const userName = '{{ auth()->user()->name }} (مدرس)';
            const userEmail = '{{ auth()->user()->email }}';
            const role = joinContext.role; // 1 = host

            const result = await ZoomMtg.init({
                leaveUrl: window.location.origin + '{{ route("instructor.online_classes.index") }}',
                isSupportAV: true,
                success: (res) => {
                    console.log('Zoom init success', res);
                    joinMeeting();
                },
                error: (res) => {
                    console.error('Zoom init error', res);
                    showError('فشل تهيئة Zoom SDK: ' + JSON.stringify(res));
                }
            });
        } catch (e) {
            console.error('Zoom SDK exception', e);
            showError('استثناء في Zoom SDK: ' + e.message);
        }
    }

    function joinMeeting() {
        ZoomMtg.join({
            signature: joinContext.signature,
            meetingNumber: joinContext.meeting_number,
            sdkKey: joinContext.sdk_key,
            userName: '{{ auth()->user()->name }} (مدرس) ',
            userEmail: '{{ auth()->user()->email }}',
            passWord: '',
            role: joinContext.role,
            success: (res) => {
                console.log('Join meeting success', res);
            },
            error: (res) => {
                console.error('Join meeting error', res);
                showError('فشل الانضمام للاجتماع: ' + JSON.stringify(res));
            }
        });
    }

    function showError(msg) {
        const root = document.getElementById('zmmtg-root');
        if (root) {
            root.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-white"><div class="text-center"><i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i><h5>تعذر تحميل الحصة</h5><p class="text-muted">' + msg + '</p></div></div>';
        }
    }

    // Start/End Class AJAX
    async function startClass() {
        const btn = document.getElementById('btnStart');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> جاري البدء...';

        try {
            const res = await fetch('{{ route("instructor.online_classes.start", $onlineClass) }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            });
            const data = await res.json();
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'فشل البدء');
            }
        } catch (e) {
            alert('خطأ في الشبكة');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-play me-2"></i> بدء الحصة';
        }
    }

    async function endClass() {
        if (!confirm('{{ __('instructor::online_classes.confirm_end') }}')) return;
        const btn = document.getElementById('btnEnd');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> جاري الإنهاء...';

        try {
            const res = await fetch('{{ route("instructor.online_classes.end", $onlineClass) }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            });
            const data = await res.json();
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'فشل الإنهاء');
            }
        } catch (e) {
            alert('خطأ في الشبكة');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-stop me-2"></i> إنهاء الحصة';
        }
    }

    // Auto-init when status becomes live (polling fallback)
    if (joinContext && '{{ $onlineClass->status }}' === 'in_progress') {
        initZoom();
    }
});
</script>
@endpush