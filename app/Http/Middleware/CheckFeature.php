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
