<section id="pricing" class="v2-section" aria-labelledby="v2-pricing-title">
    <div class="v2-container">
        <div class="text-center max-w-2xl mx-auto mb-10 lg:mb-12" data-reveal>
            <span class="v2-eyebrow mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[color:var(--color-primary-500)]"></span>
                {{ __('landing-v2.pricing.badge') }}
            </span>
            <h2 id="v2-pricing-title" class="v2-h2 mb-3">{{ __('landing-v2.pricing.title') }}</h2>
            <p class="v2-lead mb-7">{{ __('landing-v2.pricing.subtitle') }}</p>

            <div class="inline-flex p-1 rounded-xl bg-white dark:bg-[#111f1e] border border-[color:var(--color-border)] dark:border-[#1f3936] shadow-sm" role="group" aria-label="{{ __('landing-v2.pricing.badge') }}">
                @foreach(['monthly' => __('landing.pricing.monthly'), 'term' => __('landing.pricing.term'), 'yearly' => __('landing.pricing.yearly')] as $cycle => $label)
                    <button type="button"
                            class="px-4 py-2 rounded-lg text-xs font-extrabold border-0 cursor-pointer transition-all"
                            :class="cycle === '{{ $cycle }}' ? 'bg-[color:var(--color-primary-500)] text-white shadow-sm' : 'bg-transparent text-[color:var(--color-text-secondary)] hover:text-[color:var(--color-text-main)]'"
                            @click="cycle = '{{ $cycle }}'">{{ $label }}</button>
                @endforeach
            </div>
        </div>

        {{-- Dynamic plans — same DB engine as the app --}}
        <div class="grid md:grid-cols-3 gap-5 max-w-4xl mx-auto items-stretch" x-data="v2Pricing" data-reveal style="--reveal-delay: 100ms;">
            @foreach($packages as $package)
                @php
                    $pkgName = match(app()->getLocale()) {
                        'ar' => $package->name,
                        'fr' => $package->name_fr ?: ($package->name_en ?: $package->name),
                        default => $package->name_en ?: $package->name,
                    };
                    $pkgDesc = match(app()->getLocale()) {
                        'ar' => $package->description,
                        'fr' => $package->description_fr ?: ($package->description_en ?: $package->description),
                        default => $package->description_en ?: $package->description,
                    };
                @endphp
                <article class="relative flex flex-col p-6 pt-7 bg-white dark:bg-[#111f1e] {{ $package->is_featured
                    ? 'rounded-[var(--radius-2xl)] border-2 border-[color:var(--color-primary-500)] shadow-[0_20px_40px_-12px_rgba(46,139,131,0.25)] lg:-translate-y-1'
                    : 'rounded-[var(--radius-2xl)] border border-[color:var(--color-border)] dark:border-[#1f3936] shadow-[var(--shadow-elevation-1)]' }}">
                    @if($package->is_featured)
                        <span class="absolute -top-3 left-1/2 -translate-x-1/2 rtl:translate-x-1/2 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[color:var(--color-primary-500)] text-white text-[11px] font-extrabold whitespace-nowrap shadow-md">
                            <i class="fas fa-star text-[8px] text-amber-300"></i>{{ __('landing.pricing.featured') }}
                        </span>
                    @endif

                    <h3 class="text-lg font-extrabold text-[color:var(--color-text-main)] mb-1">{{ $pkgName ?: __('landing.pricing.plans.'.$package->slug.'.name') }}</h3>
                    <p class="text-xs font-medium text-[color:var(--color-text-secondary)] leading-relaxed mb-4 min-h-8">{{ $pkgDesc ?: __('landing.pricing.plans.'.$package->slug.'.description') }}</p>

                    <span class="self-start v2-chip v2-chip-primary mb-4">
                        <i class="fas fa-gift text-[9px]"></i>{{ __('landing.pricing.trial_days', ['days' => $package->trial_days ?: 30]) }}
                    </span>

                    <div class="flex items-baseline gap-1.5" x-data="v2Price(@js([
                            'monthly' => (float) $package->price,
                            'term' => (float) $package->term_price,
                            'yearly' => (float) $package->yearly_price,
                            'regional' => $package->regional_prices ?? new \stdClass(),
                        ]))">
                        <span class="text-[11px] font-extrabold text-[color:var(--color-primary-600)] uppercase" x-text="currency"></span>
                        <span class="text-[2rem] leading-none font-extrabold tracking-tight text-[color:var(--color-text-main)]" x-text="display(cycle)"></span>
                    </div>
                    <span class="block mt-1.5 text-[10px] font-bold uppercase tracking-wide text-[color:var(--color-text-muted)]"
                          x-text="{ monthly: @js(__('landing.pricing.per_month')), term: @js(__('landing.pricing.per_term')), yearly: @js(__('landing.pricing.per_year')) }[cycle]"></span>

                    <ul class="mt-5 pt-4 border-t border-[color:var(--color-border-subtle)] dark:border-[#182e2c] space-y-2.5 flex-1">
                        @foreach(($package->display_features ?? []) ? array_slice($package->display_features, 0, 5) : [] as $feature)
                            <li class="flex items-start gap-2 text-xs font-semibold text-[color:var(--color-text-secondary)]">
                                <i class="fas fa-check text-[color:var(--color-success-500)] mt-0.5"></i>
                                <span dir="auto">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <a :href="'{{ route('register') }}?plan={{ $package->slug }}&cycle=' + cycle + '&currency=' + currency"
                       data-track="v2_pricing_cta_clicked"
                       class="v2-btn w-full mt-6 {{ $package->is_featured ? 'v2-btn-primary shadow-md' : 'v2-btn-ghost dark:bg-[#142524] dark:border-[#1f3936]' }}">
                        {{ __('landing.pricing.cta_free') }}
                    </a>
                </article>
            @endforeach
        </div>

        {{-- Compact objection-handling FAQ --}}
        <div id="faq" class="max-w-2xl mx-auto mt-16 lg:mt-20 scroll-mt-24">
            <h3 class="text-center text-lg font-extrabold text-[color:var(--color-text-main)] mb-6" data-reveal>{{ __('landing-v2.pricing.faq_title') }}</h3>
            <div class="space-y-3" x-data="{ open: null }" data-reveal style="--reveal-delay: 100ms;">
                @foreach(__('landing-v2.pricing.faq') as $i => $item)
                    <div class="v2-faq-item overflow-hidden dark:bg-[#111f1e] dark:border-[#1f3936]">
                        <button type="button" class="v2-faq-btn"
                                @click="open = (open === {{ $i }} ? null : {{ $i }})"
                                :aria-expanded="open === {{ $i }} ? 'true' : 'false'"
                                aria-controls="v2-faq-a-{{ $i }}" id="v2-faq-q-{{ $i }}">
                            <span>{{ $item['q'] }}</span>
                            <i class="fas fa-chevron-down text-[10px] text-[color:var(--color-text-muted)] transition-transform" :class="open === {{ $i }} && 'rotate-180 text-[color:var(--color-primary-500)]'"></i>
                        </button>
                        <div x-show="open === {{ $i }}" x-cloak x-collapse id="v2-faq-a-{{ $i }}" role="region" aria-labelledby="v2-faq-q-{{ $i }}" class="v2-faq-panel">
                            <p>{{ $item['a'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        const REGION_KEY = { EGP: 'EG', EUR: 'FR', USD: 'default', SAR: 'SA', AED: 'AE' };

        Alpine.data('v2Pricing', () => ({
            cycle: 'monthly',
            currency: '{{ session('suggested_currency', 'EGP') }}',
            init() {
                if ('{{ app()->getLocale() }}' !== 'ar' || this.currency !== 'EGP') return;
                fetch('https://get.geojs.io/v1/ip/country.json')
                    .then((r) => r.json())
                    .then((d) => {
                        const c = d.country;
                        if (c === 'EG') this.currency = 'EGP';
                        else if (['FR', 'DE', 'IT', 'ES', 'NL', 'BE', 'AT', 'PT', 'IE'].includes(c)) this.currency = 'EUR';
                        else if (this.currency === 'EGP') this.currency = 'USD';
                    })
                    .catch(() => {});
            },
        }));

        Alpine.data('v2Price', (plan) => ({
            currency: '{{ session('suggested_currency', 'EGP') }}',
            plan,
            resolve(baseKey) {
                const key = REGION_KEY[this.currency] || 'default';
                const regional = this.plan.regional && this.plan.regional[key];
                return regional && typeof regional.amount !== 'undefined' ? regional.amount : this.plan[baseKey];
            },
            syncCurrency() { this._t = setInterval(() => {}, 1e9); },
            display(cycle) {
                // Read the shared currency chosen by v2Pricing via CSS-free event bus
                const root = document.querySelector('[x-data^="v2Pricing"], [x-data*="v2Pricing"]');
                if (root && root._x_dataStack) {
                    const shared = root._x_dataStack[0];
                    if (shared && shared.currency) this.currency = shared.currency;
                }
                return this.resolve(cycle);
            },
        }));
    });
</script>
@endpush
