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
        // Skip subscription checks in testing environment
        if (app()->environment('testing')) {
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

        $subscription = $tenant->activeSubscription();

        if (! $subscription) {
            // Redirect to billing/subscription page
            return redirect()->route('center.subscription.index', ['tenant' => $tenant->domain])
                ->with('error', __('subscription.expired'));
        }

        return $next($request);
    }
}
