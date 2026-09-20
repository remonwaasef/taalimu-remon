<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AssertTenantIdentity
{
    /**
     * Fail-closed tenant isolation guard.
     *
     * Runs on every web request after a tenant has been identified. If an
     * authenticated user's tenant does not match the tenant bound to the
     * request (e.g. a shared session cookie following a cross-tenant visit),
     * the request is rejected instead of leaking data across tenants.
     *
     * Global accounts (tenant_id = null) are only allowed on the global
     * admin panel (/admin), never inside tenant areas.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! app()->bound('tenant') || ! app('tenant')) {
            return $next($request);
        }

        $user = auth()->user();

        if (! $user) {
            return $next($request);
        }

        // The global admin panel is reachable from tenant hosts and is exempt.
        if ($request->is('admin') || $request->is('admin/*')) {
            return $next($request);
        }

        $tenantId = app('tenant')->id;

        // Defense in depth: the session should be bound to the tenant that
        // issued the login. A mismatch indicates a stale or hijacked session.
        $sessionTenantId = $request->session()->get('tenant_id');
        if ($sessionTenantId !== null && (int) $sessionTenantId !== (int) $tenantId) {
            // If the user belongs to the current tenant, refresh stale session tenant_id
            if ((int) $user->tenant_id === (int) $tenantId) {
                $request->session()->put('tenant_id', $tenantId);
            } else {
                // If user belongs to another tenant, redirect them to their own tenant dashboard/page
                if ($user->tenant) {
                    $targetPath = $request->path() === '/' ? '' : $request->path();
                    return redirect()->away(tenant_url($targetPath, $user->tenant));
                }
                abort(403, 'Unauthorized tenant access.');
            }
        }

        if ($user->tenant_id === null) {
            abort(403, 'Global accounts cannot access center areas.');
        }

        if ((int) $user->tenant_id !== (int) $tenantId) {
            // If authenticated user belongs to another tenant, gracefully redirect to their own tenant
            if ($user->tenant) {
                $targetPath = $request->path() === '/' ? '' : $request->path();
                return redirect()->away(tenant_url($targetPath, $user->tenant));
            }
            abort(403, 'Unauthorized tenant access.');
        }

        return $next($request);
    }
}
