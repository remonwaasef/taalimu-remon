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
@endphp

<x-ui.sidebar brandName="{{ $tenant->name ?? 'Taalimu Center' }}">
    <div class="space-y-1.5">

        {{-- ═══════════ DASHBOARD ═══════════ --}}
        <a href="{{ route('center.dashboard', ['tenant' => $domain]) }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $isDashboardActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('center::sidebar.dashboard') }}">
            <i class="fas fa-home w-4 text-center"></i>
            <span>{{ __('center::sidebar.dashboard') }}</span>
        </a>

        {{-- ═══════════ DAILY WORK ═══════════ --}}
        <div class="sidebar-section-header pt-4 pb-1 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
            {{ __('center::sidebar.daily_work') ?? 'Daily Work' }}
        </div>

        @if($canStudents)
        <a href="{{ route('center.students.index', ['tenant' => $domain]) }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $isStudentsActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('center::sidebar.students') }}">
            <i class="fas fa-user-graduate w-4 text-center"></i>
            <span>{{ __('center::sidebar.students') }}</span>
        </a>
        @endif

        @if($canAttendance)
        <a href="{{ route('center.attendance.index', ['tenant' => $domain]) }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $isAttendanceActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('center::sidebar.attendance') }}">
            <i class="fas fa-clipboard-check w-4 text-center"></i>
            <span>{{ __('center::sidebar.attendance') }}</span>
        </a>
        @endif

        @can('view sales')
        <a href="{{ route('center.sales.account', ['tenant' => $domain]) }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $isPaymentsActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('center::sidebar.payments') }}">
            <i class="fas fa-wallet w-4 text-center"></i>
            <span>{{ __('center::sidebar.payments') }}</span>
        </a>
        @endcan

        {{-- ═══════════ ACADEMICS ═══════════ --}}
        @if($showAcademics)
        <div x-data="{ open: {{ $isAcademicsActive ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="nav-link w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                           {{ $isAcademicsActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
                    title="{{ __('center::sidebar.academics') ?? 'Academics' }}">
                <span class="flex items-center gap-3">
                    <i class="fas fa-graduation-cap w-4 text-center"></i>
                    <span>{{ __('center::sidebar.academics') ?? 'Academics' }}</span>
                </span>
                <i class="fas fa-chevron-right text-[11px] transition-transform" :class="{ 'rotate-90': open }"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 space-y-1 ms-4">
                @if($canCourses)
                <a href="{{ route('center.courses.index', ['tenant' => $domain]) }}"
                   class="nav-link flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.courses.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}"
                   title="{{ __('center::sidebar.courses') }}">
                    <i class="fas fa-book w-4 text-center"></i>
                    <span>{{ __('center::sidebar.courses') }}</span>
                </a>
                @endif
                @if($canSchedules)
                <a href="{{ route('center.schedules.index', ['tenant' => $domain]) }}"
                   class="nav-link flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.schedules.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}"
                   title="{{ __('center::sidebar.schedules') }}">
                    <i class="fas fa-calendar-alt w-4 text-center"></i>
                    <span>{{ __('center::sidebar.schedules') }}</span>
                </a>
                @endif
                @if($canClassrooms)
                <a href="{{ route('center.classrooms.index', ['tenant' => $domain]) }}"
                   class="nav-link flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.classrooms.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}"
                   title="{{ __('center::sidebar.classrooms') }}">
                    <i class="fas fa-door-open w-4 text-center"></i>
                    <span>{{ __('center::sidebar.classrooms') }}</span>
                </a>
                @endif
                @if($canInstructors)
                <a href="{{ route('center.instructors.index', ['tenant' => $domain]) }}"
                   class="nav-link flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.instructors.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}"
                   title="{{ __('center::sidebar.instructors') }}">
                    <i class="fas fa-chalkboard-teacher w-4 text-center"></i>
                    <span>{{ __('center::sidebar.instructors') }}</span>
                </a>
                @endif
            </div>
        </div>
        @endif

        {{-- ═══════════ REPORTS ═══════════ --}}
        @if($showReports)
        <div x-data="{ open: {{ $isReportsActive ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="nav-link w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                           {{ $isReportsActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
                    title="{{ __('center::sidebar.reports') ?? 'Reports' }}">
                <span class="flex items-center gap-3">
                    <i class="fas fa-chart-bar w-4 text-center"></i>
                    <span>{{ __('center::sidebar.reports') ?? 'Reports' }}</span>
                </span>
                <i class="fas fa-chevron-right text-[11px] transition-transform" :class="{ 'rotate-90': open }"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 space-y-1 ms-4">
                @can('view expenses')
                <a href="{{ route('center.expenses.index', ['tenant' => $domain]) }}"
                   class="nav-link flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.expenses.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}"
                   title="{{ __('center::sidebar.expenses') }}">
                    <i class="fas fa-file-invoice w-4 text-center"></i>
                    <span>{{ __('center::sidebar.expenses') }}</span>
                </a>
                @endcan
                @canany(['view reports', 'view analytics'])
                <a href="{{ route('center.analytics.finance', ['tenant' => $domain]) }}"
                   class="nav-link flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.analytics.finance') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}"
                   title="{{ __('center::sidebar.financial_analytics') }}">
                    <i class="fas fa-chart-line w-4 text-center"></i>
                    <span>{{ __('center::sidebar.financial_analytics') }}</span>
                </a>
                <a href="{{ route('center.analytics.commissions', ['tenant' => $domain]) }}"
                   class="nav-link flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.analytics.commissions') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}"
                   title="{{ __('center::sidebar.financial_commissions') }}">
                    <i class="fas fa-percentage w-4 text-center"></i>
                    <span>{{ __('center::sidebar.financial_commissions') }}</span>
                </a>
                @endcanany
                @if($hasAdvancedReports)
                <a href="{{ route('center.analytics.index', ['tenant' => $domain]) }}"
                   class="nav-link flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.analytics.index') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}"
                   title="{{ __('center::analytics.general') }}">
                    <i class="fas fa-chart-pie w-4 text-center"></i>
                    <span>{{ __('center::analytics.general') }}</span>
                </a>
                @endif
            </div>
        </div>
        @endif

        {{-- ═══════════ SETTINGS ═══════════ --}}
        @if($showSettings)
        <div class="sidebar-section-header pt-4 pb-1 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
            {{ __('center::sidebar.settings') }}
        </div>

        <div x-data="{ open: {{ $isSettingsActive ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="nav-link w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                           {{ $isSettingsActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
                    title="{{ __('center::sidebar.settings') }}">
                <span class="flex items-center gap-3">
                    <i class="fas fa-cog w-4 text-center"></i>
                    <span>{{ __('center::sidebar.settings') }}</span>
                </span>
                <i class="fas fa-chevron-right text-[11px] transition-transform" :class="{ 'rotate-90': open }"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 space-y-1 ms-4">
                @can('manage billing')
                <a href="{{ route('center.subscription.index', ['tenant' => $domain]) }}"
                   class="nav-link flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.subscription.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}"
                   title="{{ __('center::sidebar.subscription') }}">
                    <i class="fas fa-credit-card w-4 text-center"></i>
                    <span>{{ __('center::sidebar.subscription') }}</span>
                    @php
                        $subEndsAt = app('tenant')->subscriptions?->last()?->ends_at;
                        $daysLeft = $subEndsAt ? max(0, now()->diffInDays($subEndsAt, false)) : null;
                    @endphp
                    @if($daysLeft !== null && $daysLeft <= 7)
                        <span class="bg-red-500 text-white text-[11px] px-1.5 py-0.5 rounded-full ms-auto">{{ $daysLeft }}d</span>
                    @endif
                </a>
                @endcan
                @can('manage settings')
                <a href="{{ route('center.settings.index', ['tenant' => $domain, 'tab' => 'general']) }}"
                   class="nav-link flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.settings.index') && (request('tab') == 'general' || !request('tab')) ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}"
                   title="{{ __('center::settings.tabs.general') }}">
                    <i class="fas fa-sliders-h w-4 text-center"></i>
                    <span>{{ __('center::settings.tabs.general') }}</span>
                </a>
                @endcan
                @can('manage users')
                <a href="{{ route('center.users.index', ['tenant' => $domain]) }}"
                   class="nav-link flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.users.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}"
                   title="{{ __('center::sidebar.users') }}">
                    <i class="fas fa-users w-4 text-center"></i>
                    <span>{{ __('center::sidebar.users') }}</span>
                </a>
                @if($tenant->getFeatureValue('advanced_roles'))
                <a href="{{ route('center.roles.index', ['tenant' => $domain]) }}"
                   class="nav-link flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.roles.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}"
                   title="{{ __('center::sidebar.permissions') }}">
                    <i class="fas fa-shield-alt w-4 text-center"></i>
                    <span>{{ __('center::sidebar.permissions') }}</span>
                </a>
                @endif
                @endcan
                @can('manage settings')
                @if($tenant->getFeatureValue('multi_branch'))
                <a href="{{ route('center.branches.index', ['tenant' => $domain]) }}"
                   class="nav-link flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.branches.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}"
                   title="{{ __('center::sidebar.branches') }}">
                    <i class="fas fa-code-branch w-4 text-center"></i>
                    <span>{{ __('center::sidebar.branches') }}</span>
                </a>
                @endif
                @endcan
                <a href="{{ route('center.tickets.index', ['tenant' => $domain]) }}"
                   class="nav-link flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.tickets.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}"
                   title="{{ __('center::sidebar.support') }}">
                    <i class="fas fa-headset w-4 text-center"></i>
                    <span>{{ __('center::sidebar.support') }}</span>
                </a>
            </div>
        </div>
        @endif
    </div>

    <x-slot name="footer">
        <form method="POST" action="{{ route('center.logout', ['tenant' => $domain]) }}">
            @csrf
            <button type="submit" class="nav-link w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors" title="{{ __('center::sidebar.logout') }}">
                <i class="fas fa-sign-out-alt w-4 text-center"></i>
                <span>{{ __('center::sidebar.logout') ?? 'Sign Out' }}</span>
            </button>
        </form>
    </x-slot>
</x-ui.sidebar>
