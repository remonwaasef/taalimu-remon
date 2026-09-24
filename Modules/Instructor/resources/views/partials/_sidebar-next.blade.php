@php
    use Illuminate\Support\Facades\Route;

    $active = $active ?? 'dashboard';
    $tenant = app()->bound('tenant') ? app('tenant') : null;

    // Route-based active state detection
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
    $isWhatsappActive = Route::currentRouteNamed('instructor.whatsapp.*');

    $activeLink = 'text-brand-primary bg-brand-100 dark:bg-brand-900/40 font-semibold';
    $inactiveLink = 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 font-medium';
@endphp

<x-ui.sidebar brandName="Taalimu">
    <div class="space-y-1.5">
        {{-- Dashboard --}}
        <a href="{{ route('instructor.dashboard') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-[10px] text-xs transition-colors
                  {{ $isDashboardActive ? $activeLink : $inactiveLink }}"
           title="{{ __('instructor::sidebar.dashboard') }}">
            <i class="fas fa-home w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.dashboard') }}</span>
        </a>

        {{-- ═══════════ TEACHING ═══════════ --}}
        <div class="sidebar-section-header pt-4 pb-1 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('instructor::sidebar.teaching') }}</div>

        <a href="{{ route('instructor.students.list') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-[10px] text-xs transition-colors
                  {{ $isStudentsActive ? $activeLink : $inactiveLink }}"
           title="{{ __('instructor::sidebar.students') }}">
            <i class="fas fa-user-graduate w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.students') }}</span>
        </a>

        <a href="{{ route('instructor.groups.list') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-[10px] text-xs transition-colors
                  {{ $isGroupsActive ? $activeLink : $inactiveLink }}"
           title="{{ __('instructor::sidebar.groups') }}">
            <i class="fas fa-users w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.groups') }}</span>
        </a>

        <a href="{{ route('instructor.schedules.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-[10px] text-xs transition-colors
                  {{ $isSchedulesActive ? $activeLink : $inactiveLink }}"
           title="{{ __('instructor::sidebar.schedules') }}">
            <i class="fas fa-calendar-alt w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.schedules') }}</span>
        </a>

        <a href="{{ route('instructor.online_classes.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-[10px] text-xs transition-colors
                 {{ $isOnlineClassesActive ? $activeLink : $inactiveLink }}"
           title="{{ __('instructor::sidebar.online_classes') }}">
            <i class="fas fa-video w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.online_classes') }}</span>
        </a>

        <a href="{{ route('instructor.recordings.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-[10px] text-xs transition-colors
                 {{ $isRecordingsActive ? $activeLink : $inactiveLink }}"
           title="{{ __('instructor::sidebar.recordings') }}">
            <i class="fas fa-clapperboard w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.recordings') }}</span>
        </a>

        <a href="{{ route('instructor.attendance.index') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-[10px] text-xs transition-colors
                  {{ $isAttendanceActive ? $activeLink : $inactiveLink }}"
           title="{{ __('instructor::sidebar.attendance') }}">
            <i class="fas fa-clipboard-check w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.attendance') }}</span>
        </a>

        {{-- ═══════════ INSIGHTS ═══════════ --}}
        <div class="sidebar-section-header pt-4 pb-1 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('instructor::sidebar.insights') ?? 'Insights' }}</div>

        <a href="{{ route('instructor.reports.students') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-[10px] text-xs transition-colors
                  {{ $isReportsActive ? $activeLink : $inactiveLink }}"
           title="{{ __('instructor::sidebar.reports') }}">
            <i class="fas fa-chart-bar w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.reports') }}</span>
        </a>

        {{-- ═══════════ ACCOUNT & SETTINGS ═══════════ --}}
        <div class="sidebar-section-header pt-4 pb-1 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ __('instructor::sidebar.account') }}</div>

        <a href="{{ route('instructor.billing') }}"
           class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-[10px] text-xs transition-colors
                  {{ $isBillingActive ? $activeLink : $inactiveLink }}"
           title="{{ __('instructor::sidebar.billing') }}">
            <i class="fas fa-wallet w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.billing') }}</span>
        </a>

        {{-- Settings with expandable sub-links --}}
        <div x-data="{ settingsOpen: {{ ($isSettingsActive || $isWhatsappActive) ? 'true' : 'false' }} }">
            <button @click="settingsOpen = !settingsOpen"
                    class="nav-link flex items-center justify-between w-full px-3 py-2.5 rounded-[10px] text-xs transition-colors cursor-pointer
                           {{ ($isSettingsActive || $isWhatsappActive) ? $activeLink : $inactiveLink }}"
                    title="{{ __('instructor::sidebar.settings') }}">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <i class="fas fa-cog w-4 text-center"></i>
                        @if(!($tenant->logo ?? false) || !($tenant->phone ?? false))
                            <span class="absolute -top-1 -right-1 w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                        @endif
                    </div>
                    <span>{{ __('instructor::sidebar.settings') }}</span>
                </div>
                <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" :class="settingsOpen ? 'rotate-180' : ''"></i>
            </button>

            {{-- Sub-links --}}
            <div x-show="settingsOpen" x-collapse x-cloak class="mt-1 ms-4 space-y-0.5 border-s-2 border-slate-200 dark:border-slate-700 ps-3">
                <a href="{{ route('instructor.settings') }}#general"
                   class="flex items-center gap-2.5 px-2.5 py-2 rounded-[8px] text-[11px] font-medium transition-colors
                          {{ $isSettingsActive && !request()->has('tab') ? 'text-brand-primary bg-brand-100/60 dark:bg-brand-900/30 font-semibold' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                    <i class="fas fa-sliders-h w-3.5 text-center text-[10px]"></i>
                    <span>{{ __('instructor::sidebar.settings_general') }}</span>
                </a>

                <a href="{{ route('instructor.settings') }}#whatsapp"
                   class="flex items-center gap-2.5 px-2.5 py-2 rounded-[8px] text-[11px] font-medium transition-colors
                          {{ $isWhatsappActive ? 'text-brand-primary bg-brand-100/60 dark:bg-brand-900/30 font-semibold' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                    <i class="fab fa-whatsapp w-3.5 text-center text-[10px]"></i>
                    <span>{{ __('instructor::sidebar.settings_whatsapp') }}</span>
                </a>

                <a href="{{ route('instructor.settings') }}#email"
                   class="flex items-center gap-2.5 px-2.5 py-2 rounded-[8px] text-[11px] font-medium transition-colors
                          text-slate-500 hover:text-slate-700 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800">
                    <i class="fas fa-envelope w-3.5 text-center text-[10px]"></i>
                    <span>{{ __('instructor::sidebar.settings_email') }}</span>
                </a>

                <a href="{{ route('instructor.settings') }}#reminders"
                   class="flex items-center gap-2.5 px-2.5 py-2 rounded-[8px] text-[11px] font-medium transition-colors
                          text-slate-500 hover:text-slate-700 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800">
                    <i class="fas fa-bell w-3.5 text-center text-[10px]"></i>
                    <span>{{ __('instructor::sidebar.settings_reminders') }}</span>
                </a>

                <a href="{{ route('instructor.settings') }}#subscription"
                   class="flex items-center gap-2.5 px-2.5 py-2 rounded-[8px] text-[11px] font-medium transition-colors
                          text-slate-500 hover:text-slate-700 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800">
                    <i class="fas fa-credit-card w-3.5 text-center text-[10px]"></i>
                    <span>{{ __('instructor::sidebar.settings_subscription') }}</span>
                </a>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <div class="p-3 rounded-[12px] bg-brand-50/70 dark:bg-brand-900/20 border border-brand-100 dark:border-brand-800/40">
            <div class="flex items-center gap-2 mb-1">
                <i class="fas fa-crown text-brand-primary text-xs"></i>
                <span class="font-bold text-xs text-brand-primary">{{ __('instructor::sidebar.upgrade_title') }}</span>
            </div>
            <p class="text-[11px] text-slate-500 mb-2 leading-tight">{{ __('instructor::sidebar.upgrade_desc') }}</p>
            <x-ui.button variant="primary" size="sm" class="w-full" href="{{ route('instructor.billing') }}">
                {{ __('instructor::sidebar.upgrade_btn') }}
            </x-ui.button>
        </div>
    </x-slot>
</x-ui.sidebar>
