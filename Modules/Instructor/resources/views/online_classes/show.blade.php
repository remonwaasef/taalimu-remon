@extends('layouts.app-next')

@section('title', $onlineClass->title . ' — استوديو البث المباشر')

@section('sidebar')
    @include('instructor::partials._sidebar-next', ['active' => 'online_classes'])
@endsection

@section('content')
@php
    $meetingUrl = $onlineClass->meeting_link ?: ($defaultMeetingLink ?: 'https://meet.google.com/new');
    $isZoom = str_contains(strtolower($meetingUrl), 'zoom');
@endphp
<div class="container-fluid px-3 px-lg-4 py-2">
    {{-- Header with Class Info & Controls --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2 bg-white dark:bg-slate-900 p-3 rounded-4 shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="d-flex align-items-center gap-3">
            <div class="w-12 h-12 rounded-3xl {{ $isZoom ? 'bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400' : 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400' }} d-flex align-items-center justify-center fs-4 shadow-xs" style="width: 46px; height: 46px;">
                <i class="{{ $isZoom ? 'fas fa-video' : 'fab fa-google' }}"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h4 class="fw-black mb-0 text-slate-900 dark:text-slate-100 font-arabic">{{ $onlineClass->title }}</h4>
                    <span class="badge {{ $onlineClass->status === 'in_progress' ? 'bg-danger animate__animated animate__pulse animate__infinite' : ($onlineClass->status === 'scheduled' ? 'bg-warning text-dark' : 'bg-secondary') }} rounded-pill px-3 py-1 text-xs">
                        <i class="fas {{ $onlineClass->status === 'in_progress' ? 'fa-circle' : 'fa-clock' }} me-1"></i>
                        {{ __('instructor::online_classes.'.$onlineClass->status) }}
                            <span class="badge bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-300 border rounded-pill px-2.5 py-1 text-xs font-bold font-arabic">
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
                    <i class="fas fa-play me-1.5"></i> بدء الحصة
                </button>
            @else
                <button type="button" onclick="toggleClassroomFullscreen()" class="btn btn-outline-secondary rounded-pill px-3.5 py-2 fw-bold text-xs d-inline-flex align-items-center gap-1.5">
                    <i class="fas fa-expand"></i> تكبير الشاشة
                </button>
                <button class="btn btn-danger rounded-pill px-4 py-2 fw-black shadow-sm" id="btnEnd" onclick="endClass()">
                    <i class="fas fa-stop me-1.5"></i> إنهاء الحصة
                </button>
            @endif
        </div>
    </div>

    {{-- Main Classroom Area: Video (Left) + Interactive Hub (Right) --}}
    <div class="row g-3">
        {{-- Video Canvas / Live Broadcast Hub (70%) --}}
        <div class="col-lg-8">
            @if($onlineClass->status === 'in_progress')
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-slate-950 d-flex flex-column border border-slate-800" style="min-height: 600px;">
                    {{-- Video Studio Header Bar --}}
                    <div class="px-4 py-2.5 bg-slate-900 border-bottom border-slate-800 d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-danger rounded-pill px-3 py-1.5 text-xs font-bold animate__animated animate__pulse animate__infinite">
                                <i class="fas fa-circle me-1" style="font-size: 8px;"></i> بث مباشر داخل المنصة
                            </span>
                            <span class="text-xs text-slate-200 font-arabic fw-bold">{{ $onlineClass->title }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" onclick="toggleClassroomFullscreen()" class="btn btn-dark btn-sm rounded-pill px-3 text-xs border-slate-700 text-slate-200">
                                <i class="fas fa-expand me-1"></i> تكبير الشاشة
                            </button>
                            @if($meetingUrl && !str_contains($meetingUrl, 'meet.jit.si'))
                                <a href="{{ $meetingUrl }}" target="_blank" class="btn btn-outline-light btn-sm rounded-pill px-2.5 text-[11px] border-slate-700 text-slate-400 hover:text-white" title="فتح كبديل خارجي">
                                    <i class="fas fa-external-link-alt me-1"></i> رابط خارجي بديل
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Camera & Microphone Permission Trigger Banner --}}
                    <div id="permissionNoticeBanner" class="p-3 bg-slate-900 border-bottom border-slate-800 d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="w-8 h-8 rounded-circle bg-amber-500/20 text-amber-400 d-flex align-items-center justify-content-center text-sm shrink-0">
                                <i class="fas fa-video"></i>
                            </span>
                            <div>
                                <span class="text-xs fw-bold text-slate-100 font-arabic d-block">إذن تشغيل الكاميرا والميكروفون</span>
                                <span class="text-[11px] text-slate-400 font-arabic">اضغط على الزر أدناه لإظهار نافذة المتصفح والموافقة على تشغيل الكاميرا والصوت</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" onclick="triggerBrowserPermissionPrompt()" id="btnRequestPermission" class="btn btn-warning btn-sm rounded-pill px-3.5 py-1.5 fw-black text-xs shadow-sm d-inline-flex align-items-center gap-1.5">
                                <i class="fas fa-hand-pointer"></i>
                                <span id="permissionBtnText">إظهار نافذة الإذن والضغط عليها 📹</span>
                            </button>
                            <button type="button" onclick="toggleUnblockInstructions()" class="btn btn-outline-light btn-sm rounded-pill px-2.5 py-1.5 text-xs text-slate-300">
                                <i class="fas fa-question-circle me-1"></i> الكاميرا محظورة؟
                            </button>
                        </div>
                    </div>

                    {{-- Interactive Unblock Helper Popup --}}
                    <div id="unblockGuideBox" class="p-3.5 bg-slate-900 border-bottom border-amber-500/30 d-none text-xs font-arabic text-slate-200">
                        <div class="d-flex align-items-start gap-2.5">
                            <div class="w-8 h-8 rounded-circle bg-amber-500/20 text-amber-400 d-flex align-items-center justify-content-center shrink-0 fs-6">
                                <i class="fas fa-sliders-h"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-bold text-amber-400 d-block mb-1.5" id="permissionErrorDetails">
                                    تفعيل الكاميرا من شريط المتصفح:
                                </span>
                                <div class="p-2.5 rounded-3 bg-slate-800/80 border border-slate-700/60 mb-2 leading-relaxed text-xs">
                                    <p class="mb-1 text-slate-200 fw-bold">
                                        انظر إلى شريط العنوان في أعلى المتصفح (حيث يكتب الرابط <code>ra3yc.taalimu.com</code>):
                                    </p>
                                    <ul class="mb-0 pe-3 ps-0 text-slate-300">
                                        <li class="mb-1.5">
                                            على <strong>يسار كلمة ra3yc</strong> مباشرة، اضغط على <strong>أيقونة المؤشرات/المفتاحين (تظهر كدائرة فيها خطان ومنزلقان 🎚️)</strong>.
                                        </li>
                                        <li class="mb-1.5">
                                            ستفتح لك نافذة صغيرة فوراً تحتوي على: <strong>الكاميرا (Camera)</strong> و <strong>الميكروفون (Microphone)</strong>.
                                        </li>
                                        <li class="mb-0">
                                            قم بتفعيل المفتاح بجانب الكاميرا (ليصبح أزرق / مفعلاً)، ثم اضغط زر إعادة المحاولة أدناه!
                                        </li>
                                    </ul>
                                </div>
                                <button type="button" onclick="triggerBrowserPermissionPrompt()" class="btn btn-sm btn-success rounded-pill px-3.5 py-1.5 text-xs fw-bold">
                                    <i class="fas fa-redo me-1"></i> إعادة المحاولة وتشغيل الكاميرا الآن 📹
                                </button>
                            </div>
                            <button type="button" onclick="toggleUnblockInstructions()" class="btn-close btn-close-white text-xs"></button>
                        </div>
                    </div>

                    {{-- Embedded Video Container --}}
                    <div class="flex-grow-1 position-relative bg-black d-flex align-items-center justify-content-center overflow-hidden" id="inapp-video-wrapper" style="min-height: 520px;">
                        {{-- Native Local Camera Stream Video --}}
                        <video id="nativeStudioVideo" autoplay playsinline muted class="w-100 h-100 object-fit-cover position-absolute top-0 start-0 d-none" style="z-index: 5;"></video>

                        {{-- In-App Jitsi Container --}}
                        <div id="classroom-video-container" class="w-100 h-100"></div>

                        {{-- Floating Native Controls Bar --}}
                        <div id="nativeStudioControls" class="position-absolute bottom-0 start-50 translate-middle-x mb-3 d-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-slate-900/90 border border-slate-700/80 shadow-2xl backdrop-blur-md d-none" style="z-index: 10;">
                            <button type="button" onclick="toggleNativeMic()" id="btnNativeMic" class="btn btn-sm btn-dark rounded-circle d-flex align-items-center justify-content-center text-xs" style="width: 36px; height: 36px;" title="كتم / تشغيل الميكروفون">
                                <i class="fas fa-microphone"></i>
                            </button>
                            <button type="button" onclick="toggleNativeCam()" id="btnNativeCam" class="btn btn-sm btn-dark rounded-circle d-flex align-items-center justify-content-center text-xs" style="width: 36px; height: 36px;" title="إيقاف / تشغيل الكاميرا">
                                <i class="fas fa-video"></i>
                            </button>
                            <button type="button" onclick="toggleNativeScreenShare()" id="btnNativeScreen" class="btn btn-sm btn-outline-info rounded-pill px-3 text-xs fw-bold d-flex align-items-center gap-1.5" title="مشاركة الشاشة">
                                <i class="fas fa-desktop"></i>
                                <span>مشاركة الشاشة</span>
                            </button>
                            <button type="button" onclick="toggleClassroomFullscreen()" class="btn btn-sm btn-outline-light rounded-circle d-flex align-items-center justify-content-center text-xs" style="width: 36px; height: 36px;" title="ملء الشاشة">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Bottom Bar with Student Link --}}
                    <div class="px-3 py-2 bg-slate-900 border-top border-slate-800 d-flex flex-wrap align-items-center justify-content-between gap-2 text-xs">
                        <div class="d-flex align-items-center gap-2 text-slate-300">
                            <i class="fas fa-link text-emerald-400"></i>
                            <span>رابط دخول الطلاب للحصة:</span>
                            <code class="text-slate-300 font-mono text-xs px-2 py-0.5 rounded bg-slate-800" id="studentJoinLinkUrl">{{ url()->current() }}</code>
                        </div>
                        <button type="button" onclick="copyStudentLink()" class="btn btn-sm btn-primary rounded-pill px-3 text-xs fw-bold" style="background: var(--primary-color);">
                            <i class="fas fa-copy me-1"></i> <span id="copyStudentLinkText">نسخ الرابط</span>
                        </button>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800" style="min-height: 560px;">
                    <div class="card-body p-4 p-lg-5 d-flex flex-column justify-content-center align-items-center text-center h-100">
                        <div class="w-20 h-20 mx-auto rounded-circle bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 d-flex align-items-center justify-center fs-1 mb-3">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h4 class="fw-black text-slate-900 dark:text-slate-100 font-arabic mb-2">استوديو الحصة الافتراضية المدمج</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-arabic mb-4 max-w-md mx-auto leading-relaxed">
                            اضغط على "بدء الحصة" لتشغيل الكاميرا والميكروفون فوراً داخل هذه الشاشة ومشاركة الشاشة مع طلابك دون مغادرة المنصة.
                        </p>
                        <button type="button" onclick="startClass()" class="btn btn-success btn-lg rounded-pill px-5 py-3 fw-black shadow-lg d-inline-flex align-items-center gap-2">
                            <i class="fas fa-play"></i>
                            <span>بدء الحصة الآن في المنصة 🚀</span>
                        </button>
                    </div>
                </div>
            @endif
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

