@php
    $active = $active ?? 'dashboard';
    use Illuminate\Support\Facades\Route;
@endphp

<x-ui.sidebar brandName="Taalimu Admin">
    <div class="space-y-1">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed('admin.dashboard') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-chart-pie w-4 text-center"></i>
            <span>{{ __('admin::admin.sidebar.dashboard') }}</span>
        </a>

        <div class="pt-4 pb-1 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('admin::admin.sidebar.management') ?? 'Management' }}</div>

        <a href="{{ route('admin.tenants.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed(['admin.tenants.*', 'admin.subscriptions.*']) ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-building w-4 text-center"></i>
            <span>{{ __('admin::admin.sidebar.tenants_subscriptions') ?? 'Educational Centers' }}</span>
        </a>

        <a href="{{ route('admin.settings.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed('admin.settings.*') && !str_contains(request()->fullUrl(), 'tab=coupons') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-cog w-4 text-center"></i>
            <span>{{ __('admin::admin.sidebar.settings') }}</span>
        </a>

        <a href="{{ route('admin.settings.index', ['tab' => 'coupons']) }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ str_contains(request()->fullUrl(), 'tab=coupons') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-ticket-alt w-4 text-center"></i>
            <span>{{ __('admin::admin.coupons_discounts') }}</span>
        </a>

        <a href="{{ route('admin.tickets.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed('admin.tickets.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-headset w-4 text-center"></i>
            <span>{{ __('admin::admin.sidebar.support') }}</span>
        </a>

        <div class="pt-4 pb-1 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('admin::admin.sidebar.monitoring') ?? 'Monitoring' }}</div>

        <a href="{{ route('admin.activity-logs.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed('admin.activity-logs.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-clipboard-list w-4 text-center"></i>
            <span>{{ __('admin::admin.sidebar.activity_logs') }}</span>
        </a>

        <a href="{{ route('admin.operation-issues.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed('admin.operation-issues.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-exclamation-triangle w-4 text-center"></i>
            <span>{{ __('admin::admin.sidebar.operation_issues') }}</span>
        </a>

        <a href="{{ route('admin.backups.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed('admin.backups.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-database w-4 text-center"></i>
            <span>{{ __('admin::admin.sidebar.backups') ?? 'Backups' }}</span>
        </a>

        <div class="pt-4 pb-1 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('admin::admin.sidebar.administration') ?? 'Administration' }}</div>

        <a href="{{ route('admin.roles.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed('admin.roles.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-shield-alt w-4 text-center"></i>
            <span>{{ __('admin::admin.sidebar.roles') }}</span>
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed('admin.users.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-users-cog w-4 text-center"></i>
            <span>{{ __('admin::admin.sidebar.admin_team') ?? 'Admin Team' }}</span>
        </a>

        <a href="{{ route('consent.report') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed('consent.report') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-cookie-bite w-4 text-center"></i>
            <span>{{ __('admin::admin.sidebar.cookie_reports') }}</span>
        </a>

        <a href="{{ route('admin.bug_reports.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ Route::currentRouteNamed('admin.bug_reports.*') ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-bug w-4 text-center"></i>
            <span>{{ __('admin::admin.sidebar.bug_reports') ?? 'Bug Reports' }}</span>
        </a>
    </div>

    <x-slot name="footer">
        <form method="POST" action="{{ route('admin.logout') }}" class="p-3">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors">
                <i class="fas fa-sign-out-alt w-4 text-center"></i>
                <span>{{ __('admin.sidebar.logout') }}</span>
            </button>
        </form>
    </x-slot>
</x-ui.sidebar>
