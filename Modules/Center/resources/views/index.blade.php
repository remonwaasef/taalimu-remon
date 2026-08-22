@extends('center::layouts.app-next')

@section('title', __('center::dashboard.title'))

@section('panel-content')
    <!-- Dashboard Header with Greeting & Quick Add Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 font-inter">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-2xl">👋</span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight leading-tight">
                    {{ __('center::dashboard.welcome_back', ['name' => auth()->user()->name ?? 'Manager']) }}
                </h1>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 font-medium flex items-center gap-2">
                <span class="font-bold text-slate-700 dark:text-slate-300">{{ $tenant->name ?? 'Educational Center' }}</span>
                <span>•</span>
                <span>{{ now()->translatedFormat('l، d F Y') }}</span>
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <x-ui.button variant="outline" icon="fas fa-qrcode" size="md" href="{{ route('center.attendance.index', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}">
                {{ __('center::dashboard.smart_attendance_btn') }}
            </x-ui.button>
            <x-ui.button variant="primary" icon="fas fa-user-plus" size="md" href="{{ route('center.students.create', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}">
                {{ __('center::dashboard.add_new_student') }}
            </x-ui.button>
        </div>
    </div>

    @if(isset($showLaunchpad) && $showLaunchpad)
        @include('center::partials.launchpad')
    @endif

    <!-- Top Key Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 motion-stagger">
        <x-ui.stats-card
            title="{{ __('center::dashboard.models.Student') }}"
            value="{{ number_format($activeStudents) }}"
            change="+12%"
            changeType="positive"
            changeLabel="{{ __('center::dashboard.enrolled_students_label') }}"
            icon="fas fa-user-graduate"
            iconColor="text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/50"
            accent="emerald"
        />

        <x-ui.stats-card
            title="{{ __('center::dashboard.models.Group') }}"
            value="{{ number_format($activeGroups) }}"
            change="Active"
            changeType="positive"
            changeLabel="{{ __('center::dashboard.active_study_groups_label') }}"
            icon="fas fa-users"
            iconColor="text-brand-primary dark:text-brand-300 bg-brand-50 dark:bg-brand-900/40"
            accent="brand"
        />

        <x-ui.stats-card
            title="{{ __('center::dashboard.models.Instructor') }}"
            value="{{ number_format($activeInstructors) }}"
            change="Verified"
            changeType="neutral"
            changeLabel="{{ __('center::dashboard.teaching_staff') }}"
            icon="fas fa-chalkboard-teacher"
            iconColor="text-sky-700 dark:text-sky-300 bg-sky-50 dark:bg-sky-950/50"
            accent="sky"
        />

        <x-ui.stats-card
            title="{{ __('center::dashboard.monthly_revenue') }}"
            value="{{ number_format($monthlyRevenue) }} {{ __('center::dashboard.currency') }}"
            change="+18%"
            changeType="positive"
            changeLabel="{{ __('center::dashboard.vs_last_month') }}"
            icon="fas fa-wallet"
            iconColor="text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/50"
            accent="amber"
        />
    </div>

    <!-- Quick Actions Section -->
    <div class="mb-8 font-inter">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                {{ __('center::dashboard.center_quick_actions') }}
            </h3>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="{{ route('center.students.create', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" class="p-4.5 rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-brand-primary/40 dark:hover:border-brand-500/40 hover:shadow-md transition-all duration-200 flex flex-col items-center justify-center group text-center shadow-xs">
                <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg mb-2.5 group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-plus"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-brand-primary dark:group-hover:text-brand-300 transition-colors leading-tight">
                    {{ __('center::dashboard.add_new_student') }}
                </span>
            </a>

            <a href="{{ route('center.courses.create', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" class="p-4.5 rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-brand-primary/40 dark:hover:border-brand-500/40 hover:shadow-md transition-all duration-200 flex flex-col items-center justify-center group text-center shadow-xs">
                <div class="w-11 h-11 rounded-xl bg-brand-50 dark:bg-brand-900/40 text-brand-primary dark:text-brand-300 flex items-center justify-center text-lg mb-2.5 group-hover:scale-110 transition-transform">
                    <i class="fas fa-folder-plus"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-brand-primary dark:group-hover:text-brand-300 transition-colors leading-tight">
                    {{ __('center::dashboard.create_new_group') }}
                </span>
            </a>

            <a href="{{ route('center.attendance.index', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" class="p-4.5 rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-brand-primary/40 dark:hover:border-brand-500/40 hover:shadow-md transition-all duration-200 flex flex-col items-center justify-center group text-center shadow-xs">
                <div class="w-11 h-11 rounded-xl bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 flex items-center justify-center text-lg mb-2.5 group-hover:scale-110 transition-transform">
                    <i class="fas fa-qrcode"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-brand-primary dark:group-hover:text-brand-300 transition-colors leading-tight">
                    {{ __('center::dashboard.smart_attendance_btn') }}
                </span>
            </a>

            <a href="{{ route('center.sales.index', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" class="p-4.5 rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-brand-primary/40 dark:hover:border-brand-500/40 hover:shadow-md transition-all duration-200 flex flex-col items-center justify-center group text-center shadow-xs">
                <div class="w-11 h-11 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg mb-2.5 group-hover:scale-110 transition-transform">
                    <i class="fas fa-receipt"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-brand-primary dark:group-hover:text-brand-300 transition-colors leading-tight">
                    {{ __('center::dashboard.collect_fees_btn') }}
                </span>
            </a>
        </div>
    </div>

    <!-- Recent Registrations & Active Groups Split Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 font-inter">
        <!-- Recent Student Registrations -->
        <x-ui.card title="{{ __('center::dashboard.recent_student_registrations') }}" subtitle="{{ __('center::dashboard.recent_registrations_sub') }}" noPadding="true">
            <x-slot name="action">
                <a href="{{ route('center.students.index', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" class="text-xs font-bold text-brand-primary hover:text-brand-600 dark:text-brand-300 hover:underline">
                    {{ __('View All') }} <i class="fas fa-arrow-left text-[10px] rtl:rotate-0 ltr:rotate-180 ms-1"></i>
                </a>
            </x-slot>

            @if(isset($recentStudents) && count($recentStudents) > 0)
                <x-ui.table :headers="[__('center::dashboard.student_name'), __('center::dashboard.phone_number'), __('center::dashboard.registration_date')]">
                    @foreach($recentStudents as $student)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-3.5 font-bold text-slate-900 dark:text-slate-100 flex items-center gap-3">
                                <x-ui.avatar :name="$student->name" size="sm" />
                                <span class="truncate">{{ $student->name }}</span>
                            </td>
                            <td class="px-6 py-3.5 text-xs font-mono text-slate-500 dark:text-slate-400">{{ $student->phone ?? '—' }}</td>
                            <td class="px-6 py-3.5 text-xs text-slate-400 dark:text-slate-500">{{ $student->created_at ? $student->created_at->diffForHumans() : '—' }}</td>
                        </tr>
                    @endforeach
                </x-ui.table>
            @else
                <x-ui.empty-state
                    title="{{ __('center::dashboard.no_recent_students') }}"
                    description="{{ __('center::dashboard.no_recent_students_desc') }}"
                    icon="fas fa-user-graduate"
                    size="sm"
                >
                    <x-slot name="action">
                        <x-ui.button variant="primary" size="sm" icon="fas fa-user-plus" href="{{ route('center.students.create', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}">
                            {{ __('center::dashboard.add_new_student') }}
                        </x-ui.button>
                    </x-slot>
                </x-ui.empty-state>
            @endif
        </x-ui.card>

        <!-- Active Center Groups -->
        <x-ui.card title="{{ __('center::dashboard.active_study_groups') }}" subtitle="{{ __('center::dashboard.active_study_groups_sub') }}" noPadding="true">
            <x-slot name="action">
                <a href="{{ route('center.courses.index', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" class="text-xs font-bold text-brand-primary hover:text-brand-600 dark:text-brand-300 hover:underline">
                    {{ __('View All') }} <i class="fas fa-arrow-left text-[10px] rtl:rotate-0 ltr:rotate-180 ms-1"></i>
                </a>
            </x-slot>

            @if(isset($recentGroups) && count($recentGroups) > 0)
                <x-ui.table :headers="[__('center::dashboard.group_title'), __('center::dashboard.instructor_label'), __('center::dashboard.students_count_label')]">
                    @foreach($recentGroups as $group)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-3.5 font-bold text-slate-900 dark:text-slate-100">{{ $group->title ?? 'Untitled Group' }}</td>
                            <td class="px-6 py-3.5 text-xs text-slate-500 dark:text-slate-400">{{ $group->instructor->name ?? 'Staff' }}</td>
                            <td class="px-6 py-3.5">
                                <x-ui.badge variant="brand" size="sm" dot="true">{{ $group->students_count ?? 0 }} {{ __('center::dashboard.enrolled_badge') }}</x-ui.badge>
                            </td>
                        </tr>
                    @endforeach
                </x-ui.table>
            @else
                <x-ui.empty-state
                    title="{{ __('center::dashboard.no_groups_configured') }}"
                    description="{{ __('center::dashboard.no_groups_configured_desc') }}"
                    icon="fas fa-users"
                    size="sm"
                >
                    <x-slot name="action">
                        <x-ui.button variant="primary" size="sm" icon="fas fa-plus" href="{{ route('center.courses.create', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}">
                            {{ __('center::dashboard.create_group_action') }}
                        </x-ui.button>
                    </x-slot>
                </x-ui.empty-state>
            @endif
        </x-ui.card>
    </div>
@endsection
