{{-- Pricing Section — Compact Layout --}}
<section id="pricing" class="section-alt" style="padding: 4rem 0; background: #f8fafc; border-bottom: 1px solid #e2e8f0;"
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

    <div class="container mx-auto px-4 lg:px-12" style="max-width: 1140px; margin: 0 auto;">
        {{-- Header --}}
        <div class="text-center mb-10" data-animate>
            <div class="section-badge" style="margin-bottom: 0.875rem; display: inline-flex; background: #E6F4F3; border: 1px solid #B2DDD9; padding: 0.3rem 0.85rem; border-radius: 9999px;">
                <span style="color: #25746D !important; font-weight: 700; font-size: 0.775rem;">{{ __('landing.pricing.badge') }}</span>
            </div>
            <h2 style="color: #0f172a !important; font-size: clamp(1.5rem, 2.8vw, 2.15rem); font-weight: 900; margin-bottom: 0.75rem; letter-spacing: -0.02em; line-height: 1.25;">
                {{ __('landing.pricing.title') }}
            </h2>
            <p style="color: #475569 !important; font-size: 0.975rem; max-width: 38rem; margin: 0 auto; font-weight: 500; line-height: 1.6;">
                {{ __('landing.pricing.subtitle') }}
            </p>

            {{-- Billing Cycle Toggles --}}
            <div style="margin-top: 1.5rem; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                <div class="pricing-toggle-container" style="background: #ffffff; border: 1px solid #cbd5e1; padding: 0.25rem; border-radius: 0.75rem; display: inline-flex; gap: 0.25rem;">
                    @foreach(['monthly' => 'landing.pricing.monthly', 'term' => 'landing.pricing.term', 'yearly' => 'landing.pricing.yearly'] as $cycle => $label)
                    <button
                        @click="billingCycle = '{{$cycle}}'"
                        class="pricing-toggle-btn"
                        :class="billingCycle === '{{$cycle}}' ? 'active' : ''"
                        :style="billingCycle === '{{$cycle}}' ? 'background:#0f172a; color:#ffffff; font-weight:800;' : 'color:#475569; font-weight:600;'"
                        style="position: relative; padding: 0.45rem 1rem; border-radius: 0.5rem; font-size: 0.8rem; border: none; transition: all 0.2s;"
                    >
                        {{ __($label) }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Pricing Cards --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-5xl mx-auto items-stretch" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; align-items: stretch;" data-stagger>
            @foreach($packages as $index => $package)
                @php
                    $isFeatured = $package->is_featured;
                    $regionalPrices = $package->regional_prices ?? [];
                @endphp

                <div style="
                    background: #ffffff;
                    border-radius: 1rem;
                    padding: 1.5rem 1.35rem;
                    display: flex;
                    flex-direction: column;
                    transition: all 0.25s;
                    position: relative;
                    {{ $isFeatured
                        ? 'border: 2px solid #2E8B83; box-shadow: 0 15px 35px -8px rgba(46,139,131,0.15); transform: translateY(-4px); z-index: 10;'
                        : 'border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.03);'
                    }}
                ">
                    @if($isFeatured)
                    <div style="position: absolute; top: -0.75rem; left: 50%; transform: translateX(-50%); background: #2E8B83; color: #ffffff; padding: 0.25rem 1rem; border-radius: 9999px; font-size: 0.675rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 0.35rem; white-space: nowrap;">
                        <i class="fas fa-crown" style="color: #fde68a; font-size: 0.65rem;"></i>
                        <span>{{ __('landing.pricing.featured') }}</span>
                    </div>
                    @endif

                    {{-- Package Header --}}
                    <div style="margin-bottom: 1.25rem;">
                        <h3 style="color: #0f172a !important; font-size: 1.25rem; font-weight: 900; margin-bottom: 0.35rem;">
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
                        <p style="color: #64748b !important; font-size: 0.825rem; font-weight: 500; line-height: 1.45; margin-bottom: 1rem; min-height: 2.25rem;">
                            {{ $packageDesc ?: __('landing.pricing.plans.' . $package->slug . '.description') }}
                        </p>

                        <div x-data="{ localPrice: {} }"
                             x-effect="localPrice = getRegionalPrice({{ json_encode($package->regional_prices) }}, {{ $package->price }}, {{ $package->term_price }}, {{ $package->yearly_price }})"
                        >
                            {{-- 30-Day Free Trial Badge --}}
                            <div style="margin-bottom: 0.75rem;">
                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.25rem 0.65rem; border-radius: 9999px; background: #E6F4F3; border: 1px solid #B2DDD9; font-size: 0.7rem; font-weight: 800; color: #25746D;">
                                    <i class="fas fa-gift" style="color: #2E8B83;"></i>
                                    <span>{{ __('landing.pricing.trial_days', ['days' => $package->trial_days ?: 30]) }}</span>
                                </span>
                            </div>

                            <div style="display: flex; align-items: baseline; gap: 0.35rem;">
                                @if(app()->getLocale() == 'ar')
                                    <span style="color: #2E8B83; font-size: 0.95rem; font-weight: 800;" x-text="localPrice.currency"></span>
                                    <span style="color: #0f172a; font-size: clamp(1.85rem, 3.5vw, 2.25rem); font-weight: 900; letter-spacing: -0.04em;"
                                          x-text="billingCycle === 'monthly' ? localPrice.amount : (billingCycle === 'term' ? localPrice.term_price : localPrice.yearly_price)">
                                    </span>
                                @else
                                    <span style="color: #0f172a; font-size: clamp(1.85rem, 3.5vw, 2.25rem); font-weight: 900; letter-spacing: -0.04em;"
                                          x-text="billingCycle === 'monthly' ? localPrice.amount : (billingCycle === 'term' ? localPrice.term_price : localPrice.yearly_price)">
                                    </span>
                                    <span style="color: #2E8B83; font-size: 0.95rem; font-weight: 800;" x-text="localPrice.currency"></span>
                                @endif
                            </div>
                            <div style="margin-top: 0.25rem; font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">
                                <span x-show="billingCycle === 'monthly'">{{ __('landing.pricing.per_month') }}</span>
                                <span x-show="billingCycle === 'term'">{{ __('landing.pricing.per_term') }}</span>
                                <span x-show="billingCycle === 'yearly'">{{ __('landing.pricing.per_year') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Features Checklist --}}
                    <ul style="list-style: none; padding: 0; margin: 0 0 1.5rem 0; flex-grow: 1; border-top: 1px solid #f1f5f9; padding-top: 1rem;">
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
                        @foreach(array_slice($pFeatures, 0, 7) as $feature)
                        <li style="display: flex; align-items: flex-start; gap: 0.625rem; margin-bottom: 0.65rem;">
                            <div style="width: 1.1rem; height: 1.1rem; border-radius: 50%; background: #E6F4F3; color: #2E8B83; display: flex; align-items: center; justify-content: center; font-size: 0.55rem; flex-shrink: 0; margin-top: 0.15rem;">
                                <i class="fas fa-check"></i>
                            </div>
                            <span style="color: #334155; font-size: 0.825rem; font-weight: 600; line-height: 1.35;">{{ $feature }}</span>
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
                                padding: 0.85rem;
                                border-radius: 0.75rem;
                                font-weight: 800;
                                font-size: 0.85rem;
                                text-decoration: none;
                                transition: all 0.2s;
                                {{ $isFeatured
                                    ? 'background: linear-gradient(135deg, #2E8B83, #10b981); color: #ffffff !important; box-shadow: 0 6px 18px rgba(46,139,131,0.25);'
                                    : 'background: #0f172a; color: #ffffff !important;'
                                }}
                           "
                           onmouseover="this.style.transform='translateY(-2px)'"
                           onmouseout="this.style.transform='translateY(0)'"
                        >
                            {{ __('landing.pricing.cta_free') }}
                        </a>
                        <p style="text-align: center; font-size: 0.675rem; font-weight: 600; color: #94a3b8; margin-top: 0.65rem;">
                            <i class="fas fa-shield-alt" style="color: #2E8B83; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 0.2rem;"></i>
                            {{ __('landing.pricing.cta_note') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <p style="text-align: center; font-size: 0.825rem; font-weight: 600; color: #64748b; margin-top: 2rem;">
            {{ __('landing.pricing.bottom_note') }}
        </p>
    </div>
</section>
