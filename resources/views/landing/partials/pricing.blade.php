{{-- Pricing Section --}}
<section id="pricing" class="section-alt" style="padding:6rem 0; border-top:1px solid #e2e8f0;"
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
        <div class="text-center mb-16" data-animate>
            <div class="section-badge section-badge-dark" style="margin-bottom:1.5rem; display:inline-flex;">
                <span style="color:#6ee7b7 !important; font-weight:700;">{{ __('landing.pricing.badge') ?? 'خطط الأسعار' }}</span>
            </div>
            <h2 style="color:#0f172a !important; font-size:clamp(1.875rem, 4vw, 3rem); font-weight:900; margin-bottom:1.5rem; letter-spacing:-0.025em; line-height:1.2;">
                {!! __('landing.pricing.title') !!}
            </h2>
            <p style="color:#475569 !important; font-size:1.125rem; max-width:42rem; margin:0 auto; font-weight:500; line-height:1.7;">
                {{ __('landing.pricing.subtitle') }}
            </p>

            {{-- Billing Cycle Toggles --}}
            <div style="margin-top:2.5rem; display:flex; flex-direction:column; align-items:center; gap:1.5rem;">
                <div class="pricing-toggle-container">
                    @foreach(['monthly' => 'landing.pricing.monthly', 'term' => 'landing.pricing.term', 'yearly' => 'landing.pricing.yearly'] as $cycle => $label)
                    <button
                        @click="billingCycle = '{{$cycle}}'"
                        class="pricing-toggle-btn"
                        :class="billingCycle === '{{$cycle}}' ? 'active' : ''"
                        :style="billingCycle === '{{$cycle}}' ? 'background:#0f172a; color:#ffffff;' : 'color:#475569;'"
                        style="position:relative;"
                    >
                        {{ __($label) }}
                        @if($cycle === 'yearly')
                        <span style="position:absolute; top:-0.625rem; {{ app()->getLocale() == 'ar' ? 'left:-0.5rem;' : 'right:-0.5rem;' }} background:#f59e0b; color:#0f172a; font-size:0.6rem; font-weight:800; padding:0.125rem 0.5rem; border-radius:9999px; border:2px solid #ffffff; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                            -17%
                        </span>
                        @endif
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
                    background:#ffffff;
                    border-radius:1.25rem;
                    padding:2rem 1.75rem;
                    display:flex;
                    flex-direction:column;
                    transition:all 0.3s;
                    position:relative;
                    {{ $isFeatured
                        ? 'border:2px solid #059669; box-shadow:0 25px 60px -12px rgba(5,150,105,0.15); transform:translateY(-8px); z-index:10;'
                        : 'border:1px solid #e2e8f0; box-shadow:0 1px 3px rgba(0,0,0,0.04);'
                    }}
                ">
                    @if($isFeatured)
                    <div style="position:absolute; top:-0.875rem; left:50%; transform:translateX(-50%); background:#059669; color:#ffffff; padding:0.375rem 1.5rem; border-radius:9999px; font-size:0.7rem; font-weight:800; text-transform:uppercase; letter-spacing:0.08em; box-shadow:0 4px 12px rgba(5,150,105,0.3); display:flex; align-items:center; gap:0.375rem; white-space:nowrap;">
                        <i class="fas fa-crown" style="color:#fde68a; font-size:0.65rem;"></i>
                        <span>{{ __('landing.pricing.featured') }}</span>
                    </div>
                    @endif

                    {{-- Package Header --}}
                    <div style="margin-bottom:2rem;">
                        <h3 style="color:#0f172a !important; font-size:1.5rem; font-weight:900; margin-bottom:0.5rem;">
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
                        <p style="color:#64748b !important; font-size:0.875rem; font-weight:500; line-height:1.5; margin-bottom:1.5rem;">
                            {{ $packageDesc ?: __('landing.pricing.plans.' . $package->slug . '.description') }}
                        </p>

                        <div x-data="{ localPrice: {} }"
                             x-effect="localPrice = getRegionalPrice({{ json_encode($package->regional_prices) }}, {{ $package->price }}, {{ $package->term_price }}, {{ $package->yearly_price }})"
                        >
                            @if($package->trial_days > 0)
                                <div style="margin-bottom:1rem;">
                                    <span style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.375rem 1rem; border-radius:9999px; background:#ecfdf5; border:1px solid #a7f3d0; font-size:0.75rem; font-weight:700; color:#047857;">
                                        <i class="fas fa-gift" style="color:#059669;"></i>
                                        {{ __('landing.pricing.trial_days', ['days' => $package->trial_days]) }}
                                    </span>
                                </div>
                            @endif

                            <div style="display:flex; align-items:baseline; gap:0.5rem;">
                                @if(app()->getLocale() == 'ar')
                                    <span style="color:#059669; font-size:1rem; font-weight:800;" x-text="localPrice.currency"></span>
                                    <span style="color:#0f172a; font-size:clamp(2.25rem, 5vw, 3rem); font-weight:900; letter-spacing:-0.05em;"
                                          x-text="billingCycle === 'monthly' ? localPrice.amount : (billingCycle === 'term' ? localPrice.term_price : localPrice.yearly_price)">
                                    </span>
                                @else
                                    <span style="color:#0f172a; font-size:clamp(2.25rem, 5vw, 3rem); font-weight:900; letter-spacing:-0.05em;"
                                          x-text="billingCycle === 'monthly' ? localPrice.amount : (billingCycle === 'term' ? localPrice.term_price : localPrice.yearly_price)">
                                    </span>
                                    <span style="color:#059669; font-size:1rem; font-weight:800;" x-text="localPrice.currency"></span>
                                @endif
                            </div>
                            <div style="margin-top:0.5rem; font-size:0.75rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.1em;">
                                <span x-show="billingCycle === 'monthly'">{{ __('landing.pricing.per_month') }}</span>
                                <span x-show="billingCycle === 'term'">{{ __('landing.pricing.per_term') }}</span>
                                <span x-show="billingCycle === 'yearly'">{{ __('landing.pricing.per_year') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Features Checklist --}}
                    <ul style="list-style:none; padding:0; margin:0 0 2rem 0; flex-grow:1; border-top:1px solid #f1f5f9; padding-top:1.5rem;">
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
                        <li style="display:flex; align-items:flex-start; gap:0.75rem; margin-bottom:0.875rem;">
                            <div class="check-icon">
                                <i class="fas fa-check" style="font-size:0.5rem;"></i>
                            </div>
                            <span style="color:#334155; font-size:0.875rem; font-weight:600; line-height:1.4;">{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>

                    {{-- CTA Button --}}
                    <div style="margin-top:auto;">
                        <a :href="'{{ route('register') }}?plan={{ $package->slug }}&cycle=' + billingCycle + '&currency=' + selectedCurrency"
                           style="
                                width:100%;
                                display:flex;
                                align-items:center;
                                justify-content:center;
                                padding:1rem;
                                border-radius:0.875rem;
                                font-weight:800;
                                font-size:0.875rem;
                                text-transform:uppercase;
                                letter-spacing:0.05em;
                                transition:all 0.3s;
                                text-decoration:none;
                                {{ $isFeatured
                                    ? 'background:linear-gradient(135deg, #059669, #10b981); color:#ffffff; box-shadow:0 8px 25px rgba(5,150,105,0.3);'
                                    : 'background:#0f172a; color:#ffffff; box-shadow:0 4px 12px rgba(15,23,42,0.15);'
                                }}
                           "
                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 35px rgba(0,0,0,0.2)'"
                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='{{ $isFeatured ? '0 8px 25px rgba(5,150,105,0.3)' : '0 4px 12px rgba(15,23,42,0.15)' }}'"
                        >
                            {{ $package->trial_days > 0 ? __('landing.pricing.cta_free') : __('landing.pricing.cta_paid') }}
                        </a>
                        @if($package->trial_days > 0)
                            <p style="text-align:center; font-size:0.7rem; font-weight:600; color:#94a3b8; margin-top:0.875rem; text-transform:uppercase; letter-spacing:0.05em;">
                                <i class="fas fa-shield-alt" style="color:#059669; margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}:0.25rem;"></i>
                                {{ __('landing.pricing.cta_note') ?? 'إلغاء في أي وقت' }}
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <p style="text-align:center; font-size:0.875rem; font-weight:600; color:#64748b; margin-top:3rem;">
            {{ __('landing.pricing.bottom_note') }}
        </p>
    </div>
</section>
