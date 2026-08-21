{{-- FAQ Section --}}
<section id="faq" class="section-light" style="padding: 6.5rem 0; background: #ffffff; border-bottom: 1px solid #e2e8f0;">
    <div class="container mx-auto px-4 lg:px-12">
        <div class="text-center mb-16" data-animate>
            <div class="section-badge" style="margin-bottom: 1.25rem; display: inline-flex; background: #E6F4F3; border: 1px solid #B2DDD9; padding: 0.375rem 1rem; border-radius: 9999px;">
                <span style="color: #25746D !important; font-weight: 700; font-size: 0.825rem;">{{ __('landing.faq.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.875rem, 4vw, 3rem); font-weight: 900; margin-bottom: 1.25rem; letter-spacing: -0.025em; line-height: 1.25;">
                {{ __('landing.faq.title') }}
            </h2>
            <p style="color: #475569 !important; font-size: 1.125rem; max-width: 44rem; margin: 0 auto; font-weight: 500; line-height: 1.7;">
                {{ __('landing.faq.subtitle') }}
            </p>
        </div>

        <div class="max-w-3xl mx-auto flex flex-col gap-4" data-stagger>
            @foreach(__('landing.faq.items') as $index => $item)
            <div 
                x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }"
                style="
                    background: #f8fafc;
                    border: 1px solid #e2e8f0;
                    border-radius: 1rem;
                    overflow: hidden;
                    transition: all 0.2s ease;
                "
                :style="open ? 'border-color: #2E8B83; box-shadow: 0 4px 12px rgba(46,139,131,0.08); background: #ffffff;' : 'background: #f8fafc;'"
            >
                <button 
                    @click="open = !open" 
                    type="button"
                    style="
                        width: 100%;
                        padding: 1.25rem 1.5rem;
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
                    <span style="font-size: 1.05rem; font-weight: 800; color: #0f172a;">
                        {{ $item['q'] }}
                    </span>
                    <div style="width: 1.75rem; height: 1.75rem; border-radius: 50%; background: #E6F4F3; color: #2E8B83; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; flex-shrink: 0; transition: transform 0.2s;"
                         :style="open ? 'transform: rotate(180deg);' : ''">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </button>

                <div 
                    x-show="open" 
                    x-collapse
                    style="padding: 0 1.5rem 1.25rem 1.5rem; color: #475569; font-size: 0.95rem; line-height: 1.7;"
                >
                    <p style="margin: 0; border-top: 1px solid #f1f5f9; padding-top: 1rem;">
                        {{ $item['a'] }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
