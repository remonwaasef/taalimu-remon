<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGrowthTenant
{
    /**
     * Ensure the tenant context is bound for Growth routes when accessed from central domain.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! app()->bound('tenant')) {
            $user = $request->user();
            if ($user && $user->tenant_id) {
                $tenant = $user->tenant ?? Tenant::find($user->tenant_id);
                if ($tenant) {
                    app()->instance('tenant', $tenant);
                }
            } elseif ($user && ($instructor = $user->instructor)) {
                $tenant = Tenant::find($instructor->tenant_id);
                if ($tenant) {
                    app()->instance('tenant', $tenant);
                }
            }
        }

        return $next($request);
    }
}
