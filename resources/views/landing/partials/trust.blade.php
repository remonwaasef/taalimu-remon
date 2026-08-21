{{-- Honest Trust & Data Protection Section --}}
<section class="section-light" style="padding: 6rem 0; background: #ffffff; border-bottom: 1px solid #e2e8f0;">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="text-center mb-16" data-animate>
            <div class="section-badge" style="margin-bottom: 1.25rem; display: inline-flex; background: #f1f5f9; border: 1px solid #cbd5e1; padding: 0.375rem 1rem; border-radius: 9999px;">
                <span style="color: #334155 !important; font-weight: 700; font-size: 0.825rem;">{{ __('landing.trust.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.875rem, 4vw, 3rem); font-weight: 900; margin-bottom: 1.25rem; letter-spacing: -0.025em; line-height: 1.25;">
                {{ __('landing.trust.title') }}
            </h2>
            <p style="color: #475569 !important; font-size: 1.125rem; max-width: 42rem; margin: 0 auto; font-weight: 500; line-height: 1.7;">
                {{ __('landing.trust.subtitle') }}
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto" data-stagger>
            @foreach(__('landing.trust.items') as $trustItem)
            <div style="
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 1rem;
                padding: 1.75rem 1.5rem;
                text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
            ">
                <div style="
                    width: 2.75rem;
                    height: 2.75rem;
                    border-radius: 0.625rem;
                    background: #E6F4F3;
                    color: #2E8B83;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1.25rem;
                    margin-bottom: 1.25rem;
                ">
                    <i class="fas {{ $trustItem['icon'] }}"></i>
                </div>
                <h3 style="font-size: 1.05rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; line-height: 1.35;">
                    {{ $trustItem['title'] }}
                </h3>
                <p style="font-size: 0.85rem; color: #64748b; margin: 0; line-height: 1.6;">
                    {{ $trustItem['desc'] }}
                </p>
            </div>
            @endforeach
        </div>
    </div>
</section>
