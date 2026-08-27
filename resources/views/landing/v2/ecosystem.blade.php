<section class="v2-section bg-white dark:bg-[#111f1e] border-y border-[color:var(--color-border-subtle)] dark:border-[#182e2c]" aria-labelledby="v2-eco-title">
    <div class="v2-container">
        <div class="text-center max-w-2xl mx-auto mb-12 lg:mb-16" data-reveal>
            <span class="v2-eyebrow mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[color:var(--color-primary-500)]"></span>
                {{ __('landing-v2.ecosystem.badge') }}
            </span>
            <h2 id="v2-eco-title" class="v2-h2 mb-3">{{ __('landing-v2.ecosystem.title') }}</h2>
            <p class="v2-lead">{{ __('landing-v2.ecosystem.subtitle') }}</p>
        </div>

        <div class="max-w-4xl mx-auto" data-reveal style="--reveal-delay: 100ms;">
            {{-- Row 1: Center + Teacher --}}
            <div class="flex items-center gap-3 sm:gap-4 mb-6 sm:mb-10">
                <div class="v2-node flex-1 dark:bg-[#142524] dark:border-[#1f3936]">
                    <span class="w-11 h-11 rounded-2xl bg-[color:var(--color-primary-50)] dark:bg-[color:var(--color-primary-light)] text-[color:var(--color-primary-600)] flex items-center justify-center shrink-0 text-lg shadow-sm">
                        <i class="fas fa-school"></i>
                    </span>
                    <span class="min-w-0">
                        <span class="block text-sm font-extrabold text-[color:var(--color-text-main)]">{{ __('landing-v2.ecosystem.center_role') }}</span>
                        <span class="block text-[11px] font-semibold text-[color:var(--color-text-muted)] truncate">{{ __('landing-v2.ecosystem.center_line') }}</span>
                    </span>
                </div>
                <div class="v2-connector hidden sm:block" aria-hidden="true"></div>
                <div class="v2-node flex-1 dark:bg-[#142524] dark:border-[#1f3936]">
                    <span class="w-11 h-11 rounded-2xl bg-[color:var(--color-primary-50)] dark:bg-[color:var(--color-primary-light)] text-[color:var(--color-primary-600)] flex items-center justify-center shrink-0 text-lg shadow-sm">
                        <i class="fas fa-chalkboard-user"></i>
                    </span>
                    <span class="min-w-0">
                        <span class="block text-sm font-extrabold text-[color:var(--color-text-main)]">{{ __('landing-v2.ecosystem.teacher_role') }}</span>
                        <span class="block text-[11px] font-semibold text-[color:var(--color-text-muted)] truncate">{{ __('landing-v2.ecosystem.teacher_line') }}</span>
                    </span>
                </div>
            </div>

            {{-- Row 2: Student + Hub + Parent (hub center on desktop) --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 sm:gap-4">
                <div class="v2-node w-full sm:w-56 dark:bg-[#142524] dark:border-[#1f3936]">
                    <span class="w-11 h-11 rounded-2xl bg-[color:var(--color-primary-50)] dark:bg-[color:var(--color-primary-light)] text-[color:var(--color-primary-600)] flex items-center justify-center shrink-0 text-lg shadow-sm">
                        <i class="fas fa-user-graduate"></i>
                    </span>
                    <span class="min-w-0">
                        <span class="block text-sm font-extrabold text-[color:var(--color-text-main)]">{{ __('landing-v2.ecosystem.student_role') }}</span>
                        <span class="block text-[11px] font-semibold text-[color:var(--color-text-muted)] truncate">{{ __('landing-v2.ecosystem.student_line') }}</span>
                    </span>
                </div>

                <div class="flex items-center gap-3 sm:gap-4 w-full sm:w-auto justify-center">
                    <div class="v2-connector hidden sm:block w-10" aria-hidden="true"></div>
                    <div class="v2-hub !bg-gradient-to-br !from-[#2E8B83] !to-[#1E5E58] !shadow-[0_16px_36px_-8px_rgba(46,139,131,0.5)]">
                        <img src="{{ asset('images/brand/logo-icon.png') }}" alt="Taalimu" class="h-10 w-auto brightness-200" width="40" height="40" loading="lazy">
                        <span class="text-[12px] font-extrabold tracking-wide text-white">{{ __('landing-v2.ecosystem.hub') }}</span>
                    </div>
                    <div class="v2-connector hidden sm:block w-10" aria-hidden="true"></div>
                </div>

                <div class="v2-node w-full sm:w-56 dark:bg-[#142524] dark:border-[#1f3936]">
                    <span class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400 flex items-center justify-center shrink-0 text-lg shadow-sm">
                        <i class="fas fa-user-shield"></i>
                    </span>
                    <span class="min-w-0">
                        <span class="block text-sm font-extrabold text-[color:var(--color-text-main)]">{{ __('landing-v2.ecosystem.parent_role') }}</span>
                        <span class="block text-[11px] font-semibold text-[color:var(--color-text-muted)] truncate">{{ __('landing-v2.ecosystem.parent_line') }}</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>
