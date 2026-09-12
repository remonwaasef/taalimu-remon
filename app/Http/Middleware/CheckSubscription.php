<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip subscription checks in testing environment unless explicitly enforced by the test
        if (app()->environment('testing') && ! config('subscription.enforce_in_testing', false)) {
            return $next($request);
        }

        if (! app()->bound('tenant')) {
            return $next($request);
        }

        $tenant = app('tenant');

        // Skip check for specific routes (e.g., billing page)
        if ($request->routeIs('center.subscription.*') || $request->routeIs('center.tickets.*') || $request->routeIs('center.sales.*') || $request->routeIs('2fa.*')) {
            return $next($request);
        }

        $subscription = $tenant->activeSubscription;

        if (! $subscription) {
            $message = __('center::subscription.trial_expired_alert');
            if ($message === 'center::subscription.trial_expired_alert') {
                $message = __('subscription.expired');
            }

            // Redirect to billing/subscription page
            return redirect()->route('center.subscription.index', ['tenant' => $tenant->domain])
                ->with('error', $message);
        }

        return $next($request);
    }
}
