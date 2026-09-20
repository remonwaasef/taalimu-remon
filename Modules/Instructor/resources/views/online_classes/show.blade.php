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
                    @elseif($onlineClass->status === 'in_progress' && $onlineClass->meeting_link)
                        {{-- Live meeting link available --}}
                        <div class="d-flex align-items-center justify-content-center h-100 text-white p-4">
                            <div class="text-center">
                                <div class="mb-3">
                                    <span class="badge bg-danger text-white rounded-pill px-3 py-2 animate__animated animate__pulse animate__infinite">
                                        <i class="fas fa-circle me-1"></i> {{ __('instructor::online_classes.in_progress') }}
                                    </span>
                                </div>
                                <i class="fas fa-video fa-4x mb-3 text-success"></i>
                                <h4 class="fw-bold mb-2">قاعة الدرس بدأت بنجاح</h4>
                                <p class="text-muted mb-4">يمكنك الانضمام مباشرة إلى الاجتماع عبر الزر أدناه:</p>
                                <a href="{{ $onlineClass->meeting_link }}" target="_blank" class="btn btn-success rounded-pill px-5 py-2 fw-bold shadow">
                                    <i class="fas fa-arrow-up-right-from-square me-2"></i> دخول الاجتماع
                                </a>
                            </div>
                        </div>
                    @elseif($onlineClass->status === 'in_progress')
                        {{-- In progress without direct link --}}
                        <div class="d-flex align-items-center justify-content-center h-100 text-white p-4">
                            <div class="text-center" style="max-width: 540px;">
                                <span class="badge bg-danger text-white rounded-pill px-3 py-2 mb-3 animate__animated animate__pulse animate__infinite">
                                    <i class="fas fa-circle me-1"></i> {{ __('instructor::online_classes.in_progress') }}
                                </span>
                                <h4 class="fw-bold mb-2">الحصة بدأت بنجاح</h4>
                                <p class="text-muted mb-3">لم يتم تعيين رابط بث مباشر لهذه الحصة بعد (أو حساب Zoom غير مفعّل API).</p>
                                
                                <div class="card bg-dark border-secondary p-3 rounded-4 mb-3 text-start">
                                    <label class="form-label text-white small fw-bold mb-2">
                                        <i class="fas fa-video text-success me-1"></i> أدخل رابط الاجتماع (Google Meet أو Zoom):
                                    </label>
                                    <form id="quickLinkForm" onsubmit="saveMeetingLink(event)">
                                        <div class="input-group">
                                            <input type="url" id="inputMeetingLink" class="form-control rounded-start-pill px-3" placeholder="https://meet.google.com/xxx-xxxx-xxx" required>
                                            <button class="btn btn-success rounded-end-pill px-3 fw-bold" type="submit" id="btnSaveLink">
                                                <i class="fas fa-save me-1"></i> حفظ وبدء البث
                                            </button>
                                        </div>
                                    </form>
                                    <div class="text-muted extra-small mt-2">
                                        <i class="fas fa-info-circle text-info me-1"></i> يمكنك نسخ رابط مكالمة من Google Meet أو Zoom ولصقه هنا وسينتقل الطلاب إليه مباشرة.
                                    </div>
                                </div>

                                <a href="{{ route('instructor.online_classes.edit', $onlineClass) }}" class="btn btn-outline-light rounded-pill px-4 btn-sm">
                                    <i class="fas fa-cog me-1"></i> تعديل كامل إعدادات الحصة
                                </a>
                            </div>
                        </div>
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
                        @forelse($participants as $p)
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
                        @endforelse
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
@if($joinContext && $onlineClass->status === 'in_progress')
<script src="https://source.zoom.us/2.18.0/zoom-meeting-2.18.0.min.js"></script>
@endif
<script>
// Start/End Class Actions - Globally available
async function startClass() {
    const btn = document.getElementById('btnStart');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> {{ __("instructor::online_classes.starting", ["default" => "جاري البدء..."]) }}';
    }

    try {
        const res = await fetch('{{ route("instructor.online_classes.start", $onlineClass) }}', {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                'Content-Type': 'application/json',
                'Accept': 'application/json' 
            },
        });
        const data = await res.json();
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'فشل بدء الحصة');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-play me-2"></i> {{ __("instructor::online_classes.start_class") }}';
            }
        }
    } catch (e) {
        alert('حدث خطأ أثناء الاتصال بالخادم. يرجى إعادة المحاولة.');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-play me-2"></i> {{ __("instructor::online_classes.start_class") }}';
        }
    }
}

