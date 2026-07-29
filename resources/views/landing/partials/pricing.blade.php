<section id="pricing" class="py-24 lg:py-32 bg-gradient-to-b from-white via-slate-50/50 to-white relative overflow-hidden" 
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
        <!-- Header -->
        <div class="text-center mb-16 lg:mb-20" data-animate>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-50 border border-emerald-200/80 mb-6 shadow-sm">
                <span class="text-xs font-black text-emerald-600 uppercase tracking-widest">{{ __('landing.pricing.badge') ?? 'خطط الأسعار' }}</span>
            </div>
            <h2 class="text-3xl md:text-5xl font-black text-slate-900 mb-6 tracking-tight leading-tight">
                {!! __('landing.pricing.title') !!}
            </h2>
            <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto font-medium leading-relaxed">
                {{ __('landing.pricing.subtitle') }}
            </p>

            <!-- Billing Cycle Toggles -->
            <div class="mt-10 flex flex-col items-center gap-6">
                <div class="inline-flex items-center bg-slate-100/80 p-2 rounded-2xl border border-slate-200 shadow-inner">
                    @foreach(['monthly' => 'landing.pricing.monthly', 'term' => 'landing.pricing.term', 'yearly' => 'landing.pricing.yearly'] as $cycle => $label)
                    <button 
                        @click="billingCycle = '{{$cycle}}'"
                        class="px-6 py-2.5 rounded-xl text-sm font-extrabold transition-all duration-300 relative"
                        :class="billingCycle === '{{$cycle}}' ? 'bg-white text-slate-900 shadow-md border border-slate-200/60' : 'text-slate-500 hover:text-slate-800'"
                    >
                        {{ __($label) }}
                        @if($cycle === 'yearly')
                        <span class="absolute -top-2.5 -right-2 px-2 py-0.5 rounded-full bg-emerald-500 text-white text-[10px] font-black border-2 border-white shadow-sm">
                            -17%
                        </span>
                        @endif
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Pricing Cards Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-6xl mx-auto items-stretch" data-stagger>
            @foreach($packages as $index => $package)
                @php
                    $isFeatured = $package->is_featured;
                    $regionalPrices = $package->regional_prices ?? []; 
                @endphp

                <div
                    class="group relative rounded-3xl p-8 lg:p-10 border transition-all duration-300 flex flex-col
                    {{ $isFeatured 
                        ? 'bg-gradient-to-b from-white to-emerald-50/20 border-emerald-500 shadow-2xl shadow-emerald-500/15 ring-2 ring-emerald-500/20 lg:-translate-y-2 z-10' 
                        : 'bg-white border-slate-200/90 hover:border-emerald-300 hover:shadow-xl hover:-translate-y-1' }}"
                >
                    @if($isFeatured)
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-6 py-1.5 rounded-full text-xs font-black uppercase tracking-wider shadow-lg shadow-emerald-500/30 flex items-center gap-1.5">
                        <i class="fas fa-crown text-amber-300 text-xs"></i>
                        <span>{{ __('landing.pricing.featured') }}</span>
                    </div>
                    @endif

                    <!-- Header -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-black text-slate-900 mb-2">
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
                        <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed mb-6">
                            {{ $packageDesc ?: __('landing.pricing.plans.' . $package->slug . '.description') }}
                        </p>
                        
                        <div x-data="{ localPrice: {} }"
                             x-effect="localPrice = getRegionalPrice({{ json_encode($package->regional_prices) }}, {{ $package->price }}, {{ $package->term_price }}, {{ $package->yearly_price }})"
                        >
                            @if($package->trial_days > 0)
                                <div class="mb-4">
                                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100/70 border border-emerald-200 text-emerald-700 text-xs font-black uppercase tracking-widest shadow-sm">
                                        <i class="fas fa-gift text-emerald-600"></i>
                                        {{ __('landing.pricing.trial_days', ['days' => $package->trial_days]) }}
                                    </span>
                                </div>
                            @endif
                            
                            <div class="flex items-baseline gap-2">
                                @if(app()->getLocale() == 'ar')
                                    <span class="text-base font-black text-emerald-600" x-text="localPrice.currency"></span>
                                    <span class="text-4xl lg:text-5xl font-black text-slate-900 tracking-tighter" 
                                          x-text="billingCycle === 'monthly' ? localPrice.amount : (billingCycle === 'term' ? localPrice.term_price : localPrice.yearly_price)">
                                    </span>
                                @else
                                    <span class="text-4xl lg:text-5xl font-black text-slate-900 tracking-tighter" 
                                          x-text="billingCycle === 'monthly' ? localPrice.amount : (billingCycle === 'term' ? localPrice.term_price : localPrice.yearly_price)">
                                    </span>
                                    <span class="text-base font-black text-emerald-600" x-text="localPrice.currency"></span>
                                @endif
                            </div>
                            <div class="mt-2 text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <span x-show="billingCycle === 'monthly'">{{ __('landing.pricing.per_month') }}</span>
                                <span x-show="billingCycle === 'term'">{{ __('landing.pricing.per_term') }}</span>
                                <span x-show="billingCycle === 'yearly'">{{ __('landing.pricing.per_year') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Features Checklist -->
                    <ul class="space-y-3.5 mb-10 flex-grow border-t border-slate-100 pt-6">
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
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-emerald-100/80 flex items-center justify-center flex-shrink-0 mt-0.5 text-emerald-700">
                                <i class="fas fa-check text-[10px]"></i>
                            </div>
                            <span class="text-sm text-slate-700 font-semibold leading-tight">{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>

                    <div class="mt-auto">
                        <a :href="'{{ route('register') }}?plan={{ $package->slug }}&cycle=' + billingCycle + '&currency=' + selectedCurrency"
                           class="w-full flex items-center justify-center py-4 rounded-2xl font-black text-sm uppercase tracking-wider transition-all duration-300
                           {{ $isFeatured 
                               ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-xl shadow-emerald-500/25 hover:from-emerald-500 hover:to-teal-500 hover:shadow-2xl hover:scale-[1.02] active:scale-95' 
                               : 'bg-slate-900 text-white hover:bg-slate-800 hover:scale-[1.02] active:scale-95 shadow-md' }}">
                            {{ $package->trial_days > 0 ? __('landing.pricing.cta_free') : __('landing.pricing.cta_paid') }}
                        </a>
                        @if($package->trial_days > 0)
                            <p class="text-center text-xs font-bold text-slate-400 mt-3.5 uppercase tracking-wider opacity-80">
                                <i class="fas fa-shield-alt text-emerald-500 me-1"></i>
                                {{ __('landing.pricing.cta_note') ?? 'إلغاء في أي وقت' }}
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <p class="text-center text-sm font-semibold text-slate-500 mt-12">
            {{ __('landing.pricing.bottom_note') }}
        </p>
    </div>
</section>

