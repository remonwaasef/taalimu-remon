{{-- Pricing — Dynamic from DB with Billing Cycle Switcher --}}
<section class="section pricing bg-slate-50 border-t border-slate-200" id="pricing"
         x-data="{
            billingCycle: 'monthly',
            selectedCurrency: '{{ session('suggested_currency', 'EGP') }}',
            async init() {
                try {
                    const response = await fetch('https://get.geojs.io/v1/ip/country.json');
                    const data = await response.json();
                    const country = data.country;
                    if (country === 'EG') this.selectedCurrency = 'EGP';
                    else if (['FR','DE','IT','ES','NL','BE','AT','PT','IE'].includes(country)) this.selectedCurrency = 'EUR';
                    else if (this.selectedCurrency === 'EGP') this.selectedCurrency = 'USD';
                } catch(e) {}
            },
            getRegionalPrice(rp, base, term, yearly) {
                if (!rp) return { amount: base, currency: this.selectedCurrency, term_price: term, yearly_price: yearly };
                const map = {'EGP':'EG','EUR':'FR','USD':'default','SAR':'SA','AED':'AE'};
                const key = map[this.selectedCurrency] || 'default';
                return rp[key] || rp['default'] || { amount: base, currency: this.selectedCurrency, term_price: term, yearly_price: yearly };
            }
         }">
  <div class="container">
    <div class="section-head center" data-animate="fade-in">
      <span class="kicker">{{ __('landing.pricing.badge') }}</span>
      <h2>{{ __('landing.pricing.title') !== 'landing.pricing.title' ? __('landing.pricing.title') : 'اختر الخطة التي' }} <span>{{ __('landing.pricing.title_highlight') !== 'landing.pricing.title_highlight' ? __('landing.pricing.title_highlight') : 'تناسب حجم مركزك' }}</span></h2>
      <p>{{ __('landing.pricing.subtitle') !== 'landing.pricing.subtitle' ? __('landing.pricing.subtitle') : 'ابدأ بتجربة مجانية لمدة 30 يومًا، ثم اختر ما يناسب عدد طلابك واحتياجاتك دون التزامات.' }}</p>
    </div>

    {{-- Billing Cycle Selector Toggle --}}
    <div class="billing-toggle flex justify-center mb-10" data-animate="fade-in">
      <div class="inline-flex p-1.5 rounded-2xl bg-slate-200/70 border border-slate-300/70 shadow-inner">
        <button type="button" 
                @click="billingCycle = 'monthly'"
                :class="billingCycle === 'monthly' ? 'bg-[#2E8B83] text-white shadow-md' : 'text-slate-700 hover:text-slate-900'"
                class="px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition-all duration-200">
          {{ __('landing.pricing.monthly') !== 'landing.pricing.monthly' ? __('landing.pricing.monthly') : 'اشتراك شهري' }}
        </button>
        <button type="button" 
                @click="billingCycle = 'term'"
                :class="billingCycle === 'term' ? 'bg-[#2E8B83] text-white shadow-md' : 'text-slate-700 hover:text-slate-900'"
                class="px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition-all duration-200 flex items-center gap-1.5">
          <span>{{ __('landing.pricing.term') !== 'landing.pricing.term' ? __('landing.pricing.term') : 'اشتراك ترم (4 أشهر)' }}</span>
          <span class="text-[10px] bg-amber-400 text-slate-900 font-extrabold px-1.5 py-0.5 rounded-md">توفير 15%</span>
        </button>
        <button type="button" 
                @click="billingCycle = 'yearly'"
                :class="billingCycle === 'yearly' ? 'bg-[#2E8B83] text-white shadow-md' : 'text-slate-700 hover:text-slate-900'"
                class="px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition-all duration-200 flex items-center gap-1.5">
          <span>{{ __('landing.pricing.yearly') !== 'landing.pricing.yearly' ? __('landing.pricing.yearly') : 'اشتراك سنوي' }}</span>
          <span class="text-[10px] bg-emerald-400 text-slate-900 font-extrabold px-1.5 py-0.5 rounded-md">شهرين مجاناً</span>
        </button>
      </div>
    </div>

    @php
      $featuredLabel = __('landing.pricing.featured') !== 'landing.pricing.featured' ? __('landing.pricing.featured') : 'الأكثر شيوعًا';
      $perMonthLabel = __('landing.pricing.per_month') !== 'landing.pricing.per_month' ? __('landing.pricing.per_month') : '/ شهرياً';
      $perTermLabel  = __('landing.pricing.per_term') !== 'landing.pricing.per_term' ? __('landing.pricing.per_term') : '/ للترم';
      $perYearLabel  = __('landing.pricing.per_year') !== 'landing.pricing.per_year' ? __('landing.pricing.per_year') : '/ سنوياً';
      $ctaFreeLabel  = __('landing.pricing.cta_free') !== 'landing.pricing.cta_free' ? __('landing.pricing.cta_free') : 'ابدأ التجربة المجانية';
      $noteLabel     = __('landing.pricing.bottom_note') !== 'landing.pricing.bottom_note' ? __('landing.pricing.bottom_note') : 'الأسعار بعملتك المحلية عند توفرها. قد تُطبَّق الضرائب.';
    @endphp

    <div class="pricing-grid" data-animate="fade-in">
      @foreach($packages as $index => $package)
        @php
          $isFeatured = $package->is_featured;
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
          $pFeatures = [];
          foreach ($package->features as $feat) {
              $val = $feat->pivot->value;
              if ($feat->type === 'boolean' && ($val === 'false' || !$val)) continue;
              if ($feat->type === 'limit' && $val === '0') continue;
              $transKey = 'features.' . $feat->code;
              $label = __($transKey) !== $transKey ? __($transKey) : (app()->getLocale() === 'en' && $feat->name_en ? $feat->name_en : $feat->name);
              $unlimitedText = __('features.unlimited') !== 'features.unlimited' ? __('features.unlimited') : 'غير محدود';
              $pFeatures[] = ($val === '-1') ? ($label . ': ' . $unlimitedText) : (($feat->type === 'boolean') ? $label : ($label . ': ' . $val));
          }
        @endphp

        <article class="price-card {{ $isFeatured ? 'popular' : '' }}"
                 x-data="{ lp: {} }"
                 x-effect="lp = getRegionalPrice({{ json_encode($package->regional_prices) }}, {{ $package->price }}, {{ $package->term_price }}, {{ $package->yearly_price }})">

          @if($isFeatured)
            <span class="popular-badge">{{ $featuredLabel }}</span>
          @endif

          <span class="plan-name">{{ $packageName }}</span>
          <p>{{ $packageDesc }}</p>

          <div class="price">
            <strong x-text="billingCycle === 'monthly' ? lp.amount : (billingCycle === 'term' ? lp.term_price : lp.yearly_price)"></strong>
            <small x-text="lp.currency + ' ' + (billingCycle === 'monthly' ? '{{ $perMonthLabel }}' : (billingCycle === 'term' ? '{{ $perTermLabel }}' : '{{ $perYearLabel }}'))"></small>
          </div>

          <ul>
            @foreach(array_slice($pFeatures, 0, 6) as $feature)
              <li>{{ $feature }}</li>
            @endforeach
          </ul>

          <a class="btn {{ $isFeatured ? 'btn-primary font-black shadow-lg shadow-teal-700/20' : 'btn-dark' }}"
             :href="'{{ route('register') }}?plan={{ $package->slug }}&cycle=' + billingCycle + '&currency=' + selectedCurrency"
             data-track="landing_pricing_cta_clicked">
            {{ $ctaFreeLabel }} ←
          </a>
        </article>
      @endforeach
    </div>

    <p class="pricing-note text-center mt-8 text-xs text-slate-500 font-bold">
      {{ $noteLabel }} • <span class="text-[#2E8B83]">بدون بطاقة بنكية • إلغاء في أي وقت</span>
    </p>
  </div>
</section>
