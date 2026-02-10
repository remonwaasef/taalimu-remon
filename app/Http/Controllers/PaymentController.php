<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function success(Request $request)
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

            // Get tenant (safely from session)
            $tenantId = session('tenant_id');
            if ($tenantId) {
                $setupToken = Str::random(40);
                Cache::put("setup_token_$setupToken", ['tenant_id' => $tenantId], now()->addMinutes(60));
                return redirect()->route('register.setup', ['token' => $setupToken]);
            }

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

    public function demoSuccess()
    {
        // Simulate successful payment in demo mode
        if (!session('tenant_id')) {
            return redirect()->route('register');
        }

        // Create a fake subscription only if doesn't exist
        $tenant = \App\Models\Tenant::find(session('tenant_id'));
        if ($tenant) {
            $existingSub = \App\Models\Subscription::where('tenant_id', $tenant->id)
                ->where('status', 'active')
                ->where(function($q) {
                    $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
                })
                ->first();

            if (!$existingSub) {
                \App\Models\Subscription::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'default',
                    'stripe_id' => 'sub_demo_' . \Illuminate\Support\Str::random(10),
                    'stripe_status' => 'active',
                    'stripe_price' => 'price_demo_' . session('selected_plan', 'pro'),
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
                    }
                }
            }
        }

        session(['registration_success' => true]);
        
        $setupToken = Str::random(40);
        Cache::put("setup_token_$setupToken", ['tenant_id' => session('tenant_id')], now()->addMinutes(60));
        
        return redirect()->route('register.setup', ['token' => $setupToken]);
    }
}
