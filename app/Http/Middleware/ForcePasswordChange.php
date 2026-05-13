<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Routes that are excluded from the password change check.
     */
    protected $except = [
        'center.logout',
        'center.password.change',
        'center.password.change.submit',
        'lang.switch',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->must_change_password) {
            // Check if the current route is in the exceptions
            $routeName = $request->route()->getName();
            
            if (!in_array($routeName, $this->except)) {
                // Determine the tenant parameter for the redirect
                $tenant = null;
                if (app()->bound('tenant')) {
                    $tenant = app('tenant')->domain;
                } else {
                    // Fallback to route parameter if bound tenant is missing (e.g. for common prefixes like 'www')
                    $tenant = $request->route('tenant');
                }

                return redirect()->route('center.password.change', ['tenant' => $tenant])
                    ->with('warning', __('auth.must_change_password'));
            }
        }

        return $next($request);
    }
}
