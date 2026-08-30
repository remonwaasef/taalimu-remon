@extends('center::layouts.app-next')

@section('panel-content')
<div class="space-y-6" id="qrPresentationContainer">
    <!-- Header Navigation & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('center.attendance.show', $schedule) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-brand-primary transition-colors">
                    <i class="fas fa-arrow-right"></i>
                    <span>{{ __('center::attendance.history') }}</span>
                </a>
                <span class="text-slate-300 dark:text-slate-700">/</span>
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $schedule->course->title }}</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-slate-100 flex items-center gap-2.5 m-0">
                <div class="w-9 h-9 rounded-xl bg-brand-50 dark:bg-brand-900/40 text-brand-primary dark:text-brand-300 flex items-center justify-center text-sm shadow-2xs">
                    <i class="fas fa-qrcode"></i>
                </div>
                <span>{{ __('center::attendance.scan_attendance_code') }}</span>
            </h2>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            <!-- Fullscreen Projector Toggle -->
            <button 
                type="button" 
                id="btnToggleFullscreen"
                onclick="toggleProjectorMode()"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-brand-50 hover:text-brand-primary dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 shadow-2xs transition-all"
            >
                <i class="fas fa-expand text-xs"></i>
                <span id="fullscreenBtnText">وضع العرض للبروجيكتور (شاشة كاملة)</span>
            </button>

            <!-- Manual Refresh Button -->
            <button 
                type="button" 
                onclick="refreshQrCode(true)"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-brand-primary bg-brand-50 hover:bg-brand-100 dark:bg-brand-900/40 dark:text-brand-300 dark:hover:bg-brand-900/60 border border-brand-200 dark:border-brand-800 shadow-2xs transition-all"
                title="تحديث الرمز الآن"
            >
                <i class="fas fa-sync-alt text-xs" id="refreshIcon"></i>
                <span>تحديث الرمز</span>
            </button>

            <!-- Back to Sheet -->
            <a 
                href="{{ route('center.attendance.show', $schedule) }}" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-all"
            >
                <i class="fas fa-clipboard-list text-xs"></i>
                <span>كشف الحضور</span>
            </a>
        </div>
    </div>

    <!-- Main QR Stage Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left Column: The Interactive QR Showcase (7 Cols) -->
        <div class="lg:col-span-7 space-y-4">
            <div class="rounded-3xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs overflow-hidden p-6 sm:p-8 text-center relative">
                
                <!-- Session Details Pill -->
                <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300 mb-6">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <span>{{ $schedule->course->title }}</span>
                    <span>•</span>
                    <span>{{ $schedule->instructor->name ?? __('center::attendance.unassigned_instructor') }}</span>
                    <span>•</span>
                    <span dir="ltr" class="font-mono text-brand-primary dark:text-brand-300 font-bold">
                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                    </span>
                </div>

                <!-- QR Frame Container -->
                <div class="relative mx-auto max-w-xs sm:max-w-sm">
                    <!-- Glow halo background -->
                    <div class="absolute -inset-2 rounded-3xl bg-gradient-to-tr from-brand-primary/20 via-emerald-400/10 to-teal-500/20 blur-xl opacity-70"></div>
                    
                    <div class="relative bg-white dark:bg-slate-950 p-6 rounded-3xl border-2 border-slate-200 dark:border-slate-800 shadow-sm flex flex-col items-center justify-center">
                        <div id="qrcode" class="flex items-center justify-center p-2 bg-white rounded-2xl"></div>

                        <!-- Live Countdown Timer Bar -->
                        <div class="w-full mt-6 space-y-2">
                            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 font-medium">
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-shield-alt text-brand-primary text-[11px]"></i>
                                    <span>رمز أمان ديناميكي مشفر</span>
                                </span>
                                <span class="font-mono font-bold text-slate-700 dark:text-slate-300">
                                    يتجدد بعد: <span id="countdownText" class="text-brand-primary text-sm font-black">60</span>s
                                </span>
                            </div>
                            <!-- Animated Progress Track -->
                            <div class="w-full h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                <div id="countdownBar" class="h-full bg-brand-primary rounded-full transition-all duration-1000 ease-linear" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Help / Instructions -->
                <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-4 text-xs text-slate-500 dark:text-slate-400">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-300 flex items-center justify-center text-[10px]">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <span>افتح كاميرا الهاتف ووجّهها للرمز</span>
                    </div>
                    <span class="hidden sm:inline text-slate-300">•</span>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-300 flex items-center justify-center text-[10px]">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <span>يتم تسجيل الحضور فورياً وتلقائياً</span>
                    </div>
                </div>

                <!-- Copy Link Option -->
                <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-center gap-3">
                    <button 
                        type="button" 
                        onclick="copyQrUrl()" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-brand-primary bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 transition-colors"
                    >
                        <i class="far fa-copy text-xs"></i>
                        <span id="copyLinkText">نسخ رابط الحضور المباشر</span>
                    </button>
                    <span class="text-slate-400 text-xs font-mono" dir="ltr">{{ today()->format('Y-m-d') }}</span>
                </div>
            </div>
        </div>

        <!-- Right Column: Live Attendees Counter & Realtime Feed (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Attendance Progress Card -->
            <div class="rounded-3xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-xs shadow-2xs">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-slate-100 text-sm m-0">إحصائية الحضور اللحظية</h4>
                    </div>
                    <span class="badge bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 px-3 py-1 rounded-full text-xs font-bold font-mono">
                        <span id="percentText">{{ $totalEnrolled > 0 ? round(($attendedCount / $totalEnrolled) * 100) : 0 }}%</span>
                    </span>
                </div>

                <div class="flex items-baseline gap-2 mb-3">
                    <span class="text-3xl font-black text-slate-900 dark:text-slate-100 font-mono" id="attendedCountText">{{ $attendedCount }}</span>
                    <span class="text-sm font-bold text-slate-400">/ <span id="totalEnrolledText">{{ $totalEnrolled }}</span> طالب مسجل</span>
                </div>

                <!-- Progress Bar -->
                <div class="w-full h-2.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden mb-2">
                    <div 
                        id="attendanceProgressBar" 
                        class="h-full bg-gradient-to-r from-brand-primary to-emerald-500 rounded-full transition-all duration-500" 
                        style="width: {{ $totalEnrolled > 0 ? min(100, round(($attendedCount / $totalEnrolled) * 100)) : 0 }}%;"
                    ></div>
                </div>
                <p class="text-[11px] text-slate-400 m-0">يتم التحديث فورياً مع كل عملية مسح لرمز QR</p>
            </div>

            <!-- Real-time Live Scans Feed -->
            <div class="rounded-3xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                        <h4 class="font-bold text-slate-900 dark:text-slate-100 text-sm m-0">سجل المسح المباشر</h4>
                    </div>
                    <span class="text-[11px] text-slate-400">تحديث تلقائي</span>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800 max-h-[380px] overflow-y-auto font-inter" id="attendeesList">
                    @forelse($attendances as $item)
                        <div class="p-4 flex items-center justify-between gap-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors animate-fadeIn">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-xs font-bold shrink-0">
                                    {{ mb_substr($item->student->name ?? 'ط', 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-xs text-slate-800 dark:text-slate-200 truncate">{{ $item->student->name ?? 'طالب' }}</div>
                                    <div class="text-[11px] text-slate-400 font-mono" dir="ltr">{{ $item->check_in_time ? $item->check_in_time->format('h:i A') : '' }}</div>
                                </div>
                            </div>
                            <div class="shrink-0">
                                @if($item->status == 'present')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        {{ __('center::attendance.present') }}
                                    </span>
                                @elseif($item->status == 'late')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                        {{ __('center::attendance.late') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">
                                        {{ __('center::attendance.absent') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-xs text-slate-400 dark:text-slate-500" id="emptyAttendees">
                            <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-2 text-slate-400">
                                <i class="fas fa-qrcode text-base"></i>
                            </div>
                            <span>في انتظار تسجيل أول طالب بالرمز...</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fullscreen Presentation Viewport Styles (Only applied in native fullscreen) -->
<style>
    :fullscreen #qrPresentationContainer,
    :-webkit-full-screen #qrPresentationContainer {
        background-color: #0f172a !important;
        color: #ffffff !important;
        padding: 2.5rem !important;
        height: 100vh !important;
        width: 100vw !important;
        overflow-y: auto !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
    }
    :fullscreen #qrPresentationContainer .bg-white,
    :-webkit-full-screen #qrPresentationContainer .bg-white {
        background-color: #1e293b !important;
        color: #ffffff !important;
        border-color: #334155 !important;
    }
    :fullscreen #qrPresentationContainer .text-slate-900,
    :-webkit-full-screen #qrPresentationContainer .text-slate-900 {
        color: #f8fafc !important;
    }
    :fullscreen #qrPresentationContainer .text-slate-700,
    :-webkit-full-screen #qrPresentationContainer .text-slate-700 {
        color: #cbd5e1 !important;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    let currentQrUrl = @json($url);
    const liveStatusUrl = @json(route('center.attendance.qr.live-status', $schedule));
    let qrcodeInstance = null;
    let countdownSecs = 60;
    let countdownInterval = null;
    let livePollingInterval = null;
    let lastAttendeeCount = {{ $attendedCount }};

    function renderQr(url) {
        const qrContainer = document.getElementById("qrcode");
        qrContainer.innerHTML = "";
        qrcodeInstance = new QRCode(qrContainer, {
            text: url,
            width: 256,
            height: 256,
            colorDark: "#102033",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    }

    function startCountdown() {
        clearInterval(countdownInterval);
        countdownSecs = 60;
        updateCountdownUI();

        countdownInterval = setInterval(() => {
            countdownSecs--;
            if (countdownSecs <= 0) {
                refreshQrCode(false);
            } else {
                updateCountdownUI();
            }
        }, 1000);
    }

    function updateCountdownUI() {
        const textEl = document.getElementById("countdownText");
        const barEl = document.getElementById("countdownBar");
        if (textEl) textEl.textContent = countdownSecs;
        if (barEl) {
            const percent = (countdownSecs / 60) * 100;
            barEl.style.width = percent + "%";
            if (countdownSecs <= 10) {
                barEl.className = "h-full bg-red-500 rounded-full transition-all duration-1000 ease-linear";
            } else {
                barEl.className = "h-full bg-brand-primary rounded-full transition-all duration-1000 ease-linear";
            }
        }
    }

    function refreshQrCode(animate = false) {
        const refreshIcon = document.getElementById("refreshIcon");
        if (animate && refreshIcon) refreshIcon.classList.add("fa-spin");

        fetch(liveStatusUrl)
            .then(res => res.json())
            .then(data => {
                if (data.qr_url) {
                    currentQrUrl = data.qr_url;
                    renderQr(currentQrUrl);
                }
                updateLiveUI(data);
                startCountdown();
            })
            .catch(err => console.error("Error refreshing QR code:", err))
            .finally(() => {
                if (animate && refreshIcon) {
                    setTimeout(() => refreshIcon.classList.remove("fa-spin"), 600);
                }
            });
    }

    function updateLiveUI(data) {
        // Update counts
        const attendedCountEl = document.getElementById("attendedCountText");
        const totalEnrolledEl = document.getElementById("totalEnrolledText");
        const percentTextEl = document.getElementById("percentText");
        const progressBarEl = document.getElementById("attendanceProgressBar");

        if (attendedCountEl) attendedCountEl.textContent = data.attended_count;
        if (totalEnrolledEl) totalEnrolledEl.textContent = data.total_enrolled;

        const percent = data.total_enrolled > 0 ? Math.round((data.attended_count / data.total_enrolled) * 100) : 0;
        if (percentTextEl) percentTextEl.textContent = percent + "%";
        if (progressBarEl) progressBarEl.style.width = Math.min(100, percent) + "%";

        // Check if new attendees scanned
        if (data.attended_count > lastAttendeeCount) {
            playScanNotification();
            lastAttendeeCount = data.attended_count;
        }

        // Update list
        const attendeesList = document.getElementById("attendeesList");
        if (attendeesList && data.attendees && data.attendees.length > 0) {
            let html = "";
            data.attendees.forEach(item => {
                const initial = item.name ? item.name.charAt(0) : "ط";
                const badgeClass = item.status === 'present' 
                    ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border-emerald-200' 
                    : (item.status === 'late' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-red-50 text-red-700 border-red-200');

                html += `
                    <div class="p-4 flex items-center justify-between gap-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-xs font-bold shrink-0">
                                ${initial}
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-xs text-slate-800 dark:text-slate-200 truncate">${item.name}</div>
                                <div class="text-[11px] text-slate-400 font-mono" dir="ltr">${item.time}</div>
                            </div>
                        </div>
                        <div class="shrink-0">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border ${badgeClass}">
                                ${item.status_label}
                            </span>
                        </div>
                    </div>
                `;
            });
            attendeesList.innerHTML = html;
        }
    }

    function playScanNotification() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = "sine";
            osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
            osc.frequency.setValueAtTime(880, ctx.currentTime + 0.1); // A5
            gain.gain.setValueAtTime(0.15, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.3);
        } catch (e) {
            // Audio context not allowed or failed silently
        }
    }

    function copyQrUrl() {
        if (navigator.clipboard && currentQrUrl) {
            navigator.clipboard.writeText(currentQrUrl).then(() => {
                const btnText = document.getElementById("copyLinkText");
                if (btnText) {
                    const oldText = btnText.textContent;
                    btnText.textContent = "تم نسخ الرابط بنجاح! ✓";
                    setTimeout(() => btnText.textContent = oldText, 2500);
                }
            });
        }
    }

    function toggleProjectorMode() {
        const elem = document.getElementById("qrPresentationContainer");
        if (!document.fullscreenElement) {
            if (elem.requestFullscreen) elem.requestFullscreen();
            else if (elem.webkitRequestFullscreen) elem.webkitRequestFullscreen();
            else if (elem.msRequestFullscreen) elem.msRequestFullscreen();
        } else {
            if (document.exitFullscreen) document.exitFullscreen();
        }
    }

    document.addEventListener("fullscreenchange", () => {
        const btnText = document.getElementById("fullscreenBtnText");
        if (document.fullscreenElement) {
            if (btnText) btnText.textContent = "إلغاء وضع ملء الشاشة (ESC)";
        } else {
            if (btnText) btnText.textContent = "وضع العرض للبروجيكتور (شاشة كاملة)";
        }
    });

    document.addEventListener("DOMContentLoaded", () => {
        renderQr(currentQrUrl);
        startCountdown();

        // Poll live attendees every 5 seconds
        livePollingInterval = setInterval(() => {
            fetch(liveStatusUrl)
                .then(res => res.json())
                .then(data => updateLiveUI(data))
                .catch(err => console.error("Polling error:", err));
        }, 5000);
    });

    window.addEventListener("beforeunload", () => {
        clearInterval(countdownInterval);
        clearInterval(livePollingInterval);
    });
</script>
@endsection
