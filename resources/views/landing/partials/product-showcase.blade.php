{{-- Product Showcase Section --}}
<section id="product-showcase" class="section-alt" style="padding: 5rem 0; background: #ffffff; border-bottom: 1px solid #e2e8f0;"
         x-data="{ activeTab: 'dashboard' }">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1140px; margin: 0 auto;">
        
        {{-- Header --}}
        <div class="text-center mb-10" data-animate="fade-up">
            <div class="section-badge" style="margin-bottom: 1rem; display: inline-flex; background: #E6F4F3; border: 1px solid #B2DDD9; padding: 0.375rem 1rem; border-radius: 9999px;">
                <span style="color: #25746D !important; font-weight: 800; font-size: 0.825rem;">{{ __('landing.showcase.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.75rem, 3.5vw, 2.5rem); font-weight: 900; margin-bottom: 0.875rem; letter-spacing: -0.02em; line-height: 1.25;">
                {{ __('landing.showcase.title') }}
            </h2>
            <p style="color: #475569 !important; font-size: 1.05rem; max-width: 42rem; margin: 0 auto; font-weight: 500; line-height: 1.65;">
                {{ __('landing.showcase.subtitle') }}
            </p>
        </div>

        {{-- Tab Navigation --}}
        <div class="flex flex-wrap justify-center gap-3 mb-10" data-animate="fade-up" data-delay="100" role="tablist" aria-label="{{ __('landing.showcase.tabs_label') }}">
            @foreach(__('landing.showcase.tabs') as $key => $tab)
            <button
                @click="activeTab = '{{ $key }}'"
                role="tab"
                :aria-selected="activeTab === '{{ $key }}'"
                aria-controls="{{ $key }}-panel"
                style="
                    padding: 0.75rem 1.5rem;
                    border-radius: 9999px;
                    font-size: 0.875rem;
                    font-weight: 700;
                    border: 1px solid;
                    background: #ffffff;
                    cursor: pointer;
                    transition: all 0.2s ease;
                "
                :style="activeTab === '{{ $key }}' ? 'background: #2E8B83; border-color: #2E8B83; color: #ffffff; box-shadow: 0 4px 16px rgba(46,139,131,0.25);' : 'border-color: #e2e8f0; color: #475569;'"
                onmouseover="activeTab !== '{{ $key }}' && (this.style.borderColor='#2E8B83', this.style.color='#2E8B83')"
                onmouseout="activeTab !== '{{ $key }}' && (this.style.borderColor='#e2e8f0', this.style.color='#475569')"
            >
                {{ $tab }}
            </button>
            @endforeach
        </div>

        {{-- Tab Panels --}}
        <div style="position: relative; min-height: 400px;">
            @foreach(__('landing.showcase.items') as $key => $item)
            <div 
                id="{{ $key }}-panel"
                role="tabpanel"
                x-show="activeTab === '{{ $key }}'"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                style="display: none;"
            >
                <div style="
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 3rem;
                    align-items: center;
                ">
                    {{-- Content --}}
                    <div data-animate="fade-up">
                        <h3 style="color: #0f172a; font-size: clamp(1.5rem, 2.5vw, 1.85rem); font-weight: 900; margin: 0 0 1rem 0; line-height: 1.3;">{{ $item['title'] }}</h3>
                        <p style="color: #475569; font-size: 1rem; margin: 0 0 1.5rem 0; line-height: 1.7;">{{ $item['desc'] }}</p>
                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.75rem;">
                            @foreach($item['highlights'] as $highlight)
                            <li style="display: flex; align-items: flex-start; gap: 0.75rem; color: #334155; font-size: 0.9rem; font-weight: 600;">
                                <div style="width: 1.5rem; height: 1.5rem; border-radius: 50%; background: #E6F4F3; color: #2E8B83; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.6rem; margin-top: 0.125rem;">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>{{ $highlight }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    {{-- Visual / Mockup --}}
                    <div data-animate="scale-in" data-delay="200">
                        <div style="
                            border-radius: 1rem;
                            overflow: hidden;
                            border: 1px solid #cbd5e1;
                            box-shadow: 0 20px 40px -10px rgba(15,23,42,0.12);
                            background: #f8fafc;
                            aspect-ratio: 16/10;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            position: relative;
                        ">
                            @if($item['image'])
                            <img src="{{ asset('images/landing/' . $item['image']) }}" alt="{{ $item['title'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                            <div style="width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #94a3b8; text-align: center; padding: 2rem;">
                                <i class="fas fa-{{ $item['icon'] }}" style="font-size: 3rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
                                <span style="font-size: 1rem; font-weight: 600;">{{ __('landing.showcase.coming_soon') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>