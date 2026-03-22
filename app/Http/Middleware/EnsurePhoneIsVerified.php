<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class EnsurePhoneIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() ||
            ($request->user() instanceof MustVerifyEmail && ! $request->user()->hasVerifiedEmail())) {
            // Keep email verification for now if still implemented, 
            // but we want to prioritize phone verification.
        }

        if ($request->user() && ! $request->user()->hasVerifiedPhone()) {
            return $request->expectsJson()
                ? abort(403, 'Your phone number is not verified.')
                : redirect()->route('verification.phone.notice');
        }

        return $next($request);
    }
}
