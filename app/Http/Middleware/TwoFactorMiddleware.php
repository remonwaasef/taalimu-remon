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
        
        if ($user && !empty($user->google2fa_secret)) {
            // Check if 2fa is verified in the current session
            if (!$request->session()->has('2fa_verified')) {
                // If not verified, and the user is not currently trying to verify, redirect them
                if (!$request->is('2fa*') && !$request->is('logout')) {
                    return redirect()->route('2fa.index');
                }
            }
        }

        return $next($request);
    }
}
