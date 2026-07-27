@php
    use Illuminate\Support\Facades\Route;

    $tenant = app('tenant');
    $domain = $tenant->domain ?? 'center';

    $canInstructors = ($tenant->getFeatureValue('max_instructors') != '0' && $tenant->getFeatureValue('max_instructors') !== false) && auth()->user()->can('view instructors');
    $canCourses = ($tenant->getFeatureValue('max_courses') != '0' && $tenant->getFeatureValue('max_courses') !== false) && auth()->user()->can('view courses');
    $canClassrooms = ($tenant->getFeatureValue('max_classrooms') != '0' && $tenant->getFeatureValue('max_classrooms') !== false) && auth()->user()->can('view schedule');
    $canSchedules = $tenant->getFeatureValue('daily_schedules') === true && auth()->user()->can('view schedule');
    $canStudents = ($tenant->getFeatureValue('max_students') != '0' && $tenant->getFeatureValue('max_students') !== false) && auth()->user()->can('view students');
    $canAttendance = $tenant->getFeatureValue('attendance_tracking') && auth()->user()->can('view attendance');
    $hasFinancialReports = $tenant->getFeatureValue('financial_reports') && auth()->user()->canAny(['view sales', 'view expenses']);
    $hasAdvancedReports = $tenant->getFeatureValue('advanced_reports') && auth()->user()->can('view reports');

    $isClassesActive = Route::currentRouteNamed(['center.classrooms.*', 'center.instructors.*', 'center.courses.*', 'center.online_classes.*', 'center.schedules.*']);
    $isReportsActive = Route::currentRouteNamed(['center.analytics.*', 'center.expenses.*']);
    $isSettingsActive = Route::currentRouteNamed(['center.assets.*', 'center.settings.*', 'center.users.*', 'center.roles.*', 'center.branches.*', 'center.tickets.*', 'center.subscription.*']);

    $showClasses = ($canInstructors || $canCourses || $canClassrooms || $canSchedules) && ($tenant->type !== 'instructor');
@endphp

