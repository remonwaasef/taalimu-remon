<section id="workflow" class="v3-section bg-white dark:bg-[#111f1e]" aria-labelledby="v3-workflow-title">
    <div class="v3-container">
        <div class="text-center max-w-2xl mx-auto mb-12 lg:mb-16" data-reveal>
            <span class="v3-eyebrow mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[color:var(--color-primary-500)]"></span>
                {{ __('landing-v3.workflow.badge') }}
            </span>
            <h2 id="v3-workflow-title" class="v3-h2 mb-3">{{ __('landing-v3.workflow.title') }}</h2>
            <p class="v3-lead">{{ __('landing-v3.workflow.subtitle') }}</p>
        </div>

        <div class="max-w-6xl mx-auto" data-reveal style="--reveal-delay: 100ms;">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6" role="list" aria-label="{{ __('landing-v3.workflow.badge') }}">
                @foreach(__('landing-v3.workflow.steps') as $index => $step)
                    <article class="v3-workflow-step relative group" style="transition-delay: {{ $index * 100 }}ms;" role="listitem">
                        {{-- Connector line (except last) --}}
                        @if($index < 6)
                            <div class="hidden lg:block absolute top-10 -end-6 w-12 h-0.5 bg-gradient-to-r from-[color:var(--color-primary-500)] to-transparent pointer-events-none" aria-hidden="true"></div>
                            <div class="hidden lg:block absolute top-10 -end-1 w-2 h-2 rounded-full bg-[color:var(--color-primary-500)] pointer-events-none" aria-hidden="true"></div>
                        @endif

                        <div class="v3-card p-6 h-full dark:bg-[#111f1e] dark:border-[#1f3936] relative">
                            {{-- Step number --}}
                            <span class="absolute -top-3 -start-3 lg:-start-4 w-8 h-8 rounded-full bg-[color:var(--color-primary-500)] text-white text-[11px] font-extrabold flex items-center justify-center shadow-sm">
                                {{ $index + 1 }}
                            </span>

                            {{-- Icon --}}
                            <div class="w-12 h-12 rounded-2xl bg-[color:var(--color-primary-50)] dark:bg-[color:var(--color-primary-light)] text-[color:var(--color-primary-600)] flex items-center justify-center mb-4 text-xl">
                                <i class="{{ $step['icon'] }}"></i>
                            </div>

                            <h3 class="text-lg font-extrabold text-[color:var(--color-text-main)] mb-2">
                                {{ $step['title'] }}
                            </h3>
                            <p class="text-sm leading-relaxed text-[color:var(--color-text-secondary)]">
                                {{ $step['desc'] }}
                            </p>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Mobile stepper (simplified) --}}
            <div class="lg:hidden mt-8 space-y-3" role="list" aria-label="{{ __('landing-v3.workflow.badge') }} (mobile)">
                @foreach(__('landing-v3.workflow.steps') as $index => $step)
                    <article class="v3-workflow-step flex items-start gap-4 p-4 v3-card dark:bg-[#111f1e] dark:border-[#1f3936]" style="transition-delay: {{ $index * 80 }}ms;" role="listitem">
                        <span class="flex-shrink-0 w-10 h-10 rounded-full bg-[color:var(--color-primary-500)] text-white text-sm font-extrabold flex items-center justify-center shadow-sm">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="{{ $step['icon'] }} text-[color:var(--color-primary-600)]"></i>
                                <h3 class="font-extrabold text-[color:var(--color-text-main)]">{{ $step['title'] }}</h3>
                            </div>
                            <p class="text-sm text-[color:var(--color-text-secondary)]">{{ $step['desc'] }}</p>
                        </div>
                        <i class="fas fa-chevron-left rtl:rotate-180 text-[color:var(--color-text-muted)]"></i>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>