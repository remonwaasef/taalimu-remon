<section id="pricing" class="py-16 lg:py-24 bg-slate-100 border-y border-slate-200" 
         x-data="{ 
             billingCycle: 'monthly',
            userCountry: 'default',
            currencySymbol: '{{ \App\Models\SiteSetting::get('currency_symbol', '$') }}',
            exchangeRates: { 'USD': 1, 'EGP': 1, 'SAR': 1, 'AED': 1, 'EUR': 1 },
            async init() {
                try {
                    // Use geojs.io - CORS-friendly geo-location API
                    const response = await fetch('https://get.geojs.io/v1/ip/country.json');
                    const data = await response.json();
                    this.userCountry = data.country; // EG, SA, AE, FR, etc.
                    
                    // Set currency symbol based on country
                    if(this.userCountry === 'EG') this.currencySymbol = 'EGP';
                    else if(this.userCountry === 'SA') this.currencySymbol = 'SAR';
                    else if(this.userCountry === 'AE') this.currencySymbol = 'AED';
                    else if(['FR', 'DE', 'IT', 'ES', 'NL'].includes(this.userCountry)) this.currencySymbol = '€';
                    else this.currencySymbol = '$';

                } catch(e) {
                    console.log('Could not fetch location', e);
                    this.userCountry = 'default';
                }
            },
            getPrice(packagePrice, packageRegionalPrices) {
                // If no regional prices, fallback to base price
                if (!packageRegionalPrices || Object.keys(packageRegionalPrices).length === 0) {
                    return { amount: packagePrice, currency: '{{ \App\Models\SiteSetting::get('currency_code', 'USD') }}' }; 
                }

                let priceData = packageRegionalPrices[this.userCountry] || packageRegionalPrices['default'];
                
                // Final fallback if specific country and default are missing in JSON
                if(!priceData) {
                     return { amount: packagePrice, currency: '{{ \App\Models\SiteSetting::get('currency_code', 'USD') }}' };
                }
                
                return priceData;
            }
         }">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12 lg:mb-16">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/5 border border-primary/10 mb-6">
                <span class="text-sm font-medium text-primary">{{ __('landing.pricing.badge') }}</span>
            </div>
            <h2 class="text-2xl lg:text-3xl font-bold text-foreground mb-3">
                {!! __('landing.pricing.title') !!}
            </h2>
            <p class="text-muted-foreground text-base max-w-2xl mx-auto mb-6">
                {{ __('landing.pricing.subtitle') }}
            </p>

            <!-- Billing Toggle -->
            <div class="flex justify-center mb-12">
                <div class="inline-flex items-center bg-muted/60 p-1.5 rounded-full border border-border/50 backdrop-blur-sm">
                    <button 
                        @click="billingCycle = 'monthly'"
                        class="px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300 flex items-center"
                        :class="billingCycle === 'monthly' ? 'bg-white text-primary shadow-md scale-105 ring-1 ring-black/5' : 'text-muted-foreground hover:text-foreground hover:bg-white/50'"
                    >
                        {{ __('landing.pricing.monthly') ?? 'Monthly' }}
                    </button>

                    <button 
                        @click="billingCycle = 'term'"
                        class="px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300 flex items-center"
                        :class="billingCycle === 'term' ? 'bg-white text-primary shadow-md scale-105 ring-1 ring-black/5' : 'text-muted-foreground hover:text-foreground hover:bg-white/50'"
                    >
                        {{ __('landing.pricing.term') ?? 'Term' }}
                    </button>

                    <button 
                        @click="billingCycle = 'yearly'"
                        class="px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300 flex items-center gap-2"
                        :class="billingCycle === 'yearly' ? 'bg-white text-primary shadow-md scale-105 ring-1 ring-black/5' : 'text-muted-foreground hover:text-foreground hover:bg-white/50'"
                    >
                        {{ __('landing.pricing.yearly') ?? 'Yearly' }}
                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 text-[10px] font-black uppercase tracking-wider border border-emerald-500/20">
                            {{ __('landing.pricing.save_20') ?? 'SAVE 17%' }}
                        </span>
                    </button>
                </div>
            </div>


        <!-- Pricing Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch max-w-8xl mx-auto px-4">
            @foreach($packages as $index => $package)
                @php
                    $isFeatured = $package->is_featured;
                    $delay = $index * 0.1;
                    
                    // Use local landing translations first
                    $transPkgName = __('landing.pricing.plans.' . $package->slug . '.name');
                    if ($transPkgName === 'landing.pricing.plans.' . $package->slug . '.name') {
                        // Fallback to English from DB or direct name
                        $transPkgName = (app()->getLocale() === 'en' && $package->name_en) ? $package->name_en : $package->name;
                    }
                    
                    $regionalPrices = $package->regional_prices ?? []; 
                @endphp

                <div
                    class="relative rounded-2xl p-6 border-2 transition-all duration-300 hover:shadow-2xl animate-fade-in flex flex-col
                    {{ $isFeatured 
                        ? 'border-primary bg-primary text-primary-foreground scale-105 shadow-xl hover:-translate-y-3 z-10' 
                        : 'bg-card border-border hover:border-primary/30 hover:-translate-y-3 shadow-sm' }}"
                    style="animation-delay: {{ $delay }}s;"
                >
                    <!-- Trial Badge -->
                    @if($package->trial_days > 0)
                        <div class="absolute -top-3 -right-3 w-12 h-12 bg-white rounded-full flex flex-col items-center justify-center shadow-lg border-4 border-primary z-20">
                            <span class="text-sm font-black text-primary leading-none">{{ $package->trial_days }}</span>
                            <span class="text-[8px] font-bold text-primary/70 uppercase">{{ __('landing.pricing.days') }}</span>
                        </div>
                    @endif

                            <!-- Static fallback or JS updated -->
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1.5 bg-white rounded-full flex items-center gap-2 shadow-lg z-20"
                                 x-show="billingCycle === 'yearly' && (localPrice.discount_label || '{{ $package->discount_label }}')"
                            >
                                <span class="text-xs font-bold text-primary tracking-wide" x-text="localPrice.discount_label || '{{ $package->discount_label ?: $package->badge }}'"></span>
                            </div>

                        <!-- Header -->
                        <div class="text-center mb-6 pt-2">
                            <h3 class="text-xl font-bold mb-2 {{ $isFeatured ? 'text-primary-foreground' : 'text-foreground' }}">
                                {{ $transPkgName }}
                            </h3>
                        
                            <!-- Dynamic Pricing -->
                            <div class="flex flex-col items-center justify-center gap-1"
                                 x-data="{ 
                                    localPrice: getPrice({{ $package->price }}, {{ json_encode($regionalPrices) }}) 
                                 }"
                                 x-effect="localPrice = getPrice({{ $package->price }}, {{ json_encode($regionalPrices) }})">
                            
                                <!-- Old Price (Strikethrough) -->
                                <template x-if="billingCycle === 'monthly' ? localPrice.old_price : (billingCycle === 'term' ? localPrice.term_old_price : localPrice.yearly_old_price)">
                                    <div class="text-sm opacity-60 font-bold {{ $isFeatured ? 'text-white' : 'text-muted-foreground' }} line-through">
                                        <span x-text="localPrice.currency"></span>
                                        <span x-text="billingCycle === 'monthly' ? localPrice.old_price : (billingCycle === 'term' ? localPrice.term_old_price : localPrice.yearly_old_price)"></span>
                                    </div>
                                </template>

                                <!-- Price Display -->
                                <div class="flex items-baseline gap-1">
                                    <span class="text-4xl font-extrabold {{ $isFeatured ? 'text-primary-foreground' : 'text-primary' }}" 
                                          x-text="billingCycle === 'monthly' ? localPrice.amount : (billingCycle === 'term' ? (localPrice.term_price || localPrice.amount * 4) : (localPrice.yearly_price || localPrice.amount * 10))">
                                    </span>
                                    <span class="text-xl font-bold {{ $isFeatured ? 'text-primary-foreground' : 'text-foreground' }}" x-text="localPrice.currency"></span>
                                </div>

                                <div class="text-xs {{ $isFeatured ? 'text-primary-foreground/80' : 'text-muted-foreground' }}">
                                    <span x-show="billingCycle === 'monthly'">{{ __('landing.pricing.per_month') }}</span>
                                    <span x-show="billingCycle === 'term'">{{ __('landing.pricing.per_term') }}</span>
                                    <span x-show="billingCycle === 'yearly'">{{ __('landing.pricing.per_year') }}</span>
                                </div>
                            </div>
                        
                        <p class="text-xs mt-3 opacity-80 {{ $isFeatured ? 'text-primary-foreground' : 'text-muted-foreground' }}">
                            @php
                                $pkgDesc = __('landing.pricing.plans.' . $package->slug . '.description');
                                if ($pkgDesc === 'landing.pricing.plans.' . $package->slug . '.description') {
                                    $pkgDesc = (app()->getLocale() === 'en' && $package->description_en) ? $package->description_en : $package->description;
                                }
                            @endphp
                            {{ $pkgDesc }}
                        </p>
                    </div>

                    <!-- Features -->
                    <ul class="space-y-3 mb-8 flex-grow">
                        @php
                            $pFeatures = [];
                            foreach ($package->features as $feat) {
                                $val = $feat->pivot->value;
                                if ($feat->type === 'boolean' && ($val === 'false' || !$val)) continue;
                                if ($feat->type === 'limit' && $val === '0') continue;
                                
                                // Try translation key first
                                $transKey = 'features.' . $feat->code;
                                $label = __($transKey);
                                
                                // Fallback to DB name if translation missing (key returned)
                                if ($label === $transKey) {
                                    $label = app()->getLocale() === 'en' && $feat->name_en ? $feat->name_en : $feat->name;
                                }
                                
                                if ($val === '-1') {
                                    $pFeatures[] = $label . ': ' . __('features.unlimited');
                                } elseif ($feat->type === 'boolean') {
                                    $pFeatures[] = $label;
                                } else {
                                    // Localize specific values like "Email" if they are hardcoded in Arabic in DB
                                    $valDisplay = $val;
                                    $normalizedVal = trim($val);
                                    if ($normalizedVal === 'إيميل') {
                                        $valDisplay = __('features.email');
                                        if ($valDisplay === 'features.email') $valDisplay = 'Email';
                                    } elseif ($normalizedVal === 'أولوية') {
                                        $valDisplay = __('features.priority');
                                        if ($valDisplay === 'features.priority') $valDisplay = 'Priority';
                                    } elseif ($normalizedVal === 'مدير حساب') {
                                        $valDisplay = __('features.account_manager');
                                        if ($valDisplay === 'features.account_manager') $valDisplay = 'Account Manager';
                                    } elseif ($normalizedVal === 'unlimited') {
                                        $valDisplay = __('features.unlimited');
                                        if ($valDisplay === 'features.unlimited') $valDisplay = 'Unlimited';
                                    }
                                    
                                    $pFeatures[] = $label . ': ' . $valDisplay;
                                }
                            }
                        @endphp
                        @foreach($pFeatures as $feature)
                            <li class="flex items-start gap-3">
                                <div class="mt-1 w-4 h-4 rounded-full flex-shrink-0 flex items-center justify-center {{ $isFeatured ? 'bg-white/20' : 'bg-primary/10' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="{{ $isFeatured ? 'text-white' : 'text-primary' }}"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                                <span class="text-xs leading-tight {{ $isFeatured ? 'text-white font-medium' : 'text-foreground/90' }}">
                                    {{ $feature }}
                                </span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-auto">
                        <a :href="'{{ route('register') }}?plan={{ $package->slug }}&cycle=' + billingCycle"
                           class="inline-flex items-center justify-center rounded-full text-sm font-bold h-10 px-6 w-full group transition-all
                           {{ $isFeatured 
                               ? 'bg-secondary text-white hover:bg-secondary/90 shadow-lg' 
                               : 'bg-secondary text-white hover:bg-secondary/90 shadow-lg' }}">
                            {{ $package->price == 0 ? __('landing.pricing.cta_free') : __('landing.pricing.cta_paid') }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <p class="text-center text-xs text-muted-foreground mt-8 mb-16">
            {{ __('landing.pricing.bottom_note') }}
        </p>
    </div>
</section>
