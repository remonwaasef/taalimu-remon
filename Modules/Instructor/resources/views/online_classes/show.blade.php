@extends('layouts.app-next')

@section('title', $onlineClass->title . ' — استوديو البث المباشر')

@section('sidebar')
    @include('instructor::partials._sidebar-next', ['active' => 'online_classes'])
@endsection

@section('content')
<div class="container-fluid px-3 px-lg-4 py-2">
    {{-- Header with Class Info & Controls --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2 bg-white dark:bg-slate-900 p-3 rounded-4 shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="d-flex align-items-center gap-3">
            <div class="w-12 h-12 rounded-3xl bg-brand-50 text-brand-primary dark:bg-emerald-950/40 dark:text-emerald-400 d-flex align-items-center justify-center fs-4 shadow-xs" style="width: 46px; height: 46px; color: var(--primary-color);">
                <i class="fas fa-video"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h4 class="fw-black mb-0 text-slate-900 dark:text-slate-100 font-arabic">{{ $onlineClass->title }}</h4>
                    <span class="badge {{ $onlineClass->status === 'in_progress' ? 'bg-danger animate__animated animate__pulse animate__infinite' : ($onlineClass->status === 'scheduled' ? 'bg-warning text-dark' : 'bg-secondary') }} rounded-pill px-3 py-1 text-xs">
                        <i class="fas {{ $onlineClass->status === 'in_progress' ? 'fa-circle' : 'fa-clock' }} me-1"></i>
                        {{ __('instructor::online_classes.'.$onlineClass->status) }}
                    </span>
                    <span class="badge bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-300 dark:border-emerald-800 rounded-pill px-2.5 py-1 text-xs font-bold font-arabic">
                        <i class="fas fa-satellite-dish me-1"></i> استوديو تعليمو المدمج
                    </span>
                </div>
                <div class="d-flex flex-wrap gap-3 text-muted small mt-1">
                    <span><i class="fas fa-book-open me-1 text-primary"></i> {{ $onlineClass->course->title ?? 'عام' }}</span>
                    <span><i class="fas fa-clock me-1 text-primary"></i> {{ $onlineClass->start_time->format('Y-m-d H:i') }} ({{ $onlineClass->duration_minutes }} دقيقة)</span>
                    <span id="liveAttendeeCounter" class="text-emerald-600 fw-bold"><i class="fas fa-users me-1"></i> <span id="onlineCount">{{ $participants->where('status', 'joined')->count() }}</span> متواجد الآن</span>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            @if($onlineClass->status !== 'in_progress')
                <button class="btn btn-success rounded-pill px-4 py-2 fw-black shadow-sm" id="btnStart" onclick="startClass()">
                    <i class="fas fa-play me-1.5"></i> بدء البث المباشر في الاستوديو
                </button>
            @else
                <button class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-bold text-xs" onclick="toggleClassroomFullscreen()">
                    <i class="fas fa-expand me-1"></i> ملء الشاشة
                </button>
                <button class="btn btn-danger rounded-pill px-4 py-2 fw-black shadow-sm" id="btnEnd" onclick="endClass()">
                    <i class="fas fa-stop me-1.5"></i> إنهاء الحصة
                </button>
            @endif
        </div>
    </div>

    {{-- Main Classroom Area: Video (Left) + Interactive Hub (Right) --}}
    <div class="row g-3">
        {{-- Video Canvas Area (70%) --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100" style="background:#090d16 !important; min-height: 580px;">
                <div class="card-body p-0 position-relative d-flex flex-column h-100" style="min-height: 580px; background:#090d16 !important;">
                    
                    @if($onlineClass->status === 'in_progress')
                        @if($onlineClass->platform === 'zoom' && $joinContext)
                            {{-- Zoom Meeting SDK Embed --}}
                            <div id="zmmtg-root" style="width:100%; height:100%; min-height: 580px;"></div>
                        @else
                            {{-- In-App Embedded Video Studio --}}
                            @php
                                $roomName = 'taalimu_' . $onlineClass->tenant_id . '_' . ($onlineClass->uuid ?? $onlineClass->id);
                                $userName = auth()->user()->name . ' (المدرس)';
                                $jitsiUrl = "https://meet.jit.si/{$roomName}#config.defaultLanguage='ar'&config.prejoinPageEnabled=false&config.startWithAudioMuted=false&config.startWithVideoMuted=false&config.toolbarButtons=['camera','microphone','desktop','fullscreen','fodeviceselection','hangup','profile','recording','livestreaming','settings','raisehand','videoquality','filmstrip','tileview','mute-everyone']&userInfo.displayName=" . urlencode($userName);
                            @endphp
                            <div id="classroom-video-container" class="w-100 h-100 d-flex flex-column flex-grow-1" style="min-height: 580px; background:#090d16 !important;">
                                <iframe src="{{ $jitsiUrl }}" 
                                        allow="camera *; microphone *; display-capture *; fullscreen *; autoplay *; clipboard-write *;" 
                                        style="width: 100%; height: 100%; min-height: 580px; border: 0; background:#090d16;" 
                                        class="w-100 flex-grow-1"
                                        id="jitsiClassroomIframe">
                                </iframe>
                            </div>
                        @endif
                    @else
                        {{-- Class Waiting / Ready Screen --}}
                        <div class="d-flex align-items-center justify-content-center h-100 text-white p-4 my-auto" style="min-height: 540px;">
                            <div class="text-center max-w-md mx-auto">
                                <div class="w-20 h-20 rounded-circle bg-emerald-500 bg-opacity-10 text-emerald-400 d-inline-flex align-items-center justify-content-center fs-1 mb-3">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <h4 class="fw-bold mb-2">استوديو الحصة جاهز للبدء</h4>
                                <p class="text-muted mb-4 small leading-relaxed font-arabic">
                                    عند الضغط على "بدء البث"، سيتم تفعيل الكاميرا والميكروفون فوراً داخل هذه الشاشة مباشرة دون مغادرة الصفحة، وسيتمكن الطلاب من الدخول والتفاعل عبر الأسئلة والدردشة.
                                </p>
                                <button type="button" onclick="startClass()" class="btn btn-success btn-lg rounded-pill px-5 py-2.5 fw-black shadow-lg">
                                    <i class="fas fa-play me-2"></i> ابدأ البث الآن 🚀
                                </button>
                                <div class="mt-4 pt-3 border-top border-secondary border-opacity-25 d-flex justify-content-center gap-3 text-muted extra-small">
                                    <span><i class="fas fa-shield-alt text-success me-1"></i> بث مشفر وآمن</span>
                                    <span><i class="fas fa-desktop text-info me-1"></i> مشاركة شاشة عالية الدقة</span>
                                    <span><i class="fas fa-comment-dots text-warning me-1"></i> شات و Q&A فوري</span>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- Interactive Classroom Hub (Right - 30%) --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 d-flex flex-column bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800" style="min-height: 560px;">
                
                {{-- Interactive Tabs Header --}}
                <div class="card-header bg-white dark:bg-slate-900 border-bottom border-slate-100 dark:border-slate-800 p-2">
                    <ul class="nav nav-pills nav-fill gap-1" id="classroomTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active py-2 px-2 text-xs font-bold rounded-xl" id="tab-chat-btn" data-bs-toggle="tab" data-bs-target="#tab-chat" type="button" role="tab">
                                <i class="fas fa-comment-dots me-1"></i> الدردشة
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-2 px-2 text-xs font-bold rounded-xl position-relative" id="tab-qa-btn" data-bs-toggle="tab" data-bs-target="#tab-qa" type="button" role="tab">
                                <i class="fas fa-question-circle me-1"></i> الأسئلة
                                <span id="qaBadge" class="badge rounded-pill bg-danger text-[10px] {{ count($questions) ? '' : 'd-none' }}">{{ count($questions) }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-2 px-2 text-xs font-bold rounded-xl position-relative" id="tab-hands-btn" data-bs-toggle="tab" data-bs-target="#tab-hands" type="button" role="tab">
                                <i class="fas fa-hand-paper me-1"></i> رفع اليد
                                <span id="handsBadge" class="badge rounded-pill bg-warning text-dark text-[10px] {{ count($handRaises) ? '' : 'd-none' }}">{{ count($handRaises) }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-2 px-2 text-xs font-bold rounded-xl" id="tab-attendance-btn" data-bs-toggle="tab" data-bs-target="#tab-attendance" type="button" role="tab">
                                <i class="fas fa-users me-1"></i> الحضور
                            </button>
                        </li>
                    </ul>
                </div>

                {{-- Tab Contents Body --}}
                <div class="card-body p-0 tab-content flex-grow-1 d-flex flex-column overflow-hidden" id="classroomTabsContent">
                    
                    {{-- 1. Live Chat Tab --}}
                    <div class="tab-pane fade show active h-100 d-flex flex-column" id="tab-chat" role="tabpanel">
                        <div class="p-2 border-bottom border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 d-flex justify-content-between align-items-center">
                            <span class="text-xs text-muted font-arabic"><i class="fas fa-shield-alt text-success me-1"></i> الدردشة نشطة لجميع الطلاب</span>
                            <span class="badge bg-light text-muted border text-[10px]">مباشر 🟢</span>
                        </div>
                        <div class="flex-grow-1 p-3 overflow-y-auto" id="chatMessagesStream" style="max-height: 420px; min-height: 340px;">
                            @forelse($messages as $msg)
                                <div class="mb-2.5 d-flex flex-column {{ $msg->user_id === auth()->id() ? 'align-items-start' : 'align-items-end' }}">
                                    <div class="d-flex align-items-center gap-1.5 mb-0.5">
                                        <span class="text-xs fw-bold {{ $msg->user_id === auth()->id() ? 'text-emerald-600' : 'text-slate-700 dark:text-slate-300' }}">
                                            {{ $msg->user?->name ?? 'طالب' }}
                                            @if($msg->user_id === auth()->id()) <span class="badge bg-emerald-100 text-emerald-800 text-[9px]">أنت</span> @endif
                                        </span>
                                        <span class="text-[10px] text-muted">{{ $msg->created_at->format('H:i') }}</span>
                                    </div>
                                    <div class="p-2.5 rounded-2xl text-xs max-w-xs {{ $msg->user_id === auth()->id() ? 'bg-emerald-50 text-emerald-950 dark:bg-emerald-950/40 dark:text-emerald-200 rounded-tr-none' : 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-200 rounded-tl-none' }}">
                                        {{ $msg->message }}
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-5 my-auto" id="emptyChatNotice">
                                    <i class="far fa-comments fa-2x mb-2 text-slate-300"></i>
                                    <p class="text-xs mb-0">لا توجد رسائل بعد. ابدأ بالترحيب بطلابك!</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="p-2.5 border-top border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 mt-auto">
                            <form id="chatForm" onsubmit="handleSendChatMessage(event)" class="d-flex gap-2">
                                <input type="text" id="chatInput" placeholder="اكتب رسالة للطلاب..." autocomplete="off" class="form-control form-control-sm rounded-pill px-3 text-xs bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700">
                                <button type="submit" id="btnSendChat" class="btn btn-sm btn-primary rounded-circle d-flex align-items-center justify-content-center shrink-0" style="width: 34px; height: 34px; background: var(--primary-color);">
                                    <i class="fas fa-paper-plane text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- 2. Q&A Tab --}}
                    <div class="tab-pane fade h-100 d-flex flex-column" id="tab-qa" role="tabpanel">
                        <div class="p-2 border-bottom border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 d-flex justify-content-between align-items-center">
                            <span class="text-xs text-muted font-arabic"><i class="fas fa-lightbulb text-warning me-1"></i> أسئلة واستفسارات الطلاب المنظمة</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary text-[10px]" id="qaCountBadge">{{ count($questions) }} أسئلة</span>
                        </div>
                        <div class="flex-grow-1 p-3 overflow-y-auto" id="qaListStream" style="max-height: 420px; min-height: 340px;">
                            @forelse($questions as $q)
                                <div class="card border rounded-3 p-2.5 mb-2.5 {{ $q->is_answered ? 'bg-slate-50/60 dark:bg-slate-800/30 opacity-75' : 'bg-white dark:bg-slate-900' }}" id="qa-card-{{ $q->id }}">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <div class="d-flex align-items-center gap-1.5">
                                            <span class="fw-bold text-xs text-slate-800 dark:text-slate-200">{{ $q->student?->name ?? $q->user?->name ?? 'طالب' }}</span>
                                            <span class="text-[10px] text-muted">{{ $q->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if($q->is_answered)
                                            <span class="badge bg-success bg-opacity-10 text-success text-[10px]"><i class="fas fa-check me-1"></i> تمت الإجابة</span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning text-[10px]">في الانتظار</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-700 dark:text-slate-300 mb-2 leading-relaxed font-arabic">{{ $q->question }}</p>
                                    <div class="d-flex justify-content-between align-items-center pt-1 border-top border-slate-100 dark:border-slate-800">
                                        <button type="button" onclick="upvoteQuestion({{ $q->id }})" class="btn btn-sm btn-light py-0.5 px-2 rounded-pill text-[11px] font-bold text-slate-600">
                                            👍 <span id="upvotes-{{ $q->id }}">{{ $q->upvotes_count }}</span>
                                        </button>
                                        <button type="button" onclick="toggleAnswerQuestion({{ $q->id }})" class="btn btn-sm btn-outline-success py-0.5 px-2 rounded-pill text-[10px] fw-bold">
                                            <i class="fas {{ $q->is_answered ? 'fa-undo' : 'fa-check' }} me-1"></i> {{ $q->is_answered ? 'إلغاء' : 'تمييز كـ تمت الإجابة' }}
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-5 my-auto" id="emptyQaNotice">
                                    <i class="far fa-question-circle fa-2x mb-2 text-slate-300"></i>
                                    <p class="text-xs mb-0">لا توجد أسئلة بعد. أسئلة الطلاب ستظهر هنا مرتبة حسب الأهمية!</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="p-2.5 border-top border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 mt-auto">
                            <form id="qaForm" onsubmit="handleAskQuestion(event)" class="d-flex gap-2">
                                <input type="text" id="qaInput" placeholder="طرح سؤال أو نقطة للنقاش..." autocomplete="off" class="form-control form-control-sm rounded-pill px-3 text-xs bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700">
                                <button type="submit" class="btn btn-sm btn-warning rounded-circle d-flex align-items-center justify-content-center shrink-0" style="width: 34px; height: 34px;">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- 3. Hand Raises Tab --}}
                    <div class="tab-pane fade h-100 d-flex flex-column" id="tab-hands" role="tabpanel">
                        <div class="p-2 border-bottom border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 d-flex justify-content-between align-items-center">
                            <span class="text-xs text-muted font-arabic"><i class="fas fa-hand-paper text-warning me-1"></i> طلبات الميكروفون والتحدث</span>
                            <span class="badge bg-warning text-dark text-[10px]" id="handsCountBadge">{{ count($handRaises) }} طلبات</span>
                        </div>
                        <div class="flex-grow-1 p-3 overflow-y-auto" id="handsListStream" style="max-height: 480px;">
                            @forelse($handRaises as $hand)
                                <div class="card border rounded-3 p-2.5 mb-2 bg-amber-50/50 dark:bg-amber-950/20 border-amber-200 dark:border-amber-800" id="hand-card-{{ $hand->id }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fs-5">✋</span>
                                            <div>
                                                <span class="fw-bold text-xs d-block text-slate-800 dark:text-slate-200">{{ $hand->student?->name ?? $hand->user?->name ?? 'طالب' }}</span>
                                                <span class="text-[10px] text-muted">{{ $hand->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <button type="button" onclick="acknowledgeHand({{ $hand->id }})" class="btn btn-sm btn-outline-secondary py-1 px-2.5 rounded-pill text-[10px] fw-bold">
                                            <i class="fas fa-check me-1"></i> خفض اليد
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-5 my-auto" id="emptyHandsNotice">
                                    <span class="fs-1 d-block mb-2 opacity-50">✋</span>
                                    <p class="text-xs mb-0">لا توجد أيدي مرفوعة حالياً.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- 4. Attendance & Participants Tab --}}
                    <div class="tab-pane fade h-100 d-flex flex-column" id="tab-attendance" role="tabpanel">
                        <div class="p-2 border-bottom border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 d-flex justify-content-between align-items-center">
                            <span class="text-xs fw-bold text-slate-700 dark:text-slate-300 font-arabic">
                                <i class="fas fa-users me-1 text-primary"></i> المسجلون بالحصة ({{ $participants->count() }})
                            </span>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" id="attendanceAuto" checked>
                                <label class="form-check-label text-[11px] text-muted" for="attendanceAuto">تسجيل ذكي</label>
                            </div>
                        </div>
                        <div class="flex-grow-1 p-0 overflow-y-auto" style="max-height: 480px;">
                            <div class="list-group list-group-flush" id="participantsList">
                                @forelse($participants as $p)
                                    <div class="list-group-item px-3 py-2.5 d-flex align-items-center justify-content-between border-slate-100 dark:border-slate-800">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 d-flex align-items-center justify-center text-xs fw-bold shadow-xs">
                                                {{ $p->student ? substr($p->student->name, 0, 1) : '?' }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-xs text-slate-800 dark:text-slate-200">{{ $p->student->name ?? 'طالب' }}</div>
                                                <div class="text-[10px] text-muted">{{ $p->student->code ?? $p->student->phone ?? '' }}</div>
                                            </div>
                                        </div>
                                        <span class="badge bg-{{ $p->status === 'joined' ? 'success' : ($p->status === 'invited' ? 'warning text-dark' : 'secondary') }} rounded-pill px-2.5 py-1 text-[10px]">
                                            {{ __('instructor::online_classes.participant_status.'.$p->status) }}
                                        </span>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-5">
                                        <i class="fas fa-user-friends fa-2x mb-2 text-slate-300"></i>
                                        <p class="text-xs mb-0">لم يسجل طلاب في هذه الحصة بعد.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Jitsi Meet Embedded External API for 100% In-App Classroom Video --}}
@if($onlineClass->status === 'in_progress' && $onlineClass->platform !== 'zoom')
<script src="https://meet.jit.si/external_api.js"></script>
@endif

@if($joinContext && $onlineClass->status === 'in_progress')
<script src="https://source.zoom.us/2.18.0/zoom-meeting-2.18.0.min.js"></script>
@endif

<script>
function toggleClassroomFullscreen() {
    const elem = document.querySelector('#classroom-video-container') || document.querySelector('#jitsiClassroomIframe') || document.documentElement;
    if (!document.fullscreenElement) {
        elem.requestFullscreen().catch(err => alert('تعذر تفعيل وضع ملء الشاشة: ' + err.message));
    } else {
        document.exitFullscreen();
    }
}

// Start / End Class Actions
async function startClass() {
    const btn = document.getElementById('btnStart');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1.5"></span> جاري تهيئة الاستوديو...';
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
                btn.innerHTML = '<i class="fas fa-play me-1.5"></i> بدء البث المباشر';
            }
        }
    } catch (e) {
        alert('حدث خطأ أثناء الاتصال بالخادم.');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-play me-1.5"></i> بدء البث المباشر';
        }
    }
}

async function endClass() {
    if (!confirm('هل أنت متأكد من إنهاء الحصة الآن وحفظ سجل الحضور؟')) return;
    const btn = document.getElementById('btnEnd');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1.5"></span> جاري الإنهاء...';
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
                btn.innerHTML = '<i class="fas fa-stop me-1.5"></i> إنهاء الحصة';
            }
        }
    } catch (e) {
        alert('حدث خطأ أثناء الاتصال بالخادم.');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-stop me-1.5"></i> إنهاء الحصة';
        }
    }
}