@push('styles')
<style>
    #classroom-video-container iframe {
        width: 100% !important;
        height: 100% !important;
        min-height: 520px !important;
        border: 0 !important;
        display: block !important;
    }
</style>
@endpush

@push('scripts')
@if($onlineClass->status === 'in_progress')
<script src="https://meet.jit.si/external_api.js"></script>
@endif
@if($joinContext && $onlineClass->status === 'in_progress')
<script src="https://source.zoom.us/2.18.0/zoom-meeting-2.18.0.min.js"></script>
@endif

<script>
let jitsiApiInstance = null;

function initInAppClassroom() {
    const container = document.getElementById('classroom-video-container');
    if (!container || typeof JitsiMeetExternalAPI === 'undefined') return;

    const roomName = 'taalimu_live_{{ $onlineClass->tenant_id }}_{{ $onlineClass->id }}_{{ substr(md5($onlineClass->id . "taalimu_secure_hash"), 0, 8) }}';
    const domain = 'meet.jit.si';

    const options = {
        roomName: roomName,
        width: '100%',
        height: '100%',
        parentNode: container,
        lang: 'ar',
        userInfo: {
            displayName: @json(auth()->user()->name ?? 'المدرس'),
            email: @json(auth()->user()->email ?? '')
        },
        configOverwrite: {
            prejoinPageEnabled: false,
            prejoinConfig: { enabled: false },
            startWithAudioMuted: false,
            startWithVideoMuted: false,
            defaultLanguage: 'ar',
            enableWelcomePage: false,
            enableClosePage: false,
            disableDeepLinking: true,
            disableInviteFunctions: true,
            doNotStoreRoom: true,
            hideConferenceSubject: false,
            toolbarButtons: [
                'camera',
                'microphone',
                'desktop',
                'chat',
                'participants-pane',
                'raisehand',
                'mute-everyone',
                'tileview',
                'toggle-camera',
                'videoquality',
                'whiteboard',
                'fullscreen',
                'settings'
            ]
        },
        interfaceConfigOverwrite: {
            SHOW_JITSI_WATERMARK: false,
            SHOW_WATERMARK_FOR_GUESTS: false,
            SHOW_BRAND_WATERMARK: false,
            SHOW_POWERED_BY: false,
            DEFAULT_BACKGROUND: '#0b0f19',
            DEFAULT_REMOTE_DISPLAY_NAME: 'طالب',
            TOOLBAR_ALWAYS_VISIBLE: true,
            DISABLE_JOIN_LEAVE_NOTIFICATIONS: false,
            MOBILE_APP_PROMO: false,
            HIDE_INVITE_MORE_HEADER: true
        }
    };

    try {
        jitsiApiInstance = new JitsiMeetExternalAPI(domain, options);
    } catch(err) {
        console.error('Failed to init classroom:', err);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    if (typeof JitsiMeetExternalAPI !== 'undefined') {
        initInAppClassroom();
    }
});

