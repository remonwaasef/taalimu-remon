{{-- Trust Section --}}
<section id="trust" class="section-alt" style="padding: 5rem 0; background: #ffffff; border-bottom: 1px solid #e2e8f0;">
    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1140px; margin: 0 auto;">
        
        {{-- Header --}}
        <div class="text-center mb-16" data-animate="fade-up">
            <div class="section-badge" style="margin-bottom: 1rem; display: inline-flex; background: #E6F4F3; border: 1px solid #B2DDD9; padding: 0.375rem 1rem; border-radius: 9999px;">
                <span style="color: #25746D !important; font-weight: 800; font-size: 0.825rem;">{{ __('landing.trust.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.75rem, 3.5vw, 2.5rem); font-weight: 900; margin-bottom: 0.875rem; letter-spacing: -0.02em; line-height: 1.25;">
                {{ __('landing.trust.title') }}
            </h2>
            <p style="color: #475569 !important; font-size: 1.05rem; max-width: 42rem; margin: 0 auto; font-weight: 500; line-height: 1.65;">
                {{ __('landing.trust.subtitle') }}
            </p>
        </div>

        {{-- Trust Items --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;" data-stagger>
            @foreach(__('landing.trust.items') as $index => $item)
            @php
                $colors = ['#2E8B83', '#2563eb', '#16a34a', '#d97706'];
                $bgs = ['#E6F4F3', '#eff6ff', '#f0fdf4', '#fffbeb'];
                $itemBg = $item['bg'] ?? ($bgs[$index % 4]);
                $itemColor = $item['color'] ?? ($colors[$index % 4]);
            @endphp
            <div style="
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 1.25rem;
                padding: 2rem 1.75rem;
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                transition: all 0.3s ease;
            " onmouseover="this.style.background='#ffffff'; this.style.borderColor='#2E8B83'; this.style.boxShadow='0 16px 32px rgba(46,139,131,0.1)'" onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                <div style="width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: {{ $itemBg }}; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; color: {{ $itemColor }}; font-size: 1.5rem;">
                    <i class="fas {{ $item['icon'] }}"></i>
                </div>
                <h3 style="color: #0f172a; font-size: 1.1rem; font-weight: 800; margin: 0 0 0.75rem 0;">{{ $item['title'] }}</h3>
                <p style="color: #64748b; font-size: 0.9rem; margin: 0; line-height: 1.6;">{{ $item['desc'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Verified Badges Row --}}
        <div class="mt-16" data-animate="fade-up" data-delay="400">
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <p style="color: #64748b; font-size: 0.875rem; font-weight: 600; margin: 0;">{{ is_array(__('landing.trust.verified_by')) ? '' : __('landing.trust.badge') }}</p>
            </div>
            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1.5rem; align-items: center;">
                <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 9999px; color: #15803d; font-size: 0.825rem; font-weight: 700;">
                    <i class="fas fa-shield-check"></i> {{ is_array(__('landing.trust.tenant_isolation')) ? '' : __('landing.trust.badge') }}
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 9999px; color: #2563eb; font-size: 0.825rem; font-weight: 700;">
                    <i class="fas fa-database"></i> {{ is_array(__('landing.trust.backups')) ? '' : __('landing.trust.badge') }}
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #fefce8; border: 1px solid #fde68a; border-radius: 9999px; color: #b45309; font-size: 0.825rem; font-weight: 700;">
                    <i class="fas fa-user-lock"></i> {{ is_array(__('landing.trust.permissions')) ? '' : __('landing.trust.badge') }}
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #fdf2f8; border: 1px solid #fbcfe8; border-radius: 9999px; color: #be185d; font-size: 0.825rem; font-weight: 700;">
                    <i class="fas fa-headset"></i> {{ is_array(__('landing.trust.support')) ? '' : __('landing.trust.badge') }}
                </div>
            </div>
        </div>
    </div>
</section>