async function endClass() {
    if (!confirm('{{ __("instructor::online_classes.confirm_end", ["default" => "هل أنت متأكد من إنهاء الحصة الآن؟"]) }}')) return;
    const btn = document.getElementById('btnEnd');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> {{ __("instructor::online_classes.ending", ["default" => "جاري الإنهاء..."]) }}';
    }

    try {
        const res = await fetch('{{ route("instructor.online_classes.end", $onlineClass) }}', {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                'Content-Type': 'application/json',
                'Accept': 'application/json' 
            },
        });
        const data = await res.json();
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'فشل إنهاء الحصة');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-stop me-2"></i> {{ __("instructor::online_classes.end_class") }}';
            }
        }
    } catch (e) {
        alert('حدث خطأ أثناء الاتصال بالخادم.');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-stop me-2"></i> {{ __("instructor::online_classes.end_class") }}';
        }
    }
}

window.startClass = startClass;
window.endClass = endClass;

async function saveMeetingLink(e) {
    e.preventDefault();
    const input = document.getElementById('inputMeetingLink');
    const btn = document.getElementById('btnSaveLink');
    if (!input || !btn) return;
    const link = input.value.trim();
    if (!link) return;

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري الحفظ...';

    try {
        const res = await fetch('{{ route("instructor.online_classes.update_link", $onlineClass) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ meeting_link: link })
        });
        const data = await res.json();
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'تعذر حفظ الرابط');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save me-1"></i> حفظ وبدء البث';
        }
    } catch (err) {
        alert('حدث خطأ أثناء الاتصال بالخادم.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1"></i> حفظ وبدء البث';
    }
}
window.saveMeetingLink = saveMeetingLink;

document.addEventListener('DOMContentLoaded', function() {
    const btnStart = document.getElementById('btnStart');
    if (btnStart) {
        btnStart.addEventListener('click', startClass);
    }

    const btnEnd = document.getElementById('btnEnd');
    if (btnEnd) {
        btnEnd.addEventListener('click', endClass);
    }

    const joinContext = @json($joinContext ?? null);

    async function initZoom() {
        if (!joinContext) return;

        try {
            if (typeof ZoomMtg === 'undefined') {
                console.warn('Zoom SDK not loaded');
                return;
            }

            ZoomMtg.setZoomJSLib('https://source.zoom.us/2.18.0/lib', '/av');
            ZoomMtg.preLoadWasm();
            ZoomMtg.prepareJssdk();

            ZoomMtg.init({
                leaveUrl: window.location.origin + '{{ route("instructor.online_classes.index") }}',
                isSupportAV: true,
                success: () => {
                    ZoomMtg.join({
                        signature: joinContext.signature,
                        meetingNumber: joinContext.meeting_number,
                        sdkKey: joinContext.sdk_key,
                        userName: '{{ auth()->user()->name }} (مدرس)',
                        userEmail: '{{ auth()->user()->email }}',
                        passWord: joinContext.password || '',
                        role: joinContext.role || 1,
                        error: (res) => console.error('Join meeting error', res)
                    });
                },
                error: (res) => console.error('Zoom init error', res)
            });
        } catch (e) {
            console.error('Zoom SDK exception', e);
        }
    }

    if (joinContext && '{{ $onlineClass->status }}' === 'in_progress') {
        initZoom();
    }
});
</script>
@endpush