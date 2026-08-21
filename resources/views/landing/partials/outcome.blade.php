{{-- Outcome / Transformation Section (Before vs After) --}}
<section id="outcome" class="section-alt" style="padding: 4rem 0; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1140px; margin: 0 auto;">
        
        {{-- Header --}}
        <div class="text-center mb-10" data-animate>
            <div class="section-badge" style="margin-bottom: 0.875rem; display: inline-flex; background: #E6F4F3; border: 1px solid #B2DDD9; padding: 0.3rem 0.85rem; border-radius: 9999px;">
                <span style="color: #25746D !important; font-weight: 700; font-size: 0.775rem;">{{ __('landing.outcome.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.5rem, 2.8vw, 2.15rem); font-weight: 900; margin-bottom: 0.75rem; letter-spacing: -0.02em; line-height: 1.25;">
                {{ __('landing.outcome.title') }}
            </h2>
            <p style="color: #475569 !important; font-size: 0.975rem; max-width: 38rem; margin: 0 auto; font-weight: 500; line-height: 1.6;">
                {{ __('landing.outcome.subtitle') }}
            </p>
        </div>

        {{-- Comparison Grid --}}
        <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;" data-stagger>
            
            {{-- Before Card --}}
            <div style="
                background: #ffffff;
                border: 1.5px solid #fee2e2;
                border-radius: 1rem;
                padding: 1.5rem 1.35rem;
                position: relative;
                box-shadow: 0 2px 8px rgba(239, 68, 68, 0.04);
            ">
                <div style="display: flex; align-items: center; gap: 0.625rem; margin-bottom: 1.25rem; padding-bottom: 0.875rem; border-bottom: 1px solid #fee2e2;">
                    <div style="width: 2rem; height: 2rem; border-radius: 0.5rem; background: #fef2f2; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-times-circle" style="color: #dc2626; font-size: 1rem;"></i>
                    </div>
                    <div>
                        <h3 style="color: #dc2626 !important; font-size: 1.05rem; font-weight: 800; margin: 0;">
                            {{ __('landing.outcome.before_title') }}
                        </h3>
                    </div>
                </div>

                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach(__('landing.outcome.before_items') as $item)
                    <li style="display: flex; align-items: flex-start; gap: 0.625rem;">
                        <span style="color: #ef4444; font-size: 0.85rem; margin-top: 0.15rem;">
                            <i class="fas fa-times"></i>
                        </span>
                        <span style="color: #64748b; font-size: 0.875rem; font-weight: 500; line-height: 1.5;">
                            {{ $item }}
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- After Card (Taalimu) --}}
            <div style="
                background: #ffffff;
                border: 1.5px solid #2E8B83;
                border-radius: 1rem;
                padding: 1.5rem 1.35rem;
                position: relative;
                box-shadow: 0 10px 25px rgba(46, 139, 131, 0.08);
            ">
                <div style="
                    position: absolute;
                    top: -0.75rem;
                    {{ app()->getLocale() == 'ar' ? 'left: 1.25rem;' : 'right: 1.25rem;' }}
                    background: #2E8B83;
                    color: #ffffff;
                    padding: 0.2rem 0.75rem;
                    border-radius: 9999px;
                    font-size: 0.675rem;
                    font-weight: 800;
                ">
                    <span>{{ __('landing.nav.start_trial') }}</span>
                </div>

                <div style="display: flex; align-items: center; gap: 0.625rem; margin-bottom: 1.25rem; padding-bottom: 0.875rem; border-bottom: 1px solid #E6F4F3;">
                    <div style="width: 2rem; height: 2rem; border-radius: 0.5rem; background: #E6F4F3; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-check-circle" style="color: #2E8B83; font-size: 1rem;"></i>
                    </div>
                    <div>
                        <h3 style="color: #2E8B83 !important; font-size: 1.05rem; font-weight: 800; margin: 0;">
                            {{ __('landing.outcome.after_title') }}
                        </h3>
                    </div>
                </div>

                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach(__('landing.outcome.after_items') as $item)
                    <li style="display: flex; align-items: flex-start; gap: 0.625rem;">
                        <span style="color: #2E8B83; font-size: 0.85rem; margin-top: 0.15rem;">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <span style="color: #0f172a; font-size: 0.875rem; font-weight: 700; line-height: 1.5;">
                            {{ $item }}
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</section>
