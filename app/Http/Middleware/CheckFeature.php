<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeature
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $featureCode): Response
    {
        if (! app()->bound('tenant')) {
            return $next($request);
        }

        $tenant = app('tenant');

        // Added logging and tenant check
        if (! $tenant) {
            \Illuminate\Support\Facades\Log::warning('CheckFeature: No tenant found in request context, but app is bound to tenant.');

            return $next($request);
        }

        $hasFeature = $tenant->hasFeature($featureCode);
        $subscription = $tenant->activeSubscription;

        if (config('app.debug')) {
            \Illuminate\Support\Facades\Log::debug('CheckFeature: Detail Check', [
                'url' => $request->fullUrl(),
                'tenant_id' => $tenant->id ?? 'N/A',
                'feature_code' => $featureCode,
                'has_feature' => $hasFeature ? 'yes' : 'no',
                'has_active_subscription' => $subscription ? 'yes' : 'no',
                'user_role' => auth()->user()->role ?? 'guest',
            ]);
        }

        if (! $hasFeature) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('هذه الميزة غير متوفرة في باقتك الحالية.'),
                ], 403);
            }

            // If it's a student trying to access student portal, don't send them to subscription index
            if (auth()->check() && auth()->user()->role === 'student' && $featureCode === 'student_portal') {
                auth()->logout();

                return redirect()->route('center.login', ['tenant' => $tenant->domain])
                    ->with('error', __('بوابة الطالب غير مفعلة لهذا المركز حالياً.'));
            }

            return redirect()->route('center.subscription.index', ['tenant' => $tenant->domain])
                ->with('error', __('هذه الميزة غير متوفرة في باقتك الحالية. يرجى الترقية للوصول إليها.'));
        }

        return $next($request);
    }
}
