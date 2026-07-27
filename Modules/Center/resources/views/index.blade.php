@extends('layouts.app-next')

@section('title', __('center::dashboard.title') ?? 'Center Dashboard')

@section('sidebar')
    <x-ui.sidebar brandName="{{ $tenant->name ?? 'Taalimu Center' }}">
        <div class="space-y-1">
            <a href="{{ route('center.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-brand-primary bg-brand-50 dark:bg-brand-900/30">
                <i class="fas fa-chart-pie w-4 text-center"></i>
                <span>{{ __('center::sidebar.dashboard') ?? 'Dashboard' }}</span>
            </a>

            <div class="pt-4 pb-1 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Management</div>

            <a href="{{ route('center.students.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-user-graduate w-4 text-center"></i>
                <span>{{ __('center::sidebar.students') ?? 'Students' }}</span>
            </a>

            <a href="{{ route('center.courses.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-users w-4 text-center"></i>
                <span>{{ __('center::sidebar.groups') ?? 'Groups & Classes' }}</span>
            </a>

            <a href="{{ route('center.instructors.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-chalkboard-teacher w-4 text-center"></i>
                <span>{{ __('center::sidebar.instructors') ?? 'Instructors' }}</span>
            </a>

            <a href="{{ route('center.attendance.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-clipboard-check w-4 text-center"></i>
                <span>{{ __('center::sidebar.attendance') ?? 'Attendance' }}</span>
            </a>

            <div class="pt-4 pb-1 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Finance & Settings</div>

            <a href="{{ route('center.payments.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-wallet w-4 text-center"></i>
                <span>{{ __('center::sidebar.payments') ?? 'Finance & Billing' }}</span>
            </a>

            <a href="{{ route('center.settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-cog w-4 text-center"></i>
                <span>{{ __('center::sidebar.settings') ?? 'Center Settings' }}</span>
            </a>
        </div>
    </x-ui.sidebar>
@endsection

@section('content')
    <x-ui.page-header
        title="Welcome back, {{ auth()->user()->name ?? 'Center Manager' }}! 👋"
        subtitle="{{ $tenant->name ?? 'Educational Center' }} &bull; {{ now()->translatedFormat('l, d F Y') }}"
    >
        <x-slot name="actions">
            <x-ui.button variant="primary" icon="fas fa-user-plus" size="md" href="{{ route('center.students.create') }}">
                {{ __('center::sidebar.add_student') ?? 'Add Student' }}
            </x-ui.button>
        </x-slot>
    </x-ui.page-header>

    <!-- Top Center Key Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <x-ui.stats-card
            title="{{ __('center::dashboard.models.Student') ?? 'Active Students' }}"
            value="{{ number_format($activeStudents) }}"
            change="+12%"
            changeType="positive"
            changeLabel="enrolled students"
            icon="fas fa-user-graduate"
            iconColor="text-emerald-600 bg-emerald-50"
        />

        <x-ui.stats-card
            title="{{ __('center::dashboard.models.Group') ?? 'Active Groups' }}"
            value="{{ number_format($activeGroups) }}"
            change="Running"
            changeType="positive"
            changeLabel="active study groups"
            icon="fas fa-users"
            iconColor="text-brand-primary bg-brand-50"
        />

        <x-ui.stats-card
            title="{{ __('center::dashboard.models.Instructor') ?? 'Instructors' }}"
            value="{{ number_format($activeInstructors) }}"
            change="Verified"
            changeType="neutral"
            changeLabel="teaching staff"
            icon="fas fa-chalkboard-teacher"
            iconColor="text-sky-600 bg-sky-50"
        />

        <x-ui.stats-card
            title="{{ __('center::dashboard.monthly_revenue') ?? 'Monthly Revenue' }}"
            value="{{ number_format($monthlyRevenue) }} EGP"
            change="+18%"
            changeType="positive"
            changeLabel="vs last month"
            icon="fas fa-wallet"
            iconColor="text-amber-600 bg-amber-50"
        />
    </div>

    <!-- Quick Actions Grid Bar -->
    <div class="mb-8">
        <h2 class="text-sm font-bold text-slate-900 dark:text-slate-100 font-inter mb-4 uppercase tracking-wider text-xs">Center Quick Actions</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="{{ route('center.students.create') }}" class="p-4 rounded-2xl border border-brand-border dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-all text-center flex flex-col items-center justify-center group shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 flex items-center justify-center text-base mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-plus"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-tight">Add New Student</span>
            </a>

            <a href="{{ route('center.courses.create') }}" class="p-4 rounded-2xl border border-brand-border dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-all text-center flex flex-col items-center justify-center group shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-900/30 text-brand-primary flex items-center justify-center text-base mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-folder-plus"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-tight">Create Group</span>
            </a>

            <a href="{{ route('center.attendance.index') }}" class="p-4 rounded-2xl border border-brand-border dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-all text-center flex flex-col items-center justify-center group shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-950/30 text-sky-600 flex items-center justify-center text-base mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-qrcode"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-tight">Smart Attendance</span>
            </a>

            <a href="{{ route('center.payments.index') }}" class="p-4 rounded-2xl border border-brand-border dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-all text-center flex flex-col items-center justify-center group shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/30 text-amber-600 flex items-center justify-center text-base mb-2 group-hover:scale-110 transition-transform">
                    <i class="fas fa-receipt"></i>
                </div>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-tight">Collect Fees</span>
            </a>
        </div>
    </div>

    <!-- Recent Registrations & Active Groups Split Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Student Registrations -->
        <x-ui.card title="Recent Student Registrations" subtitle="Newly enrolled students in center groups" noPadding="true">
            @if(isset($recentStudents) && count($recentStudents) > 0)
                <x-ui.table :headers="['Student Name', 'Phone', 'Registered']">
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
                    title="No Recent Student Registrations"
                    description="When students register for groups, they will appear here."
                    icon="fas fa-user-graduate"
                />
            @endif
        </x-ui.card>

        <!-- Active Center Groups -->
        <x-ui.card title="Active Study Groups" subtitle="Current running groups & student counts" noPadding="true">
            @if(isset($recentGroups) && count($recentGroups) > 0)
                <x-ui.table :headers="['Group Title', 'Instructor', 'Students']">
                    @foreach($recentGroups as $group)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-900 dark:text-slate-100">{{ $group->title ?? 'Untitled Group' }}</td>
                            <td class="px-6 py-4 text-xs text-slate-500">{{ $group->instructor->name ?? 'Staff' }}</td>
                            <td class="px-6 py-4">
                                <x-ui.badge variant="brand" size="sm">{{ $group->students_count ?? 0 }} enrolled</x-ui.badge>
                            </td>
                        </tr>
                    @endforeach
                </x-ui.table>
            @else
                <x-ui.empty-state
                    title="No Groups Configured"
                    description="Set up your center's study groups to organize student classes."
                    icon="fas fa-users"
                >
                    <x-slot name="action">
                        <x-ui.button variant="primary" icon="fas fa-plus" href="{{ route('center.courses.create') }}">
                            Create Group
                        </x-ui.button>
                    </x-slot>
                </x-ui.empty-state>
            @endif
        </x-ui.card>
    </div>
@endsection
