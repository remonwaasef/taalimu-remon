{{-- Outcome / Transformation Section (Before vs After) --}}
<section class="section-alt" style="padding: 6rem 0; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="text-center mb-16" data-animate>
            <div class="section-badge" style="margin-bottom: 1.25rem; display: inline-flex; background: #E6F4F3; border: 1px solid #B2DDD9; padding: 0.375rem 1rem; border-radius: 9999px;">
                <span style="color: #25746D !important; font-weight: 700; font-size: 0.825rem;">{{ __('landing.outcome.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.875rem, 4vw, 3rem); font-weight: 900; margin-bottom: 1.25rem; letter-spacing: -0.025em; line-height: 1.25;">
                {{ __('landing.outcome.title') }}
            </h2>
            <p style="color: #475569 !important; font-size: 1.125rem; max-width: 44rem; margin: 0 auto; font-weight: 500; line-height: 1.7;">
                {{ __('landing.outcome.subtitle') }}
            </p>
        </div>

        {{-- Comparison Grid --}}
        <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto" data-stagger>
            
            {{-- Before Card --}}
            <div style="
                background: #ffffff;
                border: 2px solid #fee2e2;
                border-radius: 1.25rem;
                padding: 2.25rem 2rem;
                position: relative;
                box-shadow: 0 4px 15px rgba(239, 68, 68, 0.04);
            ">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.75rem; padding-bottom: 1.25rem; border-bottom: 1px solid #fee2e2;">
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.625rem; background: #fef2f2; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-times-circle" style="color: #dc2626; font-size: 1.25rem;"></i>
                    </div>
                    <div>
                        <h3 style="color: #dc2626 !important; font-size: 1.25rem; font-weight: 900; margin: 0;">
                            {{ __('landing.outcome.before_title') }}
                        </h3>
                    </div>
                </div>

                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
                    @foreach(__('landing.outcome.before_items') as $item)
                    <li style="display: flex; align-items: flex-start; gap: 0.75rem;">
                        <span style="color: #ef4444; font-size: 1rem; margin-top: 0.15rem;">
                            <i class="fas fa-times"></i>
                        </span>
                        <span style="color: #64748b; font-size: 0.95rem; font-weight: 500; line-height: 1.5;">
                            {{ $item }}
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- After Card (Taalimu) --}}
            <div style="
                background: #ffffff;
                border: 2px solid #2E8B83;
                border-radius: 1.25rem;
                padding: 2.25rem 2rem;
                position: relative;
                box-shadow: 0 15px 35px rgba(46, 139, 131, 0.12);
            ">
                <div style="
                    position: absolute;
                    top: -0.875rem;
                    {{ app()->getLocale() == 'ar' ? 'left: 1.5rem;' : 'right: 1.5rem;' }}
                    background: #2E8B83;
                    color: #ffffff;
                    padding: 0.25rem 1rem;
                    border-radius: 9999px;
                    font-size: 0.75rem;
                    font-weight: 800;
                    box-shadow: 0 2px 8px rgba(46, 139, 131, 0.3);
                ">
                    <i class="fas fa-star" style="color: #fde68a; font-size: 0.65rem; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.25rem;"></i>
                    <span>{{ __('landing.nav.start_trial') }}</span>
                </div>

                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.75rem; padding-bottom: 1.25rem; border-bottom: 1px solid #E6F4F3;">
                    <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.625rem; background: #E6F4F3; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-check-circle" style="color: #2E8B83; font-size: 1.25rem;"></i>
                    </div>
                    <div>
                        <h3 style="color: #2E8B83 !important; font-size: 1.25rem; font-weight: 900; margin: 0;">
                            {{ __('landing.outcome.after_title') }}
                        </h3>
                    </div>
                </div>

                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
                    @foreach(__('landing.outcome.after_items') as $item)
                    <li style="display: flex; align-items: flex-start; gap: 0.75rem;">
                        <span style="color: #2E8B83; font-size: 1rem; margin-top: 0.15rem;">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <span style="color: #0f172a; font-size: 0.95rem; font-weight: 700; line-height: 1.5;">
                            {{ $item }}
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</section>
