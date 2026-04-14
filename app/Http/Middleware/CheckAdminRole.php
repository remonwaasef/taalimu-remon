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
        $user = auth()->user();

        if (!$user) {
            abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        // Bypasses Spatie strictly isolated teams validation which blocks global roles
        // Or if the user is currently impersonating someone (we allow them to return to admin panel)
        if (in_array(strtolower($user->role), ['admin', 'super_admin']) || 
            $user->hasRole('super_admin') || 
            $user->hasRole('Super Admin') ||
            session()->has('impersonator_id')) {
            return $next($request);
        }

        abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
    }
}
