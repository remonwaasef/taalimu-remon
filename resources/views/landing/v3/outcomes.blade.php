<section id="outcomes" class="v3-section" aria-labelledby="v3-outcomes-title">
    <div class="v3-container">
        <div class="text-center max-w-2xl mx-auto mb-12 lg:mb-16" data-reveal>
            <span class="v3-eyebrow mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[color:var(--color-primary-500)]"></span>
                {{ __('landing-v3.outcomes.badge') }}
            </span>
            <h2 id="v3-outcomes-title" class="v3-h2">{{ __('landing-v3.outcomes.title') }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            @foreach(__('landing-v3.outcomes.items') as $index => $outcome)
                <article class="v3-card v3-feature-card p-7 lg:p-8 flex flex-col h-full dark:bg-[#111f1e] dark:border-[#1f3936]" data-reveal style="--reveal-delay: {{ $index * 100 }}ms;">
                    <div class="w-12 h-12 rounded-2xl bg-[color:var(--color-primary-50)] dark:bg-[color:var(--color-primary-light)] text-[color:var(--color-primary-600)] flex items-center justify-center mb-5 text-xl">
                        <i class="{{ $outcome['icon'] }}"></i>
                    </div>

                    <span class="v3-chip v3-chip-primary self-start mb-4">{{ $outcome['tag'] }}</span>

                    <h3 class="text-lg font-extrabold text-[color:var(--color-text-main)] mb-3">{{ $outcome['title'] }}</h3>

                    <p class="text-sm leading-relaxed text-[color:var(--color-text-secondary)] flex-1 mb-5">{{ $outcome['desc'] }}</p>

                    <div class="pt-4 border-t border-[color:var(--color-border-subtle)] flex items-center gap-2">
                        <span class="v3-counter text-xl font-extrabold text-[color:var(--color-primary-600)]" data-target="{{ $outcome['metric'] }}" style="animation-delay: {{ $index * 200 + 500 }}ms;">0</span>
                        <span class="text-xs font-bold uppercase tracking-wide text-[color:var(--color-text-muted)]">{{ $outcome['metric'] }}</span>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>