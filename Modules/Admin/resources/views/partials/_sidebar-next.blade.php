@php
    $active = $active ?? 'dashboard';
    use Illuminate\Support\Facades\Route;

    $isDashboardActive = Route::currentRouteNamed('admin.dashboard');
    $isCentersActive = Route::currentRouteNamed(['admin.tenants.*', 'admin.subscriptions.*']);
    $isSettingsActive = Route::currentRouteNamed('admin.settings.*') && !str_contains(request()->fullUrl(), 'tab=coupons');
    $isCouponsActive = str_contains(request()->fullUrl(), 'tab=coupons');
    $isSupportActive = Route::currentRouteNamed('admin.tickets.*');
    $isMonitoringActive = Route::currentRouteNamed(['admin.activity-logs.*', 'admin.operation-issues.*', 'admin.backups.*']);
    $isAdministrationActive = Route::currentRouteNamed(['admin.roles.*', 'admin.users.*', 'consent.report', 'admin.bug_reports.*']);
@endphp

<x-ui.sidebar brandName="Taalimu Admin">
    <div class="space-y-1.5">
        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $isDashboardActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('admin::admin.sidebar.dashboard') }}">
            <i class="fas fa-chart-pie w-4 text-center"></i>
            <span>{{ __('admin::admin.sidebar.dashboard') }}</span>
        </a>

        {{-- ═══════════ CENTERS ═══════════ --}}
        <div class="sidebar-section-header pt-4 pb-1 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('admin::admin.sidebar.centers') ?? 'Centers' }}</div>

        <a href="{{ route('admin.tenants.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $isCentersActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('admin::admin.sidebar.tenants_subscriptions') }}">
            <i class="fas fa-building w-4 text-center"></i>
            <span>{{ __('admin::admin.sidebar.tenants_subscriptions') }}</span>
        </a>

        {{-- ═══════════ PLATFORM ═══════════ --}}
        <div class="sidebar-section-header pt-4 pb-1 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('admin::admin.sidebar.platform') ?? 'Platform' }}</div>

        <div x-data="{ open: {{ ($isSettingsActive || $isCouponsActive) ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="nav-link w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                           {{ ($isSettingsActive || $isCouponsActive) ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
                    title="{{ __('admin::admin.sidebar.settings') }}">
                <span class="flex items-center gap-3">
                    <i class="fas fa-cog w-4 text-center"></i>
                    <span>{{ __('admin::admin.sidebar.settings') }}</span>
                </span>
                <i class="fas fa-chevron-right text-[11px] transition-transform" :class="{ 'rotate-90': open }"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 space-y-1 ms-4">
                <a href="{{ route('admin.settings.index') }}"
                   class="nav-link flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ $isSettingsActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}"
                   title="{{ __('admin::admin.sidebar.settings') }}">
                    <i class="fas fa-sliders-h w-4 text-center"></i>
                    <span>{{ __('admin::admin.sidebar.settings') }}</span>
                </a>
                <a href="{{ route('admin.settings.index', ['tab' => 'coupons']) }}"
                   class="nav-link flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium transition-colors
                          {{ $isCouponsActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-500 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}"
                   title="{{ __('admin::admin.coupons_discounts') }}">
                    <i class="fas fa-ticket-alt w-4 text-center"></i>
                    <span>{{ __('admin::admin.coupons_discounts') }}</span>
                </a>
            </div>
        </div>

        <a href="{{ route('admin.tickets.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $isSupportActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('admin::admin.sidebar.support') }}">
            <i class="fas fa-headset w-4 text-center"></i>
            <span>{{ __('admin::admin.sidebar.support') }}</span>
        </a>

        {{-- ═══════════ MONITORING ═══════════ --}}
        <div class="sidebar-section-header pt-4 pb-1 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('admin::admin.sidebar.monitoring') }}</div>

        <a href="{{ route('admin.activity-logs.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed('admin.activity-logs.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('admin::admin.sidebar.activity_logs') }}">
            <i class="fas fa-clipboard-list w-4 text-center"></i>
            <span>{{ __('admin::admin.sidebar.activity_logs') }}</span>
        </a>

        <a href="{{ route('admin.operation-issues.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed('admin.operation-issues.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('admin::admin.sidebar.operation_issues') }}">
            <i class="fas fa-exclamation-triangle w-4 text-center"></i>
            <span>{{ __('admin::admin.sidebar.operation_issues') }}</span>
        </a>

        <a href="{{ route('admin.backups.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed('admin.backups.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('admin::admin.sidebar.backups') }}">
            <i class="fas fa-database w-4 text-center"></i>
            <span>{{ __('admin::admin.sidebar.backups') }}</span>
        </a>

        {{-- ═══════════ ADMINISTRATION ═══════════ --}}
        <div class="sidebar-section-header pt-4 pb-1 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('admin::admin.sidebar.administration') }}</div>

        <a href="{{ route('admin.roles.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed('admin.roles.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('admin::admin.sidebar.roles') }}">
            <i class="fas fa-shield-alt w-4 text-center"></i>
            <span>{{ __('admin::admin.sidebar.roles') }}</span>
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed('admin.users.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('admin::admin.sidebar.admin_team') }}">
            <i class="fas fa-users-cog w-4 text-center"></i>
            <span>{{ __('admin::admin.sidebar.admin_team') }}</span>
        </a>

        <a href="{{ route('consent.report') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed('consent.report') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('admin::admin.sidebar.cookie_reports') }}">
            <i class="fas fa-cookie-bite w-4 text-center"></i>
            <span>{{ __('admin::admin.sidebar.cookie_reports') }}</span>
        </a>

        <a href="{{ route('admin.bug_reports.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed('admin.bug_reports.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('admin::admin.sidebar.bug_reports') }}">
            <i class="fas fa-bug w-4 text-center"></i>
            <span>{{ __('admin::admin.sidebar.bug_reports') }}</span>
        </a>
    </div>

    <x-slot name="footer">
        <form method="POST" action="{{ route('admin.logout') }}" class="p-3">
            @csrf
            <button type="submit" class="nav-link w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors" title="{{ __('admin::admin.sidebar.logout') }}">
                <i class="fas fa-sign-out-alt w-4 text-center"></i>
                <span>{{ __('admin.sidebar.logout') }}</span>
            </button>
        </form>
    </x-slot>
</x-ui.sidebar>
