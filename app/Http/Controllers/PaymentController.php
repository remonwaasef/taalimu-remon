<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function success(Request $request, TelegramService $telegram)
    {
        $sessionId = $request->get('session_id');

        if ($sessionId) {
            try {
                // Security fix: Verify session with Stripe
                \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                $session = \Stripe\Checkout\Session::retrieve($sessionId);

                if ($session->payment_status !== 'paid') {
                     \Log::warning("Payment verification failed for session: " . $sessionId);
                     return redirect()->route('home')->withErrors(['error' => 'لم يتم إكمال عملية الدفع بنجاح.']);
                }
            } catch (\Exception $e) {
                \Log::error("Stripe verification error: " . $e->getMessage());
                return redirect()->route('home');
            }

            // Mark registration as successful for the view
            session(['registration_success' => true]);

            // Notify Admin
            $tenant = Tenant::find(session('tenant_id'));
            $user = \App\Models\User::where('tenant_id', $tenant->id)->where('role', 'center_admin')->first();
            
            // Check for coupon in session (if it was used during checkout)
            if (session('applied_coupon_id')) {
                $coupon = \App\Models\Coupon::find(session('applied_coupon_id'));
                if ($coupon) {
                   $coupon->incrementUsage();
                   try {
                       app(\App\Services\TelegramService::class)->sendCouponAlert($tenant, $coupon, session('discount_amount', 0));
                   } catch (\Throwable $e) {}
                }
            }

            $telegram->sendRegistrationAlert($tenant, $user, '******** (Password set during registration)');

            return redirect()->route('registration.success');
        }

        return redirect()->route('home');
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
        $expectedHmac = hash_hmac('sha256', session('tenant_id') . '|' . \App\Models\User::where('tenant_id', session('tenant_id'))->where('role', 'center_admin')->value('id'), config('app.key'));
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
                    'ends_at' => session('billing_cycle') === 'yearly' ? now()->addYear() : now()->addDays(30),
                    'status' => 'active',
                    'billing_cycle' => session('billing_cycle', 'monthly'),
                    'coupon_id' => session('applied_coupon_id'),
                    'coupon_code' => session('applied_coupon_code'),
                    'discount_amount' => session('discount_amount', 0),
                    'total_amount' => session('total_amount', 0),
                    'base_price' => session('base_price', 0),
                ]);

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
                    'ends_at' => session('billing_cycle') === 'yearly' ? now()->addYear() : now()->addDays(30),
                    'billing_cycle' => session('billing_cycle', 'monthly'),
                    'coupon_id' => session('applied_coupon_id'),
                    'coupon_code' => session('applied_coupon_code'),
                    'discount_amount' => session('discount_amount', 0),
                    'total_amount' => session('total_amount', 0),
                    'base_price' => session('base_price', 0),
                ])->save();

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
        $user = \App\Models\User::where('tenant_id', $tenant->id)->where('role', 'center_admin')->first();
        $telegram->sendRegistrationAlert($tenant, $user, '********');

        return redirect()->route('registration.success');
    }
}
