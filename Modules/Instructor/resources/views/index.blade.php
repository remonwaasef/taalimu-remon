@extends('layouts.app-next')

@section('title', __('instructor::dashboard.title') ?? 'Instructor Dashboard')

@section('sidebar')
    <x-ui.sidebar brandName="Taalimu">
        <div class="space-y-1">
            <a href="{{ route('instructor.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-brand-primary bg-brand-50 dark:bg-brand-900/30">
                <i class="fas fa-home w-4 text-center"></i>
                <span>{{ __('instructor::sidebar.dashboard') }}</span>
            </a>

            <div class="pt-4 pb-1 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Teaching</div>

            <a href="{{ route('instructor.students.list') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-user-graduate w-4 text-center"></i>
                <span>{{ __('instructor::sidebar.students') }}</span>
            </a>

            <a href="{{ route('instructor.groups.list') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-users w-4 text-center"></i>
                <span>{{ __('instructor::sidebar.groups') }}</span>
            </a>

            <a href="{{ route('instructor.schedules.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-calendar-alt w-4 text-center"></i>
                <span>{{ __('instructor::sidebar.schedules') }}</span>
            </a>

            <a href="{{ route('instructor.online_classes.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-video w-4 text-center"></i>
                <span>{{ __('instructor::sidebar.online_classes') }}</span>
            </a>

            <a href="{{ route('instructor.attendance.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-clipboard-check w-4 text-center"></i>
                <span>{{ __('instructor::sidebar.attendance') }}</span>
            </a>

            <div class="pt-4 pb-1 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Account</div>

            <a href="{{ route('instructor.billing') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-wallet w-4 text-center"></i>
                <span>{{ __('instructor::sidebar.billing') }}</span>
            </a>

            <a href="{{ route('instructor.settings') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-cog w-4 text-center"></i>
                <span>{{ __('instructor::sidebar.settings') }}</span>
            </a>
        </div>

        <x-slot name="footer">
            <div class="p-3 rounded-2xl bg-brand-50/60 dark:bg-brand-900/20 border border-brand-100 dark:border-brand-800/40">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-crown text-brand-primary text-xs"></i>
                    <span class="font-bold text-xs text-brand-primary">Upgrade to Premium</span>
                </div>
                <p class="text-[11px] text-slate-500 mb-2 leading-tight">Unlock online classes & automated WhatsApp alerts.</p>
                <x-ui.button variant="primary" size="sm" class="w-full" href="{{ route('instructor.billing') }}">
                    Upgrade Now
                </x-ui.button>
            </div>
        </x-slot>
    </x-ui.sidebar>
@endsection

@section('content')
    <x-ui.page-header
        title="Good afternoon, {{ auth()->user()->name ?? 'Remon' }}! 👋"
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
        <x-ui.card glass="true" class="mb-8 border-brand-primary/20 bg-gradient-to-r from-brand-50/50 to-indigo-50/30 dark:from-slate-900 dark:to-slate-900">
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <x-ui.stats-card
            title="{{ __('instructor::dashboard.total_students') }}"
            value="{{ number_format($totalStudents) }}"
            change="+15%"
            changeType="positive"
            changeLabel="this month"
            icon="fas fa-user-graduate"
            iconColor="text-brand-primary bg-brand-50"
        />

        <x-ui.stats-card
            title="{{ __('instructor::dashboard.active_groups') }}"
            value="{{ number_format($totalCourses) }}"
            change="Active"
            changeType="positive"
            changeLabel="running groups"
            icon="fas fa-users"
            iconColor="text-emerald-600 bg-emerald-50"
        />

        <x-ui.stats-card
            title="{{ __('instructor::dashboard.monthly_revenue') }}"
            value="{{ number_format($monthlyRevenue) }} {{ app('tenant')->settings['currency'] ?? 'EGP' }}"
            change="+12%"
            changeType="positive"
            changeLabel="vs last month"
            icon="fas fa-wallet"
            iconColor="text-amber-600 bg-amber-50"
        />

        <x-ui.stats-card
            title="Attendance Rate"
            value="95%"
            change="+5%"
            changeType="positive"
            changeLabel="high engagement"
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
                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-800 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="text-center px-2.5 py-1 bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700">
                                <span class="block text-xs font-bold text-brand-primary">09:00</span>
                                <span class="block text-[10px] text-slate-400">10:00</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Mathematics</h4>
                                <p class="text-xs text-slate-400">Grade 10 &bull; Group A</p>
                            </div>
                        </div>
                        <x-ui.badge variant="success" size="sm" dot="true">In Progress</x-ui.badge>
                    </div>

                    <div class="p-3.5 rounded-xl bg-white dark:bg-slate-900 border border-brand-border dark:border-slate-800 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="text-center px-2.5 py-1 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                                <span class="block text-xs font-bold text-slate-700 dark:text-slate-300">11:00</span>
                                <span class="block text-[10px] text-slate-400">12:00</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Physics</h4>
                                <p class="text-xs text-slate-400">Grade 11 &bull; Group B</p>
                            </div>
                        </div>
                        <x-ui.badge variant="neutral" size="sm">Upcoming</x-ui.badge>
                    </div>

                    <div class="p-3.5 rounded-xl bg-white dark:bg-slate-900 border border-brand-border dark:border-slate-800 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="text-center px-2.5 py-1 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                                <span class="block text-xs font-bold text-slate-700 dark:text-slate-300">14:00</span>
                                <span class="block text-[10px] text-slate-400">15:00</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">English Language</h4>
                                <p class="text-xs text-slate-400">Grade 9 &bull; Group C</p>
                            </div>
                        </div>
                        <x-ui.badge variant="neutral" size="sm">Upcoming</x-ui.badge>
                    </div>
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
                            <path stroke="#5B5FEF" stroke-width="3" stroke-dasharray="60, 100" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <span class="absolute text-lg font-extrabold text-slate-800 dark:text-slate-100">60%</span>
                    </div>
                </div>
                <div class="space-y-1.5 text-xs">
                    <div class="flex items-center gap-2 text-emerald-600 font-semibold">
                        <i class="fas fa-check-circle text-xs"></i>
                        <span>Profile Information</span>
                    </div>
                    <div class="flex items-center gap-2 text-emerald-600 font-semibold">
                        <i class="fas fa-check-circle text-xs"></i>
                        <span>Group Creation</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-400">
                        <i class="far fa-circle text-xs"></i>
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
