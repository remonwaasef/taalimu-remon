{{-- Product Showcase Section --}}
<section id="showcase" class="section-light" style="padding: 6.5rem 0; background: #ffffff; border-bottom: 1px solid #e2e8f0;"
         x-data="{ activeTab: 'dashboard' }">
    <div class="container mx-auto px-4 lg:px-12">
        
        {{-- Section Header --}}
        <div class="text-center mb-12" data-animate>
            <div class="section-badge" style="margin-bottom: 1.25rem; display: inline-flex; background: #E6F4F3; border: 1px solid #B2DDD9; padding: 0.375rem 1rem; border-radius: 9999px;">
                <span style="color: #25746D !important; font-weight: 700; font-size: 0.825rem;">{{ __('landing.showcase.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.875rem, 4vw, 3rem); font-weight: 900; margin-bottom: 1.25rem; letter-spacing: -0.025em; line-height: 1.25;">
                {{ __('landing.showcase.title') }}
            </h2>
            <p style="color: #475569 !important; font-size: 1.125rem; max-width: 44rem; margin: 0 auto; font-weight: 500; line-height: 1.7;">
                {{ __('landing.showcase.subtitle') }}
            </p>
        </div>

        {{-- Interactive Tabs Header --}}
        <div class="flex flex-wrap justify-center gap-2 mb-10" data-animate>
            @foreach(__('landing.showcase.tabs') as $tabKey => $tabName)
            <button 
                @click="activeTab = '{{ $tabKey }}'"
                type="button"
                style="
                    padding: 0.75rem 1.5rem;
                    border-radius: 0.75rem;
                    font-weight: 700;
                    font-size: 0.9rem;
                    transition: all 0.2s ease;
                    border: 1px solid;
                "
                :style="activeTab === '{{ $tabKey }}' ? 'background: #0f172a; color: #ffffff; border-color: #0f172a; box-shadow: 0 4px 12px rgba(15,23,42,0.15);' : 'background: #f8fafc; color: #64748b; border-color: #e2e8f0;'"
            >
                @if($tabKey === 'dashboard') <i class="fas fa-th-large me-1"></i>
                @elseif($tabKey === 'students') <i class="fas fa-user-graduate me-1"></i>
                @elseif($tabKey === 'attendance') <i class="fas fa-qrcode me-1"></i>
                @elseif($tabKey === 'payments') <i class="fas fa-receipt me-1"></i>
                @elseif($tabKey === 'whatsapp') <i class="fab fa-whatsapp me-1"></i>
                @else <i class="fas fa-chart-bar me-1"></i>
                @endif
                <span>{{ $tabName }}</span>
            </button>
            @endforeach
        </div>

        {{-- Showcase Display Box --}}
        <div class="max-w-5xl mx-auto" data-animate>
            <div style="
                background: #ffffff;
                border: 1px solid #cbd5e1;
                border-radius: 1.25rem;
                overflow: hidden;
                box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.12);
            ">
                {{-- Top Feature Description Bar --}}
                @foreach(__('landing.showcase.items') as $itemKey => $itemData)
                <div 
                    x-show="activeTab === '{{ $itemKey }}'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    style="
                        padding: 1.5rem 2rem;
                        background: #f8fafc;
                        border-bottom: 1px solid #e2e8f0;
                        display: flex;
                        flex-wrap: wrap;
                        align-items: center;
                        justify-content: space-between;
                        gap: 1rem;
                    "
                >
                    <div style="max-width: 650px;">
                        <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 0.25rem;">
                            {{ $itemData['title'] }}
                        </h3>
                        <p style="font-size: 0.925rem; color: #475569; margin: 0; line-height: 1.5;">
                            {{ $itemData['desc'] }}
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('register') }}" style="
                            display: inline-flex;
                            align-items: center;
                            gap: 0.5rem;
                            padding: 0.65rem 1.25rem;
                            background: #2E8B83;
                            color: #ffffff !important;
                            border-radius: 0.625rem;
                            font-size: 0.85rem;
                            font-weight: 700;
                            text-decoration: none;
                        ">
                            <span>{{ __('landing.hero.cta_free') }}</span>
                            <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i>
                        </a>
                    </div>
                </div>
                @endforeach

                {{-- Real Visual Preview Area --}}
                <div style="background: #ffffff; padding: 1.5rem; display: flex; justify-content: center;">
                    <img src="{{ asset('images/hero-dashboard.webp') }}" alt="Taalimu Feature Showcase" style="width: 100%; max-width: 900px; height: auto; border-radius: 0.75rem; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: block;">
                </div>
            </div>
        </div>

    </div>
</section>
