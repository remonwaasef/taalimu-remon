<section id="value" class="v2-section" aria-labelledby="v2-value-title">
    <div class="v2-container">
        <div class="text-center max-w-2xl mx-auto mb-12 lg:mb-16" data-reveal>
            <span class="v2-eyebrow mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[color:var(--color-primary-500)]"></span>
                {{ __('landing-v2.value.badge') }}
            </span>
            <h2 id="v2-value-title" class="v2-h2">{{ __('landing-v2.value.title') }}</h2>
        </div>

        <div class="grid lg:grid-cols-5 gap-6 lg:gap-8 items-stretch">

            {{-- Primary outcome: students & attendance --}}
            <article class="lg:col-span-3 v2-card p-7 lg:p-9 flex flex-col relative overflow-hidden dark:bg-[#111f1e] dark:border-[#1f3936]" data-reveal>
                <div class="pointer-events-none absolute -top-20 -end-20 w-64 h-64 rounded-full blur-3xl opacity-60 dark:opacity-20" style="background: radial-gradient(closest-side, rgba(46,139,131,0.2), transparent);" aria-hidden="true"></div>
                <span class="v2-chip v2-chip-primary self-start mb-4">{{ __('landing-v2.value.items.primary.tag') }}</span>
                <h3 class="text-xl lg:text-2xl font-extrabold text-[color:var(--color-text-main)] mb-3">{{ __('landing-v2.value.items.primary.title') }}</h3>
                <p class="text-sm lg:text-[0.95rem] font-medium leading-relaxed text-[color:var(--color-text-secondary)] mb-6">{{ __('landing-v2.value.items.primary.desc') }}</p>

                <ul class="space-y-3 mt-auto">
                    @foreach(__('landing-v2.value.items.primary.points') as $point)
                        <li class="flex items-center gap-2.5 text-[13px] font-bold text-[color:var(--color-text-main)]">
                            <span class="w-5 h-5 rounded-full bg-[color:var(--color-primary-50)] dark:bg-[color:var(--color-primary-light)] text-[color:var(--color-primary-600)] flex items-center justify-center text-[9px] shrink-0"><i class="fas fa-check"></i></span>
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>

                {{-- Mini QR check-in strip (verified feature, token-styled) --}}
                <div class="mt-7 flex items-center gap-3 rounded-xl bg-[color:var(--color-bg-main)] dark:bg-[#0b1312] border border-[color:var(--color-border-subtle)] dark:border-[#182e2c] px-4 py-3">
                    <span class="w-9 h-9 rounded-lg bg-[color:var(--color-primary-600)] text-white flex items-center justify-center shadow-sm"><i class="fas fa-qrcode"></i></span>
                    <span class="flex-1 min-w-0">
                        <span class="block text-xs font-extrabold text-[color:var(--color-text-main)]">{{ __('landing-v2.hero.float_qr') }}</span>
                        <span class="block text-[10px] font-semibold text-[color:var(--color-text-muted)]">{{ __('landing-v2.hero.float_qr_sub') }}</span>
                    </span>
                    <span class="v2-chip v2-chip-success"><i class="fas fa-check"></i></span>
                </div>
            </article>

            {{-- Supporting outcomes --}}
            <div class="lg:col-span-2 grid gap-6 lg:gap-8">
                <article class="v2-card p-7 flex flex-col dark:bg-[#111f1e] dark:border-[#1f3936]" data-reveal style="--reveal-delay: 100ms;">
                    <span class="v2-chip v2-chip-primary self-start mb-4">{{ __('landing-v2.value.items.money.tag') }}</span>
                    <h3 class="text-lg font-extrabold text-[color:var(--color-text-main)] mb-2.5">{{ __('landing-v2.value.items.money.title') }}</h3>
                    <p class="text-[13px] font-medium leading-relaxed text-[color:var(--color-text-secondary)]">{{ __('landing-v2.value.items.money.desc') }}</p>
                </article>

                <article class="v2-card p-7 flex flex-col dark:bg-[#111f1e] dark:border-[#1f3936]" data-reveal style="--reveal-delay: 200ms;">
                    <span class="v2-chip v2-chip-success self-start mb-4">{{ __('landing-v2.value.items.peace.tag') }}</span>
                    <h3 class="text-lg font-extrabold text-[color:var(--color-text-main)] mb-2.5">{{ __('landing-v2.value.items.peace.title') }}</h3>
                    <p class="text-[13px] font-medium leading-relaxed text-[color:var(--color-text-secondary)]">{{ __('landing-v2.value.items.peace.desc') }}</p>
                </article>
            </div>
        </div>
    </div>
</section>