window.nativeStream = null;

async function triggerBrowserPermissionPrompt() {
    const btn = document.getElementById('btnRequestPermission');
    const btnText = document.getElementById('permissionBtnText');
    const guideBox = document.getElementById('unblockGuideBox');

    if (btnText) btnText.innerText = 'جاري طلب الإذن من المتصفح...';

    try {
        const stream = await navigator.mediaDevices.getUserMedia({ 
            video: { width: { ideal: 1280 }, height: { ideal: 720 } }, 
            audio: true 
        });

        window.nativeStream = stream;

        if (btn) {
            btn.className = 'btn btn-success btn-sm rounded-pill px-3.5 py-1.5 fw-black text-xs shadow-sm';
            btn.innerHTML = '<i class="fas fa-check-circle me-1"></i> تم السماح وتفعيل الكاميرا ✓';
        }
        if (guideBox) guideBox.classList.add('d-none');

        const videoElem = document.getElementById('nativeStudioVideo');
        const controlsElem = document.getElementById('nativeStudioControls');
        const jitsiContainer = document.getElementById('classroom-video-container');

        if (videoElem) {
            videoElem.srcObject = stream;
            videoElem.classList.remove('d-none');
            videoElem.play();
        }
        if (controlsElem) controlsElem.classList.remove('d-none');
        if (jitsiContainer) jitsiContainer.style.display = 'none';

        if (window.jitsiApiInstance) {
            try { window.jitsiApiInstance.executeCommand('toggleVideo'); } catch(e) {}
        }

    } catch (err) {
        console.error('Permission error:', err);
        
        let errorMsg = '';
        if (err.name === 'NotAllowedError') {
            errorMsg = '⛔ المتصفح حظر الكاميرا لهذا الموقع. يجب فتح إعدادات الموقع من شريط العنوان يدوياً.';
        } else if (err.name === 'NotFoundError') {
            errorMsg = '⚠️ لم يتم العثور على كاميرا أو ميكروفون متصل بالجهاز.';
        } else if (err.name === 'NotReadableError' || err.name === 'AbortError') {
            errorMsg = '⚠️ الكاميرا مستخدمة حالياً بواسطة برنامج آخر. أغلق أي تطبيق يستخدم الكاميرا (مثل Google Meet أو Zoom) ثم أعد المحاولة.';
        } else {
            errorMsg = '❌ خطأ غير متوقع: ' + (err.message || err.name);
        }
        
        if (btnText) btnText.innerText = 'إظهار نافذة الإذن والضغط عليها 📹';
        
        const errorDetails = document.getElementById('permissionErrorDetails');
        if (errorDetails) {
            errorDetails.innerHTML = errorMsg + '<br><a href="chrome://settings/content/camera" target="_blank" class="text-info text-decoration-underline mt-1 d-inline-block" style="font-size: 11px;">أو افتح إعدادات الكاميرا في كروم مباشرة ←</a>';
        }
        
        if (guideBox) {
            guideBox.classList.remove('d-none');
        }
    }
}