<x-ui.sidebar brandName="{{ $tenant->name ?? 'Taalimu Center' }}">
    <div class="space-y-1">
        <a href="{{ route('center.dashboard', ['tenant' => $domain]) }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed('center.dashboard') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-home w-4 text-center"></i>
            <span>{{ __('center::sidebar.dashboard') }}</span>
        </a>

        @if($showClasses)
        <div x-data="{ open: {{ $isClassesActive ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                           {{ $isClassesActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                <span class="flex items-center gap-3">
                    <i class="fas fa-university w-4 text-center"></i>
                    <span>{{ __('center::sidebar.classes_structure') ?? 'Classes & Structure' }}</span>
                </span>
                <i class="fas fa-chevron-right text-[10px] transition-transform" :class="{ 'rotate-90': open }"></i>
            </button>
            <div x-show="open" class="mt-1 space-y-1 ms-4">
                @if($canClassrooms)
                <a href="{{ route('center.classrooms.index', ['tenant' => $domain]) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.classrooms.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                    <i class="fas fa-door-open w-4 text-center"></i>
                    <span>{{ __('center::sidebar.classrooms') }}</span>
                </a>
                @endif
                @if($canSchedules)
                <a href="{{ route('center.schedules.index', ['tenant' => $domain]) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.schedules.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                    <i class="fas fa-calendar-alt w-4 text-center"></i>
                    <span>{{ __('center::sidebar.schedules') }}</span>
                </a>
                @endif
                @if($canCourses)
                <a href="{{ route('center.courses.index', ['tenant' => $domain]) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.courses.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                    <i class="fas fa-book w-4 text-center"></i>
                    <span>{{ __('center::sidebar.courses') }}</span>
                </a>
                @endif
                @if($canInstructors)
                <a href="{{ route('center.instructors.index', ['tenant' => $domain]) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.instructors.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                    <i class="fas fa-chalkboard-teacher w-4 text-center"></i>
                    <span>{{ __('center::sidebar.instructors') }}</span>
                </a>
                @endif
            </div>
        </div>
        @endif

        @if($canStudents)
        <a href="{{ route('center.students.index', ['tenant' => $domain]) }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed('center.students.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-user-graduate w-4 text-center"></i>
            <span>{{ __('center::sidebar.students') ?? 'Students' }}</span>
        </a>
        @endif

        @if($canAttendance)
        <a href="{{ route('center.attendance.index', ['tenant' => $domain]) }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed('center.attendance.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-clipboard-check w-4 text-center"></i>
            <span>{{ __('center::sidebar.attendance') ?? 'Attendance' }}</span>
        </a>
        @endif

        @can('view sales')
        <a href="{{ route('center.sales.account', ['tenant' => $domain]) }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed('center.sales.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-wallet w-4 text-center"></i>
            <span>{{ __('center::sidebar.payments') ?? 'Payments & Invoices' }}</span>
        </a>
        @endcan

        @if($hasFinancialReports || $hasAdvancedReports)
        <div x-data="{ open: {{ $isReportsActive ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                           {{ $isReportsActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                <span class="flex items-center gap-3">
                    <i class="fas fa-chart-bar w-4 text-center"></i>
                    <span>{{ __('center::sidebar.reports') ?? 'Reports & Expenses' }}</span>
                </span>
                <i class="fas fa-chevron-right text-[10px] transition-transform" :class="{ 'rotate-90': open }"></i>
            </button>
            <div x-show="open" class="mt-1 space-y-1 ms-4">
                @can('view expenses')
                <a href="{{ route('center.expenses.index', ['tenant' => $domain]) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.expenses.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                    <i class="fas fa-file-invoice w-4 text-center"></i>
                    <span>{{ __('center::sidebar.expenses') }}</span>
                </a>
                @endcan
                @canany(['view reports', 'view analytics'])
                <a href="{{ route('center.analytics.finance') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.analytics.finance') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                    <i class="fas fa-chart-line w-4 text-center"></i>
                    <span>{{ __('center::sidebar.financial_analytics') }}</span>
                </a>
                <a href="{{ route('center.analytics.commissions') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.analytics.commissions') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                    <i class="fas fa-percentage w-4 text-center"></i>
                    <span>{{ __('center::sidebar.financial_commissions') }}</span>
                </a>
                @endcanany
                @if($hasAdvancedReports)
                <a href="{{ route('center.analytics.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.analytics.index') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                    <i class="fas fa-chart-pie w-4 text-center"></i>
                    <span>{{ __('center::analytics.general') }}</span>
                </a>
                @endif
            </div>
        </div>
        @endif

        @canany(['manage users', 'manage settings', 'manage billing'])
        <div x-data="{ open: {{ $isSettingsActive ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                           {{ $isSettingsActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                <span class="flex items-center gap-3">
                    <i class="fas fa-cogs w-4 text-center"></i>
                    <span>{{ __('center::sidebar.settings') }}</span>
                </span>
                <i class="fas fa-chevron-right text-[10px] transition-transform" :class="{ 'rotate-90': open }"></i>
            </button>
            <div x-show="open" class="mt-1 space-y-1 ms-4">
                @can('manage billing')
                <a href="{{ route('center.subscription.index', ['tenant' => $domain]) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.subscription.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                    <i class="fas fa-credit-card w-4 text-center"></i>
                    <span>{{ __('center::sidebar.subscription') }}</span>
                    @php
                        $subEndsAt = app('tenant')->subscriptions?->last()?->ends_at;
                        $daysLeft = $subEndsAt ? max(0, now()->diffInDays($subEndsAt, false)) : null;
                    @endphp
                    @if($daysLeft !== null && $daysLeft <= 7)
                        <span class="bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full ms-auto">{{ $daysLeft }}d</span>
                    @endif
                </a>
                @endcan
                @can('manage settings')
                <a href="{{ route('center.settings.index', ['tenant' => $domain, 'tab' => 'general']) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.settings.index') && (request('tab') == 'general' || !request('tab')) ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                    <i class="fas fa-sliders-h w-4 text-center"></i>
                    <span>{{ __('center::settings.tabs.general') }}</span>
                </a>
                @endcan
                @can('manage users')
                <a href="{{ route('center.users.index', ['tenant' => $domain]) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.users.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                    <i class="fas fa-users w-4 text-center"></i>
                    <span>{{ __('center::sidebar.users') }}</span>
                </a>
                @if($tenant->getFeatureValue('advanced_roles'))
                <a href="{{ route('center.roles.index', ['tenant' => $domain]) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.roles.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                    <i class="fas fa-shield-alt w-4 text-center"></i>
                    <span>{{ __('center::sidebar.permissions') }}</span>
                </a>
                @endif
                @endcan
                @can('manage settings')
                @if($tenant->getFeatureValue('multi_branch'))
                <a href="{{ route('center.branches.index', ['tenant' => $domain]) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.branches.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                    <i class="fas fa-code-branch w-4 text-center"></i>
                    <span>{{ __('center::sidebar.branches') }}</span>
                </a>
                @endif
                @endcan
                <a href="{{ route('center.tickets.index', ['tenant' => $domain]) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ Route::currentRouteNamed('center.tickets.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                    <i class="fas fa-headset w-4 text-center"></i>
                    <span>{{ __('center::sidebar.support') }}</span>
                </a>
            </div>
        </div>
        @endcanany
    </div>

    <x-slot name="footer">
        <form method="POST" action="{{ route('center.logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors">
                <i class="fas fa-sign-out-alt w-4 text-center"></i>
                <span>{{ __('center::sidebar.logout') ?? 'Sign Out' }}</span>
            </button>
        </form>
    </x-slot>
</x-ui.sidebar>
