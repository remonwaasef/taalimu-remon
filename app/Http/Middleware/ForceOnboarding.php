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
        'center.onboarding',
        'center.onboarding.save-step',
        'center.onboarding.complete',
        'center.onboarding.skip',
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

        // Redirect to onboarding wizard
        $tenantParam = $tenant->domain ?? $request->route('tenant');
        return redirect()->route('center.onboarding', ['tenant' => $tenantParam]);
    }
}
