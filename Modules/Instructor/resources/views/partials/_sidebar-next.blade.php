@php
    use Illuminate\Support\Facades\Route;

    $active = $active ?? 'dashboard';

    // Route-based active state detection (more reliable than string comparison)
    $isDashboardActive = Route::currentRouteNamed('instructor.dashboard');
    $isStudentsActive = Route::currentRouteNamed('instructor.students.*');
    $isGroupsActive = Route::currentRouteNamed('instructor.groups.*');
    $isSchedulesActive = Route::currentRouteNamed('instructor.schedules.*');
    $isOnlineClassesActive = Route::currentRouteNamed('instructor.online_classes.*');
    $isRecordingsActive = Route::currentRouteNamed('instructor.recordings.*');
    $isAttendanceActive = Route::currentRouteNamed('instructor.attendance.*');
    $isReportsActive = Route::currentRouteNamed('instructor.reports', 'instructor.reports.students', 'instructor.reports.payments');
    $isBillingActive = Route::currentRouteNamed('instructor.billing');
    $isSettingsActive = Route::currentRouteNamed('instructor.settings');
@endphp

<x-ui.sidebar brandName="Taalimu">
    <div class="space-y-1.5">
        {{-- Dashboard --}}
        <a href="{{ route('instructor.dashboard') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $isDashboardActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('instructor::sidebar.dashboard') }}">
            <i class="fas fa-home w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.dashboard') }}</span>
        </a>

        {{-- ═══════════ TEACHING ═══════════ --}}
        <div class="sidebar-section-header pt-4 pb-1 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('instructor::sidebar.teaching') }}</div>

        <a href="{{ route('instructor.students.list') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $isStudentsActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('instructor::sidebar.students') }}">
            <i class="fas fa-user-graduate w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.students') }}</span>
        </a>

        <a href="{{ route('instructor.groups.list') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $isGroupsActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('instructor::sidebar.groups') }}">
            <i class="fas fa-users w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.groups') }}</span>
        </a>

        <a href="{{ route('instructor.schedules.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $isSchedulesActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('instructor::sidebar.schedules') }}">
            <i class="fas fa-calendar-alt w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.schedules') }}</span>
        </a>

<a href="{{ route('instructor.online_classes.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                 {{ $isOnlineClassesActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('instructor::sidebar.online_classes') }}">
            <i class="fas fa-video w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.online_classes') }}</span>
        </a>

        <a href="{{ route('instructor.recordings.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                 {{ $isRecordingsActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('instructor::sidebar.recordings') }}">
            <i class="fas fa-clapperboard w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.recordings') }}</span>
        </a>

        <a href="{{ route('instructor.attendance.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $isAttendanceActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('instructor::sidebar.attendance') }}">
            <i class="fas fa-clipboard-check w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.attendance') }}</span>
        </a>

        {{-- ═══════════ INSIGHTS ═══════════ --}}
        <div class="sidebar-section-header pt-4 pb-1 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('instructor::sidebar.insights') ?? 'Insights' }}</div>

        <a href="{{ route('instructor.reports.students') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $isReportsActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('instructor::sidebar.reports') }}">
            <i class="fas fa-chart-bar w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.reports') }}</span>
        </a>

        {{-- ═══════════ ACCOUNT ═══════════ --}}
        <div class="sidebar-section-header pt-4 pb-1 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('instructor::sidebar.account') }}</div>

        <a href="{{ route('instructor.billing') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $isBillingActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('instructor::sidebar.billing') }}">
            <i class="fas fa-wallet w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.billing') }}</span>
        </a>

        <a href="{{ route('instructor.settings') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $isSettingsActive ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}"
           title="{{ __('instructor::sidebar.settings') }}">
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
