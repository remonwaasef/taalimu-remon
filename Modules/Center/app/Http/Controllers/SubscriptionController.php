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

        // Modular Payment Gateway Logic
        $billingCycle = $request->query('cycle', 'monthly');
        $gatewayName = $request->input('payment_gateway', 'paymob');

        try {
            $gateway = \App\Services\PaymentFactory::make($gatewayName);
            
            $redirectUrl = $gateway->createCheckoutSession($tenant, $package, $billingCycle, [
                'success_url' => route('center.subscription.success', ['tenant' => $tenant->domain]),
                'cancel_url'  => route('center.subscription.index', ['tenant' => $tenant->domain]),
                'is_upgrade'  => true,
            ]);

            return redirect()->away($redirectUrl);
        } catch (\Exception $e) {
            \Log::error("Subscription checkout error: " . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء معالجة عملية الدفع.');
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
