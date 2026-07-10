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

        if ($user && ! empty($user->google2fa_secret)) {
            $verified = $request->session()->get('2fa_verified');
            $verifiedIp = $request->session()->get('2fa_verified_ip');

            if ($verified) {
                $currentIp = $request->ip();
                if ($verifiedIp !== $currentIp) {
                    $request->session()->forget(['2fa_verified', '2fa_verified_ip']);
                    $verified = false;
                }
            }

            if (! $verified) {
                if (! $request->is('2fa*') && ! $request->is('logout')) {
                    return redirect()->route('2fa.verify');
                }
            }
        }

        return $next($request);
    }
}
