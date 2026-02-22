<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = Cache::remember('subscription_packages', 3600, function () {
            return Package::select('id', 'name', 'price', 'display_features', 'description', 'stripe_price_id', 'is_active')->get();
        });
        $tenant = app('tenant');
        
        return view('center::subscription.index', compact('packages', 'tenant'));
    }

    /**
     * Initiate a checkout session.
     */
    public function checkout(Request $request, $packageId)
    {
        $this->authorize('update', app('tenant'));
        $package = Package::findOrFail($packageId);
        $tenant = app('tenant');

        if (!$package->stripe_price_id) {
            return back()->with('error', __('center::messages.msg_087'));
        }

        return $tenant->newSubscription('default', $package->stripe_price_id)
            ->checkout([
                'success_url' => route('center.subscription.success'),
                'cancel_url' => route('center.subscription.index'),
            ]);
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
        return redirect()->route('center.subscription.index')->with('info', __('center::messages.msg_088'));
    }
}
