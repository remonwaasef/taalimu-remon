@extends('layouts.app-next')

@section('title', __('instructor::dashboard.title') ?? 'Instructor Dashboard')

@section('sidebar')
    @include('instructor::partials._sidebar-next', ['active' => 'dashboard'])
@endsection

@section('content')
    <x-ui.page-header
        title="Good afternoon, {{ auth()->user()->name ?? 'Remon' }}! ðŸ‘‹"
        subtitle="Here's what's happening with your school today."
    >
        <x-slot name="actions">
            <x-ui.button variant="primary" icon="fas fa-plus" size="md" href="{{ route('instructor.students.create') }}">
                Add New Student
            </x-ui.button>
        </x-slot>
    </x-ui.page-header>

    <!-- Getting Started Banner (If empty state) -->
    @if($totalCourses == 0 || $totalStudents == 0)
        <x-ui.card glass="true" class="mb-8 border-brand-primary/20 bg-gradient-to-r from-brand-50/50 to-emerald-50/30 dark:from-slate-900 dark:to-slate-900">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                <div class="space-y-2 max-w-xl">
                    <x-ui.badge variant="brand" size="sm" dot="true">{{ __('instructor::dashboard.getting_started_title') }}</x-ui.badge>
                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-slate-100 font-inter">{{ __('instructor::dashboard.getting_started_desc') }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Complete setup steps to start inviting students and tracking attendance.</p>
                    
                    <div class="flex items-center gap-3 pt-2">
                        <x-ui.button variant="primary" size="sm" icon="fas fa-folder-plus" href="{{ route('instructor.groups.create') }}">
                            {{ __('instructor::dashboard.step_create_group') }}
                        </x-ui.button>
                        <x-ui.button variant="outline" size="sm" icon="fas fa-user-plus" href="{{ route('instructor.students.create') }}">
                            {{ __('instructor::dashboard.step_add_student') }}
                        </x-ui.button>
                    </div>
                </div>

                <div class="hidden lg:flex w-24 h-24 rounded-full bg-brand-primary/10 items-center justify-center text-brand-primary text-4xl shrink-0">
                    <i class="fas fa-rocket animate-pulse"></i>
                </div>
            </div>
        </x-ui.card>
    @endif

    <!-- Top Key Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 motion-stagger">
        <x-ui.stats-card
            title="{{ __('instructor::dashboard.total_students') }}"
            value="{{ number_format($totalStudents) }}"
            change="{{ $totalStudents > 0 ? '+'.$totalStudents : '0' }}"
            changeType="{{ $totalStudents > 0 ? 'positive' : 'neutral' }}"
            changeLabel="enrolled students"
            icon="fas fa-user-graduate"
            iconColor="text-brand-primary bg-brand-50"
        />

        <x-ui.stats-card
            title="{{ __('instructor::dashboard.active_groups') }}"
            value="{{ number_format($totalCourses) }}"
            change="{{ $totalCourses > 0 ? $totalCourses : '0' }}"
            changeType="{{ $totalCourses > 0 ? 'positive' : 'neutral' }}"
            changeLabel="running groups"
            icon="fas fa-users"
            iconColor="text-emerald-600 bg-emerald-50"
        />

        <x-ui.stats-card
            title="{{ __('instructor::dashboard.monthly_revenue') }}"
            value="{{ number_format($monthlyRevenue) }} {{ app('tenant')->settings['currency'] ?? 'EGP' }}"
            change="{{ $monthlyRevenue > 0 ? 'Active' : '0' }}"
            changeType="{{ $monthlyRevenue > 0 ? 'positive' : 'neutral' }}"
            changeLabel="this month"
            icon="fas fa-wallet"
            iconColor="text-amber-600 bg-amber-50"
        />

        <x-ui.stats-card
            title="Attendance Rate"
            value="{{ $totalAttendanceCount > 0 ? $attendanceRate.'%' : '0%' }}"
            change="{{ $totalAttendanceCount > 0 ? $totalAttendanceCount : '0' }}"
            changeType="{{ $totalAttendanceCount > 0 ? 'positive' : 'neutral' }}"
            changeLabel="recorded logs"
            icon="fas fa-chart-pie"
            iconColor="text-sky-600 bg-sky-50"
        />
    </div>

    <!-- Middle Split Grid: Today's Schedule | Quick Actions | Setup Progress -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
        <!-- Today's Schedule (5 cols) -->
        <div class="lg:col-span-5">
            <x-ui.card title="Today's Schedule" subtitle="Upcoming & in-progress sessions">
                <x-slot name="action">
                    <a href="{{ route('instructor.schedules.index') }}" class="text-xs font-semibold text-brand-primary hover:underline">View full &rarr;</a>
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
                                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $sched->course->title ?? 'Session' }}</h4>
                                    <p class="text-xs text-slate-400">{{ $sched->room ?? 'Main Hall' }}</p>
                                </div>
                            </div>
                            <x-ui.badge variant="success" size="sm">Active</x-ui.badge>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400 text-xs font-medium">
                            <i class="far fa-calendar-times text-3xl block mb-2 opacity-40"></i>
                            <span>No sessions scheduled for today</span>
                        </div>
                    @endforelse
                </div>
            </x-ui.card>
        </div>

        <!-- Quick Actions (4 cols) -->
        <div class="lg:col-span-4">
            <x-ui.card title="{{ __('instructor::dashboard.quick_links') }}" subtitle="Frequently used tasks">
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

                    <a href="{{ route('instructor.settings') }}" class="p-4 rounded-xl border border-brand-border dark:border-slate-800 hover:border-emerald-500/40 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-all text-center flex flex-col items-center justify-center group">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-500 flex items-center justify-center text-base mb-2 group-hover:scale-110 transition-transform">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-tight">WhatsApp API</span>
                    </a>
                </div>
            </x-ui.card>
        </div>

        <!-- Setup Progress (3 cols) -->
        <div class="lg:col-span-3">
            <x-ui.card title="Setup Progress" subtitle="Completion indicator">
                <div class="text-center my-2">
                    <div class="inline-flex items-center justify-center relative">
                        <svg class="w-24 h-24" viewBox="0 0 36 36">
                            <path stroke="#E7EAF3" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path stroke="#5B5FEF" stroke-width="3" stroke-dasharray="{{ $setupProgress }}, 100" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <span class="absolute text-lg font-extrabold text-slate-800 dark:text-slate-100">{{ $setupProgress }}%</span>
                    </div>
                </div>
                <div class="space-y-1.5 text-xs">
                    <div class="flex items-center gap-2 {{ $hasProfile ? 'text-emerald-600 font-semibold' : 'text-slate-400' }}">
                        <i class="{{ $hasProfile ? 'fas fa-check-circle' : 'far fa-circle' }} text-xs"></i>
                        <span>Profile Information</span>
                    </div>
                    <div class="flex items-center gap-2 {{ $hasGroup ? 'text-emerald-600 font-semibold' : 'text-slate-400' }}">
                        <i class="{{ $hasGroup ? 'fas fa-check-circle' : 'far fa-circle' }} text-xs"></i>
                        <span>Group Creation</span>
                    </div>
                    <div class="flex items-center gap-2 {{ $hasStudents ? 'text-emerald-600 font-semibold' : 'text-slate-400' }}">
                        <i class="{{ $hasStudents ? 'fas fa-check-circle' : 'far fa-circle' }} text-xs"></i>
                        <span>Add Students</span>
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
                            <span class="text-xs text-slate-400 font-normal">{{ $course->schedules->count() }} sessions</span>
                        </td>
                        <td class="px-6 py-4">
                            <x-ui.badge variant="brand" size="sm">{{ $course->enrollments_count ?? 0 }} enrolled</x-ui.badge>
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
                                QR Scanner
                            </x-ui.button>
                        </td>
                    </tr>
                @endforeach
            </x-ui.table>
        @else
            <x-ui.empty-state
                title="No Groups Created Yet"
                description="Create your first study group to start registering students."
                icon="fas fa-users"
            >
                <x-slot name="action">
                    <x-ui.button variant="primary" icon="fas fa-plus" href="{{ route('instructor.groups.create') }}">
                        Create First Group
                    </x-ui.button>
                </x-slot>
            </x-ui.empty-state>
        @endif
    </x-ui.card>
@endsection
