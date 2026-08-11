<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Gate on google2fa_enabled (only true after a valid OTP was
        // confirmed). A stored secret alone must never trigger the challenge —
        // unconfirmed setup secrets no longer reach the database.
        if ($user && (bool) $user->google2fa_enabled) {
            // Check if 2fa is verified in the current session
            if (! $request->session()->has('2fa_verified')) {
                // If not verified, and the user is not currently trying to verify, redirect them
                $on2faFlow = $request->routeIs('2fa.*')
                    || $request->routeIs('center.logout')
                    || $request->routeIs('logout')
                    || $request->is('logout');

                if (! $on2faFlow) {
                    return redirect()->route('2fa.verify');
                }
            }
        }

        return $next($request);
    }
}
