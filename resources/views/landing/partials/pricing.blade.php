{{-- Pricing Section --}}
<section id="pricing" class="section-alt" style="padding: 6.5rem 0; background: #f8fafc; border-bottom: 1px solid #e2e8f0;"
         x-data="{
            billingCycle: 'monthly',
            selectedCurrency: '{{ session('suggested_currency', 'EGP') }}',
            async init() {
                const locale = '{{ app()->getLocale() }}';

                if (locale !== 'ar' && this.selectedCurrency !== 'EGP') {
                    return;
                }

                try {
                    const response = await fetch('https://get.geojs.io/v1/ip/country.json');
                    const data = await response.json();
                    const country = data.country;

                    if (country === 'EG') this.selectedCurrency = 'EGP';
                    else if (['FR', 'DE', 'IT', 'ES', 'NL', 'BE', 'AT', 'PT', 'IE'].includes(country)) this.selectedCurrency = 'EUR';
                    else if (this.selectedCurrency === 'EGP') this.selectedCurrency = 'USD';
                } catch(e) {
                    console.log('Geo fetch failed, keeping: ' + this.selectedCurrency);
                }
            },
            getRegionalPrice(packageRegionalPrices, basePrice, baseTermPrice, baseYearlyPrice) {
                if (!packageRegionalPrices) {
                    return {
                        amount: basePrice,
                        currency: this.selectedCurrency,
                        term_price: baseTermPrice,
                        yearly_price: baseYearlyPrice
                    };
                }

                const currencyToRegionKey = {
                    'EGP': 'EG',
                    'EUR': 'FR',
                    'USD': 'default',
                    'SAR': 'SA',
                    'AED': 'AE'
                };

                const regionKey = currencyToRegionKey[this.selectedCurrency] || 'default';

                if (packageRegionalPrices[regionKey]) {
                    return packageRegionalPrices[regionKey];
                }

                if (packageRegionalPrices['default']) {
                    return packageRegionalPrices['default'];
                }

                return {
                    amount: basePrice,
                    currency: this.selectedCurrency,
                    term_price: baseTermPrice,
                    yearly_price: baseYearlyPrice
                };
            }
         }">

    <div class="container mx-auto px-4 lg:px-12">
        {{-- Header --}}
        <div class="text-center mb-14" data-animate>
            <div class="section-badge" style="margin-bottom: 1.25rem; display: inline-flex; background: #E6F4F3; border: 1px solid #B2DDD9; padding: 0.375rem 1rem; border-radius: 9999px;">
                <span style="color: #25746D !important; font-weight: 700; font-size: 0.825rem;">{{ __('landing.pricing.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.875rem, 4vw, 3rem); font-weight: 900; margin-bottom: 1.25rem; letter-spacing: -0.025em; line-height: 1.25;">
                {{ __('landing.pricing.title') }}
            </h2>
            <p style="color: #475569 !important; font-size: 1.125rem; max-width: 44rem; margin: 0 auto; font-weight: 500; line-height: 1.7;">
                {{ __('landing.pricing.subtitle') }}
            </p>

            {{-- Billing Cycle Toggles --}}
            <div style="margin-top: 2rem; display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                <div class="pricing-toggle-container" style="background: #ffffff; border: 1px solid #cbd5e1; padding: 0.35rem; border-radius: 0.875rem; display: inline-flex; gap: 0.35rem; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                    @foreach(['monthly' => 'landing.pricing.monthly', 'term' => 'landing.pricing.term', 'yearly' => 'landing.pricing.yearly'] as $cycle => $label)
                    <button
                        @click="billingCycle = '{{$cycle}}'"
                        class="pricing-toggle-btn"
                        :class="billingCycle === '{{$cycle}}' ? 'active' : ''"
                        :style="billingCycle === '{{$cycle}}' ? 'background:#0f172a; color:#ffffff; font-weight:800;' : 'color:#475569; font-weight:600;'"
                        style="position: relative; padding: 0.5rem 1.25rem; border-radius: 0.625rem; font-size: 0.85rem; border: none; transition: all 0.2s;"
                    >
                        {{ __($label) }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Pricing Cards --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-6xl mx-auto items-stretch" data-stagger>
            @foreach($packages as $index => $package)
                @php
                    $isFeatured = $package->is_featured;
                    $regionalPrices = $package->regional_prices ?? [];
                @endphp

                <div style="
                    background: #ffffff;
                    border-radius: 1.25rem;
                    padding: 2.25rem 1.75rem;
                    display: flex;
                    flex-direction: column;
                    transition: all 0.3s;
                    position: relative;
                    {{ $isFeatured
                        ? 'border: 2px solid #2E8B83; box-shadow: 0 25px 60px -12px rgba(46,139,131,0.18); transform: translateY(-8px); z-index: 10;'
                        : 'border: 1px solid #e2e8f0; box-shadow: 0 1px 4px rgba(0,0,0,0.04);'
                    }}
                ">
                    @if($isFeatured)
                    <div style="position: absolute; top: -0.875rem; left: 50%; transform: translateX(-50%); background: #2E8B83; color: #ffffff; padding: 0.375rem 1.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; box-shadow: 0 4px 12px rgba(46,139,131,0.3); display: flex; align-items: center; gap: 0.375rem; white-space: nowrap;">
                        <i class="fas fa-crown" style="color: #fde68a; font-size: 0.7rem;"></i>
                        <span>{{ __('landing.pricing.featured') }}</span>
                    </div>
                    @endif

                    {{-- Package Header --}}
                    <div style="margin-bottom: 1.75rem;">
                        <h3 style="color: #0f172a !important; font-size: 1.5rem; font-weight: 900; margin-bottom: 0.5rem;">
                            @php
                                $packageName = match(app()->getLocale()) {
                                    'ar' => $package->name,
                                    'fr' => $package->name_fr ?: ($package->name_en ?: $package->name),
                                    default => $package->name_en ?: $package->name,
                                };
                                $packageDesc = match(app()->getLocale()) {
                                    'ar' => $package->description,
                                    'fr' => $package->description_fr ?: ($package->description_en ?: $package->description),
                                    default => $package->description_en ?: $package->description,
                                };
                            @endphp
                            {{ $packageName ?: __('landing.pricing.plans.' . $package->slug . '.name') }}
                        </h3>
                        <p style="color: #64748b !important; font-size: 0.875rem; font-weight: 500; line-height: 1.5; margin-bottom: 1.5rem; min-height: 2.5rem;">
                            {{ $packageDesc ?: __('landing.pricing.plans.' . $package->slug . '.description') }}
                        </p>

                        <div x-data="{ localPrice: {} }"
                             x-effect="localPrice = getRegionalPrice({{ json_encode($package->regional_prices) }}, {{ $package->price }}, {{ $package->term_price }}, {{ $package->yearly_price }})"
                        >
                            {{-- 30-Day Free Trial Badge --}}
                            <div style="margin-bottom: 1rem;">
                                <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.35rem 0.875rem; border-radius: 9999px; background: #E6F4F3; border: 1px solid #B2DDD9; font-size: 0.75rem; font-weight: 800; color: #25746D;">
                                    <i class="fas fa-gift" style="color: #2E8B83;"></i>
                                    <span>{{ __('landing.pricing.trial_days', ['days' => $package->trial_days ?: 30]) }}</span>
                                </span>
                            </div>

                            <div style="display: flex; align-items: baseline; gap: 0.5rem;">
                                @if(app()->getLocale() == 'ar')
                                    <span style="color: #2E8B83; font-size: 1.1rem; font-weight: 800;" x-text="localPrice.currency"></span>
                                    <span style="color: #0f172a; font-size: clamp(2.25rem, 5vw, 3rem); font-weight: 900; letter-spacing: -0.05em;"
                                          x-text="billingCycle === 'monthly' ? localPrice.amount : (billingCycle === 'term' ? localPrice.term_price : localPrice.yearly_price)">
                                    </span>
                                @else
                                    <span style="color: #0f172a; font-size: clamp(2.25rem, 5vw, 3rem); font-weight: 900; letter-spacing: -0.05em;"
                                          x-text="billingCycle === 'monthly' ? localPrice.amount : (billingCycle === 'term' ? localPrice.term_price : localPrice.yearly_price)">
                                    </span>
                                    <span style="color: #2E8B83; font-size: 1.1rem; font-weight: 800;" x-text="localPrice.currency"></span>
                                @endif
                            </div>
                            <div style="margin-top: 0.35rem; font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">
                                <span x-show="billingCycle === 'monthly'">{{ __('landing.pricing.per_month') }}</span>
                                <span x-show="billingCycle === 'term'">{{ __('landing.pricing.per_term') }}</span>
                                <span x-show="billingCycle === 'yearly'">{{ __('landing.pricing.per_year') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Features Checklist --}}
                    <ul style="list-style: none; padding: 0; margin: 0 0 2rem 0; flex-grow: 1; border-top: 1px solid #f1f5f9; padding-top: 1.5rem;">
                        @php
                            $pFeatures = [];
                            foreach ($package->features as $feat) {
                                $val = $feat->pivot->value;
                                if ($feat->type === 'boolean' && ($val === 'false' || !$val)) continue;
                                if ($feat->type === 'limit' && $val === '0') continue;
                                $transKey = 'features.' . $feat->code;
                                $label = __($transKey) !== $transKey ? __($transKey) : (app()->getLocale() === 'en' && $feat->name_en ? $feat->name_en : $feat->name);
                                $unlimitedText = __('features.unlimited');
                                $pFeatures[] = ($val === '-1') ? ($label . ': ' . $unlimitedText) : (($feat->type === 'boolean') ? $label : ($label . ': ' . $val));
                            }
                        @endphp
                        @foreach(array_slice($pFeatures, 0, 8) as $feature)
                        <li style="display: flex; align-items: flex-start; gap: 0.75rem; margin-bottom: 0.875rem;">
                            <div style="width: 1.25rem; height: 1.25rem; border-radius: 50%; background: #E6F4F3; color: #2E8B83; display: flex; align-items: center; justify-content: center; font-size: 0.6rem; flex-shrink: 0; margin-top: 0.15rem;">
                                <i class="fas fa-check"></i>
                            </div>
                            <span style="color: #334155; font-size: 0.875rem; font-weight: 600; line-height: 1.4;">{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>

                    {{-- CTA Button --}}
                    <div style="margin-top: auto;">
                        <a :href="'{{ route('register') }}?plan={{ $package->slug }}&cycle=' + billingCycle + '&currency=' + selectedCurrency"
                           style="
                                width: 100%;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                padding: 1rem;
                                border-radius: 0.875rem;
                                font-weight: 800;
                                font-size: 0.9rem;
                                text-decoration: none;
                                transition: all 0.25s;
                                {{ $isFeatured
                                    ? 'background: linear-gradient(135deg, #2E8B83, #10b981); color: #ffffff !important; box-shadow: 0 8px 25px rgba(46,139,131,0.3);'
                                    : 'background: #0f172a; color: #ffffff !important; box-shadow: 0 4px 12px rgba(15,23,42,0.15);'
                                }}
                           "
                           onmouseover="this.style.transform='translateY(-2px)'"
                           onmouseout="this.style.transform='translateY(0)'"
                        >
                            {{ __('landing.pricing.cta_free') }}
                        </a>
                        <p style="text-align: center; font-size: 0.725rem; font-weight: 600; color: #94a3b8; margin-top: 0.75rem;">
                            <i class="fas fa-shield-alt" style="color: #2E8B83; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.25rem;"></i>
                            {{ __('landing.pricing.cta_note') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <p style="text-align: center; font-size: 0.9rem; font-weight: 600; color: #64748b; margin-top: 3rem;">
            {{ __('landing.pricing.bottom_note') }}
        </p>
    </div>
</section>
