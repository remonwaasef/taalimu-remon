{{-- Features Section --}}
<section id="features" class="section-light" style="padding:6rem 0;">
    <div class="container mx-auto px-4 lg:px-12">
        {{-- Header --}}
        <div class="text-center mb-16" data-animate>
            <div class="section-badge section-badge-dark" style="margin-bottom:1.5rem; display:inline-flex;">
                <span style="color:#6ee7b7 !important; font-weight:700;">{{ __('landing.features.badge') }}</span>
            </div>
            <h2 style="color:#0f172a !important; font-size:clamp(1.875rem, 4vw, 3rem); font-weight:900; margin-bottom:1.5rem; letter-spacing:-0.025em; line-height:1.2;">
                {!! __('landing.features.title') !!}
            </h2>
            <p style="color:#475569 !important; font-size:1.125rem; max-width:42rem; margin:0 auto; font-weight:500; line-height:1.7;">
                {{ __('landing.features.subtitle') }}
            </p>
        </div>

        {{-- Feature Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8" data-stagger>
            @php
                $featuresData = [
                    ['icon' => 'fa-users', 'bg' => '#059669', 'shadow' => 'rgba(5,150,105,0.25)'],
                    ['icon' => 'fa-calendar-check', 'bg' => '#3b82f6', 'shadow' => 'rgba(59,130,246,0.25)'],
                    ['icon' => 'fa-credit-card', 'bg' => '#8b5cf6', 'shadow' => 'rgba(139,92,246,0.25)'],
                    ['icon' => 'fa-chart-pie', 'bg' => '#f59e0b', 'shadow' => 'rgba(245,158,11,0.25)'],
                ];
            @endphp
            @foreach(__('landing.features.items') as $index => $item)
                @if($index >= 4) @break @endif
                @php $data = $featuresData[$index] ?? $featuresData[0]; @endphp
                <div class="landing-card" style="text-align:center; display:flex; flex-direction:column; justify-content:space-between;">
                    <div>
                        <div style="width:4rem; height:4rem; margin:0 auto 1.5rem; border-radius:1rem; background:{{ $data['bg'] }}; display:flex; align-items:center; justify-content:center; box-shadow:0 8px 20px {{ $data['shadow'] }}; transition:transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            <i class="fas {{ $data['icon'] }}" style="color:#ffffff; font-size:1.25rem;"></i>
                        </div>
                        <h3 style="color:#0f172a !important; font-weight:800; font-size:1.125rem; margin-bottom:0.75rem;">{{ $item['title'] }}</h3>
                        <p style="color:#64748b !important; font-size:0.875rem; line-height:1.6;">{{ Str::limit($item['description'], 110) }}</p>
                    </div>
                    <div style="margin-top:1.5rem; padding-top:1rem; border-top:1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; color:#059669; transition:transform 0.2s;">
                        <span>{{ __('landing.nav.features') }}</span>
                        <i class="fas {{ app()->getLocale() == 'ar' ? 'fa-arrow-left me-1' : 'fa-arrow-right ms-1' }}"></i>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
