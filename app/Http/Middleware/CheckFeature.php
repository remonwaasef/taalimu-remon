<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $featureCode
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $featureCode): Response
    {
        if (!app()->bound('tenant')) {
            return $next($request);
        }

        $tenant = app('tenant');

        // Added logging and tenant check
        if (!$tenant) {
            \Illuminate\Support\Facades\Log::warning('CheckFeature: No tenant found in request context, but app is bound to tenant.');
            return $next($request);
        }

        \Illuminate\Support\Facades\Log::debug('CheckFeature: Checking feature', [
            'url' => $request->fullUrl(),
            'tenant_id' => $tenant->id ?? 'N/A', // Use null coalescing for safety
            'feature_code' => $featureCode,
            'has_feature_before_check' => $tenant->hasFeature($featureCode) ? 'yes' : 'no' // Log the result of the check
        ]);

        if (!$tenant->hasFeature($featureCode)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('هذه الميزة غير متوفرة في باقتك الحالية.')
                ], 403);
            }

            return redirect()->route('center.subscription.index', ['tenant' => $tenant->domain])
                ->with('error', __('هذه الميزة غير متوفرة في باقتك الحالية. يرجى الترقية للوصول إليها.'));
        }

        return $next($request);
    }
}