// Auto-trigger permission prompt on page load for live classes
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('permissionNoticeBanner')) {
        // Small delay to let the page render first
        setTimeout(function() {
            triggerBrowserPermissionPrompt();
        }, 1500);
    }
});

function toggleUnblockInstructions() {
    const guideBox = document.getElementById('unblockGuideBox');
    if (guideBox) {
        guideBox.classList.toggle('d-none');
    }
}

function toggleNativeMic() {
    if (!window.nativeStream) return;
    const audioTrack = window.nativeStream.getAudioTracks()[0];
    if (audioTrack) {
        audioTrack.enabled = !audioTrack.enabled;
        const btn = document.getElementById('btnNativeMic');
        if (btn) {
            btn.innerHTML = audioTrack.enabled ? '<i class="fas fa-microphone"></i>' : '<i class="fas fa-microphone-slash text-danger"></i>';
            btn.className = audioTrack.enabled ? 'btn btn-sm btn-dark rounded-circle text-xs' : 'btn btn-sm btn-danger rounded-circle text-xs';
        }
    }
}

function toggleNativeCam() {
    if (!window.nativeStream) return;
    const videoTrack = window.nativeStream.getVideoTracks()[0];
    if (videoTrack) {
        videoTrack.enabled = !videoTrack.enabled;
        const btn = document.getElementById('btnNativeCam');
        if (btn) {
            btn.innerHTML = videoTrack.enabled ? '<i class="fas fa-video"></i>' : '<i class="fas fa-video-slash text-danger"></i>';
            btn.className = videoTrack.enabled ? 'btn btn-sm btn-dark rounded-circle text-xs' : 'btn btn-sm btn-danger rounded-circle text-xs';
        }
    }
}

