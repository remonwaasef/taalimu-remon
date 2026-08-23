@php
    use Illuminate\Support\Facades\Route;

    $tenant = app('tenant');
    $domain = $tenant->domain ?? 'center';

    // Feature & permission checks
    $canInstructors = ($tenant->getFeatureValue('max_instructors') != '0' && $tenant->getFeatureValue('max_instructors') !== false) && auth()->user()->can('view instructors');
    $canCourses = ($tenant->getFeatureValue('max_courses') != '0' && $tenant->getFeatureValue('max_courses') !== false) && auth()->user()->can('view courses');
    $canClassrooms = ($tenant->getFeatureValue('max_classrooms') != '0' && $tenant->getFeatureValue('max_classrooms') !== false) && auth()->user()->can('view schedule');
    $canSchedules = $tenant->getFeatureValue('daily_schedules') === true && auth()->user()->can('view schedule');
    $canStudents = ($tenant->getFeatureValue('max_students') != '0' && $tenant->getFeatureValue('max_students') !== false) && auth()->user()->can('view students');
    $canAttendance = $tenant->getFeatureValue('attendance_tracking') && auth()->user()->can('view attendance');
    $hasFinancialReports = $tenant->getFeatureValue('financial_reports') && auth()->user()->canAny(['view sales', 'view expenses']);
    $hasAdvancedReports = $tenant->getFeatureValue('advanced_reports') && auth()->user()->can('view reports');

    // Active state detection
    $isDashboardActive = Route::currentRouteNamed('center.dashboard');
    $isStudentsActive = Route::currentRouteNamed('center.students.*');
    $isAttendanceActive = Route::currentRouteNamed('center.attendance.*');
    $isPaymentsActive = Route::currentRouteNamed('center.sales.*');
    $isAcademicsActive = Route::currentRouteNamed(['center.courses.*', 'center.schedules.*', 'center.classrooms.*', 'center.instructors.*', 'center.online_classes.*']);
    $isReportsActive = Route::currentRouteNamed(['center.analytics.*', 'center.expenses.*']);
    $isSettingsActive = Route::currentRouteNamed(['center.settings.*', 'center.users.*', 'center.roles.*', 'center.branches.*', 'center.tickets.*', 'center.subscription.*', 'center.assets.*']);

    // Visibility flags
    $showAcademics = ($canInstructors || $canCourses || $canClassrooms || $canSchedules) && ($tenant->type !== 'instructor');
    $showReports = $hasFinancialReports || $hasAdvancedReports;
    $showSettings = auth()->user()->canAny(['manage users', 'manage settings', 'manage billing']);

    $activeLink = "bg-brand-50 text-brand-primary dark:bg-brand-900/30 dark:text-brand-300 font-bold shadow-xs";
    $inactiveLink = "text-slate-600 hover:text-slate-900 hover:bg-slate-50/80 dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-800/60 font-semibold";
@endphp

