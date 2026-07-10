<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePhoneIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user) {
            return $next($request);
        }

        $excludedRoutes = [
            'logout',
            'phone.verify.show',
            'phone.verify.send',
            'phone.verify.submit',
            '2fa.verify',
            '2fa.setup',
            '2fa.enable',
        ];

        if ($request->route() && in_array($request->route()->getName(), $excludedRoutes)) {
            return $next($request);
        }

        if ($user->hasVerifiedPhone()) {
            return $next($request);
        }

        if ($user->phone) {
            $code = $user->generatePhoneVerificationCode();
            \Illuminate\Support\Facades\Log::info("Phone verification code for {$user->phone}: {$code}");
        }

        return redirect()->route('phone.verify.show');
    }
}
