

<?php $__env->startSection('title', __('center::subscription.page_title')); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .subscription-hero {
        background: #fff;
        border-radius: 1.25rem;
        color: var(--bs-dark);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
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
    .plan-card:hover          { border-color: #10b981; transform: translateY(-4px); box-shadow: 0 12px 30px rgba(16,185,129,0.12); }
    .plan-card.current-plan   { border-color: #10b981; background: linear-gradient(135deg, #f0fdf4, #f8fafc); }
    .plan-card.featured-plan  { border-color: #10b981; }
    .feature-check { color: #10b981; }
    .feature-x     { color: #d1d5db; }
    .info-tile {
        background: rgba(0,0,0,0.03);
        border-radius: 0.75rem;
        padding: 1rem 1.25rem;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .pulse-dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        background: #10b981;
        animation: pulse-green 2s infinite;
    }
    @keyframes pulse-green {
        0%   { box-shadow: 0 0 0 0 rgba(16,185,129,0.6); }
        70%  { box-shadow: 0 0 0 8px rgba(16,185,129,0); }
        100% { box-shadow: 0 0 0 0 rgba(16,185,129,0); }
    }
    .contact-card {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
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
        top: 4px;
        bottom: 4px;
        left: 4px; 
        width: calc(33.33% - 5px);
        background: #10b981;
        border-radius: 999px;
        transition: transform 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
        z-index: 0;
        box-shadow: 0 4px 12px rgba(16,185,129, 0.3);
    }
    .dual-toggle .toggle-slider {
        width: calc(50% - 6px);
    }
    .billing-toggle input[type="radio"]:nth-of-type(2):checked ~ .toggle-slider {
        transform: translateX(100%);
    }
    .dual-toggle input[type="radio"]:nth-of-type(2):checked ~ .toggle-slider {
        transform: translateX(100%);
    }
    .billing-toggle input[type="radio"]:nth-of-type(3):checked ~ .toggle-slider {
        transform: translateX(200%);
    }
    .save-badge {
        position: absolute;
        top: -15px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.75rem;
        padding: 4px 8px;
        white-space: nowrap;
        background: #f59e0b !important;
        color: #fff !important;
        border-radius: 6px;
        box-shadow: 0 4px 10px rgba(245,158,11,0.3);
        z-index: 10;
        border: 1px solid rgba(255,255,255,0.3) !important;
        animation: pulse-orange 2.5s infinite;
        font-weight: 800;
    }
    @keyframes pulse-orange {
        0%   { box-shadow: 0 0 0 0 rgba(245,158,11,0.5); transform: translateX(-50%) scale(1); }
        70%  { box-shadow: 0 0 0 8px rgba(245,158,11,0); transform: translateX(-50%) scale(1.05); }
        100% { box-shadow: 0 0 0 0 rgba(245,158,11,0); transform: translateX(-50%) scale(1); }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('page-title', __('center::subscription.page_title')); ?>

<?php $__env->startSection('page-actions'); ?>
    <?php
        $subStatus = $subscription?->stripe_status ?? 'none';
        $isActive  = in_array($subStatus, ['active', 'trialing']);
    ?>
    <?php if($subStatus === 'trialing'): ?>
        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm">
            <i class="fas fa-hourglass-half me-1"></i> <?php echo e(__('center::subscription.trial_badge')); ?>

        </span>
    <?php elseif($isActive): ?>
        <span class="badge bg-white text-success px-3 py-2 rounded-pill shadow-sm d-inline-flex align-items-center gap-2">
            <span class="pulse-dot" style="background: #22c55e;"></span> <?php echo e(__('center::subscription.active_badge')); ?>

        </span>
    <?php else: ?>
        <span class="badge bg-white text-danger px-3 py-2 rounded-pill shadow-sm">
            <i class="fas fa-times-circle me-1"></i> <?php echo e(__('center::subscription.expired')); ?>

        </span>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-0">

    
    <?php if(session('info')): ?>
        <div class="alert alert-info alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-info-circle me-2"></i><?php echo e(session('info')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    
    <div class="subscription-hero p-4 p-md-5 mb-4 shadow-lg">
        <div class="row align-items-center g-4 position-relative" style="z-index:1;">
            <div class="col-md-7">
                <h2 class="fw-bold mb-1 text-dark" style="font-size:1.75rem;">
                    <?php
                        $transCurrName = $currentPackage ? __('center::subscription.plans.' . $currentPackage->slug) : null;
                        if ($transCurrName === 'center::subscription.plans.' . ($currentPackage->slug ?? '')) {
                            $transCurrName = (app()->getLocale() === 'en' && $currentPackage?->name_en) ? $currentPackage->name_en : $currentPackage?->name;
                        }
                    ?>
                    <?php
                        $currSlug = $currentPackage?->slug ?? 'free';
                        $transPkgName = __('center::subscription.plans.' . $currSlug . '.name');
                        if ($transPkgName === 'center::subscription.plans.' . $currSlug . '.name') {
                            $transPkgName = (app()->getLocale() === 'en' && $currentPackage?->name_en) ? $currentPackage->name_en : ($currentPackage?->name ?? __('center::subscription.no_subscription'));
                        }

                        $transPkgDesc = __('center::subscription.plans.' . $currSlug . '.desc');
                        if ($transPkgDesc === 'center::subscription.plans.' . $currSlug . '.desc') {
                            $transPkgDesc = (app()->getLocale() === 'en' && $currentPackage?->description_en) ? $currentPackage->description_en : ($currentPackage?->description ?? __('center::subscription.no_package_activated'));
                        }
                    ?>
                    <?php echo e($transPkgName); ?>

                </h2>
                <p class="text-muted mb-4">
                    <?php echo e($transPkgDesc); ?>

                </p>

                
                <?php if($daysRemaining !== null): ?>
                <div class="mb-2 d-flex justify-content-between small opacity-80">
                    <span><?php echo e($progressPercent); ?>% <?php echo e(__('center::subscription.used_percentage')); ?></span>
                    <span><?php echo e($daysRemaining); ?> <?php echo e(trans_choice('center::subscription.days_remaining', $daysRemaining)); ?></span>
                </div>
                <div class="progress-bar-custom mb-4">
                    <div class="progress-bar-fill" style="width: <?php echo e($progressPercent); ?>%"></div>
                </div>
                <?php endif; ?>

                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <?php if($subscription?->ends_at): ?>
                        <div class="info-tile text-center">
                            <div class="fw-black fs-4"><?php echo e($subscription->ends_at->format('d/m/Y')); ?></div>
                            <div class="small opacity-70"><?php echo e(__('center::subscription.expiry_date')); ?></div>
                        </div>
                    <?php endif; ?>
                    <div class="info-tile text-center">
                        <div class="fw-black fs-4"><?php echo e($subscription?->billing_cycle === 'yearly' ? __('center::subscription.yearly') : __('center::subscription.monthly')); ?></div>
                        <div class="small opacity-70"><?php echo e(__('center::subscription.billing_cycle')); ?></div>
                    </div>
                    <div class="info-tile text-center">
                        <div class="fw-black fs-4">
                            <?php echo e(number_format($subscription?->total_amount ?? 0, 0)); ?>

                            <small class="fs-6"><?php echo e(get_currency_symbol()); ?></small>
                        </div>
                        <div class="small opacity-70"><?php echo e(__('center::subscription.total_amount')); ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-5 text-center d-none d-md-block">
                <div style="width:180px;height:180px;margin:auto;position:relative;">
                    <?php $remaining = 100 - $progressPercent; ?>
                    <svg viewBox="0 0 36 36" class="w-100 h-100" style="transform: rotate(-90deg);">
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="rgba(0,0,0,0.05)" stroke-width="3"/>
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="var(--bs-primary)" stroke-width="3"
                                stroke-dasharray="<?php echo e($remaining); ?> <?php echo e(100 - $remaining); ?>"
                                stroke-linecap="round"/>
                    </svg>
                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                        <div class="fw-bold text-dark" style="font-size:2rem;"><?php echo e($daysRemaining ?? '—'); ?></div>
                        <div class="small text-muted"><?php echo e(trans_choice('center::subscription.days_remaining', $daysRemaining ?? 0)); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <?php
        $initialCycle = $subscription?->billing_cycle ?? 'term';
    ?>

    <div class="mb-4 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-layer-group me-2 text-primary"></i><?php echo e(__('center::subscription.available_plans')); ?>

            </h5>
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 ms-3">
                <?php echo e($packages->count()); ?> <?php echo e(trans_choice('center::subscription.plans_count', $packages->count())); ?>

            </span>
        </div>
        
        <div class="billing-toggle">
            <input type="radio" id="billing-monthly" name="billing_cycle" value="monthly" <?php echo e($subscription?->billing_cycle === 'monthly' ? 'checked' : ''); ?>>
            <label for="billing-monthly"><?php echo e(__('center::subscription.billing_monthly')); ?></label>

            <input type="radio" id="billing-term" name="billing_cycle" value="term" <?php echo e(($subscription?->billing_cycle === 'term' || !$subscription) ? 'checked' : ''); ?>>
            <label for="billing-term"><?php echo e(__('center::subscription.billing_term')); ?></label>
            
            <input type="radio" id="billing-yearly" name="billing_cycle" value="yearly" <?php echo e($subscription?->billing_cycle === 'yearly' ? 'checked' : ''); ?>>
            <label for="billing-yearly">
                <?php echo e(__('center::subscription.billing_year')); ?> 
                <span class="badge bg-success save-badge"><?php echo e(__('center::subscription.save_badge')); ?></span>
            </label>
            
            <div class="toggle-slider" style="direction: ltr;"></div>
        </div>

        <div class="billing-toggle dual-toggle ms-md-3">
            <input type="radio" id="gateway-paymob" name="payment_gateway" value="paymob" checked>
            <label for="gateway-paymob"><i class="bi bi-credit-card me-1"></i> <?php echo e(__('center::subscription.card_payment')); ?> (Paymob)</label>
            
            <input type="radio" id="gateway-paypal" name="payment_gateway" value="paypal">
            <label for="gateway-paypal"><i class="bi bi-paypal me-1"></i> PayPal</label>
            
            <div class="toggle-slider" style="direction: ltr;"></div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
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

                    if ($val === '-1' || $val === 'unlimited') {
                        $pFeatures[] = $label . ': ' . __('center::subscription.unlimited');
                    } elseif ($feat->type === 'boolean') {
                        $pFeatures[] = $label;
                    } else {
                        $transVal = __('center::subscription.values.' . $val);
                        if ($transVal === 'center::subscription.values.' . $val) {
                            $transVal = $val;
                        }
                        $pFeatures[] = $label . ': ' . $transVal;
                    }
                }
            ?>

            <div class="col-md-6 col-lg-<?php echo e($packages->count() <= 3 ? '4' : '3'); ?>">
                <div class="plan-card h-100 p-4 d-flex flex-column <?php echo e($isCurrent ? 'current-plan' : ''); ?> <?php echo e($isFeatured && !$isCurrent ? 'featured-plan' : ''); ?>">

                    
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <?php if($isCurrent): ?>
                                <span class="badge bg-primary rounded-pill mb-2 px-3 py-1">
                                    <i class="fas fa-check-circle me-1"></i> <?php echo e(__('center::subscription.current_plan')); ?>

                                </span>
                            <?php elseif($isFeatured): ?>
                                <span class="badge" style="background: linear-gradient(90deg,#059669,#10b981); color:#fff; border-radius:999px;" class="rounded-pill mb-2 px-3 py-1">
                                    ⚡ <?php echo e(__('center::subscription.recommended')); ?>

                                </span>
                            <?php endif; ?>
                            <h5 class="fw-black mb-0 mt-1">
                                <?php
                                    $pkgSlug = $package->slug ?? 'free';
                                    $transPkgName = __('center::subscription.plans.' . $pkgSlug . '.name');
                                    if ($transPkgName === 'center::subscription.plans.' . $pkgSlug . '.name') {
                                        $transPkgName = app()->getLocale() === 'en' && $package->name_en ? $package->name_en : $package->name;
                                    }
                                ?>
                                <?php echo e($transPkgName); ?>

                            </h5>
                        </div>
                        <div class="text-end">
                            <?php
                                $currentPrice = match($initialCycle) {
                                    'monthly' => $package->price,
                                    'yearly'  => $package->yearly_price ?: ($package->price * 12),
                                    default   => $package->term_price ?: ($package->price * 5),
                                };
                                $oldPriceValue = match($initialCycle) {
                                    'monthly' => $package->old_price,
                                    'yearly'  => $package->old_price ? ($package->old_price * 12) : null,
                                    default   => $package->old_price ? ($package->old_price * 5) : null,
                                };
                                $cycleText = match($initialCycle) {
                                    'monthly' => __('center::subscription.billing_month_cycle'),
                                    'yearly'  => __('center::subscription.billing_year_cycle'),
                                    default   => __('center::subscription.billing_term_cycle'),
                                };
                            ?>
                            <?php if($oldPriceValue && $oldPriceValue > $currentPrice): ?>
                                <div class="text-muted small plan-old-price" style="text-decoration: line-through; opacity: 0.6;" 
                                     data-monthly="<?php echo e($package->old_price); ?>" 
                                     data-term="<?php echo e($package->old_price * 5); ?>"
                                     data-yearly="<?php echo e($package->old_price * 12); ?>">
                                    <?php echo e(number_format((float)$oldPriceValue, 0)); ?> <span class="plan-currency"><?php echo e(get_currency_symbol()); ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="fw-black text-primary plan-price-display" style="font-size:1.6rem; line-height:1;" 
                                 data-monthly="<?php echo e($package->price); ?>" 
                                 data-term="<?php echo e($package->term_price ?: ($package->price * 5)); ?>"
                                 data-yearly="<?php echo e($package->yearly_price ?: ($package->price * 12)); ?>">
                                <?php echo e(number_format((float)$currentPrice, 0)); ?>

                            </div>
                            <small class="text-muted"><span class="plan-currency"><?php echo e(get_currency_symbol()); ?></span> / <span class="plan-cycle-text"><?php echo e($cycleText); ?></span></small>
                            <?php if($package->old_price && $package->old_price > $package->price): ?>
                                <div class="mt-1">
                                    <span class="badge bg-warning text-white rounded-pill px-2 py-1 shadow-sm" style="font-size: 0.7rem; background-color: #f59e0b !important;">
                                        <i class="fas fa-tag me-1"></i> <?php echo e(__('center::subscription.save_badge')); ?>

                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <p class="text-muted small mb-3">
                        <?php
                            $transPkgDesc = __('center::subscription.plans.' . $pkgSlug . '.desc');
                            if ($transPkgDesc === 'center::subscription.plans.' . $pkgSlug . '.desc') {
                                $transPkgDesc = app()->getLocale() === 'en' && $package->description_en ? $package->description_en : $package->description;
                            }
                        ?>
                        <?php echo e($transPkgDesc); ?>

                    </p>

                    
                    <ul class="list-unstyled mb-4 flex-grow-1">
                        <?php $__currentLoopData = $pFeatures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="d-flex align-items-start gap-2 mb-2 small">
                                <i class="fas fa-check-circle feature-check mt-1 flex-shrink-0"></i>
                                <span><?php echo e($f); ?></span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>

                    
                    <div class="mt-auto">
                        <?php if($isCurrent): ?>
                            <button class="btn btn-light w-100 rounded-pill fw-bold" disabled>
                                <i class="fas fa-check me-1"></i> <?php echo e(__('center::subscription.current_plan')); ?>

                            </button>
                        <?php elseif($package->stripe_price_id): ?>
                            <a href="<?php echo e(route('center.subscription.checkout', ['tenant' => $tenant->domain, 'package' => $package->id])); ?>?cycle=<?php echo e($initialCycle); ?>&payment_gateway=paymob"
                               class="btn w-100 rounded-pill fw-bold plan-checkout-btn <?php echo e($isFeatured ? 'btn-primary shadow-sm' : 'btn-outline-primary'); ?>"
                               data-base-url="<?php echo e(route('center.subscription.checkout', ['tenant' => $tenant->domain, 'package' => $package->id])); ?>"
                               data-name="<?php echo e(addslashes($package->name)); ?>"
                               onclick="return confirm('<?php echo e(__('center::subscription.confirm_upgrade', ['name' => addslashes($package->name)])); ?>')">
                                <?php if($currentPackage && $package->price > $currentPackage->price): ?>
                                    <i class="fas fa-arrow-up me-1"></i> <?php echo e(__('center::subscription.upgrade_now')); ?>

                                <?php elseif($currentPackage && $package->price < $currentPackage->price): ?>
                                    <i class="fas fa-arrow-down me-1"></i> <?php echo e(__('center::subscription.downgrade')); ?>

                                <?php else: ?>
                                    <i class="fas fa-exchange-alt me-1"></i> <?php echo e(__('center::subscription.subscribe')); ?>

                                <?php endif; ?>
                            </a>
                        <?php else: ?>
                            <button class="btn btn-light w-100 rounded-pill fw-bold" disabled>
                                <?php echo e(__('center::subscription.contact_support')); ?>

                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="contact-card p-4 p-md-5">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <h4 class="fw-black mb-2">
                    <i class="fas fa-headset me-2 opacity-75"></i>
                    <?php echo e(__('center::subscription.help_title')); ?>

                </h4>
                <p class="opacity-70 mb-0">
                    <?php echo e(__('center::subscription.help_desc')); ?>

                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="<?php echo e(route('center.tickets.create', ['tenant' => $tenant->domain])); ?>"
                   class="btn btn-light rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fas fa-ticket-alt me-2"></i> <?php echo e(__('center::subscription.open_ticket')); ?>

                </a>
            </div>
        </div>
    </div>

</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('input[name="billing_cycle"]');
    const priceDisplays = document.querySelectorAll('.plan-price-display');
    const cycleTexts = document.querySelectorAll('.plan-cycle-text');
    const checkoutBtns = document.querySelectorAll('.plan-checkout-btn');

    const trans = {
        monthly: '<?php echo e(__('center::subscription.billing_month_cycle')); ?>',
        term: '<?php echo e(__('center::subscription.billing_term_cycle')); ?>',
        yearly: '<?php echo e(__('center::subscription.billing_year_cycle')); ?>'
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

    // Initialize pricing and links on load
    const checkedRadio = document.querySelector('input[name="billing_cycle"]:checked');
    if (checkedRadio) {
        updatePricing(checkedRadio.value);
    }
    
    // Sync gateway if different from default
    const checkedGateway = document.querySelector('input[name="payment_gateway"]:checked');
    if (checkedGateway && checkedGateway.value !== 'paymob') {
        const gateway = checkedGateway.value;
        checkoutBtns.forEach(btn => {
            let url = new URL(btn.href);
            url.searchParams.set('payment_gateway', gateway);
            btn.href = url.toString();
        });
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('center::layouts.hope-master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\new project\antigravty\edu\edu\Modules\Center\resources\views\subscription\index.blade.php ENDPATH**/ ?>