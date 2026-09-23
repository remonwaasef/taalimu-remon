@extends('layouts.app-next')

@section('title', __('instructor::dashboard.title') ?? 'Instructor Dashboard')

@section('sidebar')
    @include('instructor::partials._sidebar-next', ['active' => 'dashboard'])
@endsection

@section('content')
    <x-ui.page-header
        title="{{ __('instructor::dashboard.greeting', ['name' => auth()->user()->name ?? '']) }}"
        subtitle="{{ __('instructor::dashboard.greeting_subtitle') }}"
    >
        <x-slot name="actions">
            <x-ui.button variant="primary" icon="fas fa-plus" size="md" href="{{ route('instructor.students.create') }}">
                {{ __('instructor::dashboard.add_new_student_btn') }}
            </x-ui.button>
        </x-slot>
    </x-ui.page-header>

    <!-- Setup Progress Banner (Shown only when setup < 100%) -->
    @if($setupProgress < 100)
        @if($setupProgress == 0)
            {{-- ========== FULL ONBOARDING BANNER (New User - 0%) ========== --}}
            <div class="mb-8 rounded-3xl overflow-hidden border border-emerald-200 dark:border-emerald-800/50 shadow-lg bg-gradient-to-br from-emerald-50 via-white to-teal-50 dark:from-slate-800 dark:via-slate-900 dark:to-emerald-950/30">
                <div class="p-6 lg:p-8">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        {{-- Left: Title & Progress --}}
                        <div class="flex-1">
                            <h2 class="text-xl font-extrabold text-slate-800 dark:text-slate-100 mb-2 font-arabic">
                                {{ __('instructor::sidebar.setup_banner_title') }}
                            </h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4 font-arabic">
                                {{ __('instructor::sidebar.setup_banner_subtitle') }}
                            </p>
                            {{-- Progress Bar --}}
                            <div class="flex items-center gap-3 mb-2">
                                <div class="flex-1 h-2.5 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full transition-all duration-700" style="width: {{ $setupProgress }}%"></div>
                                </div>
                                <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400 whitespace-nowrap">{{ $setupProgress }}%</span>
                            </div>
                            <p class="text-xs text-slate-400 font-arabic">{{ __('instructor::sidebar.setup_banner_progress', ['completed' => $completedSteps ?? 0, 'total' => 4]) }}</p>
                        </div>
                        {{-- Right: CTA Button --}}
                        <div class="flex-shrink-0">
                            <a href="{{ route('instructor.settings') }}"
                               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                                <i class="fas fa-cog"></i>
                                {{ __('instructor::sidebar.setup_go_to_settings') }}
                            </a>
                        </div>
                    </div>

                    {{-- Step Cards Grid --}}
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mt-6">
                        <button type="button" onclick="openTeachingSystemModal()"
                                class="group p-4 rounded-2xl border-2 transition-all duration-200 text-start cursor-pointer
                                       {{ $hasTeachingSystem ? 'border-emerald-300 bg-emerald-50/50 dark:bg-emerald-950/20 dark:border-emerald-700' : 'border-slate-200 bg-white hover:border-emerald-400 hover:shadow-md dark:bg-slate-800 dark:border-slate-700 dark:hover:border-emerald-600' }}">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $hasTeachingSystem ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400 group-hover:bg-emerald-100 group-hover:text-emerald-600 dark:bg-slate-700' }}">
                                    <i class="{{ $hasTeachingSystem ? 'fas fa-check' : 'fas fa-chalkboard-teacher' }} text-sm"></i>
                                </div>
                                <span class="text-[10px] font-bold {{ $hasTeachingSystem ? 'text-emerald-600' : 'text-slate-400' }}">1/4</span>
                            </div>
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 font-arabic">{{ __('instructor::dashboard.teaching_system_step') }}</h4>
                            <p class="text-[10px] text-slate-400 mt-0.5 font-arabic">{{ $hasTeachingSystem ? '✅ ' . __('instructor::sidebar.status_configured') : __('instructor::sidebar.status_needs_setup') }}</p>
                        </button>

                        <button type="button" onclick="openMeetingLinkModal()"
                                class="group p-4 rounded-2xl border-2 transition-all duration-200 text-start cursor-pointer
                                       {{ $hasLiveStream ? 'border-emerald-300 bg-emerald-50/50 dark:bg-emerald-950/20 dark:border-emerald-700' : 'border-slate-200 bg-white hover:border-emerald-400 hover:shadow-md dark:bg-slate-800 dark:border-slate-700 dark:hover:border-emerald-600' }}">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $hasLiveStream ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400 group-hover:bg-emerald-100 group-hover:text-emerald-600 dark:bg-slate-700' }}">
                                    <i class="{{ $hasLiveStream ? 'fas fa-check' : 'fas fa-video' }} text-sm"></i>
                                </div>
                                <span class="text-[10px] font-bold {{ $hasLiveStream ? 'text-emerald-600' : 'text-slate-400' }}">2/4</span>
                            </div>
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 font-arabic">{{ __('instructor::dashboard.live_stream_step') }}</h4>
                            <p class="text-[10px] text-slate-400 mt-0.5 font-arabic">{{ $hasLiveStream ? '✅ ' . __('instructor::sidebar.status_configured') : __('instructor::sidebar.status_needs_setup') }}</p>
                        </button>

                        <a href="{{ route('instructor.groups.create') }}"
                           class="group p-4 rounded-2xl border-2 transition-all duration-200 text-start
                                  {{ $hasGroup ? 'border-emerald-300 bg-emerald-50/50 dark:bg-emerald-950/20 dark:border-emerald-700' : 'border-slate-200 bg-white hover:border-emerald-400 hover:shadow-md dark:bg-slate-800 dark:border-slate-700 dark:hover:border-emerald-600' }}">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $hasGroup ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400 group-hover:bg-emerald-100 group-hover:text-emerald-600 dark:bg-slate-700' }}">
                                    <i class="{{ $hasGroup ? 'fas fa-check' : 'fas fa-users' }} text-sm"></i>
                                </div>
                                <span class="text-[10px] font-bold {{ $hasGroup ? 'text-emerald-600' : 'text-slate-400' }}">3/4</span>
                            </div>
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 font-arabic">{{ __('instructor::dashboard.group_creation') }}</h4>
                            <p class="text-[10px] text-slate-400 mt-0.5 font-arabic">{{ $hasGroup ? '✅ ' . __('instructor::sidebar.status_configured') : __('instructor::sidebar.status_needs_setup') }}</p>
                        </a>

                        <button type="button" onclick="openShareLinkModal()"
                                class="group p-4 rounded-2xl border-2 transition-all duration-200 text-start cursor-pointer
                                       {{ $hasStudents ? 'border-emerald-300 bg-emerald-50/50 dark:bg-emerald-950/20 dark:border-emerald-700' : 'border-slate-200 bg-white hover:border-emerald-400 hover:shadow-md dark:bg-slate-800 dark:border-slate-700 dark:hover:border-emerald-600' }}">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $hasStudents ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400 group-hover:bg-emerald-100 group-hover:text-emerald-600 dark:bg-slate-700' }}">
                                    <i class="{{ $hasStudents ? 'fas fa-check' : 'fas fa-share-alt' }} text-sm"></i>
                                </div>
                                <span class="text-[10px] font-bold {{ $hasStudents ? 'text-emerald-600' : 'text-slate-400' }}">4/4</span>
                            </div>
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 font-arabic">{{ __('instructor::dashboard.add_students_step') }}</h4>
                            <p class="text-[10px] text-slate-400 mt-0.5 font-arabic">{{ $hasStudents ? '✅ ' . __('instructor::sidebar.status_configured') : __('instructor::sidebar.status_needs_setup') }}</p>
                        </button>
                    </div>
                </div>
            </div>
        @else
            {{-- ========== COMPACT PROGRESS BANNER (Partial progress > 0%) ========== --}}
            <div class="mb-6 px-5 py-4 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-slate-800 dark:to-emerald-950/20 rounded-2xl border border-emerald-200 dark:border-emerald-800/50 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4 flex-1">
                    {{-- Circular Progress --}}
                    <div class="relative flex-shrink-0">
                        <svg class="w-14 h-14" viewBox="0 0 36 36">
                            <path stroke="#E7EAF3" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path stroke="#2E8B83" stroke-width="3" stroke-dasharray="{{ $setupProgress }}, 100" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <span class="absolute inset-0 flex items-center justify-center text-sm font-extrabold text-emerald-700 dark:text-emerald-400">{{ $setupProgress }}%</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 font-arabic">{{ __('instructor::sidebar.setup_banner_remaining', ['count' => 4 - ($completedSteps ?? 0)]) }}</h3>
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            @if(!$hasTeachingSystem)
                                <button type="button" onclick="openTeachingSystemModal()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-medium bg-white border border-slate-200 text-slate-600 hover:border-emerald-400 hover:text-emerald-700 transition-all cursor-pointer dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300">
                                    <i class="far fa-circle text-[8px] text-slate-400"></i> {{ __('instructor::dashboard.teaching_system_step') }}
                                </button>
                            @endif
                            @if(!$hasLiveStream)
                                <button type="button" onclick="openMeetingLinkModal()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-medium bg-white border border-slate-200 text-slate-600 hover:border-emerald-400 hover:text-emerald-700 transition-all cursor-pointer dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300">
                                    <i class="far fa-circle text-[8px] text-slate-400"></i> {{ __('instructor::dashboard.live_stream_step') }}
                                </button>
                            @endif
                            @if(!$hasGroup)
                                <a href="{{ route('instructor.groups.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-medium bg-white border border-slate-200 text-slate-600 hover:border-emerald-400 hover:text-emerald-700 transition-all dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300">
                                    <i class="far fa-circle text-[8px] text-slate-400"></i> {{ __('instructor::dashboard.group_creation') }}
                                </a>
                            @endif
                            @if(!$hasStudents)
                                <button type="button" onclick="openShareLinkModal()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-medium bg-white border border-slate-200 text-slate-600 hover:border-emerald-400 hover:text-emerald-700 transition-all cursor-pointer dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300">
                                    <i class="far fa-circle text-[8px] text-slate-400"></i> {{ __('instructor::dashboard.add_students_step') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                <a href="{{ route('instructor.settings') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all flex-shrink-0">
                    <i class="fas fa-cog"></i>
                    {{ __('instructor::sidebar.setup_go_to_settings') }}
                </a>
            </div>
        @endif
    @endif

    <!-- Top Key Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 motion-stagger">
        <x-ui.stats-card
            title="{{ __('instructor::dashboard.total_students') }}"
            value="{{ number_format($totalStudents) }}"
            change="{{ $totalStudents > 0 ? '+'.$totalStudents : '0' }}"
            changeType="{{ $totalStudents > 0 ? 'positive' : 'neutral' }}"
            changeLabel="{{ __('instructor::dashboard.enrolled_students') }}"
            icon="fas fa-user-graduate"
            iconColor="text-brand-primary bg-brand-50"
        />

        <x-ui.stats-card
            title="{{ __('instructor::dashboard.active_groups') }}"
            value="{{ number_format($totalCourses) }}"
            change="{{ $totalCourses > 0 ? $totalCourses : '0' }}"
            changeType="{{ $totalCourses > 0 ? 'positive' : 'neutral' }}"
            changeLabel="{{ __('instructor::dashboard.running_groups') }}"
            icon="fas fa-users"
            iconColor="text-emerald-600 bg-emerald-50"
        />

        <x-ui.stats-card
            title="{{ __('instructor::dashboard.monthly_revenue') }}"
            value="{{ number_format($monthlyRevenue) }} {{ app()->getLocale() === 'ar' ? 'ج.م' : (app('tenant')->settings['currency'] ?? 'EGP') }}"
            change="{{ $monthlyRevenue > 0 ? __('instructor::dashboard.active_badge') : '0' }}"
            changeType="{{ $monthlyRevenue > 0 ? 'positive' : 'neutral' }}"
            changeLabel="{{ __('instructor::dashboard.this_month') }}"
            icon="fas fa-wallet"
            iconColor="text-amber-600 bg-amber-50"
        />

        <x-ui.stats-card
            title="{{ __('instructor::dashboard.attendance_rate') }}"
            value="{{ $totalAttendanceCount > 0 ? $attendanceRate.'%' : '0%' }}"
            change="{{ $totalAttendanceCount > 0 ? $totalAttendanceCount : '0' }}"
            changeType="{{ $totalAttendanceCount > 0 ? 'positive' : 'neutral' }}"
            changeLabel="{{ __('instructor::dashboard.recorded_logs') }}"
            icon="fas fa-chart-pie"
            iconColor="text-sky-600 bg-sky-50"
        />
    </div>

    <!-- Middle Split Grid: Today's Schedule | Quick Actions | Setup Progress -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
        <!-- Today's Schedule (5 cols) -->
        <div class="lg:col-span-5">
            <x-ui.card title="{{ __('instructor::dashboard.todays_schedule') }}" subtitle="{{ __('instructor::dashboard.todays_schedule_sub') }}">
                <x-slot name="action">
                    <a href="{{ route('instructor.schedules.index') }}" class="text-xs font-semibold text-brand-primary hover:underline">{{ __('instructor::dashboard.view_all') }}</a>
                </x-slot>

                <div class="space-y-3">
                    @forelse($todaySchedules as $sched)
                        <div class="p-3.5 rounded-xl bg-white dark:bg-slate-900 border border-brand-border dark:border-slate-800 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="text-center px-2.5 py-1 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                                    <span class="block text-xs font-bold text-brand-primary">{{ $sched->start_time ? \Carbon\Carbon::parse($sched->start_time)->format('H:i') : '--:--' }}</span>
                                    <span class="block text-[11px] text-slate-400">{{ $sched->end_time ? \Carbon\Carbon::parse($sched->end_time)->format('H:i') : '--:--' }}</span>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $sched->course->title ?? __('instructor::dashboard.group') }}</h4>
                                    <p class="text-xs text-slate-400">{{ $sched->room ?? '' }}</p>
                                </div>
                            </div>
                            <x-ui.badge variant="success" size="sm">{{ __('instructor::dashboard.active_badge') }}</x-ui.badge>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400 text-xs font-medium">
                            <i class="far fa-calendar-times text-3xl block mb-2 opacity-40"></i>
                            <span>{{ __('instructor::dashboard.no_sessions_today') }}</span>
                        </div>
                    @endforelse
                </div>
            </x-ui.card>
        </div>

        <!-- Quick Actions (4 cols) -->
        <div class="lg:col-span-4">
            <x-ui.card title="{{ __('instructor::dashboard.quick_links') }}" subtitle="{{ __('instructor::dashboard.frequently_used') }}">
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('instructor.students.create') }}" class="p-4 rounded-xl border border-brand-border dark:border-slate-800 hover:border-brand-primary/40 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-all text-center flex flex-col items-center justify-center group">
                        <div class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-900/30 text-brand-primary flex items-center justify-center text-base mb-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-tight">{{ __('instructor::dashboard.add_new_student') }}</span>
                    </a>

                    <a href="{{ route('instructor.groups.create') }}" class="p-4 rounded-xl border border-brand-border dark:border-slate-800 hover:border-emerald-500/40 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-all text-center flex flex-col items-center justify-center group">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 flex items-center justify-center text-base mb-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-folder-plus"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-tight">{{ __('instructor::dashboard.create_new_group') }}</span>
                    </a>

                    <a href="{{ route('instructor.attendance.index') }}" class="p-4 rounded-xl border border-brand-border dark:border-slate-800 hover:border-sky-500/40 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-all text-center flex flex-col items-center justify-center group">
                        <div class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-950/30 text-sky-600 flex items-center justify-center text-base mb-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-tight">{{ __('instructor::dashboard.smart_attendance') }}</span>
                    </a>

                    <a href="{{ route('instructor.settings') }}" class="p-4 rounded-xl border border-brand-border dark:border-slate-800 hover:border-amber-500/40 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-all text-center flex flex-col items-center justify-center group relative">
                        @if($setupProgress < 100)
                            <span class="absolute top-2 end-2 w-2.5 h-2.5 bg-amber-500 rounded-full animate-pulse"></span>
                        @endif
                        <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 flex items-center justify-center text-base mb-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-cog"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-tight">{{ __('instructor::sidebar.settings') }}</span>
                    </a>
                </div>
            </x-ui.card>
        </div>

        <!-- Setup Progress (3 cols) -->
        <div class="lg:col-span-3">
            <x-ui.card title="{{ __('instructor::dashboard.setup_progress') }}" subtitle="{{ __('instructor::dashboard.completion_indicator') }}">
                <div class="text-center my-2">
                    <div class="inline-flex items-center justify-center relative">
                        <svg class="w-24 h-24" viewBox="0 0 36 36">
                            <path stroke="#E7EAF3" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path stroke="#2E8B83" stroke-width="3" stroke-dasharray="{{ $setupProgress }}, 100" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <span class="absolute text-lg font-extrabold text-slate-800 dark:text-slate-100">{{ $setupProgress }}%</span>
                    </div>
                </div>
                <div class="space-y-2 text-xs pt-1">
                    <div class="flex items-center justify-between {{ $hasTeachingSystem ? 'text-emerald-600 font-semibold' : 'text-slate-500' }}">
                        <div class="flex items-center gap-2">
                            <i class="{{ $hasTeachingSystem ? 'fas fa-check-circle' : 'far fa-circle' }} text-xs"></i>
                            <span>{{ __('instructor::dashboard.teaching_system_step') }}</span>
                        </div>
                        <button type="button" onclick="openTeachingSystemModal()" class="text-[10px] font-bold text-brand-primary hover:underline cursor-pointer">
                            {{ $hasTeachingSystem ? 'تعديل' : 'ضبط' }}
                        </button>
                    </div>

                    <div class="flex items-center justify-between {{ $hasLiveStream ? 'text-emerald-600 font-semibold' : 'text-slate-500' }}">
                        <div class="flex items-center gap-2">
                            <i class="{{ $hasLiveStream ? 'fas fa-check-circle' : 'far fa-circle' }} text-xs"></i>
                            <span>{{ __('instructor::dashboard.live_stream_step') }}</span>
                        </div>
                        <button type="button" onclick="openMeetingLinkModal()" class="text-[10px] font-bold text-brand-primary hover:underline cursor-pointer">
                            {{ $hasLiveStream ? 'تعديل' : 'ربط' }}
                        </button>
                    </div>

                    <div class="flex items-center justify-between {{ $hasGroup ? 'text-emerald-600 font-semibold' : 'text-slate-500' }}">
                        <div class="flex items-center gap-2">
                            <i class="{{ $hasGroup ? 'fas fa-check-circle' : 'far fa-circle' }} text-xs"></i>
                            <span>{{ __('instructor::dashboard.group_creation') }}</span>
                        </div>
                        <a href="{{ route('instructor.groups.create') }}" class="text-[10px] font-bold text-brand-primary hover:underline">
                            {{ $hasGroup ? 'عرض' : 'إنشاء' }}
                        </a>
                    </div>

                    <div class="flex items-center justify-between {{ $hasStudents ? 'text-emerald-600 font-semibold' : 'text-slate-500' }}">
                        <div class="flex items-center gap-2">
                            <i class="{{ $hasStudents ? 'fas fa-check-circle' : 'far fa-circle' }} text-xs"></i>
                            <span>{{ __('instructor::dashboard.add_students_step') }}</span>
                        </div>
                        <button type="button" onclick="openShareLinkModal()" class="text-[10px] font-bold text-brand-primary hover:underline cursor-pointer">
                            مشاركة
                        </button>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>

    <!-- Active Groups Table -->
    <x-ui.card title="{{ __('instructor::dashboard.groups_and_registration') }}" noPadding="true" class="mb-8">
        @if($courses->count() > 0)
            <x-ui.table :headers="[__('instructor::dashboard.group'), __('instructor::dashboard.students'), __('instructor::dashboard.registration_link'), __('instructor::dashboard.actions')]">
                @foreach($courses as $course)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-slate-900 dark:text-slate-100">
                            <div>{{ $course->title }}</div>
                            <span class="text-xs text-slate-400 font-normal">{{ __('instructor::dashboard.sessions_count', ['count' => $course->schedules->count()]) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <x-ui.badge variant="brand" size="sm">{{ __('instructor::dashboard.enrolled_count', ['count' => $course->enrollments_count ?? 0]) }}</x-ui.badge>
                        </td>
                        <td class="px-6 py-4">
                            @if($course->registration_token)
                                <div class="flex items-center gap-2 max-w-xs">
                                    <input type="text" class="h-8 px-3 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg w-full text-slate-600 dark:text-slate-300 font-mono" value="{{ route('group.register', ['token' => $course->registration_token]) }}" id="link-{{ $course->id }}" readonly />
                                    <x-ui.button variant="secondary" size="sm" onclick="navigator.clipboard.writeText(document.getElementById('link-{{ $course->id }}').value)">
                                        <i class="fas fa-copy"></i>
                                    </x-ui.button>
                                </div>
                            @else
                                <span class="text-xs text-slate-400">{{ __('instructor::dashboard.no_link') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-end">
                            <x-ui.button variant="outline" size="sm" icon="fas fa-qrcode" href="{{ route('instructor.scanner', $course->id) }}">
                                {{ __('instructor::dashboard.qr_scanner_btn') }}
                            </x-ui.button>
                        </td>
                    </tr>
                @endforeach
            </x-ui.table>
        @else
            <x-ui.empty-state
                title="{{ __('instructor::dashboard.no_groups_created_yet') }}"
                description="{{ __('instructor::dashboard.no_groups_created_desc') }}"
                icon="fas fa-users"
            >
                <x-slot name="action">
                    <x-ui.button variant="primary" icon="fas fa-plus" href="{{ route('instructor.groups.create') }}">
                        {{ __('instructor::dashboard.create_first_group_btn') }}
                    </x-ui.button>
                </x-slot>
            </x-ui.empty-state>
        @endif
    </x-ui.card>

    @include('instructor::partials._onboarding-modals')
@endsection