async function toggleNativeScreenShare() {
    try {
        const screenStream = await navigator.mediaDevices.getDisplayMedia({ video: true });
        const videoElem = document.getElementById('nativeStudioVideo');
        if (videoElem) {
            videoElem.srcObject = screenStream;
            videoElem.play();
        }
        screenStream.getVideoTracks()[0].onended = () => {
            if (window.nativeStream && videoElem) {
                videoElem.srcObject = window.nativeStream;
            }
        };
    } catch(err) {
        console.log('Screen share cancelled:', err);
    }
}

function toggleClassroomFullscreen() {
    const elem = document.getElementById('inapp-video-wrapper') || document.getElementById('classroom-video-container');
    if (!elem) return;
    if (!document.fullscreenElement) {
        elem.requestFullscreen().catch(err => console.warn(err));
    } else {
        document.exitFullscreen();
    }
}

function copyStudentLink() {
    const text = document.getElementById('studentJoinLinkUrl')?.innerText || window.location.href;
    navigator.clipboard.writeText(text).then(() => {
        const btnText = document.getElementById('copyStudentLinkText');
        if (btnText) {
            const orig = btnText.innerText;
            btnText.innerText = 'تم النسخ ✓';
            setTimeout(() => btnText.innerText = orig, 2000);
        }
    });
}

function copyMeetingUrl() {
    const input = document.getElementById('liveMeetingUrlInput');
    if (!input) return;
    navigator.clipboard.writeText(input.value).then(() => {
        const text = document.getElementById('copyUrlText');
        if (text) {
            const orig = text.innerText;
            text.innerText = 'تم النسخ ✓';
            setTimeout(() => text.innerText = orig, 2000);
        }
    });
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