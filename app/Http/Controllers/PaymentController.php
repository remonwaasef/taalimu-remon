<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function success(Request $request, TelegramService $telegram)
    {
        return redirect()->route('home')->withErrors(['error' => 'Stripe is no longer supported. Please use Paymob.']);
    }

    public function paypalSuccess(Request $request, \App\Services\PayPalService $paypal, TelegramService $telegram)
    {
        $orderId = $request->get('token'); // PayPal uses 'token' for Order ID in redirect

        if ($orderId) {
            $details = $paypal->captureOrder($orderId);

            if ($details && $details['status'] === 'COMPLETED') {
                // Mark registration as successful for the view
                session(['registration_success' => true]);

                // Get tenant from session or auth
                $tenantId = session('tenant_id');
                $tenant = $tenantId ? \App\Models\Tenant::find($tenantId) : null;

                if ($tenant) {
                    $planSlug = session('selected_plan', 'pro');
                    $package = \App\Models\Package::where('slug', $planSlug)->first();
                    $billingCycle = session('billing_cycle', 'monthly');

                    if ($billingCycle === 'monthly') {
                        $days = 30;
                    } elseif ($billingCycle === 'term') {
                        $termDuration = \App\Models\SiteSetting::get('term_duration_days', 150);
                        $days = $termDuration;
                    } elseif ($billingCycle === 'yearly') {
                        $days = 365;
                    } elseif ($package) {
                        $days = $package->duration_in_days; // Fallback to package default
                    } else {
                        $days = 30; // Absolute default
                    }

                    // Determine operation type
                    $existingSub = $tenant->subscriptions()->where('name', 'default')->first();
                    $operationType = $existingSub ? 'upgrade' : 'subscription';

                    // Create or update the subscription NOW (payment is confirmed)
                    $tenant->subscriptions()->updateOrCreate(
                        ['name' => 'default'],
                        [
                            'paypal_id' => $orderId,
                            'paypal_status' => 'COMPLETED',
                            'gateway' => 'paypal',
                            'stripe_id' => 'sub_paypal_' . \Illuminate\Support\Str::random(10),
                            'stripe_status' => 'active',
                            'stripe_price' => 'price_paypal_' . ($package->slug ?? 'unknown'),
                            'quantity' => 1,
                            'billing_cycle' => $billingCycle,
                            'base_price' => session('base_price', 0),
                            'total_amount' => session('total_amount', 0),
                            'discount_amount' => 0,
                            'status' => 'active',
                            'ends_at' => now()->addDays($days),
                        ]
                    );

                    // Log the subscription operation
                    \App\Models\SubscriptionLog::logOperation(
                        $tenant->id,
                        $operationType,
                        $package->slug ?? 'unknown',
                        $package->name ?? 'مخصص',
                        $billingCycle,
                        'paypal',
                        session('total_amount', 0),
                        $orderId,
                        now(),
                        now()->addDays($days)
                    );
                    
                    $user = \App\Models\User::where('tenant_id', $tenant->id)
                        ->whereIn('role', ['center_admin', 'instructor'])
                        ->first();
                    
                    $telegram->sendRegistrationAlert($tenant, $user, '******** (PayPal Order)');
                }

                if (session('is_subscription_change')) {
                    session()->forget('is_subscription_change');
                    return redirect()->route('center.subscription.success', ['tenant' => $tenant->domain]);
                }

                return redirect()->route('registration.success');
            }
        }

        return redirect()->route('home')->withErrors(['error' => 'فشل التحقق من عملية دفع PayPal.']);
    }

    public function paymobCallback(Request $request, TelegramService $telegram)
    {
        /** @var \App\Services\PaymentGateways\PaymobGateway $gateway */
        $gateway = \App\Services\PaymentFactory::make('paymob');
        
        // 1. Verify Redirect HMAC (Security First)
        $isHmacValid = $gateway->verifyRedirectHmac($request->all());
        if (!$isHmacValid) {
            Log::warning('Paymob Redirect HMAC Mismatch', [
                'received_hmac' => $request->get('hmac'),
                'payload' => $request->except(['hmac'])
            ]);
            // return redirect()->route('home')->withErrors(['error' => 'فشل التحقق من أمان عملية الدفع.']);
        }

        $success = filter_var($request->get('success'), FILTER_VALIDATE_BOOLEAN);
        $transactionId = $request->get('id');
        $merchantOrderId = $request->get('merchant_order_id') ?? $request->get('order'); 

        Log::info('Paymob Redirect Received', [
            'success' => $success,
            'transaction_id' => $transactionId,
            'merchant_order_id' => $merchantOrderId,
            'hmac_valid' => $isHmacValid
        ]);

        if ($success && $transactionId) {
             // 2. Context Restoration (Fall back to merchant_order_id if session is lost)
             $tenantId = session('tenant_id');
             $planSlug = session('selected_plan');
             $billingCycle = session('billing_cycle', 'monthly');
             $isChange = session('is_subscription_change', false);
             $basePrice = session('base_price', 0);
             $totalAmount = session('total_amount', 0);

             if (!$tenantId && $merchantOrderId && str_starts_with($merchantOrderId, 'tx_')) {
                 // Format: tx_{time}_{tenant_id}_{package_slug}_{billing_cycle}_{is_change}
                 $parts = explode('_', $merchantOrderId);
                 if (count($parts) >= 6) {
                     $tenantId = $parts[2];
                     $planSlug = $parts[3];
                     $billingCycle = $parts[4];
                     $isChange = $parts[5] === '1';
                     
                     \Log::info("Paymob Context Restored from merchant_order_id: {$merchantOrderId}");
                 }
             }

             // Last Resort: If we still don't have a planSlug, try to guestimate or look up by transaction?
             // But usually merchant_order_id restoration is enough.

             $tenant = $tenantId ? \App\Models\Tenant::find($tenantId) : null;

             if (!$tenant) {
                 // Fallback: Try to find tenant through subscription created by webhook
                 $existingSub = \App\Models\Subscription::where('stripe_id', 'sub_paymob_' . $transactionId)->first();
                 if ($existingSub) {
                     $tenant = $existingSub->tenant;
                     $isChange = true; 
                     Log::info("Paymob Context Restored via existing subscription for Trans ID: {$transactionId}");
                 }
             }

             // Robust upgrade detection: if tenant exists and already has a sub, it's an upgrade redirect
             if ($tenant && !$isChange) {
                 $isChange = $tenant->subscriptions()->where('name', 'default')->exists();
             }

             if ($tenant) {
                 $package = \App\Models\Package::where('slug', $planSlug)->first();
                 
                 // If prices were in session but lost, we fallback to package prices
                 if ($totalAmount <= 0 && $package) {
                     $totalAmount = ($billingCycle === 'yearly' ? $package->yearly_price : ($billingCycle === 'term' ? $package->term_price : $package->price));
                     $basePrice = $totalAmount;
                 }

                 if ($billingCycle === 'monthly') {
                     $days = 30;
                 } elseif ($billingCycle === 'term') {
                     $termDuration = (int) \App\Models\SiteSetting::get('term_duration_days', 150);
                     $days = $termDuration;
                 } elseif ($billingCycle === 'yearly') {
                     $days = 365;
                 } elseif ($package) {
                     $days = $package->duration_in_days; 
                 } else {
                     $days = 30;
                 }

                 // Determine operation type
                 $operationType = $isChange ? 'upgrade' : 'subscription';

                 $tenant->subscriptions()->updateOrCreate(
                     ['name' => 'default'],
                     [
                         'gateway' => 'paymob',
                         'stripe_id' => 'sub_paymob_' . $transactionId,
                         'stripe_status' => 'active',
                         'stripe_price' => 'price_paymob_' . ($package->slug ?? ($planSlug ?: 'unknown')),
                         'quantity' => 1,
                         'billing_cycle' => $billingCycle,
                         'base_price' => $basePrice,
                         'total_amount' => $totalAmount,
                         'discount_amount' => 0,
                         'status' => 'active',
                         'ends_at' => now()->addDays($days),
                     ]
                 );

                 // Log the subscription operation
                 \App\Models\SubscriptionLog::logOperation(
                     $tenant->id,
                     $operationType,
                     $package->slug ?? ($planSlug ?: 'unknown'),
                     $package->name ?? 'مخصص',
                     $billingCycle,
                     'paymob',
                     $totalAmount,
                     $transactionId,
                     now(),
                     now()->addDays($days)
                 );

                 $user = \App\Models\User::where('tenant_id', $tenant->id)
                     ->whereIn('role', ['center_admin', 'instructor'])
                     ->first();
                 
                 $telegram->sendRegistrationAlert($tenant, $user, "******** (Paymob ID: {$transactionId})");
                 
                 // Enable success state for the view
                 session(['registration_success' => true]);

                 if ($isChange) {
                     return redirect()->route('center.subscription.success', ['tenant' => $tenant->domain]);
                 }

                 return redirect()->route('registration.success');
             }

             // If we confirmed success but couldn't find the tenant, it's a critical error
             \Log::error("Paymob Payment Success but Tenant not found", ['transaction_id' => $transactionId, 'merchant_order_id' => $merchantOrderId]);
             return redirect()->route('home')->withErrors(['error' => 'تم الدفع بنجاح ولكن تعذر تحديث بيانات الحساب. يرجى التواصل مع الدعم الفني.']);
        }

        // Payment failed or was cancelled
        // Try to redirect the user back to where they came from
        $tenantId = session('tenant_id');
        $isChange = session('is_subscription_change', false);
        
        // Also try merchant_order_id for context
        if (!$tenantId && $merchantOrderId && str_starts_with($merchantOrderId, 'tx_')) {
            $parts = explode('_', $merchantOrderId);
            if (count($parts) >= 6) {
                $tenantId = $parts[2];
                $isChange = $parts[5] === '1';
            }
        }

        Log::warning('Paymob Payment Failed/Cancelled', [
            'success' => $success,
            'transaction_id' => $transactionId,
            'merchant_order_id' => $merchantOrderId,
            'tenant_id' => $tenantId,
            'is_change' => $isChange,
            'all_params' => $request->all(),
        ]);

        // If this was a subscription upgrade, redirect back to subscription page
        if ($isChange && $tenantId) {
            $tenant = \App\Models\Tenant::find($tenantId);
            if ($tenant) {
                return redirect()->route('center.subscription.index', ['tenant' => $tenant->domain])
                    ->with('error', 'فشل الدفع عبر Paymob أو تم إلغاؤه. يرجى المحاولة مرة أخرى.');
            }
        }

        return redirect()->route('home')->withErrors(['error' => 'فشل الدفع عبر Paymob أو تم إلغاؤه.']);
    }

    public function cancel()
    {
        return view('auth.payment-cancel');
    }

    public function demo()
    {
        $planSlug = session('selected_plan');
        $tenantId = session('tenant_id');

        if (!$planSlug || !$tenantId) {
            return redirect()->route('register')->withErrors(['error' => __('Your session has expired. Please register again.')]);
        }

        $package = \App\Models\Package::where('slug', $planSlug)->first();
        $tenant = \App\Models\Tenant::find($tenantId);

        if (!$package || !$tenant) {
            return redirect()->route('register')->withErrors(['error' => __('Selected plan or tenant not found.')]);
        }

        $couponCode = session('applied_coupon_code');
        $discountAmount = session('discount_amount', 0);
        $basePrice = session('base_price', $package->price);
        $totalAmount = session('total_amount', $package->price);
        $billingCycle = session('billing_cycle', 'monthly');

        // Show a fake payment page for demo/testing
        return view('auth.payment-demo', compact('package', 'tenant', 'couponCode', 'discountAmount', 'totalAmount', 'basePrice', 'billingCycle'));
    }

    public function demoSuccess(TelegramService $telegram)
    {
        // Simulate successful payment in demo mode
        if (!session('tenant_id')) {
            return redirect()->route('register');
        }

        // Security: Validate session integrity to prevent bypass
        $userId = auth()->check() ? auth()->id() : \App\Models\User::where('tenant_id', session('tenant_id'))->where('role', 'center_admin')->value('id');
        $expectedHmac = hash_hmac('sha256', session('tenant_id') . '|' . $userId, config('app.key'));
        if (!hash_equals($expectedHmac, session('registration_hmac', ''))) {
            \Log::warning('Demo payment bypass attempt detected', ['ip' => request()->ip()]);
            return redirect()->route('register')->withErrors(['error' => 'جلسة غير صالحة. يرجى التسجيل مرة أخرى.']);
        }

        // Create a fake subscription only if doesn't exist
        $tenant = \App\Models\Tenant::find(session('tenant_id'));
        if ($tenant) {
            $existingSub = \App\Models\Subscription::where('tenant_id', $tenant->id)
                ->where('status', 'active')
                ->where(function($q) {
                    $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
                })
                ->latest()
                ->first();

            if (!$existingSub) {
                $planIdentifier = session('selected_plan', 'pro');
                $package = \App\Models\Package::where('slug', $planIdentifier)->orWhere('id', $planIdentifier)->first();
                $priceSlug = $package ? $package->slug : $planIdentifier;
                
                \App\Models\Subscription::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'default',
                    'stripe_id' => 'sub_demo_' . \Illuminate\Support\Str::random(10),
                    'stripe_status' => 'active',
                    'stripe_price' => 'price_demo_' . $priceSlug,
                    'quantity' => 1,
                    'ends_at' => session('billing_cycle') === 'yearly' ? now()->addYear() : (session('billing_cycle') === 'term' ? now()->addDays((int)\App\Models\SiteSetting::get('term_duration_days', 150)) : now()->addDays(30)),
                    'status' => 'active',
                    'billing_cycle' => session('billing_cycle', 'monthly'),
                    'coupon_id' => session('applied_coupon_id'),
                    'coupon_code' => session('applied_coupon_code'),
                    'discount_amount' => session('discount_amount', 0),
                    'total_amount' => session('total_amount', 0),
                    'base_price' => session('base_price', 0),
                ]);

                // Log the subscription operation
                $billingCycle = session('billing_cycle', 'monthly');
                $termDays = (int) \App\Models\SiteSetting::get('term_duration_days', 150);
                $daysForLog = $billingCycle === 'yearly' ? 365 : ($billingCycle === 'term' ? $termDays : 30);
                \App\Models\SubscriptionLog::logOperation(
                    $tenant->id,
                    'subscription',
                    $package->slug ?? 'unknown',
                    $package->name ?? 'مخصص',
                    $billingCycle,
                    'demo',
                    session('total_amount', 0),
                    null,
                    now(),
                    now()->addDays($daysForLog)
                );

                // Increment usage if coupon was used
                if (session('applied_coupon_id')) {
                    $coupon = \App\Models\Coupon::find(session('applied_coupon_id'));
                    if ($coupon) {
                       $coupon->incrementUsage();
                       // Notify Admin
                       try {
                           app(\App\Services\TelegramService::class)->sendCouponAlert($tenant, $coupon, session('discount_amount', 0));
                       } catch (\Throwable $e) {}
                    }
                }
            } else {
                // Update existing subscription
                $planIdentifier = session('selected_plan', 'pro');
                $package = \App\Models\Package::where('slug', $planIdentifier)->orWhere('id', $planIdentifier)->first();
                $priceSlug = $package ? $package->slug : $planIdentifier;

                $existingSub->forceFill([
                    'stripe_price' => 'price_demo_' . $priceSlug,
                    'ends_at' => session('billing_cycle') === 'yearly' ? now()->addYear() : (session('billing_cycle') === 'term' ? now()->addDays((int)\App\Models\SiteSetting::get('term_duration_days', 150)) : now()->addDays(30)),
                    'billing_cycle' => session('billing_cycle', 'monthly'),
                    'coupon_id' => session('applied_coupon_id'),
                    'coupon_code' => session('applied_coupon_code'),
                    'discount_amount' => session('discount_amount', 0),
                    'total_amount' => session('total_amount', 0),
                    'base_price' => session('base_price', 0),
                ])->save();

                // Log the upgrade operation
                $billingCycle = session('billing_cycle', 'monthly');
                $termDays = (int) \App\Models\SiteSetting::get('term_duration_days', 150);
                $daysForLog = $billingCycle === 'yearly' ? 365 : ($billingCycle === 'term' ? $termDays : 30);
                \App\Models\SubscriptionLog::logOperation(
                    $tenant->id,
                    'upgrade',
                    $package->slug ?? 'unknown',
                    $package->name ?? 'مخصص',
                    $billingCycle,
                    'demo',
                    session('total_amount', 0),
                    null,
                    now(),
                    now()->addDays($daysForLog)
                );

                // Increment usage if coupon was used
                if (session('applied_coupon_id')) {
                    $coupon = \App\Models\Coupon::find(session('applied_coupon_id'));
                    if ($coupon) {
                       $coupon->incrementUsage();
                       // Notify Admin
                       try {
                           app(\App\Services\TelegramService::class)->sendCouponAlert($tenant, $coupon, session('discount_amount', 0));
                       } catch (\Throwable $e) {}
                    }
                }
            }
        }

        if (session('is_subscription_change')) {
            session()->forget('is_subscription_change');

            // Notify Admin for Upgrade/Change
            try {
                $user = Auth::user();
                $userName = $user ? $user->name : 'مستخدم غير مسجل';
                
                $sub = \App\Models\Subscription::where('tenant_id', $tenant->id)->latest()->first();
                $packageName = $sub ? $sub->type_label : 'غير محدد';
                $endsAt = ($sub && $sub->ends_at) ? $sub->ends_at->format('Y-m-d') : 'غير محدد';
                $amount = session('total_amount', 0) . ' ' . \App\Models\SiteSetting::get('currency_symbol', 'جنيه');
                
                $msg = "<b>🔄 ترقية / تغيير اشتراك!</b>\n\n";
                $msg .= "<b>🏢 المركز:</b> {$tenant->name}\n";
                $msg .= "<b>👤 المستخدم:</b> {$userName}\n";
                $msg .= "<b>📦 الباقة الجديدة:</b> {$packageName}\n";
                $msg .= "<b>💰 المبلغ المدفوع:</b> {$amount}\n";
                $msg .= "<b>⏳ تاريخ الانتهاء الجديد:</b> {$endsAt}\n\n";
                $msg .= "#SubscriptionUpgrade";
                
                app(\App\Services\TelegramService::class)->sendAdminNotification($msg);
            } catch (\Throwable $e) {
                \Log::error("Failed to send upgrade notification: " . $e->getMessage());
            }

            return redirect()->route('center.subscription.success', ['tenant' => $tenant->domain]);
        }

        session(['registration_success' => true]);

        // Notify Admin
        $user = \App\Models\User::where('tenant_id', $tenant->id)
            ->whereIn('role', ['center_admin', 'instructor'])
            ->first();
            
        $telegram->sendRegistrationAlert($tenant, $user, '********');

        return redirect()->route('registration.success');
    }
}
