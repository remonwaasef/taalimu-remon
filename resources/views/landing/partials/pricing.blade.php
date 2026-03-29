<section id="pricing" class="py-24 bg-white relative overflow-hidden" 
         x-data="{ 
            billingCycle: 'monthly',
            userCountry: 'default',
            currencySymbol: '{{ \App\Models\SiteSetting::get('currency_symbol', '$') }}',
            async init() {
                try {
                    const response = await fetch('https://get.geojs.io/v1/ip/country.json');
                    const data = await response.json();
                    this.userCountry = data.country;
                    
                    if(this.userCountry === 'EG') this.currencySymbol = 'EGP';
                    else if(this.userCountry === 'SA') this.currencySymbol = 'SAR';
                    else if(this.userCountry === 'AE') this.currencySymbol = 'AED';
                    else if(['FR', 'DE', 'IT', 'ES', 'NL'].includes(this.userCountry)) this.currencySymbol = '€';
                    else this.currencySymbol = '$';
                } catch(e) { console.log('Geo fetch failed'); }
            },
            getPrice(packagePrice, packageRegionalPrices) {
                if (!packageRegionalPrices || Object.keys(packageRegionalPrices).length === 0) {
                    return { amount: packagePrice, currency: '{{ \App\Models\SiteSetting::get('currency_code', 'USD') }}' }; 
                }
                let priceData = packageRegionalPrices[this.userCountry] || packageRegionalPrices['default'];
                return priceData || { amount: packagePrice, currency: '{{ \App\Models\SiteSetting::get('currency_code', 'USD') }}' };
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

            <!-- Billing Toggle -->
            <div class="mt-10 flex justify-center">
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
                        {{ __('landing.pricing.featured') ?? 'Most Popular' }}
                    </div>
                    @endif

                    <!-- Header -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-slate-900 mb-6">
                            {{ __('landing.pricing.plans.' . $package->slug . '.name') }}
                        </h3>
                        
                        <div x-data="{ localPrice: getPrice({{ $package->price }}, {{ json_encode($regionalPrices) }}) }"
                             x-effect="localPrice = getPrice({{ $package->price }}, {{ json_encode($regionalPrices) }})"
                        >
                            <div class="flex items-baseline gap-1">
                                <span class="text-sm font-bold text-slate-400" x-text="localPrice.currency"></span>
                                <span class="text-5xl font-black text-slate-900 tracking-tighter" 
                                      x-text="billingCycle === 'monthly' ? localPrice.amount : (billingCycle === 'term' ? (localPrice.term_price || localPrice.amount * 4) : (localPrice.yearly_price || localPrice.amount * 10))">
                                </span>
                            </div>
                            <div class="mt-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                <span x-show="billingCycle === 'monthly'">{{ __('landing.pricing.per_month') }}</span>
                                <span x-show="billingCycle === 'term'">{{ __('landing.pricing.per_term') }}</span>
                                <span x-show="billingCycle === 'yearly'">{{ __('landing.pricing.per_year') }}</span>
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
                                $label = __($feat->code) === $feat->code ? (app()->getLocale() === 'en' && $feat->name_en ? $feat->name_en : $feat->name) : __($feat->code);
                                $pFeatures[] = ($val === '-1') ? ($label . ': Unlimited') : (($feat->type === 'boolean') ? $label : ($label . ': ' . $val));
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
                        <a :href="'{{ route('register') }}?plan={{ $package->slug }}&cycle=' + billingCycle"
                           class="w-full flex items-center justify-center py-3.5 rounded-xl font-bold text-sm transition-all duration-300
                           {{ $isFeatured 
                               ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20 hover:bg-emerald-400 hover:scale-[1.02]' 
                               : 'bg-slate-100 text-slate-800 hover:bg-slate-200' }}">
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
