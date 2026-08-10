<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class EnsureInstructorAccess
{
    /**
     * Gate the instructor panel to tenant staff roles only.
     *
     * SEC-02: without this check, any authenticated tenant user (student,
     * parent, custom role) could reach mark-paid, billing, student editing
     * and attendance endpoints.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        $allowed = ['instructor', 'center_admin', 'center_owner'];

        // Role column first (fast path), then Spatie roles for custom setups.
        if (in_array(strtolower((string) $user->role), $allowed, true)) {
            return $next($request);
        }

        if ($user->hasAnyRole($allowed)) {
            return $next($request);
        }

        abort(403, __('لا تملك صلاحية الوصول إلى لوحة المعلم.'));
    }
}