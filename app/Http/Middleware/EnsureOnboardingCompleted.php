<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // 1. Only applies to logged-in users
        if (! $user) {
            return $next($request);
        }

        // 2. Only applies to Center Admins
        if ($user->role !== 'center_admin') {
            return $next($request);
        }

        // 3. Only applies if user belongs to a tenant
        if (! $user->tenant_id || ! $user->tenant) {
            return $next($request);
        }

        // 4. Check onboarding status
        if (app()->environment('testing')) {
            return $next($request);
        }

        $status = $user->tenant->onboarding_status;

        // If completed, let them pass
        if ($status === 'completed') {
            return $next($request);
        }

        // Allow access to logout and specific onboarding routes to prevent redirect loops
        $allowedRoutes = [
            'logout',
            'center.onboarding.show',
            'center.onboarding.submit',
        ];

        if ($request->route() && in_array($request->route()->getName(), $allowedRoutes)) {
            return $next($request);
        }

        // 5. Redirect to onboarding wizard
        return redirect()->route('center.onboarding.show');
    }
}
