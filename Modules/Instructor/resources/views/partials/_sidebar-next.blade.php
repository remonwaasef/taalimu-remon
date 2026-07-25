{{-- Shared Instructor Sidebar Partial --}}
{{-- Usage: @include('instructor::partials._sidebar-next', ['active' => 'dashboard']) --}}

@php
    $active = $active ?? 'dashboard';
@endphp

<x-ui.sidebar brandName="Taalimu">
    <div class="space-y-1">
        <a href="{{ route('instructor.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $active === 'dashboard' ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-home w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.dashboard') }}</span>
        </a>

        <div class="pt-4 pb-1 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('instructor::sidebar.teaching') }}</div>

        <a href="{{ route('instructor.students.list') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $active === 'students' ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-user-graduate w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.students') }}</span>
        </a>

        <a href="{{ route('instructor.groups.list') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $active === 'groups' ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-users w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.groups') }}</span>
        </a>

        <a href="{{ route('instructor.schedules.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $active === 'schedules' ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-calendar-alt w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.schedules') }}</span>
        </a>

        <a href="{{ route('instructor.online_classes.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $active === 'online_classes' ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-video w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.online_classes') }}</span>
        </a>

        <a href="{{ route('instructor.attendance.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $active === 'attendance' ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-clipboard-check w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.attendance') }}</span>
        </a>

        <a href="{{ route('instructor.reports.students') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $active === 'reports' ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-chart-bar w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.reports') ?? 'Reports' }}</span>
        </a>

        <div class="pt-4 pb-1 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('instructor::sidebar.account') }}</div>

        <a href="{{ route('instructor.billing') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $active === 'billing' ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
            <i class="fas fa-wallet w-4 text-center"></i>
            <span>{{ __('instructor::sidebar.billing') }}</span>
        </a>

        <a href="{{ route('instructor.settings') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-colors
                  {{ $active === 'settings' ? 'text-brand-primary bg-brand-50 dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
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
