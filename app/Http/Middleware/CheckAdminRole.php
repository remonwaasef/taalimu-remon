<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('admin')->user();

        if (! $user) {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        // Bypasses Spatie strictly isolated teams validation which blocks global roles.
        // Impersonated tenant sessions must not inherit admin-panel access; returning
        // from impersonation is handled by the dedicated auth-only route.
        // SEC-01: the admin panel is reserved for GLOBAL accounts (tenant_id = null) —
        // a tenant user must never pass by role-column value alone.
        $isGlobal = $user->tenant_id === null;
        $roleColumn = strtolower((string) $user->role);

        if ($isGlobal && (
                in_array($roleColumn, ['admin', 'super_admin']) ||
                $user->hasRole('super_admin') ||
                $user->hasRole('Super Admin')
            )) {
            return $next($request);
        }

        abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
    }
}
