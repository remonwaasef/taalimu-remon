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
      <span class="kicker">خطط بسيطة وواضحة</span>
      <h2>اختر الخطة التي <span>تناسب حجم مركزك</span></h2>
      <p>ابدأ بتجربة مجانية ثم اختر ما يناسب عدد طلابك واحتياجاتك.</p>
    </div>

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
              $unlimitedText = __('features.unlimited');
              $pFeatures[] = ($val === '-1') ? ($label . ': ' . $unlimitedText) : (($feat->type === 'boolean') ? $label : ($label . ': ' . $val));
          }
        @endphp

        <article class="price-card {{ $isFeatured ? 'popular' : '' }}"
                 x-data="{ lp: {} }"
                 x-effect="lp = getRegionalPrice({{ json_encode($package->regional_prices) }}, {{ $package->price }}, {{ $package->term_price }}, {{ $package->yearly_price }})">

          @if($isFeatured)
            <span class="popular-badge">{{ __('landing.pricing.featured') }}</span>
          @endif

          <span class="plan-name">{{ $packageName }}</span>
          <p>{{ $packageDesc }}</p>

          <div class="price">
            <strong x-text="billingCycle === 'monthly' ? lp.amount : (billingCycle === 'term' ? lp.term_price : lp.yearly_price)"></strong>
            <small x-text="lp.currency + ' / ' + (billingCycle === 'monthly' ? '{{ __('landing.pricing.per_month') }}' : (billingCycle === 'term' ? '{{ __('landing.pricing.per_term') }}' : '{{ __('landing.pricing.per_year') }}'))"></small>
          </div>

          <ul>
            @foreach(array_slice($pFeatures, 0, 5) as $feature)
              <li>{{ $feature }}</li>
            @endforeach
          </ul>

          <a class="btn {{ $isFeatured ? 'btn-primary' : 'btn-dark' }}"
             :href="'{{ route('register') }}?plan={{ $package->slug }}&cycle=' + billingCycle + '&currency=' + selectedCurrency"
             data-track="landing_pricing_cta_clicked">
            {{ __('landing.pricing.cta_free') }}
          </a>
        </article>
      @endforeach
    </div>

    <p class="pricing-note">{{ __('landing.pricing.bottom_note') }}</p>
  </div>
</section>