// Interactive Chat Handler
async function handleSendChatMessage(e) {
    e.preventDefault();
    const input = document.getElementById('chatInput');
    const msg = input.value.trim();
    if (!msg) return;

    input.value = '';

    try {
        const res = await fetch('{{ route("instructor.online_classes.send_message", $onlineClass) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: msg })
        });
        const data = await res.json();
        if (data.success) {
            appendChatMessage(data.message);
        }
    } catch(err) {
        console.error('Chat error:', err);
    }
}

function appendChatMessage(m) {
    const stream = document.getElementById('chatMessagesStream');
    const emptyNotice = document.getElementById('emptyChatNotice');
    if (emptyNotice) emptyNotice.remove();

    const div = document.createElement('div');
    div.className = 'mb-2.5 d-flex flex-column ' + (m.is_me ? 'align-items-start' : 'align-items-end');
    div.innerHTML = `
        <div class="d-flex align-items-center gap-1.5 mb-0.5">
            <span class="text-xs fw-bold ${m.is_me ? 'text-emerald-600' : 'text-slate-700 dark:text-slate-300'}">
                ${m.user_name}
                ${m.is_me ? '<span class="badge bg-emerald-100 text-emerald-800 text-[9px]">أنت</span>' : ''}
            </span>
            <span class="text-[10px] text-muted">${m.time}</span>
        </div>
        <div class="p-2.5 rounded-2xl text-xs max-w-xs ${m.is_me ? 'bg-emerald-50 text-emerald-950 dark:bg-emerald-950/40 dark:text-emerald-200 rounded-tr-none' : 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-200 rounded-tl-none'}">
            ${escapeHtml(m.message)}
        </div>
    `;
    stream.appendChild(div);
    stream.scrollTop = stream.scrollHeight;
}

