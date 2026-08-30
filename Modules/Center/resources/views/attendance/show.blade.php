@extends('center::layouts.app-next')

@section('panel-content')
    <div class="mb-4">
        <h2 class="fw-bold text-dark">{{ __('center::attendance.sheet_title', ['title' => $schedule->course->title]) }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('center.attendance.index') }}">{{ __('center::attendance.history') }}</a></li>
                <li class="breadcrumb-item active">{{ __('center::attendance.details') }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border border-slate-200/90 dark:border-slate-800 shadow-xs rounded-3xl bg-white dark:bg-slate-900 overflow-hidden">
                <div class="card-header bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 p-5 sm:p-6 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="fw-bold mb-1 text-slate-900 dark:text-slate-100 flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-primary dark:bg-brand-900/40 dark:text-brand-300 flex items-center justify-center text-xs">
                                <i class="fas fa-users"></i>
                            </div>
                            {{ __('center::attendance.enrolled_list') }}
                        </h5>
                        <p class="text-muted small mb-0 flex items-center gap-2 mt-1">
                            <span class="inline-flex items-center gap-1 font-semibold" dir="ltr">
                                <i class="far fa-clock text-slate-400"></i>
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}
                            </span>
                            <span>•</span>
                            <span>{{ __('center::attendance.classroom_label') }} {{ $schedule->classroom->name ?? __('center::schedules.classroom') }}</span>
                        </p>
                    </div>
                    <div class="text-end d-flex align-items-center gap-2 flex-wrap">
                        <!-- Scan Button -->
                        <button type="button" class="btn btn-primary rounded-xl px-4 py-2 text-xs font-bold shadow-xs" onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'scanQrModal' }))">
                            <i class="fas fa-qrcode me-1.5"></i> {{ __('center::attendance.scan_qr_btn') }}
                        </button>

                        @php
                            $tz = config('app.timezone', 'Africa/Cairo');
                            $isEnded = now($tz)->gte(\Carbon\Carbon::parse(today($tz)->format('Y-m-d') . ' ' . $schedule->end_time, $tz));
                            $hasUnrecorded = $schedule->course->enrollments->count() > $attendances->count();
                        @endphp
                        @if($isEnded && $hasUnrecorded)
                            <form action="{{ route('center.attendance.bulkAbsent', $schedule) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger rounded-xl px-4 py-2 text-xs font-bold">
                                    <i class="fas fa-user-times me-1.5"></i> {{ __('center::attendance.mark_all_absent') }}
                                </button>
                            </form>
                        @endif
                        <span class="badge bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700 px-3 py-2 rounded-xl text-xs font-mono font-semibold" dir="ltr">
                            {{ today()->format('Y-m-d') }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" data-mobile-cards>
                        <table class="table align-middle custom-table mb-0">
                            <thead class="bg-slate-50/95 dark:bg-slate-800/90 border-b border-slate-200 dark:border-slate-700 text-[11px] uppercase font-bold text-slate-600 dark:text-slate-300 tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5 text-start">{{ __('center::attendance.student_name') }}</th>
                                    <th class="px-6 py-3.5 text-start">{{ __('center::attendance.student_code') }}</th>
                                    <th class="px-6 py-3.5 text-center">{{ __('center::attendance.status') }}</th>
                                    <th class="px-6 py-3.5 text-center">{{ __('center::attendance.record_attendance') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 font-inter">
                                @forelse($schedule->course->enrollments as $enrollment)
                                    @php
                                        $student = $enrollment->user->student ?? null;
                                        $attendance = $student ? $attendances->get($student->id) : null;
                                    @endphp
                                    @if($student)
                                    @php
                                        $isAdmin = auth()->user()->hasRole('center_admin');
                                        $lockPresent = ($attendance && !$isAdmin) || ($isEnded && !$isAdmin && (!$attendance || $attendance->status !== 'present'));
                                        $lockLate = ($attendance && !$isAdmin) || ($isEnded && !$isAdmin && (!$attendance || $attendance->status !== 'late'));
                                        $lockAbsent = ($attendance && !$isAdmin) || ($isEnded && !$isAdmin && (!$attendance || $attendance->status !== 'absent'));
                                    @endphp
                                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="fw-bold text-slate-900 dark:text-slate-100 text-sm mb-0.5">{{ $student->name }}</div>
                                            <small class="text-slate-400 text-xs">{{ $enrollment->user->email }}</small>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-slate-100 dark:bg-slate-800 text-brand-primary dark:text-brand-300 border border-slate-200 dark:border-slate-700">
                                                #{{ $student->id }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($attendance)
                                                <span class="inline-flex flex-column align-items-center gap-0.5 px-3 py-1 rounded-full text-xs font-bold {{ $attendance->status == 'present' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : ($attendance->status == 'late' ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-200 dark:border-amber-800' : 'bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-300 border border-red-200 dark:border-red-800') }}">
                                                    <span>
                                                        @if($attendance->status == 'late')
                                                            {{ __('center::attendance.late') }} ({{ $attendance->late_minutes }} {{ __('center::attendance.minutes') }})
                                                        @else
                                                            {{ $attendance->status == 'present' ? __('center::attendance.present') : __('center::attendance.absent') }}
                                                        @endif
                                                    </span>
                                                    <span class="text-slate-400 font-mono" style="font-size: 0.65rem;" dir="ltr">{{ $attendance->check_in_time->format('h:i A') }}</span>
                                                </span>
                                            @elseif($isEnded)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-300 border border-red-200 dark:border-red-800">{{ __('center::attendance.absent') }}</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs text-slate-400 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">{{ __('center::attendance.not_recorded') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="d-inline-flex justify-content-center gap-1.5 flex-wrap">
                                                <form action="{{ route('center.attendance.store') }}" method="POST" class="d-inline m-0">
                                                    @csrf
                                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                    <input type="hidden" name="course_id" value="{{ $schedule->course_id }}">
                                                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                                    <input type="hidden" name="session_date" value="{{ today()->format('Y-m-d') }}">
                                                    <input type="hidden" name="status" value="present">
                                                    <button type="submit" 
                                                            class="inline-flex items-center justify-center px-3 py-1.5 rounded-xl text-xs font-bold transition-all shadow-2xs {{ $attendance && $attendance->status == 'present' ? 'bg-emerald-600 text-white border border-emerald-600 shadow-xs' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white border border-emerald-200 hover:border-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800 dark:hover:bg-emerald-600 dark:hover:text-white' }}" 
                                                            {{ $lockPresent ? 'disabled' : '' }}>
                                                        <i class="fas fa-check me-1"></i> {{ __('center::attendance.present') }}
                                                    </button>
                                                </form>
                                                
                                                <!-- Smart Late Button (Triggers Modal) -->
                                                <button type="button" 
                                                        class="inline-flex items-center justify-center px-3 py-1.5 rounded-xl text-xs font-bold transition-all shadow-2xs {{ $attendance && $attendance->status == 'late' ? 'bg-amber-500 text-white border border-amber-500 shadow-xs' : 'bg-amber-50 text-amber-700 hover:bg-amber-500 hover:text-white border border-amber-200 hover:border-amber-500 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800 dark:hover:bg-amber-500 dark:hover:text-white' }}" 
                                                        onclick="openLateModal({{ $student->id }}, '{{ $schedule->course_id }}', '{{ $schedule->id }}', '{{ e($student->name) }}')" 
                                                        {{ $lockLate ? 'disabled' : '' }}>
                                                    <i class="fas fa-clock me-1"></i> {{ __('center::attendance.late') }}
                                                </button>

                                                <form action="{{ route('center.attendance.store') }}" method="POST" class="d-inline m-0">
                                                    @csrf
                                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                    <input type="hidden" name="course_id" value="{{ $schedule->course_id }}">
                                                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                                    <input type="hidden" name="session_date" value="{{ today()->format('Y-m-d') }}">
                                                    <input type="hidden" name="status" value="absent">
                                                    <button type="submit" 
                                                            class="inline-flex items-center justify-center px-3 py-1.5 rounded-xl text-xs font-bold transition-all shadow-2xs {{ $attendance && $attendance->status == 'absent' ? 'bg-red-600 text-white border border-red-600 shadow-xs' : 'bg-red-50 text-red-700 hover:bg-red-600 hover:text-white border border-red-200 hover:border-red-600 dark:bg-red-950/40 dark:text-red-300 dark:border-red-800 dark:hover:bg-red-600 dark:hover:text-white' }}" 
                                                            {{ $lockAbsent ? 'disabled' : '' }}>
                                                        <i class="fas fa-times me-1"></i> {{ __('center::attendance.absent') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">{{ __('center::attendance.no_students_enrolled') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Smart Late Modal -->
<x-ui.modal id="lateModal" title="تسجيل تأخير" size="sm">
    <form action="{{ route('center.attendance.store') }}" method="POST">
        @csrf
        <div class="text-center">
            <p class="text-slate-500 text-sm mb-3">طالب: <strong id="lateModalStudentName" class="text-slate-900 dark:text-white"></strong></p>
            
            <input type="hidden" name="student_id" id="lateModalStudentId">
            <input type="hidden" name="course_id" id="lateModalCourseId">
            <input type="hidden" name="schedule_id" id="lateModalScheduleId">
            <input type="hidden" name="session_date" value="{{ today()->format('Y-m-d') }}">
            <input type="hidden" name="status" value="late">
            
            <label class="block font-semibold text-sm mb-2 text-start">كم دقيقة تأخير؟</label>
            <div class="flex gap-2 mb-2">
                <input type="number" name="late_minutes" id="lateModalMinutes" class="flex-1 px-4 py-3 border border-brand-border dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-center font-bold text-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" required min="1" value="">
                <span class="px-4 py-3 bg-slate-100 dark:bg-slate-800 border border-brand-border dark:border-slate-700 rounded-xl text-slate-500 flex items-center">دقيقة</span>
            </div>
            <small class="text-green-600 dark:text-green-400 block mb-4 text-xs"><i class="bi bi-robot"></i> تم الحساب آلياً بناءً على وقت الحصة</small>
        </div>
        <div class="flex items-center justify-center gap-3">
            <button type="button" class="px-5 py-2 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors" onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'lateModal' }))">إلغاء</button>
            <button type="submit" class="px-5 py-2 rounded-full bg-amber-500 hover:bg-amber-600 text-white font-semibold transition-colors">حفظ التأخير</button>
        </div>
    </form>
</x-ui.modal>

<!-- Scan QR Modal -->
<x-ui.modal id="scanQrModal" title="{{ __('center::attendance.scan_qr_modal_title') }}">
    <div class="text-center">
        <div id="reader" style="width: 100%; min-height: 300px;" class="bg-slate-900 rounded-xl overflow-hidden mx-auto"></div>
        <div id="scan-result" class="hidden absolute bottom-0 start-0 w-full p-3 bg-white/95 text-slate-900 font-bold">{{ __('center::attendance.verifying') }}</div>
    </div>
    <x-slot name="footer">
        <small class="text-slate-500 w-full text-center">{{ __('center::attendance.facing_camera_hint') }}</small>
    </x-slot>
</x-ui.modal>

@feature('offline_attendance')
<div id="offlineStatusBar" class="position-fixed bottom-0 start-0 w-100 d-none" style="z-index: 9999;">
    <div class="d-flex align-items-center justify-content-between px-4 py-2" id="offlineBarInner">
        <div class="d-flex align-items-center">
            <span id="offlineIcon" class="me-2"></span>
            <span id="offlineText" class="fw-bold small"></span>
        </div>
        <div id="offlinePending" class="small"></div>
    </div>
</div>
@endfeature

@feature('offline_attendance')
<style>
    #offlineStatusBar .bar-offline {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }
    #offlineStatusBar .bar-syncing {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        color: #333;
    }
    #offlineStatusBar .bar-online {
        background: linear-gradient(135deg, #28a745, #218838);
        color: white;
    }
    .offline-saved-badge {
        display: inline-block;
        font-size: 0.65rem;
        padding: 2px 8px;
        border-radius: 20px;
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffc107;
        animation: pulse-badge 1.5s infinite;
    }
    @keyframes pulse-badge {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }
</style>
@endfeature

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
@include('center::attendance.partials._show-scripts')

<style>
    @keyframes slideDown { from { transform: translateX(-50%) translateY(-100%); opacity: 0; } to { transform: translateX(-50%) translateY(0); opacity: 1; } }
    @keyframes slideUp { from { transform: translateX(-50%) translateY(0); opacity: 1; } to { transform: translateX(-50%) translateY(-100%); opacity: 0; } }
    .spin-icon { animation: spin 1s linear infinite; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>
@endpush
@endsection
