{{-- Pricing — Dynamic from DB — Redesigned Reference --}}
<section class="section pricing" id="pricing"
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
    <div class="section-head center">
      <span class="kicker">{{ __('landing.pricing.badge') }}</span>
      <h2>اختر الخطة التي <span>تناسب حجم مركزك</span></h2>
      <p>ابدأ بتجربة مجانية لمدة 30 يومًا، ثم اختر ما يناسب عدد طلابك واحتياجاتك دون التزامات.</p>
    </div>

    @php
      $featuredLabel = __('landing.pricing.featured') !== 'landing.pricing.featured' ? __('landing.pricing.featured') : 'الأكثر شيوعًا';
      $perMonthLabel = __('landing.pricing.per_month') !== 'landing.pricing.per_month' ? __('landing.pricing.per_month') : '/ شهرياً';
      $perTermLabel  = __('landing.pricing.per_term') !== 'landing.pricing.per_term' ? __('landing.pricing.per_term') : '/ للترم';
      $perYearLabel  = __('landing.pricing.per_year') !== 'landing.pricing.per_year' ? __('landing.pricing.per_year') : '/ سنوياً';
      $ctaFreeLabel  = __('landing.pricing.cta_free') !== 'landing.pricing.cta_free' ? __('landing.pricing.cta_free') : 'ابدأ التجربة المجانية';
      $noteLabel     = __('landing.pricing.bottom_note') !== 'landing.pricing.bottom_note' ? __('landing.pricing.bottom_note') : 'الأسعار بعملتك المحلية عند توفرها. قد تُطبَّق الضرائب.';
    @endphp

    <div class="pricing-grid">
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
            @foreach(array_slice($pFeatures, 0, 5) as $feature)
              <li>{{ $feature }}</li>
            @endforeach
          </ul>

          <a class="btn {{ $isFeatured ? 'btn-primary' : 'btn-dark' }}"
             :href="'{{ route('register') }}?plan={{ $package->slug }}&cycle=' + billingCycle + '&currency=' + selectedCurrency"
             data-track="landing_pricing_cta_clicked">
            {{ $ctaFreeLabel }}
          </a>
        </article>
      @endforeach
    </div>

    <p class="pricing-note">{{ $noteLabel }} • <b>بدون بطاقة بنكية • إلغاء في أي وقت</b></p>
  </div>
</section>

