<section id="whatsapp" class="v3-section bg-white dark:bg-[#111f1e] border-y border-[color:var(--color-border-subtle)] dark:border-[#182e2c]" aria-labelledby="v3-whatsapp-title">
    <div class="v3-container">
        <div class="text-center max-w-2xl mx-auto mb-12 lg:mb-16" data-reveal>
            <span class="v3-eyebrow mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[color:var(--color-primary-500)]"></span>
                {{ __('landing-v3.whatsapp.badge') }}
            </span>
            <h2 id="v3-whatsapp-title" class="v3-h2 mb-3">{{ __('landing-v3.whatsapp.title') }}</h2>
            <p class="v3-lead">{{ __('landing-v3.whatsapp.subtitle') }}</p>
        </div>

        <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-start">
            {{-- Triggers --}}
            <div data-reveal>
                <h3 class="text-xl font-extrabold text-[color:var(--color-text-main)] mb-6">إشعارات آلية لكل لحظة مهمة</h3>
                <div class="space-y-4" role="list">
                    @foreach(__('landing-v3.whatsapp.triggers') as $trigger)
                        <article class="v3-card p-5 dark:bg-[#111f1e] dark:border-[#1f3936] group" role="listitem">
                            <div class="flex items-start gap-4">
                                <span class="flex-shrink-0 w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl">
                                    <i class="{{ $trigger['icon'] }}"></i>
                                </span>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-extrabold text-[color:var(--color-text-main)] mb-1">{{ $trigger['title'] }}</h4>
                                    <p class="text-sm text-[color:var(--color-text-secondary)] mb-2">{{ $trigger['desc'] }}</p>
                                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800 border border-[color:var(--color-border-subtle)] dark:border-[#182e2c]">
                                        <p class="text-xs font-medium text-[color:var(--color-text-main)]">
                                            <span class="font-bold text-emerald-600 dark:text-emerald-400">مثال:</span>
                                            <span class="text-[color:var(--color-text-secondary)]"> {{ $trigger['example'] }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            {{-- WhatsApp Mockup + Features --}}
            <div data-reveal style="--reveal-delay: 100ms;">
                {{-- WhatsApp Message Preview --}}
                <div class="v3-card p-5 dark:bg-[#111f1e] dark:border-[#1f3936] mb-6 max-w-md mx-auto lg:mx-0">
                    <div class="v3-wa-bubble dark:bg-emerald-950/30 dark:border-emerald-800/50">
                        <div class="flex items-start gap-3">
                            <span class="w-9 h-9 rounded-full bg-[color:var(--color-success-500)] text-white flex items-center justify-center shrink-0"><i class="fab fa-whatsapp"></i></span>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <span class="text-xs font-extrabold text-[color:var(--color-success-800)] dark:text-emerald-300">Taalimu</span>
                                    <span class="text-[10px] font-semibold text-[color:var(--color-text-muted)]">{{ __('landing-v3.showcase.whatsapp_time') }}</span>
                                </div>
                                <p class="text-[13px] font-medium leading-relaxed text-[color:var(--color-text-main)]">{{ __('landing-v3.showcase.whatsapp_msg') }}</p>
                                <span class="flex items-center justify-end gap-1 mt-2 text-[10px] font-bold text-[color:var(--color-success-700)] dark:text-emerald-400">
                                    {{ __('landing-v3.showcase.whatsapp_via') }}
                                    <i class="fas fa-check-double"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Features --}}
                <h3 class="text-xl font-extrabold text-[color:var(--color-text-main)] mb-4">مصمم للمراكز التعليمية</h3>
                <ul class="space-y-3" role="list">
                    @foreach(__('landing-v3.whatsapp.features') as $feature)
                        <li class="flex items-center gap-3 p-3 v3-card dark:bg-[#111f1e] dark:border-[#1f3936]" role="listitem">
                            <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                <i class="fas fa-check text-sm"></i>
                            </span>
                            <span class="text-sm font-medium text-[color:var(--color-text-main)]">{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>

                {{-- CTA --}}
                <div class="mt-8">
                    <a href="{{ route('register') }}" data-track="v3_wa_cta_clicked" class="v3-btn v3-btn-primary w-full sm:w-auto px-8">
                        {{ __('landing-v3.whatsapp.cta') }}
                        <i class="fas fa-arrow-left text-sm rtl:rotate-0 ltr:rotate-180"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>