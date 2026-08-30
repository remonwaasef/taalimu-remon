@php
    $steps = [
        'education_system' => ['icon' => 'fa-graduation-cap', 'color' => 'brand', 'route' => 'center.settings.index'],
        'instructor' => ['icon' => 'fa-chalkboard-teacher', 'color' => 'brand', 'route' => 'center.instructors.create'],
        'course' => ['icon' => 'fa-book-open', 'color' => 'brand', 'route' => 'center.courses.create'],
        'student' => ['icon' => 'fa-user-graduate', 'color' => 'brand', 'route' => 'center.students.create'],
        'attendance' => ['icon' => 'fa-clipboard-check', 'color' => 'brand', 'route' => 'center.attendance.index'],
    ];

    // Find first incomplete step to highlight it
    $highlightStep = null;
    foreach($launchpadSteps as $key => $isDone) {
        if(!$isDone) {
            $highlightStep = $key;
            break;
        }
    }

    $hasDemoData = \App\Models\Instructor::where('tenant_id', app('tenant')->id)->where('email', 'like', '%.demo@%')->exists();
@endphp

<div class="mb-8 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs relative overflow-hidden font-inter transition-all duration-200">
    <!-- Top Progress Gradient Bar -->
    <div class="h-1 w-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
        <div class="h-full bg-gradient-to-r from-brand-600 via-brand-primary to-brand-400 rounded-full transition-all duration-700 ease-out" style="width: {{ $launchpadProgress }}%;"></div>
    </div>

    <div class="p-5 sm:p-6">
        {{-- Header & Controls --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-5 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-900/30 text-brand-primary dark:text-brand-300 flex items-center justify-center text-lg shrink-0 border border-brand-100 dark:border-brand-800/40 shadow-xs">
                    <i class="fas fa-rocket"></i>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h4 class="text-base font-extrabold text-slate-900 dark:text-slate-100 tracking-tight leading-tight">
                            {{ __('center::dashboard.launchpad.title', ['name' => auth()->user()->name]) }}
                        </h4>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-50 dark:bg-brand-900/30 text-brand-primary dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/40">
                            <span>{{ $launchpadProgress }}%</span>
                            <span class="text-[10px] opacity-75">{{ __('center::dashboard.launchpad.progress') }}</span>
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-snug">
                        {{ __('center::dashboard.launchpad.subtitle') }}
                    </p>
                </div>
            </div>

            {{-- Action Tools: Demo Seed / Reset --}}
            <div class="flex items-center gap-2 shrink-0">
                @if($hasDemoData)
                    <form action="{{ route('center.demo.reset', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" method="POST" id="deleteRowForm_1" class="m-0">
                        @csrf
                        <button type="button" data-confirm-delete data-form="deleteRowForm_1" class="px-3 py-1.5 rounded-xl text-xs font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/40 hover:bg-red-100 dark:hover:bg-red-900/40 border border-red-200/60 dark:border-red-800/40 transition-colors flex items-center gap-1.5">
                            <i class="fas fa-trash-alt text-[10px]"></i>
                            <span>{{ __('center::dashboard.launchpad.reset_demo') }}</span>
                        </button>
                    </form>
                @else
                    <form action="{{ route('center.demo.seed', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" method="POST" id="demoDataForm" class="m-0">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold text-brand-primary dark:text-brand-300 bg-brand-50 dark:bg-brand-900/30 hover:bg-brand-100 dark:hover:bg-brand-900/50 border border-brand-200/60 dark:border-brand-800/40 transition-colors flex items-center gap-1.5">
                            <i class="fas fa-magic text-[10px]"></i>
                            <span>{{ __('center::dashboard.launchpad.explore_demo') }}</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- 5 Step Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 pt-5">
            @foreach($steps as $key => $data)
                @php
                    $isCompleted = $launchpadSteps[$key] ?? false;
                    $isCurrent = ($key === $highlightStep);
                @endphp
                <div class="
                    p-3.5 rounded-xl flex flex-col justify-between transition-all duration-200 relative group
                    {{ $isCompleted 
                        ? 'bg-slate-50/60 dark:bg-slate-800/40 border border-slate-200/60 dark:border-slate-700/60' 
                        : ($isCurrent 
                            ? 'bg-brand-50/50 dark:bg-brand-900/20 border-2 border-brand-primary shadow-xs' 
                            : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800') }}
                ">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="
                                w-8 h-8 rounded-lg flex items-center justify-center text-xs shrink-0
                                {{ $isCompleted 
                                    ? 'bg-brand-50 text-brand-primary dark:bg-brand-900/40 dark:text-brand-300' 
                                    : ($isCurrent 
                                        ? 'bg-brand-primary text-white shadow-xs' 
                                        : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400') }}
                            ">
                                <i class="fas {{ $isCompleted ? 'fa-check' : $data['icon'] }}"></i>
                            </div>

                            <span class="text-[10px] font-bold {{ $isCompleted ? 'text-brand-primary dark:text-brand-300' : 'text-slate-400' }}">
                                {{ $loop->iteration }}/5
                            </span>
                        </div>

                        <h5 class="text-xs font-bold mb-1 leading-snug {{ $isCompleted ? 'text-slate-500 dark:text-slate-400 line-through' : 'text-slate-900 dark:text-slate-100' }}">
                            {{ __('center::dashboard.launchpad.steps.'.$key.'.title') }}
                        </h5>
                    </div>

                    <div class="pt-3 mt-auto">
                        @if($isCompleted)
                            <div class="text-[11px] font-bold text-brand-primary dark:text-brand-300 flex items-center gap-1">
                                <i class="fas fa-check-circle text-[10px]"></i>
                                <span>{{ __('center::launchpad.demo_data_ready') }}</span>
                            </div>
                        @elseif($key === 'education_system')
                            <button
                                type="button" 
                                class="w-full py-1.5 px-3 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1.5 {{ $isCurrent ? 'bg-brand-primary text-white hover:bg-brand-600 shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200' }}"
                                @click="$dispatch('open-modal', 'education-system-modal')"
                            >
                                <span>{{ __('center::dashboard.launchpad.action') }}</span>
                                <i class="fas fa-arrow-left text-[9px] rtl:rotate-0 ltr:rotate-180"></i>
                            </button>
                        @else
                            <a
                                href="{{ route($data['route'], ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" 
                                class="w-full py-1.5 px-3 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1.5 no-underline {{ $isCurrent ? 'bg-brand-primary text-white hover:bg-brand-600 shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200' }}"
                            >
                                <span>{{ __('center::dashboard.launchpad.action') }}</span>
                                <i class="fas fa-arrow-left text-[9px] rtl:rotate-0 ltr:rotate-180"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Quick Education System Setup Modal -->
<x-ui.modal id="education-system-modal" size="md">
    <x-slot name="header">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-brand-50 dark:bg-brand-900/30 flex items-center justify-center text-brand-primary dark:text-brand-300">
                <i class="fas fa-graduation-cap text-sm"></i>
            </div>
            <h4 class="font-bold text-base text-slate-900 dark:text-slate-100 m-0">
                {{ __('center::dashboard.launchpad.steps.education_system.title') }}
            </h4>
        </div>
    </x-slot>

    <form action="{{ route('center.settings.apply-template', ['tenant' => $tenant->domain ?? app('tenant')?->domain]) }}" method="POST" id="educationSystemForm" class="space-y-4">
        @csrf
        <div>
            <p class="text-slate-500 dark:text-slate-400 text-xs mb-3">
                {{ __('center::dashboard.launchpad.steps.education_system.desc') }}
            </p>
            
            <x-ui.form-field label="{{ __('center::launchpad.select_data_type') }}" name="template_key" :required="true">
                <x-ui.select name="template_key" placeholder="{{ __('center::launchpad.select_placeholder') }}" :required="true">
                    @foreach(config('academic.templates', []) as $tKey => $template)
                        <option value="{{ $tKey }}">{{ __($template['name']) }}</option>
                    @endforeach
                </x-ui.select>
            </x-ui.form-field>
        </div>

        <div class="bg-brand-50/80 dark:bg-brand-900/30 border border-brand-200/70 dark:border-brand-800/40 rounded-xl text-xs p-3 text-brand-700 dark:text-brand-300 flex items-start gap-2.5">
            <i class="fas fa-info-circle shrink-0 mt-0.5"></i>
            <span>{{ __('center::launchpad.demo_data_hint') }}</span>
        </div>

        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
            <x-ui.button variant="ghost" size="sm" @click="$dispatch('close-modal', 'education-system-modal')">
                {{ __('center::launchpad.cancel') }}
            </x-ui.button>
            <x-ui.button variant="primary" size="sm" type="submit" id="submitTemplateBtn" icon="fas fa-check-circle">
                {{ __('center::launchpad.start_generation') }}
            </x-ui.button>
        </div>
    </form>
</x-ui.modal>
