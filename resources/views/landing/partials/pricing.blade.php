<section id="pricing" class="py-24 bg-white relative overflow-hidden section-wave" 
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
    
    <!-- Background Decor -->
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-slate-50 rounded-full blur-[120px] -z-10 opacity-70"></div>

    <div class="container mx-auto px-4 lg:px-12">
        <!-- Section Header -->
        <div class="text-center mb-16" data-animate>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#f0fdf4] border border-green-100 mb-6">
                <span class="text-xs font-black text-[#22c55e] uppercase tracking-[0.2em]">{{ __('landing.pricing.badge') }}</span>
            </div>
            <h2 class="text-3xl md:text-5xl font-black text-[#0f172a] mb-6 tracking-tight leading-tight">
                {!! __('landing.pricing.title') !!}
            </h2>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto font-medium">
                {{ __('landing.pricing.subtitle') }}
            </p>

            <!-- Refined Billing Toggle -->
            <div class="mt-12 flex justify-center">
                <div class="inline-flex items-center bg-slate-100 p-1.5 rounded-[2rem] border border-slate-200">
                    @foreach(['monthly' => 'landing.pricing.monthly', 'term' => 'landing.pricing.term', 'yearly' => 'landing.pricing.yearly'] as $cycle => $label)
                    <button 
                        @click="billingCycle = '{{$cycle}}'"
                        class="px-8 py-3 rounded-[1.75rem] text-sm font-black transition-all duration-300 relative"
                        :class="billingCycle === '{{$cycle}}' ? 'bg-white text-[#22c55e] shadow-soft scale-105' : 'text-slate-500 hover:text-slate-800'"
                    >
                        {{ __($label) }}
                        @if($cycle === 'yearly')
                        <span class="absolute -top-3 -right-3 px-2 py-0.5 rounded-full bg-[#22c55e] text-white text-[9px] font-black border-2 border-white shadow-sm">
                            -17%
                        </span>
                        @endif
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Pricing Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 items-stretch" data-stagger>
            @foreach($packages as $index => $package)
                @php
                    $isFeatured = $package->is_featured;
                    $regionalPrices = $package->regional_prices ?? []; 
                @endphp

                <div
                    class="group relative rounded-[2.5rem] p-10 border transition-all duration-500 flex flex-col bg-white
                    {{ $isFeatured 
                        ? 'border-[#22c55e] shadow-premium scale-105 z-10' 
                        : 'border-slate-100 shadow-soft hover:shadow-premium hover:-translate-y-2' }}"
                >
                    @if($isFeatured)
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-[#22c55e] text-white px-6 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">
                        {{ __('landing.pricing.featured') ?? 'Most Popular' }}
                    </div>
                    @endif

                    <!-- Header -->
                    <div class="text-center mb-10">
                        <h3 class="text-xl font-black text-[#0f172a] mb-6 tracking-tight">
                            {{ __('landing.pricing.plans.' . $package->slug . '.name') }}
                        </h3>
                        
                        <!-- Price Display -->
                        <div x-data="{ localPrice: getPrice({{ $package->price }}, {{ json_encode($regionalPrices) }}) }"
                             x-effect="localPrice = getPrice({{ $package->price }}, {{ json_encode($regionalPrices) }})"
                             class="flex flex-col items-center">
                            
                            <div class="flex items-baseline gap-2">
                                <span class="text-sm font-black text-slate-400" x-text="localPrice.currency"></span>
                                <span class="text-5xl font-black text-[#0f172a] tracking-tighter" 
                                      x-text="billingCycle === 'monthly' ? localPrice.amount : (billingCycle === 'term' ? (localPrice.term_price || localPrice.amount * 4) : (localPrice.yearly_price || localPrice.amount * 10))">
                                </span>
                            </div>
                            <div class="mt-2 text-xs font-bold text-slate-500 uppercase tracking-widest opacity-60">
                                <span x-show="billingCycle === 'monthly'">{{ __('landing.pricing.per_month') }}</span>
                                <span x-show="billingCycle === 'term'">{{ __('landing.pricing.per_term') }}</span>
                                <span x-show="billingCycle === 'yearly'">{{ __('landing.pricing.per_year') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Features -->
                    <ul class="space-y-4 mb-10 flex-grow">
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
                        @foreach(array_slice($pFeatures, 0, 8) as $feature)
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-[#f0fdf4] flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-check text-[10px] text-[#22c55e]"></i>
                            </div>
                            <span class="text-[14px] font-medium text-slate-600 leading-tight">{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>

                    <div class="mt-auto">
                        <a :href="'{{ route('register') }}?plan={{ $package->slug }}&cycle=' + billingCycle"
                           class="w-full flex items-center justify-center py-5 rounded-2xl font-black text-sm tracking-wide transition-all duration-300
                           {{ $isFeatured 
                               ? 'bg-[#22c55e] text-white shadow-lg shadow-green-500/30 hover:shadow-xl hover:scale-[1.02]' 
                               : 'bg-slate-100 text-slate-800 hover:bg-slate-200' }}">
                            {{ __('landing.pricing.cta_paid') }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <p class="text-center text-sm font-medium text-slate-400 mt-12 mb-16">
            {{ __('landing.pricing.bottom_note') }}
        </p>
    </div>
</section>
