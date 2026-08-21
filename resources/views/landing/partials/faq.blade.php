{{-- FAQ Section — Compact Layout --}}
<section id="faq" class="section-light" style="padding: 3.5rem 0; background: #ffffff; border-bottom: 1px solid #e2e8f0;">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1140px; margin: 0 auto;">
        
        <div class="text-center mb-8" data-animate>
            <div class="section-badge" style="margin-bottom: 0.875rem; display: inline-flex; background: #E6F4F3; border: 1px solid #B2DDD9; padding: 0.3rem 0.85rem; border-radius: 9999px;">
                <span style="color: #25746D !important; font-weight: 700; font-size: 0.775rem;">{{ __('landing.faq.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.5rem, 2.8vw, 2.15rem); font-weight: 900; margin-bottom: 0.5rem; letter-spacing: -0.02em; line-height: 1.25;">
                {{ __('landing.faq.title') }}
            </h2>
            <p style="color: #475569 !important; font-size: 0.975rem; max-width: 38rem; margin: 0 auto; font-weight: 500; line-height: 1.6;">
                {{ __('landing.faq.subtitle') }}
            </p>
        </div>

        <div class="max-w-2xl mx-auto flex flex-col gap-3" data-stagger>
            @foreach(array_slice(__('landing.faq.items'), 0, 4) as $index => $item)
            <div 
                x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }"
                style="
                    background: #f8fafc;
                    border: 1px solid #e2e8f0;
                    border-radius: 0.75rem;
                    overflow: hidden;
                    transition: all 0.2s ease;
                "
                :style="open ? 'border-color: #2E8B83; box-shadow: 0 2px 8px rgba(46,139,131,0.06); background: #ffffff;' : 'background: #f8fafc;'"
            >
                <button 
                    @click="open = !open" 
                    type="button"
                    style="
                        width: 100%;
                        padding: 0.85rem 1.125rem;
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        gap: 0.75rem;
                        text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
                        border: none;
                        background: transparent;
                        cursor: pointer;
                    "
                >
                    <span style="font-size: 0.925rem; font-weight: 800; color: #0f172a;">
                        {{ $item['q'] }}
                    </span>
                    <div style="width: 1.5rem; height: 1.5rem; border-radius: 50%; background: #E6F4F3; color: #2E8B83; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; flex-shrink: 0; transition: transform 0.2s;"
                         :style="open ? 'transform: rotate(180deg);' : ''">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </button>

                <div 
                    x-show="open" 
                    x-collapse
                    style="padding: 0 1.125rem 0.85rem 1.125rem; color: #475569; font-size: 0.875rem; line-height: 1.6;"
                >
                    <p style="margin: 0; border-top: 1px solid #f1f5f9; padding-top: 0.65rem;">
                        {{ $item['a'] }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
