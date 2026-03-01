<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SubscriptionController extends Controller
{
    /**
     * Display the subscription management page.
     */
    public function index()
    {
        $tenant = app('tenant');

        // Current active subscription
        $subscription = Subscription::where('tenant_id', $tenant->id)
            ->latest()
            ->first();

        // Resolve current package
        $currentPackage = $subscription?->resolved_package;

        // Days remaining calculation
        $daysRemaining   = null;
        $daysTotal       = null;
        $progressPercent = 0;

        if ($subscription && $subscription->ends_at) {
            $daysRemaining   = max(0, intval(ceil(now()->diffInDays($subscription->ends_at, false))));
            $daysTotal       = $subscription->created_at
                ? intval(ceil($subscription->created_at->diffInDays($subscription->ends_at)))
                : 30;
            $daysTotal       = max(1, $daysTotal);
            $progressPercent = min(100, round((($daysTotal - $daysRemaining) / $daysTotal) * 100));
        }

        // All available packages (for upgrade section)
        $packages = Cache::remember('subscription_packages_full', 3600, function () {
            return Package::with('features')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });

        $currency = \App\Models\SiteSetting::get('currency_symbol', 'جنيه');

        return view('center::subscription.index', compact(
            'tenant',
            'subscription',
            'currentPackage',
            'packages',
            'daysRemaining',
            'daysTotal',
            'progressPercent',
            'currency'
        ));
    }

    /**
     * Initiate a checkout session.
     */
    public function checkout(Request $request, $packageId)
    {
        $this->authorize('update', app('tenant'));
        $package = Package::findOrFail($packageId);
        $tenant  = app('tenant');

        if (!$package->stripe_price_id) {
            return back()->with('error', __('center::messages.msg_087'));
        }

        // 1. Handle Free Trial locally (bypass Stripe)
        if ($package->slug === 'free-trial' || $package->stripe_price_id === 'price_free' || $package->price <= 0) {
            \App\Models\Subscription::create([
                'tenant_id' => $tenant->id,
                'name' => 'default',
                'stripe_id' => 'sub_local_' . \Illuminate\Support\Str::random(10),
                'stripe_status' => 'active',
                'stripe_price' => $package->stripe_price_id ?? 'price_free',
                'quantity' => 1,
                'ends_at' => now()->addDays(14),
                'status' => 'active',
                'billing_cycle' => 'monthly',
                'base_price' => 0,
                'total_amount' => 0,
                'discount_amount' => 0,
            ]);

            return redirect()->route('center.subscription.success', ['tenant' => $tenant->domain]);
        }

        // 2. Check for Demo Mode or Missing Keys
        $stripeKey = config('services.stripe.secret');
        $isDemo = config('services.stripe.demo_mode') || empty($stripeKey);

        $billingCycle = $request->query('cycle', 'monthly');
        $cyclePrice = $billingCycle === 'yearly' ? $package->yearly_price : $package->price;

        if ($isDemo) {
            \Log::info("Using Demo Mode for subscription checkout", ['tenant_id' => $tenant->id, 'package_id' => $package->id, 'cycle' => $billingCycle]);
            
            // Replicate session data used by PaymentController@demo
            session([
                'tenant_id' => $tenant->id,
                'selected_plan' => $package->slug,
                'billing_cycle' => $billingCycle,
                'base_price' => $cyclePrice,
                'total_amount' => $cyclePrice,
                'registration_hmac' => hash_hmac('sha256', $tenant->id . '|' . auth()->id(), config('app.key')),
                'is_subscription_change' => true,
            ]);

            return redirect()->route('payment.demo');
        }

        try {
            return $tenant->newSubscription('default', $package->stripe_price_id)
                ->checkout([
                    'success_url' => route('center.subscription.success', ['tenant' => $tenant->domain]),
                    'cancel_url'  => route('center.subscription.index', ['tenant' => $tenant->domain]),
                ]);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            if (str_contains($e->getMessage(), 'No such price')) {
                \Log::warning("Stripe Price ID '{$package->stripe_price_id}' not found. Falling back to Demo Mode.", ['exception' => $e->getMessage()]);

                // Fallback to Demo Mode logic
                session([
                    'tenant_id' => $tenant->id,
                    'selected_plan' => $package->slug,
                    'billing_cycle' => $billingCycle,
                    'base_price' => $cyclePrice,
                    'total_amount' => $cyclePrice,
                    'registration_hmac' => hash_hmac('sha256', $tenant->id . '|' . auth()->id(), config('app.key')),
                    'is_subscription_change' => true,
                    'error_flash' => "تنبيه: محرك الدفع (Stripe) لم يجد كود السعر '{$package->stripe_price_id}'. تم تحويلك لوضع التجربة."
                ]);

                return redirect()->route('payment.demo');
            }
            throw $e;
        }
    }

    /**
     * Handle successful subscription.
     */
    public function success(Request $request)
    {
        return view('center::subscription.success');
    }

    /**
     * Handle cancellation.
     */
    public function cancel()
    {
        return redirect()->route('center.subscription.index', ['tenant' => app('tenant')->domain])
            ->with('info', __('center::messages.msg_088'));
    }
}
