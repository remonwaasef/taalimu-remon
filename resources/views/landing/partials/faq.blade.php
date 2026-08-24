{{-- FAQ Section --}}
<section id="faq" class="section-light" style="padding: 5rem 0; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1140px; margin: 0 auto;">
        
        {{-- Header --}}
        <div class="text-center mb-12" data-animate="fade-up">
            <div class="section-badge" style="margin-bottom: 1rem; display: inline-flex; background: #E6F4F3; border: 1px solid #B2DDD9; padding: 0.375rem 1rem; border-radius: 9999px;">
                <span style="color: #25746D !important; font-weight: 800; font-size: 0.825rem;">{{ __('landing.faq.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.75rem, 3.5vw, 2.5rem); font-weight: 900; margin-bottom: 0.875rem; letter-spacing: -0.02em; line-height: 1.25;">
                {{ __('landing.faq.title') }}
            </h2>
            <p style="color: #475569 !important; font-size: 1.05rem; max-width: 42rem; margin: 0 auto; font-weight: 500; line-height: 1.65;">
                {{ __('landing.faq.subtitle') }}
            </p>
        </div>

        {{-- FAQ Accordion --}}
        <div class="max-w-3xl mx-auto" data-stagger>
            @foreach(__('landing.faq.items') as $index => $item)
            <div 
                x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }"
                style="
                    background: #ffffff;
                    border: 1px solid #e2e8f0;
                    border-radius: 1rem;
                    margin-bottom: 1rem;
                    overflow: hidden;
                    transition: all 0.2s ease;
                "
                :style="open ? 'border-color: #2E8B83; box-shadow: 0 4px 16px rgba(46,139,131,0.08);' : ''"
            >
                <button 
                    @click="open = !open" 
                    type="button"
                    aria-expanded="false"
                    :aria-expanded="open.toString()"
                    style="
                        width: 100%;
                        padding: 1.125rem 1.5rem;
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        gap: 1rem;
                        text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
                        border: none;
                        background: transparent;
                        cursor: pointer;
                    "
                >
                    <span style="font-size: 1rem; font-weight: 800; color: #0f172a;">{{ $item['q'] ?? $item['question'] ?? '' }}</span>
                    <div style="width: 2rem; height: 2rem; border-radius: 50%; background: #E6F4F3; color: #2E8B83; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; flex-shrink: 0; transition: transform 0.2s;"
                         :style="open ? 'transform: rotate(180deg);' : ''">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </button>

                <div 
                    x-show="open" 
                    x-collapse
                    style="padding: 0 1.5rem 1.25rem 1.5rem; color: #475569; font-size: 0.95rem; line-height: 1.7;"
                >
                    <p style="margin: 0; border-top: 1px solid #f1f5f9; padding-top: 1rem;">{{ $item['a'] ?? $item['answer'] ?? '' }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- FAQ CTA --}}
        <div class="text-center mt-12" data-animate="fade-up" data-delay="400">
            <p style="color: #64748b; font-size: 0.95rem; margin: 0 0 1rem 0;">{{ is_array(__('landing.faq.cta_text')) ? '' : __('landing.faq.cta_text') }}</p>
            <a href="{{ route('register') }}" style="
                display: inline-flex;
                align-items: center;
                gap: 0.625rem;
                padding: 0.875rem 1.75rem;
                background: #2E8B83;
                color: #ffffff !important;
                font-weight: 800;
                font-size: 0.9rem;
                border-radius: 0.875rem;
                text-decoration: none;
                transition: transform 0.2s ease;
            " onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                {{ is_array(__('landing.faq.cta_button')) ? __('landing.nav.start_trial') : __('landing.faq.cta_button') }}
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i>
            </a>
        </div>
    </div>
</section>