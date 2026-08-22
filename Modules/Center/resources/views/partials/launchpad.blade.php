@php
    $steps = [
        'education_system' => ['icon' => 'fa-map-signs', 'color' => '#2E8B83', 'route' => 'center.settings.index'],
        'instructor' => ['icon' => 'fa-user-tie', 'color' => '#0284c7', 'route' => 'center.instructors.create'],
        'course' => ['icon' => 'fa-book-open', 'color' => '#d97706', 'route' => 'center.courses.create'],
        'student' => ['icon' => 'fa-user-graduate', 'color' => '#16a34a', 'route' => 'center.students.create'],
        'attendance' => ['icon' => 'fa-clipboard-check', 'color' => '#dc2626', 'route' => 'center.attendance.index'],
    ];

    // Find first incomplete step to highlight it
    $highlightStep = null;
    foreach($launchpadSteps as $key => $isDone) {
        if(!$isDone) {
            $highlightStep = $key;
            break;
        }
    }
@endphp

<div class="card launchpad-card mb-6 overflow-hidden relative bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/60 rounded-2xl shadow-sm">
    <!-- Decorative rocket -->
    <div class="absolute top-2 {{ app()->getLocale() == 'ar' ? 'left-6' : 'right-6' }} opacity-5 dark:opacity-10 pointer-events-none text-brand-primary">
        <i class="fas fa-rocket text-7xl -rotate-12"></i>
    </div>

    <div class="card-body p-5 relative z-10">
        {{-- Header & Progress --}}
        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <h5 class="text-base sm:text-lg font-black text-slate-900 dark:text-white m-0">
                        🚀 {{ __('center::dashboard.launchpad.title', ['name' => auth()->user()->name]) }}
                    </h5>
                    @php
                        $hasDemoData = \App\Models\Instructor::where('tenant_id', app('tenant')->id)->where('email', 'like', '%.demo@%')->exists();
                    @endphp

                    @if($hasDemoData)
                        <form action="{{ route('center.demo.reset', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" method="POST" id="deleteRowForm_1" class="m-0">
                            @csrf
                            <button type="button" data-confirm-delete data-form="deleteRowForm_1" class="bg-red-50 dark:bg-red-950/40 border border-dashed border-red-300 dark:border-red-500/40 text-red-600 dark:text-red-400 px-3 py-1 rounded-full text-xs font-bold hover:bg-red-100 transition-colors">
                                <i class="fas fa-trash-alt {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                {{ __('center::dashboard.launchpad.reset_demo') }}
                            </button>
                        </form>
                    @else
                        <form action="{{ route('center.demo.seed', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" method="POST" id="demoDataForm" class="m-0">
                            @csrf
                            <button type="submit" class="bg-brand-50 dark:bg-brand-950/40 border border-dashed border-brand-300 dark:border-brand-500/40 text-brand-primary dark:text-brand-300 px-3 py-1 rounded-full text-xs font-bold hover:bg-brand-100 transition-colors">
                                <i class="fas fa-magic {{ app()->getLocale() == 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                {{ __('center::dashboard.launchpad.explore_demo') }}
                            </button>
                        </form>
                    @endif
                </div>
                <p class="text-slate-500 dark:text-slate-400 text-xs mt-1 mb-0">{{ __('center::dashboard.launchpad.subtitle') }}</p>
            </div>

            {{-- Progress Bar --}}
            <div class="min-w-[200px] flex-initial w-64">
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ __('center::dashboard.launchpad.progress') }}</span>
                    <span class="text-xs font-black text-brand-primary dark:text-brand-300 bg-brand-50 dark:bg-brand-950/50 px-2 py-0.5 rounded-full">{{ $launchpadProgress }}%</span>
                </div>
                <div class="h-2 rounded-full bg-slate-100 dark:bg-slate-900 overflow-hidden border border-slate-200/60 dark:border-slate-700/50">
                    <div class="h-full bg-gradient-to-r from-brand-primary to-emerald-500 rounded-full transition-all duration-500" style="width: {{ $launchpadProgress }}%;"></div>
                </div>
            </div>
        </div>

        {{-- Horizontal Steps Grid (Theme Adaptive) --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 items-stretch">
            @foreach($steps as $key => $data)
                @php
                    $isCompleted = $launchpadSteps[$key] ?? false;
                    $isCurrent = ($key === $highlightStep);
                @endphp
                <div class="
                    p-3.5 rounded-xl flex flex-col items-center text-center transition-all duration-200 hover:-translate-y-0.5
                    {{ $isCompleted ? 'bg-emerald-50/70 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/40' : 
                       ($isCurrent ? 'bg-brand-50/60 dark:bg-brand-950/30 border-2 border-brand-primary shadow-sm shadow-brand-primary/10' : 
                                     'bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700/60') }}
                ">
                    {{-- Step Icon --}}
                    <div class="
                        w-10 h-10 rounded-full flex items-center justify-center text-sm mb-2.5 shrink-0
                        {{ $isCompleted ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-700/50' : 
                           ($isCurrent ? 'bg-white dark:bg-slate-800 text-brand-primary dark:text-brand-300 border border-brand-primary/40' : 
                                         'bg-white dark:bg-slate-800 text-slate-400 dark:text-slate-500 border border-slate-200 dark:border-slate-700/60') }}
                    ">
                        <i class="fas {{ $isCompleted ? 'fa-check' : $data['icon'] }}"></i>
                    </div>

                    {{-- Step Title --}}
                    <h6 class="
                        text-xs font-black mb-2.5 leading-snug
                        {{ $isCompleted ? 'text-emerald-700 dark:text-emerald-400' : 
                           ($isCurrent ? 'text-slate-900 dark:text-white' : 
                                         'text-slate-600 dark:text-slate-400') }}
                    ">
                        {{ __('center::dashboard.launchpad.steps.'.$key.'.title') }}
                    </h6>

                    {{-- Action Button / Status --}}
                    <div class="mt-auto w-full">
                        @if($isCompleted)
                            <div class="text-emerald-600 dark:text-emerald-400 text-[11px] font-black inline-flex items-center gap-1">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ __('center::launchpad.demo_data_ready') }}</span>
                            </div>
                        @elseif($key === 'education_system')
                            <button type="button" 
                                    class="w-full py-1.5 px-2 rounded-lg text-xs font-bold transition-all cursor-pointer {{ $isCurrent ? 'bg-brand-primary text-white hover:bg-brand-secondary shadow-sm shadow-brand-primary/20' : 'bg-slate-200/80 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-300' }}"
                                    @click="$dispatch('open-modal', 'education-system-modal')">
                               {{ __('center::dashboard.launchpad.action') }}
                            </button>
                        @else
                            <a href="{{ route($data['route'], ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" 
                               class="w-full block py-1.5 px-2 rounded-lg text-xs font-bold transition-all no-underline {{ $isCurrent ? 'bg-brand-primary text-white hover:bg-brand-secondary shadow-sm shadow-brand-primary/20' : 'bg-slate-200/80 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-300' }}">
                               {{ __('center::dashboard.launchpad.action') }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Quick Education System Setup Modal (Taalimu UI Design System Component) -->
<x-ui.modal id="education-system-modal" size="md">
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-950/50 flex items-center justify-center text-brand-primary dark:text-brand-300">
                <i class="fas fa-map-signs text-sm"></i>
            </div>
            <h5 class="font-black text-base text-slate-900 dark:text-white m-0">
                {{ __('center::dashboard.launchpad.steps.education_system.title') }}
            </h5>
        </div>
    </x-slot>

    <form action="{{ route('center.settings.apply-template', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" method="POST" id="educationSystemForm" class="space-y-4">
        @csrf
        <div>
            <p class="text-slate-500 dark:text-slate-400 text-xs mb-3">
                {{ __('center::dashboard.launchpad.steps.education_system.desc') }}
            </p>
            
            <label class="form-label font-bold text-xs text-slate-700 dark:text-slate-300 mb-1.5 block">
                {{ __('center::launchpad.select_data_type') }}
            </label>
            <select name="template_key" class="w-full px-3 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all outline-none" required>
                <option value="">{{ __('center::launchpad.select_placeholder') }}</option>
                @foreach(config('academic.templates', []) as $tKey => $template)
                    <option value="{{ $tKey }}">{{ __($template['name']) }}</option>
                @endforeach
            </select>
        </div>

        <div class="bg-brand-50/80 dark:bg-brand-950/40 border border-brand-200/70 dark:border-brand-800/40 rounded-xl text-xs p-3 text-brand-primary dark:text-brand-300 flex items-start gap-2.5">
            <i class="fas fa-info-circle shrink-0 mt-0.5"></i>
            <span>{{ __('center::launchpad.demo_data_hint') }}</span>
        </div>

        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
            <button type="button" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors" @click="show = false">
                {{ __('center::launchpad.cancel') }}
            </button>
            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-brand-primary hover:bg-brand-secondary shadow-sm shadow-brand-primary/20 transition-all flex items-center gap-1.5" id="submitTemplateBtn">
                <span class="normal-state flex items-center gap-1.5">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ __('center::launchpad.start_generation') }}</span>
                </span>
                <span class="loading-state d-none flex items-center gap-1.5">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>{{ __('center::launchpad.generating') }}</span>
                </span>
            </button>
        </div>
    </form>
</x-ui.modal>

<script>
    document.getElementById('educationSystemForm')?.addEventListener('submit', function() {
        const btn = document.getElementById('submitTemplateBtn');
        if (btn) {
            btn.disabled = true;
            btn.querySelector('.normal-state')?.classList.add('d-none');
            btn.querySelector('.loading-state')?.classList.remove('d-none');
        }
    });
</script>
