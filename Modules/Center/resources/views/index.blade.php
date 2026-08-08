@extends('center::layouts.app-next')

@section('title', __('center::dashboard.title'))

@section('panel-content')
    <x-ui.page-header
        title="{{ __('center::dashboard.welcome_back', ['name' => auth()->user()->name ?? 'Manager']) }}"
        subtitle="{{ $tenant->name ?? 'Educational Center' }} • {{ now()->translatedFormat('l, d F Y') }}"
    >
        <x-slot name="actions">
            <x-ui.button variant="primary" icon="fas fa-user-plus" size="md" href="{{ route('center.students.create') }}">
                {{ __('center::dashboard.add_new_student') }}
            </x-ui.button>
        </x-slot>
    </x-ui.page-header>

    @if(isset($showLaunchpad) && $showLaunchpad)
        @include('center::partials.launchpad')
    @endif

    <!-- Top Center Key Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <x-ui.stats-card
            title="{{ __('center::dashboard.models.Student') }}"
            value="{{ number_format($activeStudents) }}"
            change="+12%"
            changeType="positive"
            changeLabel="{{ __('center::dashboard.enrolled_students_label') }}"
            icon="fas fa-user-graduate"
            iconColor="text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40"
        />

        <x-ui.stats-card
            title="{{ __('center::dashboard.models.Group') }}"
            value="{{ number_format($activeGroups) }}"
            change="Active"
            changeType="positive"
            changeLabel="{{ __('center::dashboard.active_study_groups_label') }}"
            icon="fas fa-users"
            iconColor="text-brand-primary dark:text-brand-300 bg-brand-50 dark:bg-brand-900/40"
        />

        <x-ui.stats-card
            title="{{ __('center::dashboard.models.Instructor') }}"
            value="{{ number_format($activeInstructors) }}"
            change="Verified"
            changeType="neutral"
            changeLabel="{{ __('center::dashboard.teaching_staff') }}"
            icon="fas fa-chalkboard-teacher"
            iconColor="text-sky-600 dark:text-sky-400 bg-sky-50 dark:bg-sky-950/40"
        />

        <x-ui.stats-card
            title="{{ __('center::dashboard.monthly_revenue') }}"
            value="{{ number_format($monthlyRevenue) }} {{ __('center::dashboard.currency') }}"
            change="+18%"
            changeType="positive"
            changeLabel="{{ __('center::dashboard.vs_last_month') }}"
            icon="fas fa-wallet"
            iconColor="text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40"
        />
    </div>

    <!-- Quick Actions Grid Bar -->
    <div class="mb-8">
        <h2 class="text-sm font-bold text-slate-900 dark:text-slate-100 font-inter mb-4 uppercase tracking-wider text-xs">{{ __('center::dashboard.center_quick_actions') }}</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="{{ route('center.students.create') }}" class="p-4 rounded-2xl border border-brand-border dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-all text-center flex flex-col items-center justify-center group shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-base mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-plus"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-tight">{{ __('center::dashboard.add_new_student') }}</span>
            </a>

            <a href="{{ route('center.courses.create') }}" class="p-4 rounded-2xl border border-brand-border dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-all text-center flex flex-col items-center justify-center group shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-900/40 text-brand-primary dark:text-brand-300 flex items-center justify-center text-base mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-folder-plus"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-tight">{{ __('center::dashboard.create_new_group') }}</span>
            </a>

            <a href="{{ route('center.attendance.index') }}" class="p-4 rounded-2xl border border-brand-border dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-all text-center flex flex-col items-center justify-center group shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 flex items-center justify-center text-base mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-qrcode"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-tight">{{ __('center::dashboard.smart_attendance_btn') }}</span>
            </a>

            <a href="{{ route('center.sales.index') }}" class="p-4 rounded-2xl border border-brand-border dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-all text-center flex flex-col items-center justify-center group shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-base mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-receipt"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-tight">{{ __('center::dashboard.collect_fees_btn') }}</span>
            </a>
        </div>
    </div>

    <!-- Recent Registrations & Active Groups Split Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Student Registrations -->
        <x-ui.card title="{{ __('center::dashboard.recent_student_registrations') }}" subtitle="{{ __('center::dashboard.recent_registrations_sub') }}" noPadding="true">
            @if(isset($recentStudents) && count($recentStudents) > 0)
                <x-ui.table :headers="[__('center::dashboard.student_name'), __('center::dashboard.phone_number'), __('center::dashboard.registration_date')]">
                    @foreach($recentStudents as $student)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-900 dark:text-slate-100 flex items-center gap-3">
                                <x-ui.avatar :name="$student->name" size="sm" />
                                <span>{{ $student->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-slate-500">{{ $student->phone ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-xs text-slate-400">{{ $student->created_at ? $student->created_at->diffForHumans() : 'N/A' }}</td>
                        </tr>
                    @endforeach
                </x-ui.table>
            @else
                <x-ui.empty-state
                    title="{{ __('center::dashboard.no_recent_students') }}"
                    description="{{ __('center::dashboard.no_recent_students_desc') }}"
                    icon="fas fa-user-graduate"
                />
            @endif
        </x-ui.card>

        <!-- Active Center Groups -->
        <x-ui.card title="{{ __('center::dashboard.active_study_groups') }}" subtitle="{{ __('center::dashboard.active_study_groups_sub') }}" noPadding="true">
            @if(isset($recentGroups) && count($recentGroups) > 0)
                <x-ui.table :headers="[__('center::dashboard.group_title'), __('center::dashboard.instructor_label'), __('center::dashboard.students_count_label')]">
                    @foreach($recentGroups as $group)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-900 dark:text-slate-100">{{ $group->title ?? 'Untitled Group' }}</td>
                            <td class="px-6 py-4 text-xs text-slate-500">{{ $group->instructor->name ?? 'Staff' }}</td>
                            <td class="px-6 py-4">
                                <x-ui.badge variant="brand" size="sm">{{ $group->students_count ?? 0 }} {{ __('center::dashboard.enrolled_badge') }}</x-ui.badge>
                            </td>
                        </tr>
                    @endforeach
                </x-ui.table>
            @else
                <x-ui.empty-state
                    title="{{ __('center::dashboard.no_groups_configured') }}"
                    description="{{ __('center::dashboard.no_groups_configured_desc') }}"
                    icon="fas fa-users"
                >
                    <x-slot name="action">
                        <x-ui.button variant="primary" icon="fas fa-plus" href="{{ route('center.courses.create') }}">
                            {{ __('center::dashboard.create_group_action') }}
                        </x-ui.button>
                    </x-slot>
                </x-ui.empty-state>
            @endif
        </x-ui.card>
    </div>
@endsection
