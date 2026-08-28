{{-- Pricing Section — Premium Responsive Layout --}}
<section id="pricing" class="py-16 lg:py-24 bg-[#f8fafc] border-t border-b border-slate-200/80"
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

    <div class="container mx-auto px-5 lg:px-12 max-w-7xl">
        {{-- Section Header --}}
        <div class="text-center mb-12" data-animate="fade-in">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#e8f5f3] border border-[#c5e8e4] text-xs font-bold text-[#2E8B83] mb-4">
                <i class="fas fa-[#2E8B83] fa-tags text-[10px]"></i>
                <span>{{ __('landing.pricing.badge') }}</span>
            </div>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 leading-tight mb-3">
                {{ __('landing.pricing.title') }}
            </h2>
            <p class="text-sm sm:text-base text-slate-600 font-medium max-w-xl mx-auto leading-relaxed">
                {{ __('landing.pricing.subtitle') }}
            </p>

            {{-- Billing Cycle Toggles --}}
            <div class="mt-6 flex flex-col items-center justify-center gap-3">
                <div class="bg-white p-1 rounded-full border border-slate-200 shadow-2xs inline-flex items-center gap-1">
                    @foreach(['monthly' => 'landing.pricing.monthly', 'term' => 'landing.pricing.term', 'yearly' => 'landing.pricing.yearly'] as $cycle => $label)
                    <button
                        @click="billingCycle = '{{$cycle}}'"
                        class="px-4 py-2 rounded-full text-xs font-extrabold transition-all"
                        :class="billingCycle === '{{$cycle}}' ? 'bg-[#2E8B83] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                    >
                        {{ __($label) }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Pricing Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 items-stretch" data-animate="fade-in">
            @foreach($packages as $index => $package)
                @php
                    $isFeatured = $package->is_featured;
                    $regionalPrices = $package->regional_prices ?? [];
                @endphp

                <div class="bg-white rounded-3xl p-6 sm:p-8 flex flex-col justify-between relative transition-all duration-300 {{ $isFeatured ? 'border-2 border-[#2E8B83] shadow-xl hover:shadow-2xl' : 'border border-slate-200/80 shadow-2xs hover:shadow-lg' }}">
                    
                    @if($isFeatured)
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-[#2E8B83] text-white px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 shadow-xs whitespace-nowrap">
                        <i class="fas fa-crown text-amber-300 text-xs"></i>
                        <span>{{ __('landing.pricing.featured') }}</span>
                    </div>
                    @endif

                    {{-- Package Header --}}
                    <div>
                        <h3 class="text-xl font-black text-slate-900 mb-2">
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
                        <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed mb-5 min-h-[40px]">
                            {{ $packageDesc ?: __('landing.pricing.plans.' . $package->slug . '.description') }}
                        </p>

                        <div x-data="{ localPrice: {} }"
                             x-effect="localPrice = getRegionalPrice({{ json_encode($package->regional_prices) }}, {{ $package->price }}, {{ $package->term_price }}, {{ $package->yearly_price }})"
                             class="mb-6"
                        >
                            {{-- Free Trial Badge --}}
                            <div class="mb-3">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#e8f5f3] border border-[#c5e8e4] text-xs font-extrabold text-[#2E8B83]">
                                    <i class="fas fa-gift text-xs"></i>
                                    <span>{{ __('landing.pricing.trial_days', ['days' => $package->trial_days ?: 30]) }}</span>
                                </span>
                            </div>

                            <div class="flex items-baseline gap-1.5">
                                @if(app()->getLocale() == 'ar')
                                    <span class="text-lg font-black text-[#2E8B83]" x-text="localPrice.currency"></span>
                                    <span class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight"
                                          x-text="billingCycle === 'monthly' ? localPrice.amount : (billingCycle === 'term' ? localPrice.term_price : localPrice.yearly_price)">
                                    </span>
                                @else
                                    <span class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight"
                                          x-text="billingCycle === 'monthly' ? localPrice.amount : (billingCycle === 'term' ? localPrice.term_price : localPrice.yearly_price)">
                                    </span>
                                    <span class="text-lg font-black text-[#2E8B83]" x-text="localPrice.currency"></span>
                                @endif
                            </div>
                            <div class="mt-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">
                                <span x-show="billingCycle === 'monthly'">{{ __('landing.pricing.per_month') }}</span>
                                <span x-show="billingCycle === 'term'">{{ __('landing.pricing.per_term') }}</span>
                                <span x-show="billingCycle === 'yearly'">{{ __('landing.pricing.per_year') }}</span>
                            </div>
                        </div>

                        {{-- Features Checklist --}}
                        <ul class="space-y-3 pt-5 border-t border-slate-100 mb-8">
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
                            <li class="flex items-start gap-2.5 text-xs sm:text-sm font-semibold text-slate-700">
                                <div class="w-5 h-5 rounded-full bg-[#e8f5f3] text-[#2E8B83] flex items-center justify-center text-[10px] shrink-0 mt-0.5">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>{{ $feature }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- CTA Button --}}
                    <div>
                        <a :href="'{{ route('register') }}?plan={{ $package->slug }}&cycle=' + billingCycle + '&currency=' + selectedCurrency"
                           data-track="landing_pricing_cta_clicked"
                           class="w-full py-3.5 rounded-full font-extrabold text-sm text-center inline-flex items-center justify-center gap-2 text-decoration-none transition-all shadow-xs hover:shadow-md {{ $isFeatured ? 'bg-[#2E8B83] text-white hover:bg-[#1b635d]' : 'bg-slate-900 text-white hover:bg-slate-800' }}"
                        >
                            <span>{{ __('landing.pricing.cta_free') }}</span>
                            <i class="fas fa-arrow-left text-xs rtl:rotate-0 ltr:rotate-180"></i>
                        </a>
                        <p class="text-center text-[11px] font-semibold text-slate-400 mt-2.5">
                            <i class="fas fa-shield-alt text-[#2E8B83] me-1"></i>
                            <span>{{ __('landing.pricing.cta_note') }}</span>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <p class="text-center text-xs sm:text-sm font-bold text-slate-500 mt-10">
            {{ __('landing.pricing.bottom_note') }}
        </p>
    </div>
</section>