<x-ui.sidebar brandName="{{ $tenant->name ?? 'Taalimu Center' }}" homeUrl="{{ route('center.dashboard', ['tenant' => $domain]) }}">
    <div class="space-y-1">

        {{-- ═══════════ DASHBOARD ═══════════ --}}
        <a href="{{ route('center.dashboard', ['tenant' => $domain]) }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-colors duration-150 group {{ $isDashboardActive ? $activeLink : $inactiveLink }}"
           title="{{ __('center::sidebar.dashboard') }}">
            <div class="w-5 flex items-center justify-center shrink-0">
                <i class="fas fa-home text-sm {{ $isDashboardActive ? 'text-brand-primary dark:text-brand-300' : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}"></i>
            </div>
            <span x-show="!collapsed" class="truncate">{{ __('center::sidebar.dashboard') }}</span>
        </a>

        {{-- ═══════════ DAILY WORK ═══════════ --}}
        <div x-show="!collapsed" class="pt-3 pb-1 px-3 text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
            {{ __('center::sidebar.daily_work') ?? 'Daily Work' }}
        </div>

        @if($canStudents)
        <a href="{{ route('center.students.index', ['tenant' => $domain]) }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-colors duration-150 group {{ $isStudentsActive ? $activeLink : $inactiveLink }}"
           title="{{ __('center::sidebar.students') }}">
            <div class="w-5 flex items-center justify-center shrink-0">
                <i class="fas fa-user-graduate text-sm {{ $isStudentsActive ? 'text-brand-primary dark:text-brand-300' : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}"></i>
            </div>
            <span x-show="!collapsed" class="truncate">{{ __('center::sidebar.students') }}</span>
        </a>
        @endif

        @if($canAttendance)
        <a href="{{ route('center.attendance.index', ['tenant' => $domain]) }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-colors duration-150 group {{ $isAttendanceActive ? $activeLink : $inactiveLink }}"
           title="{{ __('center::sidebar.attendance') }}">
            <div class="w-5 flex items-center justify-center shrink-0">
                <i class="fas fa-clipboard-check text-sm {{ $isAttendanceActive ? 'text-brand-primary dark:text-brand-300' : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}"></i>
            </div>
            <span x-show="!collapsed" class="truncate">{{ __('center::sidebar.attendance') }}</span>
        </a>
        @endif

        @can('view sales')
        <a href="{{ route('center.sales.account', ['tenant' => $domain]) }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-colors duration-150 group {{ $isPaymentsActive ? $activeLink : $inactiveLink }}"
           title="{{ __('center::sidebar.payments') }}">
            <div class="w-5 flex items-center justify-center shrink-0">
                <i class="fas fa-wallet text-sm {{ $isPaymentsActive ? 'text-brand-primary dark:text-brand-300' : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}"></i>
            </div>
            <span x-show="!collapsed" class="truncate">{{ __('center::sidebar.payments') }}</span>
        </a>
        @endcan

        {{-- ═══════════ ACADEMICS ═══════════ --}}
        @if($showAcademics)
        <div x-data="{ open: {{ $isAcademicsActive ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-xs transition-colors duration-150 group {{ $isAcademicsActive ? $activeLink : $inactiveLink }}"
                    title="{{ __('center::sidebar.academics') ?? 'Academics' }}">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-5 flex items-center justify-center shrink-0">
                        <i class="fas fa-graduation-cap text-sm {{ $isAcademicsActive ? 'text-brand-primary dark:text-brand-300' : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}"></i>
                    </div>
                    <span x-show="!collapsed" class="truncate">{{ __('center::sidebar.academics') ?? 'Academics' }}</span>
                </div>
                <i x-show="!collapsed" class="fas fa-chevron-right text-[10px] transition-transform duration-200 text-slate-400" :class="{ 'rotate-90': open }"></i>
            </button>
            <div x-show="open && !collapsed" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 space-y-0.5 ms-6 ps-2 border-s border-slate-200 dark:border-slate-800">
                @if($canCourses)
                <a href="{{ route('center.courses.index', ['tenant' => $domain]) }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition-colors {{ Route::currentRouteNamed('center.courses.*') ? 'text-brand-primary dark:text-brand-300 font-bold bg-brand-50/50 dark:bg-brand-900/20' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium' }}">
                    <i class="fas fa-book text-xs w-4"></i>
                    <span>{{ __('center::sidebar.courses') }}</span>
                </a>
                @endif
                @if($canSchedules)
                <a href="{{ route('center.schedules.index', ['tenant' => $domain]) }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition-colors {{ Route::currentRouteNamed('center.schedules.*') ? 'text-brand-primary dark:text-brand-300 font-bold bg-brand-50/50 dark:bg-brand-900/20' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium' }}">
                    <i class="fas fa-calendar-alt text-xs w-4"></i>
                    <span>{{ __('center::sidebar.schedules') }}</span>
                </a>
                @endif
                @if($canClassrooms)
                <a href="{{ route('center.classrooms.index', ['tenant' => $domain]) }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition-colors {{ Route::currentRouteNamed('center.classrooms.*') ? 'text-brand-primary dark:text-brand-300 font-bold bg-brand-50/50 dark:bg-brand-900/20' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium' }}">
                    <i class="fas fa-door-open text-xs w-4"></i>
                    <span>{{ __('center::sidebar.classrooms') }}</span>
                </a>
                @endif
                @if($canInstructors)
                <a href="{{ route('center.instructors.index', ['tenant' => $domain]) }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition-colors {{ Route::currentRouteNamed('center.instructors.*') ? 'text-brand-primary dark:text-brand-300 font-bold bg-brand-50/50 dark:bg-brand-900/20' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium' }}">
                    <i class="fas fa-chalkboard-teacher text-xs w-4"></i>
                    <span>{{ __('center::sidebar.instructors') }}</span>
                </a>
                @endif
                @can('manage schedule')
                <a href="{{ route('center.online_classes.index', ['tenant' => $domain]) }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition-colors {{ Route::currentRouteNamed('center.online_classes.*') ? 'text-brand-primary dark:text-brand-300 font-bold bg-brand-50/50 dark:bg-brand-900/20' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium' }}">
                    <i class="fas fa-video text-xs w-4"></i>
                    <span>{{ __('center::sidebar.online_classes') }}</span>
                </a>
                @endcan
            </div>
        </div>
        @endif

        {{-- ═══════════ REPORTS ═══════════ --}}
        @if($showReports)
        <div x-data="{ open: {{ $isReportsActive ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-xs transition-colors duration-150 group {{ $isReportsActive ? $activeLink : $inactiveLink }}"
                    title="{{ __('center::sidebar.reports') ?? 'Reports' }}">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-5 flex items-center justify-center shrink-0">
                        <i class="fas fa-chart-bar text-sm {{ $isReportsActive ? 'text-brand-primary dark:text-brand-300' : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}"></i>
                    </div>
                    <span x-show="!collapsed" class="truncate">{{ __('center::sidebar.reports') ?? 'Reports' }}</span>
                </div>
                <i x-show="!collapsed" class="fas fa-chevron-right text-[10px] transition-transform duration-200 text-slate-400" :class="{ 'rotate-90': open }"></i>
            </button>
            <div x-show="open && !collapsed" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 space-y-0.5 ms-6 ps-2 border-s border-slate-200 dark:border-slate-800">
                @can('view expenses')
                <a href="{{ route('center.expenses.index', ['tenant' => $domain]) }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition-colors {{ Route::currentRouteNamed('center.expenses.*') ? 'text-brand-primary dark:text-brand-300 font-bold bg-brand-50/50 dark:bg-brand-900/20' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium' }}">
                    <i class="fas fa-file-invoice text-xs w-4"></i>
                    <span>{{ __('center::sidebar.expenses') }}</span>
                </a>
                @endcan
                @canany(['view reports', 'view analytics'])
                <a href="{{ route('center.analytics.finance', ['tenant' => $domain]) }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition-colors {{ Route::currentRouteNamed('center.analytics.finance') ? 'text-brand-primary dark:text-brand-300 font-bold bg-brand-50/50 dark:bg-brand-900/20' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium' }}">
                    <i class="fas fa-chart-line text-xs w-4"></i>
                    <span>{{ __('center::sidebar.financial_analytics') }}</span>
                </a>
                <a href="{{ route('center.analytics.commissions', ['tenant' => $domain]) }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition-colors {{ Route::currentRouteNamed('center.analytics.commissions') ? 'text-brand-primary dark:text-brand-300 font-bold bg-brand-50/50 dark:bg-brand-900/20' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium' }}">
                    <i class="fas fa-percentage text-xs w-4"></i>
                    <span>{{ __('center::sidebar.financial_commissions') }}</span>
                </a>
                @endcanany
                @if($hasAdvancedReports)
                <a href="{{ route('center.analytics.index', ['tenant' => $domain]) }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition-colors {{ Route::currentRouteNamed('center.analytics.index') ? 'text-brand-primary dark:text-brand-300 font-bold bg-brand-50/50 dark:bg-brand-900/20' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium' }}">
                    <i class="fas fa-chart-pie text-xs w-4"></i>
                    <span>{{ __('center::analytics.general') }}</span>
                </a>
                @endif
            </div>
        </div>
        @endif

        {{-- ═══════════ SETTINGS ═══════════ --}}
        @if($showSettings)
        <div x-show="!collapsed" class="pt-3 pb-1 px-3 text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
            {{ __('center::sidebar.settings') }}
        </div>

        <div x-data="{ open: {{ $isSettingsActive ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-xs transition-colors duration-150 group {{ $isSettingsActive ? $activeLink : $inactiveLink }}"
                    title="{{ __('center::sidebar.settings') }}">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-5 flex items-center justify-center shrink-0">
                        <i class="fas fa-cog text-sm {{ $isSettingsActive ? 'text-brand-primary dark:text-brand-300' : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}"></i>
                    </div>
                    <span x-show="!collapsed" class="truncate">{{ __('center::sidebar.settings') }}</span>
                </div>
                <i x-show="!collapsed" class="fas fa-chevron-right text-[10px] transition-transform duration-200 text-slate-400" :class="{ 'rotate-90': open }"></i>
            </button>
            <div x-show="open && !collapsed" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 space-y-0.5 ms-6 ps-2 border-s border-slate-200 dark:border-slate-800">
                @can('manage billing')
                <a href="{{ route('center.subscription.index', ['tenant' => $domain]) }}"
                   class="flex items-center justify-between px-3 py-2 rounded-xl text-xs transition-colors {{ Route::currentRouteNamed('center.subscription.*') ? 'text-brand-primary dark:text-brand-300 font-bold bg-brand-50/50 dark:bg-brand-900/20' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium' }}">
                    <span class="flex items-center gap-2.5 truncate">
                        <i class="fas fa-credit-card text-xs w-4"></i>
                        <span>{{ __('center::sidebar.subscription') }}</span>
                    </span>
                    @php
                        $subEndsAt = app('tenant')->subscriptions?->last()?->ends_at;
                        $daysLeft = $subEndsAt ? max(0, now()->diffInDays($subEndsAt, false)) : null;
                    @endphp
                    @if($daysLeft !== null && $daysLeft <= 7)
                        <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $daysLeft }}d</span>
                    @endif
                </a>
                @endcan
                @can('manage settings')
                <a href="{{ route('center.settings.index', ['tenant' => $domain, 'tab' => 'general']) }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition-colors {{ Route::currentRouteNamed('center.settings.index') && (request('tab') == 'general' || !request('tab')) ? 'text-brand-primary dark:text-brand-300 font-bold bg-brand-50/50 dark:bg-brand-900/20' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium' }}">
                    <i class="fas fa-sliders-h text-xs w-4"></i>
                    <span>{{ __('center::settings.tabs.general') }}</span>
                </a>
                @endcan
                @can('manage users')
                <a href="{{ route('center.users.index', ['tenant' => $domain]) }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition-colors {{ Route::currentRouteNamed('center.users.*') ? 'text-brand-primary dark:text-brand-300 font-bold bg-brand-50/50 dark:bg-brand-900/20' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium' }}">
                    <i class="fas fa-users text-xs w-4"></i>
                    <span>{{ __('center::sidebar.users') }}</span>
                </a>
                @if($tenant->getFeatureValue('advanced_roles'))
                <a href="{{ route('center.roles.index', ['tenant' => $domain]) }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition-colors {{ Route::currentRouteNamed('center.roles.*') ? 'text-brand-primary dark:text-brand-300 font-bold bg-brand-50/50 dark:bg-brand-900/20' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium' }}">
                    <i class="fas fa-shield-alt text-xs w-4"></i>
                    <span>{{ __('center::sidebar.permissions') }}</span>
                </a>
                @endif
                @endcan
                @can('manage settings')
                @if($tenant->getFeatureValue('multi_branch'))
                <a href="{{ route('center.branches.index', ['tenant' => $domain]) }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition-colors {{ Route::currentRouteNamed('center.branches.*') ? 'text-brand-primary dark:text-brand-300 font-bold bg-brand-50/50 dark:bg-brand-900/20' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium' }}">
                    <i class="fas fa-code-branch text-xs w-4"></i>
                    <span>{{ __('center::sidebar.branches') }}</span>
                </a>
                @endif
                @endcan
                <a href="{{ route('center.tickets.index', ['tenant' => $domain]) }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs transition-colors {{ Route::currentRouteNamed('center.tickets.*') ? 'text-brand-primary dark:text-brand-300 font-bold bg-brand-50/50 dark:bg-brand-900/20' : 'text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/40 font-medium' }}">
                    <i class="fas fa-headset text-xs w-4"></i>
                    <span>{{ __('center::sidebar.support') }}</span>
                </a>
            </div>
        </div>
        @endif
    </div>

    <x-slot name="footer">
        <form method="POST" action="{{ route('center.logout', ['tenant' => $domain]) }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors focus:outline-none" title="{{ __('center::sidebar.logout') }}">
                <i class="fas fa-sign-out-alt text-xs shrink-0"></i>
                <span x-show="!collapsed">{{ __('center::sidebar.logout') ?? 'Sign Out' }}</span>
            </button>
        </form>
    </x-slot>
</x-ui.sidebar>