// Interactive Q&A Handler
async function handleAskQuestion(e) {
    e.preventDefault();
    const input = document.getElementById('qaInput');
    const q = input.value.trim();
    if (!q) return;

    input.value = '';

    try {
        const res = await fetch('{{ route("instructor.online_classes.ask_question", $onlineClass) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ question: q })
        });
        const data = await res.json();
        if (data.success) {
            refreshQuestions();
        }
    } catch(err) {
        console.error('QA error:', err);
    }
}

async function upvoteQuestion(questionId) {
    try {
        const res = await fetch(`/instructor/online-classes/{{ $onlineClass->id }}/questions/${questionId}/upvote`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (data.success) {
            const counter = document.getElementById(`upvotes-${questionId}`);
            if (counter) counter.innerText = data.upvotes;
        }
    } catch(err) {
        console.error(err);
    }
}

async function toggleAnswerQuestion(questionId) {
    try {
        const res = await fetch(`/instructor/online-classes/{{ $onlineClass->id }}/questions/${questionId}/toggle-answer`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (data.success) {
            refreshQuestions();
        }
    } catch(err) {
        console.error(err);
    }
}

async function refreshQuestions() {
    try {
        const res = await fetch('{{ route("instructor.online_classes.questions", $onlineClass) }}');
        const data = await res.json();
        if (data.questions) {
            const stream = document.getElementById('qaListStream');
            const badge = document.getElementById('qaBadge');
            const countBadge = document.getElementById('qaCountBadge');
            if (badge) {
                badge.innerText = data.questions.length;
                badge.classList.toggle('d-none', data.questions.length === 0);
            }
            if (countBadge) countBadge.innerText = data.questions.length + ' أسئلة';

            if (data.questions.length === 0) {
                stream.innerHTML = `
                    <div class="text-center text-muted py-5 my-auto" id="emptyQaNotice">
                        <i class="far fa-question-circle fa-2x mb-2 text-slate-300"></i>
                        <p class="text-xs mb-0">لا توجد أسئلة بعد.</p>
                    </div>`;
                return;
            }

            stream.innerHTML = data.questions.map(q => `
                <div class="card border rounded-3 p-2.5 mb-2.5 ${q.is_answered ? 'bg-slate-50/60 dark:bg-slate-800/30 opacity-75' : 'bg-white dark:bg-slate-900'}" id="qa-card-${q.id}">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div class="d-flex align-items-center gap-1.5">
                            <span class="fw-bold text-xs text-slate-800 dark:text-slate-200">${escapeHtml(q.user_name)}</span>
                            <span class="text-[10px] text-muted">${q.time}</span>
                        </div>
                        ${q.is_answered ? '<span class="badge bg-success bg-opacity-10 text-success text-[10px]"><i class="fas fa-check me-1"></i> تمت الإجابة</span>' : '<span class="badge bg-warning bg-opacity-10 text-warning text-[10px]">في الانتظار</span>'}
                    </div>
                    <p class="text-xs text-slate-700 dark:text-slate-300 mb-2 leading-relaxed font-arabic">${escapeHtml(q.question)}</p>
                    <div class="d-flex justify-content-between align-items-center pt-1 border-top border-slate-100 dark:border-slate-800">
                        <button type="button" onclick="upvoteQuestion(${q.id})" class="btn btn-sm btn-light py-0.5 px-2 rounded-pill text-[11px] font-bold text-slate-600">
                            👍 <span id="upvotes-${q.id}">${q.upvotes}</span>
                        </button>
                        <button type="button" onclick="toggleAnswerQuestion(${q.id})" class="btn btn-sm btn-outline-success py-0.5 px-2 rounded-pill text-[10px] fw-bold">
                            <i class="fas ${q.is_answered ? 'fa-undo' : 'fa-check'} me-1"></i> ${q.is_answered ? 'إلغاء' : 'تمييز كـ تمت الإجابة'}
                        </button>
                    </div>
                </div>
            `).join('');
        }
    } catch(err) {
        console.error(err);
    }
}

// Hand Raise Acknowledge
async function acknowledgeHand(handId) {
    try {
        const res = await fetch(`/instructor/online-classes/{{ $onlineClass->id }}/hand-raises/${handId}/acknowledge`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (data.success) {
            const card = document.getElementById(`hand-card-${handId}`);
            if (card) card.remove();
            refreshHandRaises();
        }
    } catch(err) {
        console.error(err);
    }
}

async function refreshHandRaises() {
    try {
        const res = await fetch('{{ route("instructor.online_classes.hand_raises", $onlineClass) }}');
        const data = await res.json();
        if (data.hand_raises) {
            const stream = document.getElementById('handsListStream');
            const badge = document.getElementById('handsBadge');
            const countBadge = document.getElementById('handsCountBadge');
            if (badge) {
                badge.innerText = data.hand_raises.length;
                badge.classList.toggle('d-none', data.hand_raises.length === 0);
            }
            if (countBadge) countBadge.innerText = data.hand_raises.length + ' طلبات';

            if (data.hand_raises.length === 0) {
                stream.innerHTML = `
                    <div class="text-center text-muted py-5 my-auto" id="emptyHandsNotice">
                        <span class="fs-1 d-block mb-2 opacity-50">✋</span>
                        <p class="text-xs mb-0">لا توجد أيدي مرفوعة حالياً.</p>
                    </div>`;
                return;
            }

            stream.innerHTML = data.hand_raises.map(h => `
                <div class="card border rounded-3 p-2.5 mb-2 bg-amber-50/50 dark:bg-amber-950/20 border-amber-200 dark:border-amber-800" id="hand-card-${h.id}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fs-5">✋</span>
                            <div>
                                <span class="fw-bold text-xs d-block text-slate-800 dark:text-slate-200">${escapeHtml(h.student_name)}</span>
                                <span class="text-[10px] text-muted">${h.time}</span>
                            </div>
                        </div>
                        <button type="button" onclick="acknowledgeHand(${h.id})" class="btn btn-sm btn-outline-secondary py-1 px-2.5 rounded-pill text-[10px] fw-bold">
                            <i class="fas fa-check me-1"></i> خفض اليد
                        </button>
                    </div>
                </div>
            `).join('');
        }
    } catch(err) {
        console.error(err);
    }
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>"']/g, function(m) {
        return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[m];
    });
}

// Background polling for real-time interaction during live class
document.addEventListener('DOMContentLoaded', function() {
    const isLive = '{{ $onlineClass->status }}' === 'in_progress';

    if (isLive) {
        // Poll for new messages, questions, and hand raises every 4 seconds
        setInterval(() => {
            refreshQuestions();
            refreshHandRaises();
        }, 4000);
    }
});
</script>
@endpush