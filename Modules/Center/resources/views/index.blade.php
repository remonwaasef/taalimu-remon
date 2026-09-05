@extends('center::layouts.app-next')

@section('title', __('center::dashboard.title'))

@section('panel-content')
    <!-- Dashboard Header: Welcome Greeting & Executive Actions -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 pb-2">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="text-2xl animate-bounce">👋</span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight leading-tight">
                    {{ __('center::dashboard.welcome_back', ['name' => '']) }}
                    <bdi class="text-brand-primary dark:text-brand-300">{{ auth()->user()->name ?? 'Manager' }}</bdi>
                </h1>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1.5 font-medium flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 font-bold text-slate-700 dark:text-slate-300">
                    <i class="fas fa-school text-brand-primary text-xs"></i>
                    {{ $tenant->name ?? 'Educational Center' }}
                </span>
                <span class="text-slate-300 dark:text-slate-700">•</span>
                <span class="inline-flex items-center gap-1.5 text-slate-500 dark:text-slate-400">
                    <i class="far fa-calendar-alt text-xs text-slate-400"></i>
                    {{ now()->translatedFormat('l، d F Y') }}
                </span>
            </p>
        </div>

        <!-- Quick Primary Actions -->
        <div class="flex items-center gap-2.5 shrink-0">
            <x-ui.button variant="outline" icon="fas fa-qrcode" size="md" href="{{ route('center.attendance.index', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" class="shadow-2xs">
                {{ __('center::dashboard.smart_attendance_btn') }}
            </x-ui.button>
            <x-ui.button variant="primary" icon="fas fa-user-plus" size="md" @click="$dispatch('open-modal', 'quick-student-modal')" class="shadow-sm">
                {{ __('center::dashboard.add_new_student') }}
            </x-ui.button>
        </div>
    </div>

    @if(isset($showLaunchpad) && $showLaunchpad)
        @include('center::partials.launchpad')
    @endif

    <!-- Top Key Metrics Grid (4 High-Value KPI Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- 1. Active Students -->
        <x-ui.stats-card
            title="{{ __('center::dashboard.active_students') }}"
            value="{{ number_format($activeStudents) }}"
            change="+12%"
            changeType="positive"
            changeLabel="{{ __('center::dashboard.enrolled_students_label') }}"
            icon="fas fa-user-graduate"
            iconColor="text-brand-primary dark:text-brand-300 bg-brand-50 dark:bg-brand-900/30"
            accent="brand"
        />

        <!-- 2. Today's Sessions & Active Groups -->
        <x-ui.stats-card
            title="{{ __('center::dashboard.todays_sessions') }}"
            value="{{ number_format($sessionsToday) }}"
            change="{{ number_format($activeGroups) }}"
            changeType="neutral"
            changeLabel="{{ __('center::dashboard.active_study_groups_label') }}"
            icon="fas fa-calendar-check"
            iconColor="text-sky-600 dark:text-sky-300 bg-sky-50 dark:bg-sky-950/50"
            accent="sky"
        />

        <!-- 3. Attendance Rate -->
        <x-ui.stats-card
            title="{{ __('center::dashboard.overview') }} - نسبة الحضور"
            value="{{ $attendanceRate }}%"
            change="{{ $attendanceRate >= 80 ? 'ممتاز' : 'متوسط' }}"
            changeType="{{ $attendanceRate >= 80 ? 'positive' : 'neutral' }}"
            changeLabel="مؤشر حضور هذا الأسبوع"
            icon="fas fa-clipboard-check"
            iconColor="text-emerald-600 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/50"
            accent="emerald"
        />

        <!-- 4. Monthly Revenue & Overdue -->
        <x-ui.stats-card
            title="{{ __('center::dashboard.monthly_revenue') }}"
            value="{{ number_format($monthlyRevenue) }} {{ __('center::dashboard.currency') }}"
            change="{{ $overdueAmount > 0 ? 'متأخرات: ' . number_format($overdueAmount) . ' ' . __('center::dashboard.currency') : '+18%' }}"
            changeType="{{ $overdueAmount > 0 ? 'negative' : 'positive' }}"
            changeLabel="{{ $overdueAmount > 0 ? 'مستحقات معلقة' : __('center::dashboard.vs_last_month') }}"
            icon="fas fa-wallet"
            iconColor="text-amber-600 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/50"
            accent="amber"
        />
    </div>

    <!-- Smart Compact Quick Actions Dock -->
    <div class="mb-8">
        <div class="flex items-center gap-2 mb-3">
            <span class="w-2 h-2 rounded-full bg-brand-primary"></span>
            <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                {{ __('center::dashboard.center_quick_actions') }}
            </h3>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
            <!-- 1. Quick Add Student (Modal) -->
            <button type="button" @click="$dispatch('open-modal', 'quick-student-modal')" class="p-3.5 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-brand-primary/40 dark:hover:border-brand-primary/40 hover:shadow-sm hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-3 text-start group">
                <div class="w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-900/30 text-brand-primary dark:text-brand-300 flex items-center justify-center text-base shrink-0 group-hover:scale-105 transition-transform">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="min-w-0">
                    <span class="block text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-brand-primary dark:group-hover:text-brand-300 transition-colors truncate">
                        {{ __('center::dashboard.add_new_student') }}
                    </span>
                    <span class="block text-[11px] text-slate-400 dark:text-slate-500 truncate">تسجيل فوري لطالب جديد</span>
                </div>
            </button>

            <!-- 2. Quick Create Group (Modal) -->
            <button type="button" @click="$dispatch('open-modal', 'quick-course-modal')" class="p-3.5 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-sky-500/40 dark:hover:border-sky-500/40 hover:shadow-sm hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-3 text-start group">
                <div class="w-10 h-10 rounded-lg bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 flex items-center justify-center text-base shrink-0 group-hover:scale-105 transition-transform">
                    <i class="fas fa-folder-plus"></i>
                </div>
                <div class="min-w-0">
                    <span class="block text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors truncate">
                        {{ __('center::dashboard.create_new_group') }}
                    </span>
                    <span class="block text-[11px] text-slate-400 dark:text-slate-500 truncate">فصل ومجموعة جديدة</span>
                </div>
            </button>

            <!-- 3. Smart Attendance -->
            <a href="{{ route('center.attendance.index', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" class="p-3.5 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-emerald-500/40 dark:hover:border-emerald-500/40 hover:shadow-sm hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-3 text-start group">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-base shrink-0 group-hover:scale-105 transition-transform">
                    <i class="fas fa-qrcode"></i>
                </div>
                <div class="min-w-0">
                    <span class="block text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors truncate">
                        {{ __('center::dashboard.smart_attendance_btn') }}
                    </span>
                    <span class="block text-[11px] text-slate-400 dark:text-slate-500 truncate">مسح الباركود والغياب</span>
                </div>
            </a>

            <!-- 4. Collect Fees -->
            <a href="{{ route('center.sales.index', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" class="p-3.5 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-amber-500/40 dark:hover:border-amber-500/40 hover:shadow-sm hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-3 text-start group">
                <div class="w-10 h-10 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-base shrink-0 group-hover:scale-105 transition-transform">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="min-w-0">
                    <span class="block text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors truncate">
                        {{ __('center::dashboard.collect_fees_btn') }}
                    </span>
                    <span class="block text-[11px] text-slate-400 dark:text-slate-500 truncate">سند قبض وفواتير</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Operational Split Grid: Recent Registrations & Active Study Groups -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- 1. Recent Student Registrations -->
        <x-ui.card noPadding="true" class="border border-slate-200/90 dark:border-slate-800 shadow-xs">
            <x-slot name="header">
                <div class="px-6 py-4.5 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-900/30 text-brand-primary dark:text-brand-300 flex items-center justify-center text-xs">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 leading-tight">
                                {{ __('center::dashboard.recent_student_registrations') }}
                            </h3>
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">
                                {{ __('center::dashboard.recent_registrations_sub') }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('center.students.index', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-primary hover:text-brand-700 dark:text-brand-300 hover:underline transition-colors">
                        <span>{{ __('center::dashboard.view_all') }}</span>
                        <i class="fas fa-arrow-left text-[10px] rtl:rotate-0 ltr:rotate-180"></i>
                    </a>
                </div>
            </x-slot>

            @if(isset($recentStudents) && count($recentStudents) > 0)
                <div class="divide-y divide-slate-100 dark:divide-slate-800/70">
                    @foreach($recentStudents as $student)
                        <div class="px-6 py-3.5 flex items-center justify-between hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <x-ui.avatar :name="$student->name" size="sm" />
                                <div class="min-w-0">
                                    <span class="block text-xs font-bold text-slate-900 dark:text-slate-100 truncate">
                                        {{ $student->name }}
                                    </span>
                                    <div class="flex items-center gap-2 text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">
                                        <span class="font-mono">{{ $student->phone ?? '—' }}</span>
                                        <span>•</span>
                                        <span>{{ $student->created_at ? $student->created_at->diffForHumans() : '—' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                @if(!empty($student->phone))
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $student->phone);
                                        if(str_starts_with($cleanPhone, '01')) {
                                            $cleanPhone = '2' . $cleanPhone;
                                        }
                                    @endphp
                                    <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" title="مراسلة عبر واتساب" class="w-7 h-7 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400 dark:hover:bg-emerald-900/50 transition-colors flex items-center justify-center text-xs shadow-2xs">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                @endif
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                    مسجل
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <x-ui.empty-state
                    title="{{ __('center::dashboard.no_recent_students') }}"
                    description="{{ __('center::dashboard.no_recent_students_desc') }}"
                    icon="fas fa-user-graduate"
                    size="sm"
                >
                    <x-slot name="action">
                        <x-ui.button variant="primary" size="sm" icon="fas fa-user-plus" @click="$dispatch('open-modal', 'quick-student-modal')">
                            {{ __('center::dashboard.add_new_student') }}
                        </x-ui.button>
                    </x-slot>
                </x-ui.empty-state>
            @endif
        </x-ui.card>

        <!-- 2. Active Center Groups -->
        <x-ui.card noPadding="true" class="border border-slate-200/90 dark:border-slate-800 shadow-xs">
            <x-slot name="header">
                <div class="px-6 py-4.5 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 flex items-center justify-center text-xs">
                            <i class="fas fa-chalkboard"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 leading-tight">
                                {{ __('center::dashboard.active_study_groups') }}
                            </h3>
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">
                                {{ __('center::dashboard.active_study_groups_sub') }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('center.courses.index', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-primary hover:text-brand-700 dark:text-brand-300 hover:underline transition-colors">
                        <span>{{ __('center::dashboard.view_all') }}</span>
                        <i class="fas fa-arrow-left text-[10px] rtl:rotate-0 ltr:rotate-180"></i>
                    </a>
                </div>
            </x-slot>

            @if(isset($recentGroups) && count($recentGroups) > 0)
                <div class="divide-y divide-slate-100 dark:divide-slate-800/70">
                    @foreach($recentGroups as $group)
                        <div class="px-6 py-3.5 flex items-center justify-between hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                            <div class="min-w-0 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 flex items-center justify-center text-xs shrink-0 font-bold">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <div class="min-w-0">
                                    <span class="block text-xs font-bold text-slate-900 dark:text-slate-100 truncate">
                                        {{ $group->title ?? 'Untitled Group' }}
                                    </span>
                                    <span class="block text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 truncate">
                                        <i class="fas fa-chalkboard-teacher text-[10px] me-1"></i>
                                        {{ $group->instructor->name ?? 'Staff' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-primary dark:text-brand-300 bg-brand-50 dark:bg-brand-900/30 px-2.5 py-1 rounded-full border border-brand-200/50 dark:border-brand-800/40">
                                    <i class="fas fa-users text-[10px]"></i>
                                    <span>{{ $group->students_count ?? 0 }} {{ __('center::dashboard.enrolled_badge') }}</span>
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <x-ui.empty-state
                    title="{{ __('center::dashboard.no_groups_configured') }}"
                    description="{{ __('center::dashboard.no_groups_configured_desc') }}"
                    icon="fas fa-users"
                    size="sm"
                >
                    <x-slot name="action">
                        <x-ui.button variant="primary" size="sm" icon="fas fa-plus" @click="$dispatch('open-modal', 'quick-course-modal')">
                            {{ __('center::dashboard.create_group_action') }}
                        </x-ui.button>
                    </x-slot>
                </x-ui.empty-state>
            @endif
        </x-ui.card>
    </div>

    {{-- Quick Action Modals for Instant Setup --}}
    @include('center::partials._quick-instructor-modal')
    @include('center::partials._quick-course-modal')
    @include('center::partials._quick-student-modal')
@endsection
