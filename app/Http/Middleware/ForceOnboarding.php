<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceOnboarding
{
    /**
     * Routes excluded from the onboarding check.
     */
    protected array $except = [
        'center.onboarding.complete',
        'center.logout',
        'center.login',
        'center.password.change',
        'center.password.update',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !app()->bound('tenant')) {
            return $next($request);
        }

        // Only apply to center_admin users
        if ($user->role !== 'center_admin' && !$user->hasRole('center_admin')) {
            return $next($request);
        }

        $tenant = app('tenant');

        // If onboarding is already completed, skip
        if ($tenant->onboarding_completed_at !== null) {
            return $next($request);
        }

        // Check if current route is in the exceptions
        $routeName = $request->route()?->getName();
        if ($routeName && in_array($routeName, $this->except)) {
            return $next($request);
        }

        // Redirect to dashboard with onboarding query param if not already there
        if ($request->routeIs('center.dashboard') || $request->routeIs('center.dashboard.alt')) {
            return $next($request);
        }

        $tenantParam = $tenant->domain ?? $request->route('tenant');
        return redirect()->route('center.dashboard', ['tenant' => $tenantParam, 'onboarding_step' => 1]);
    }
}
