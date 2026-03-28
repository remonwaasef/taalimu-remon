@extends('center::layouts.hope-master')

@section('title', __('center::subscription.page_title'))

@push('styles')
<style>
    .subscription-hero {
        background: linear-gradient(135deg, #3A0CA3 0%, #2A4DFF 100%);
        border-radius: 1.25rem;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .subscription-hero::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 220px; height: 220px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }
    .subscription-hero::after {
        content: '';
        position: absolute;
        bottom: -40px; left: -40px;
        width: 160px; height: 160px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
    }
    .progress-bar-custom {
        height: 8px;
        border-radius: 999px;
        background: rgba(255,255,255,0.2);
        overflow: hidden;
    }
    .progress-bar-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, #7ecbff, #fff);
        transition: width 1s ease;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
    }
    .status-active   { background: rgba(34,197,94,0.15); color: #16a34a; }
    .status-trial    { background: rgba(251,191,36,0.15); color: #d97706; }
    .status-expired  { background: rgba(239,68,68,0.15);  color: #dc2626; }
    .plan-card {
        border: 2px solid #e5e7eb;
        border-radius: 1rem;
        transition: all 0.3s ease;
        background: #fff;
        cursor: pointer;
    }
    .plan-card:hover          { border-color: #3A0CA3; transform: translateY(-4px); box-shadow: 0 12px 30px rgba(58,12,163,0.12); }
    .plan-card.current-plan   { border-color: #3A0CA3; background: linear-gradient(135deg, #f5f3ff, #eff6ff); }
    .plan-card.featured-plan  { border-color: #2A4DFF; }
    .feature-check { color: #16a34a; }
    .feature-x     { color: #d1d5db; }
    .info-tile {
        background: rgba(255,255,255,0.12);
        border-radius: 0.75rem;
        padding: 1rem 1.25rem;
        backdrop-filter: blur(4px);
    }
    .pulse-dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        background: #4ade80;
        animation: pulse-green 2s infinite;
    }
    @keyframes pulse-green {
        0%   { box-shadow: 0 0 0 0 rgba(74,222,128,0.6); }
        70%  { box-shadow: 0 0 0 8px rgba(74,222,128,0); }
        100% { box-shadow: 0 0 0 0 rgba(74,222,128,0); }
    }
    .contact-card {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        border-radius: 1rem;
        color: #fff;
    }
    /* Redesigned Toggle Styles - Upgraded to 3 choices */
    .billing-toggle {
        display: inline-flex;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 999px;
        padding: 5px;
        position: relative;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        direction: ltr !important; 
    }
    .billing-toggle label {
        cursor: pointer;
        padding: 10px 18px;
        font-weight: 800;
        border-radius: 999px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1;
        font-size: 0.85rem;
        color: #64748b;
        position: relative;
        min-width: 100px;
        text-align: center;
    }
    .billing-toggle input[type="radio"]:checked + label {
        color: #fff !important;
    }
    .billing-toggle input[type="radio"] {
        display: none;
    }
    .toggle-slider {
        position: absolute;
        top: 5px;
        bottom: 5px;
        left: 5px; 
        width: calc(33.33% - 5px);
        background: #3A0CA3;
        border-radius: 999px;
        transition: transform 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
        z-index: 0;
        box-shadow: 0 4px 12px rgba(58, 12, 163, 0.3);
    }
    .billing-toggle input[type="radio"]:nth-of-type(2):checked ~ .toggle-slider {
        transform: translateX(100%);
    }
    .billing-toggle input[type="radio"]:nth-of-type(3):checked ~ .toggle-slider {
        transform: translateX(200%);
    }
    .save-badge {
        position: absolute;
        top: -12px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.7rem;
        padding: 3px 8px;
        white-space: nowrap;
    }
</style>
@endpush

@section('page-title', __('center::subscription.page_title'))

@section('content')
<div class="container-fluid px-0">

    {{-- ─── Flash Messages ──────────────────────────────────── --}}
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ─── Hero: Current Subscription Card ───────────────────── --}}
    <div class="subscription-hero p-4 p-md-5 mb-4 shadow-lg">
        <div class="row align-items-center g-4 position-relative" style="z-index:1;">
            <div class="col-md-7">
                {{-- Status badge --}}
                @php
                    $subStatus = $subscription?->stripe_status ?? 'none';
                    $isActive  = in_array($subStatus, ['active', 'trialing']);
                @endphp
                <div class="mb-3">
                    @if($subStatus === 'trialing')
                        <span class="status-badge" style="background:rgba(251,191,36,0.25);color:#fde68a;">
                            <i class="fas fa-hourglass-half fa-xs"></i> {{ __('center::subscription.trial_badge') }}
                        </span>
                    @elseif($isActive)
                        <span class="badge bg-success bg-opacity-25 text-white px-3 py-2 rounded-pill d-inline-flex align-items-center gap-2">
                            <span class="pulse-dot"></span> {{ __('center::subscription.active_badge') }}
                        </span>
                    @else
                        <span class="badge bg-danger bg-opacity-25 text-white px-3 py-2 rounded-pill">
                            <i class="fas fa-times-circle fa-xs me-1"></i> {{ __('center::subscription.expired') }}
                        </span>
                    @endif
                </div>

                <h2 class="fw-black mb-1" style="font-size:2rem;">
                    @php
                        $transCurrName = $currentPackage ? __('center::subscription.plans.' . $currentPackage->slug) : null;
                        if ($transCurrName === 'center::subscription.plans.' . ($currentPackage->slug ?? '')) {
                            $transCurrName = (app()->getLocale() === 'en' && $currentPackage?->name_en) ? $currentPackage->name_en : $currentPackage?->name;
                        }
                    @endphp
                    {{ $transCurrName ?? __('center::subscription.no_subscription') }}
                </h2>
                <p class="opacity-80 mb-4">
                    {{ (app()->getLocale() === 'en' && $currentPackage?->description_en) ? $currentPackage->description_en : ($currentPackage?->description ?? __('center::subscription.no_package_activated')) }}
                </p>

                {{-- Days Progress --}}
                @if($daysRemaining !== null)
                <div class="mb-2 d-flex justify-content-between small opacity-80">
                    <span>{{ $progressPercent }}% {{ __('center::subscription.used_percentage') }}</span>
                    <span>{{ $daysRemaining }} {{ trans_choice('center::subscription.days_remaining', $daysRemaining) }}</span>
                </div>
                <div class="progress-bar-custom mb-4">
                    <div class="progress-bar-fill" style="width: {{ $progressPercent }}%"></div>
                </div>
                @endif

                <div class="d-flex flex-wrap gap-3 align-items-center">
                    @if($subscription?->ends_at)
                        <div class="info-tile text-center">
                            <div class="fw-black fs-4">{{ $subscription->ends_at->format('d/m/Y') }}</div>
                            <div class="small opacity-70">{{ __('center::subscription.expiry_date') }}</div>
                        </div>
                    @endif
                    <div class="info-tile text-center">
                        <div class="fw-black fs-4">{{ $subscription?->billing_cycle === 'yearly' ? __('center::subscription.yearly') : __('center::subscription.monthly') }}</div>
                        <div class="small opacity-70">{{ __('center::subscription.billing_cycle') }}</div>
                    </div>
                    <div class="info-tile text-center">
                        <div class="fw-black fs-4">
                            {{ number_format($subscription?->total_amount ?? 0, 0) }}
                            <small class="fs-6">{{ $currency }}</small>
                        </div>
                        <div class="small opacity-70">{{ __('center::subscription.total_amount') }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-5 text-center d-none d-md-block">
                <div style="width:180px;height:180px;margin:auto;position:relative;">
                    @php $remaining = 100 - $progressPercent; @endphp
                    <svg viewBox="0 0 36 36" class="w-100 h-100" style="transform: rotate(-90deg);">
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="3"/>
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#7ecbff" stroke-width="3"
                                stroke-dasharray="{{ $remaining }} {{ 100 - $remaining }}"
                                stroke-linecap="round"/>
                    </svg>
                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                        <div class="fw-black" style="font-size:2rem;">{{ $daysRemaining ?? '—' }}</div>
                        <div class="small opacity-70">{{ trans_choice('center::subscription.days_remaining', $daysRemaining ?? 0) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Section: Available Plans ─────────────────────────── --}}
    <div class="mb-4 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-layer-group me-2 text-primary"></i>{{ __('center::subscription.available_plans') }}
            </h5>
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 ms-3">
                {{ $packages->count() }} {{ trans_choice('center::subscription.plans_count', $packages->count()) }}
            </span>
        </div>
        
        <div class="billing-toggle">
            <input type="radio" id="billing-monthly" name="billing_cycle" value="monthly" {{ $subscription?->billing_cycle === 'monthly' ? 'checked' : '' }}>
            <label for="billing-monthly">{{ __('center::subscription.billing_monthly') }}</label>

            <input type="radio" id="billing-term" name="billing_cycle" value="term" {{ ($subscription?->billing_cycle === 'term' || !$subscription) ? 'checked' : '' }}>
            <label for="billing-term">{{ __('center::subscription.billing_term') }}</label>
            
            <input type="radio" id="billing-yearly" name="billing_cycle" value="yearly" {{ $subscription?->billing_cycle === 'yearly' ? 'checked' : '' }}>
            <label for="billing-yearly">
                {{ __('center::subscription.billing_year') }} 
                <span class="badge bg-success save-badge">{{ __('center::subscription.save_badge') }}</span>
            </label>
            
            <div class="toggle-slider" style="direction: ltr;"></div>
        </div>

        <div class="billing-toggle ms-md-3">
            <input type="radio" id="gateway-paymob" name="payment_gateway" value="paymob" checked>
            <label for="gateway-paymob"><i class="bi bi-credit-card me-1"></i> {{ __('center::subscription.card_payment') }} (Paymob)</label>
            
            <input type="radio" id="gateway-paypal" name="payment_gateway" value="paypal">
            <label for="gateway-paypal"><i class="bi bi-paypal me-1"></i> PayPal</label>
            
            <div class="toggle-slider" style="direction: ltr;"></div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        @foreach($packages as $package)
            @php
                $isCurrent  = $currentPackage?->id === $package->id;
                $isFeatured = $package->is_featured;

                $pFeatures = [];
                foreach ($package->features as $feat) {
                    $val = $feat->pivot->value;
                    if ($feat->type === 'boolean' && ($val === 'false' || !$val)) continue;
                    if ($feat->type === 'limit' && $val === '0') continue;

                    $transKey = 'features.' . $feat->code;
                    $label    = __($transKey);
                    if ($label === $transKey) {
                        $label = app()->getLocale() === 'en' && $feat->name_en ? $feat->name_en : $feat->name;
                    }

                    if ($val === '-1')              $pFeatures[] = $label . ': ' . __('center::subscription.unlimited');
                    elseif ($feat->type === 'boolean') $pFeatures[] = $label;
                    else                              $pFeatures[] = $label . ': ' . $val;
                }
            @endphp

            <div class="col-md-6 col-lg-{{ $packages->count() <= 3 ? '4' : '3' }}">
                <div class="plan-card h-100 p-4 d-flex flex-column {{ $isCurrent ? 'current-plan' : '' }} {{ $isFeatured && !$isCurrent ? 'featured-plan' : '' }}">

                    {{-- Plan Header --}}
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            @if($isCurrent)
                                <span class="badge bg-primary rounded-pill mb-2 px-3 py-1">
                                    <i class="fas fa-check-circle me-1"></i> {{ __('center::subscription.current_plan') }}
                                </span>
                            @elseif($isFeatured)
                                <span class="badge" style="background: linear-gradient(90deg,#3A0CA3,#2A4DFF); color:#fff; border-radius:999px;" class="rounded-pill mb-2 px-3 py-1">
                                    ⚡ {{ __('center::subscription.recommended') }}
                                </span>
                            @endif
                            <h5 class="fw-black mb-0 mt-1">
                                @php
                                    $transPkgName = __('center::subscription.plans.' . $package->slug);
                                    if ($transPkgName === 'center::subscription.plans.' . $package->slug) {
                                        $transPkgName = app()->getLocale() === 'en' && $package->name_en ? $package->name_en : $package->name;
                                    }
                                @endphp
                                {{ $transPkgName }}
                            </h5>
                        </div>
                        <div class="text-end">
                            @if($package->old_price && $package->old_price > $package->price)
                                <div class="text-muted small plan-old-price" style="text-decoration: line-through; opacity: 0.6;" 
                                     data-monthly="{{ $package->old_price }}" 
                                     data-term="{{ $package->old_price * 5 }}"
                                     data-yearly="{{ $package->old_price * 12 }}">
                                    {{ number_format($package->old_price, 0) }} <span class="plan-currency">{{ $currency }}</span>
                                </div>
                            @endif
                            <div class="fw-black text-primary plan-price-display" style="font-size:1.6rem; line-height:1;" 
                                 data-monthly="{{ $package->price }}" 
                                 data-term="{{ $package->term_price ?: ($package->price * 5) }}"
                                 data-yearly="{{ $package->yearly_price ?: ($package->price * 12) }}">
                                {{ number_format($package->term_price ?: ($package->price * 5), 0) }}
                            </div>
                            <small class="text-muted"><span class="plan-currency">{{ $currency }}</span> / <span class="plan-cycle-text">{{ __('center::subscription.billing_term_cycle') }}</span></small>
                            @if($package->old_price && $package->old_price > $package->price)
                                @php
                                    $discountPercent = round((($package->old_price - $package->price) / $package->old_price) * 100);
                                @endphp
                                <div class="mt-1">
                                    <span class="badge bg-success bg-opacity-15 text-success rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                        {{ __('center::subscription.save_badge') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <p class="text-muted small mb-3">
                        {{ app()->getLocale() === 'en' && $package->description_en ? $package->description_en : $package->description }}
                    </p>

                    {{-- Features List --}}
                    <ul class="list-unstyled mb-4 flex-grow-1">
                        @foreach($pFeatures as $f)
                            <li class="d-flex align-items-start gap-2 mb-2 small">
                                <i class="fas fa-check-circle feature-check mt-1 flex-shrink-0"></i>
                                <span>{{ $f }}</span>
                            </li>
                        @endforeach
                    </ul>

                    {{-- CTA Button --}}
                    <div class="mt-auto">
                        @if($isCurrent)
                            <button class="btn btn-light w-100 rounded-pill fw-bold" disabled>
                                <i class="fas fa-check me-1"></i> {{ __('center::subscription.current_plan') }}
                            </button>
                        @elseif($package->stripe_price_id)
                            <a href="{{ route('center.subscription.checkout', ['tenant' => $tenant->domain, 'package' => $package->id]) }}?cycle=monthly"
                               class="btn btn-primary w-100 rounded-pill fw-bold plan-checkout-btn {{ $isFeatured ? '' : 'btn-outline-primary' }}"
                               data-base-url="{{ route('center.subscription.checkout', ['tenant' => $tenant->domain, 'package' => $package->id]) }}"
                               data-name="{{ addslashes($package->name) }}"
                               style="{{ $isFeatured ? '' : 'background:transparent; color:#3A0CA3; border-color:#3A0CA3;' }}"
                               onclick="return confirm('{{ __('center::subscription.confirm_upgrade', ['name' => addslashes($package->name)]) }}')">
                                @if($currentPackage && $package->price > $currentPackage->price)
                                    <i class="fas fa-arrow-up me-1"></i> {{ __('center::subscription.upgrade_now') }}
                                @elseif($currentPackage && $package->price < $currentPackage->price)
                                    <i class="fas fa-arrow-down me-1"></i> {{ __('center::subscription.downgrade') }}
                                @else
                                    <i class="fas fa-exchange-alt me-1"></i> {{ __('center::subscription.subscribe') }}
                                @endif
                            </a>
                        @else
                            <button class="btn btn-light w-100 rounded-pill fw-bold" disabled>
                                {{ __('center::subscription.contact_support') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ─── Section: Contact & Support ────────────────────────── --}}
    <div class="contact-card p-4 p-md-5">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <h4 class="fw-black mb-2">
                    <i class="fas fa-headset me-2 opacity-75"></i>
                    {{ __('center::subscription.help_title') }}
                </h4>
                <p class="opacity-70 mb-0">
                    {{ __('center::subscription.help_desc') }}
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('center.tickets.create', ['tenant' => $tenant->domain]) }}"
                   class="btn btn-light rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fas fa-ticket-alt me-2"></i> {{ __('center::subscription.open_ticket') }}
                </a>
            </div>
        </div>
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('input[name="billing_cycle"]');
    const priceDisplays = document.querySelectorAll('.plan-price-display');
    const cycleTexts = document.querySelectorAll('.plan-cycle-text');
    const checkoutBtns = document.querySelectorAll('.plan-checkout-btn');

    const trans = {
        monthly: '{{ __('center::subscription.billing_month_cycle') }}',
        term: '{{ __('center::subscription.billing_term_cycle') }}',
        yearly: '{{ __('center::subscription.billing_year_cycle') }}'
    };

    function updatePricing(cycle) {
        priceDisplays.forEach(display => {
            const price = parseFloat(display.getAttribute('data-' + cycle));
            display.textContent = price.toLocaleString('en-US', { maximumFractionDigits: 0 });
        });

        // Update old prices (strikethrough)
        const oldPriceDisplays = document.querySelectorAll('.plan-old-price');
        oldPriceDisplays.forEach(display => {
            const oldPrice = parseFloat(display.getAttribute('data-' + cycle));
            if (oldPrice) {
                const currencySpan = display.querySelector('.plan-currency');
                const currencyText = currencySpan ? currencySpan.textContent : '';
                display.innerHTML = oldPrice.toLocaleString('en-US', { maximumFractionDigits: 0 }) + ' <span class="plan-currency">' + currencyText + '</span>';
            }
        });

        cycleTexts.forEach(text => {
            text.textContent = trans[cycle];
        });

        checkoutBtns.forEach(btn => {
            const baseUrl = btn.getAttribute('data-base-url');
            let url = new URL(btn.href);
            url.searchParams.set('cycle', cycle);
            btn.href = url.toString();
        });
    }

    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            updatePricing(this.value);
        });
    });
    
    // Update links on gateway change
    const gatewayRadios = document.querySelectorAll('input[name="payment_gateway"]');
    gatewayRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const gateway = this.value;
            const checkoutBtns = document.querySelectorAll('.plan-checkout-btn');
            
            checkoutBtns.forEach(btn => {
                let url = new URL(btn.href);
                url.searchParams.set('payment_gateway', gateway);
                btn.href = url.toString();
            });
        });
    });
});
</script>
@endpush
