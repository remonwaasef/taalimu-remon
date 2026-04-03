<section id="pricing" class="py-24 bg-white relative overflow-hidden" 
         x-data="{ 
            billingCycle: 'monthly',
            selectedCurrency: 'EGP',
            async init() {
                const locale = '{{ app()->getLocale() }}';
                
                // Override based on explicitly selected language
                if (locale === 'fr') {
                    this.selectedCurrency = 'EUR';
                    return;
                } else if (locale === 'en') {
                    this.selectedCurrency = 'USD';
                    return;
                }

                // Default to Geographical logic if Arabic or other
                try {
                    const response = await fetch('https://get.geojs.io/v1/ip/country.json');
                    const data = await response.json();
                    const country = data.country;
                    
                    if (country === 'EG') this.selectedCurrency = 'EGP';
                    else if (['FR', 'DE', 'IT', 'ES', 'NL', 'BE', 'AT', 'PT', 'IE'].includes(country)) this.selectedCurrency = 'EUR';
                    else this.selectedCurrency = 'USD';
                } catch(e) { 
                    console.log('Geo fetch failed, defaulting to EGP');
                    this.selectedCurrency = 'EGP';
                }
            },
            getRegionalPrice(packageRegionalPrices, basePrice, baseTermPrice, baseYearlyPrice) {
                if (!packageRegionalPrices || !packageRegionalPrices[this.selectedCurrency]) {
                    return { 
                        amount: basePrice, 
                        currency: this.selectedCurrency,
                        term_price: baseTermPrice,
                        yearly_price: baseYearlyPrice
                    };
                }
                return packageRegionalPrices[this.selectedCurrency];
            }
         }">
    
    <div class="container mx-auto px-4 lg:px-12">
        <!-- Header -->
        <div class="text-center mb-16" data-animate>
            <h2 class="text-3xl md:text-5xl font-black text-slate-900 mb-6 tracking-tight leading-tight">
                {!! __('landing.pricing.title') !!}
            </h2>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto font-medium">
                {{ __('landing.pricing.subtitle') }}
            </p>

            <!-- Billing & Currency Toggles -->
            <div class="mt-10 flex flex-col items-center gap-6">
                <!-- Billing Cycle -->
                <div class="inline-flex items-center bg-slate-100 p-1.5 rounded-2xl border border-slate-200">
                    @foreach(['monthly' => 'landing.pricing.monthly', 'term' => 'landing.pricing.term', 'yearly' => 'landing.pricing.yearly'] as $cycle => $label)
                    <button 
                        @click="billingCycle = '{{$cycle}}'"
                        class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-300 relative"
                        :class="billingCycle === '{{$cycle}}' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/50' : 'text-slate-500 hover:text-slate-800'"
                    >
                        {{ __($label) }}
                        @if($cycle === 'yearly')
                        <span class="absolute -top-2.5 -right-2 px-2 py-0.5 rounded-full bg-emerald-500 text-white text-[9px] font-bold border-2 border-white">
                            -17%
                        </span>
                        @endif
                    </button>
                    @endforeach
                </div>


            </div>
        </div>

        <!-- Pricing Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-5xl mx-auto items-stretch" data-stagger>
            @foreach($packages as $index => $package)
                @php
                    $isFeatured = $package->is_featured;
                    $regionalPrices = $package->regional_prices ?? []; 
                    $isFirst = $index === 0;
                @endphp

                <div
                    class="group relative rounded-2xl p-8 border transition-all duration-300 flex flex-col
                    {{ $isFeatured 
                        ? 'bg-white border-emerald-500 shadow-2xl shadow-emerald-500/10 scale-105 z-10' 
                        : 'bg-white border-slate-200 hover:border-slate-300 hover:shadow-lg hover:-translate-y-1' }}"
                >
                    @if($isFeatured)
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-emerald-500 text-white px-5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                        {{ __('landing.pricing.featured') }}
                    </div>
                    @endif

                    <!-- Header -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-slate-900 mb-2">
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
                        <p class="text-xs text-slate-400 font-medium mb-6">
                            {{ $packageDesc ?: __('landing.pricing.plans.' . $package->slug . '.description') }}
                        </p>
                        
                        <div x-data="{ localPrice: {} }"
                             x-effect="localPrice = getRegionalPrice({{ json_encode($package->regional_prices) }}, {{ $package->price }}, {{ $package->term_price }}, {{ $package->yearly_price }})"
                        >
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-sm font-bold text-emerald-500" x-text="localPrice.currency"></span>
                                <span class="text-5xl font-black text-slate-900 tracking-tighter" 
                                      x-text="billingCycle === 'monthly' ? localPrice.amount : (billingCycle === 'term' ? localPrice.term_price : localPrice.yearly_price)">
                                </span>
                            </div>
                            <div class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <span x-show="billingCycle === 'monthly'">{{ __('landing.pricing.per_month') }}</span>
                                <span x-show="billingCycle === 'term'">{{ __('landing.pricing.per_term') }}</span>
                                <span x-show="billingCycle === 'yearly'">{{ __('landing.pricing.per_year') }}</span>
                                <template x-if="billingCycle !== 'monthly'">
                                    <span class="px-2 py-0.5 bg-slate-100 rounded text-emerald-600 lowercase" x-text="'≈ ' + (billingCycle === 'term' ? (localPrice.term_price / 4).toFixed(1) : (localPrice.yearly_price / 12).toFixed(1)) + ' /mo'"></span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Features -->
                    <ul class="space-y-3 mb-8 flex-grow">
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
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-emerald-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-check text-[9px] text-emerald-500"></i>
                            </div>
                            <span class="text-sm text-slate-600 leading-tight">{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>

                    <div class="mt-auto">
                        <a :href="'{{ route('register') }}?plan={{ $package->slug }}&cycle=' + billingCycle + '&currency=' + selectedCurrency"
                           class="w-full flex items-center justify-center py-4 rounded-xl font-black text-xs uppercase tracking-widest transition-all duration-300
                           {{ $isFeatured 
                               ? 'bg-emerald-500 text-white shadow-xl shadow-emerald-500/20 hover:bg-emerald-600 hover:scale-[1.02] active:scale-95' 
                               : 'bg-slate-900 text-white hover:bg-slate-800 hover:scale-[1.02] active:scale-95' }}">
                            {{ __('landing.pricing.cta_paid') }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <p class="text-center text-sm font-medium text-slate-400 mt-10">
            {{ __('landing.pricing.bottom_note') }}
        </p>
    </div>
</section>
