<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $nonce = base64_encode(random_bytes(16));
        view()->share('csp_nonce', $nonce);
        $request->attributes->set('csp_nonce', $nonce);

        $cdn = 'cdn.jsdelivr.net cdnjs.cloudflare.com unpkg.com';
        $fonts = 'fonts.googleapis.com fonts.gstatic.com fonts.bunny.net';

        if (app()->environment('local')) {
            $csp = [
                "default-src 'self' {$cdn} {$fonts}",
                "script-src 'self' 'nonce-{$nonce}' {$cdn}",
                "style-src 'self' 'nonce-{$nonce}' {$cdn} {$fonts}",
                "font-src 'self' data: {$cdn} {$fonts}",
                "img-src 'self' data: blob: https:",
                "connect-src 'self' ws: wss:",
                "media-src 'self' https://assets.mixkit.co",
                "worker-src 'self' blob:",
                "manifest-src 'self'",
                "frame-ancestors 'none'",
                "frame-src 'self' https://www.youtube.com https://player.vimeo.com https://js.stripe.com",
                "base-uri 'self'",
                "form-action 'self'",
            ];
        } else {
            $csp = [
                "default-src 'self'",
                "script-src 'self' 'nonce-{$nonce}' {$cdn}",
                "style-src 'self' 'nonce-{$nonce}' {$cdn} {$fonts}",
                "font-src 'self' data: {$cdn} {$fonts}",
                "img-src 'self' data: blob: https:",
                "connect-src 'self'",
                "media-src 'self' https://assets.mixkit.co",
                "worker-src 'self' blob:",
                "manifest-src 'self'",
                "frame-ancestors 'none'",
                "frame-src 'self' https://www.youtube.com https://player.vimeo.com https://js.stripe.com",
                "base-uri 'self'",
                "form-action 'self'",
            ];
        }

        $response->headers->set('Content-Security-Policy', implode('; ', $csp));

        // Additional security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=(self)');

        // Prevent server info disclosure
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        // HSTS - Strict Transport Security (Only in production/https)
        if (! app()->environment('local')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
