<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCenterAdministrator
{
    /**
     * Restrict a route group to the tenant's administrators (legacy role
     * column is authoritative alongside the Spatie roles — see Gate::before).
     * SEC-AUTH-1: onboarding endpoints mutate tenant-wide data (settings,
     * instructor accounts, courses, students, sales) and must never be
     * reachable by students, parents or staff.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user || ! in_array($user->role, ['center_admin', 'center_owner'], true)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}