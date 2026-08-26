<section class="v2-section bg-white border-y border-[color:var(--color-border-subtle)]" aria-labelledby="v2-eco-title">
    <div class="v2-container">
        <div class="text-center max-w-2xl mx-auto mb-12 lg:mb-16" data-reveal>
            <span class="v2-eyebrow mb-4">{{ __('landing-v2.ecosystem.badge') }}</span>
            <h2 id="v2-eco-title" class="v2-h2 mb-3">{{ __('landing-v2.ecosystem.title') }}</h2>
            <p class="v2-lead">{{ __('landing-v2.ecosystem.subtitle') }}</p>
        </div>

        <div class="max-w-4xl mx-auto" data-reveal style="--reveal-delay: 100ms;">
            {{-- Row 1: Center + Teacher --}}
            <div class="flex items-center gap-3 sm:gap-4 mb-6 sm:mb-10">
                <div class="v2-node flex-1">
                    <span class="w-10 h-10 rounded-xl bg-[color:var(--color-primary-50)] text-[color:var(--color-primary-600)] flex items-center justify-center shrink-0"><i class="fas fa-school"></i></span>
                    <span class="min-w-0">
                        <span class="block text-sm font-extrabold text-[color:var(--color-text-main)]">{{ __('landing-v2.ecosystem.center_role') }}</span>
                        <span class="block text-[11px] font-semibold text-[color:var(--color-text-muted)] truncate">{{ __('landing-v2.ecosystem.center_line') }}</span>
                    </span>
                </div>
                <div class="v2-connector hidden sm:block" aria-hidden="true"></div>
                <div class="v2-node flex-1">
                    <span class="w-10 h-10 rounded-xl bg-[color:var(--color-primary-50)] text-[color:var(--color-primary-600)] flex items-center justify-center shrink-0"><i class="fas fa-chalkboard-user"></i></span>
                    <span class="min-w-0">
                        <span class="block text-sm font-extrabold text-[color:var(--color-text-main)]">{{ __('landing-v2.ecosystem.teacher_role') }}</span>
                        <span class="block text-[11px] font-semibold text-[color:var(--color-text-muted)] truncate">{{ __('landing-v2.ecosystem.teacher_line') }}</span>
                    </span>
                </div>
            </div>

            {{-- Row 2: Student + Hub + Parent (hub center on desktop) --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 sm:gap-4">
                <div class="v2-node w-full sm:w-56">
                    <span class="w-10 h-10 rounded-xl bg-[color:var(--color-primary-50)] text-[color:var(--color-primary-600)] flex items-center justify-center shrink-0"><i class="fas fa-user-graduate"></i></span>
                    <span class="min-w-0">
                        <span class="block text-sm font-extrabold text-[color:var(--color-text-main)]">{{ __('landing-v2.ecosystem.student_role') }}</span>
                        <span class="block text-[11px] font-semibold text-[color:var(--color-text-muted)] truncate">{{ __('landing-v2.ecosystem.student_line') }}</span>
                    </span>
                </div>

                <div class="flex items-center gap-3 sm:gap-4 w-full sm:w-auto justify-center">
                    <div class="v2-connector hidden sm:block w-10" aria-hidden="true"></div>
                    <div class="v2-hub">
                        <img src="{{ asset('images/brand/logo-icon.png') }}" alt="" class="h-9 w-auto" width="36" height="36" loading="lazy">
                        <span class="text-[11px] font-extrabold tracking-wide">{{ __('landing-v2.ecosystem.hub') }}</span>
                    </div>
                    <div class="v2-connector hidden sm:block w-10" aria-hidden="true"></div>
                </div>

                <div class="v2-node w-full sm:w-56">
                    <span class="w-10 h-10 rounded-xl bg-[color:var(--color-success-50)] text-[color:var(--color-success-600)] flex items-center justify-center shrink-0"><i class="fas fa-user-shield"></i></span>
                    <span class="min-w-0">
                        <span class="block text-sm font-extrabold text-[color:var(--color-text-main)]">{{ __('landing-v2.ecosystem.parent_role') }}</span>
                        <span class="block text-[11px] font-semibold text-[color:var(--color-text-muted)] truncate">{{ __('landing-v2.ecosystem.parent_line') }}</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>
