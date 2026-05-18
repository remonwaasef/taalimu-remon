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

        // Content Security Policy
        if (app()->environment('local')) {
            // أكثر مرونة في بيئة التطوير لتقليل الأعطال أثناء التطوير
            $csp = [
                "default-src * data: blob: 'unsafe-inline' 'unsafe-eval'",
                "script-src * 'unsafe-inline' 'unsafe-eval'",
                "style-src * 'unsafe-inline'",
                "font-src * data:",
                "img-src * data: blob:",
                "connect-src *",
                "frame-ancestors 'none'",
            ];

            $response->headers->set('X-CSP-Debug', 'true');
        } else {
            // نسخة أكثر أمانًا للإنتاج: نقيّد المصادر ونمنع unsafe-eval
            $csp = [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https: cdn.jsdelivr.net", 
                "style-src 'self' 'unsafe-inline' https: cdn.jsdelivr.net fonts.googleapis.com", 
                "font-src 'self' data: https: fonts.gstatic.com",
                "img-src 'self' data: blob: https:",
                "connect-src 'self' https:",
                "worker-src 'self'",
                "manifest-src 'self'",
                "frame-ancestors 'none'",
                "frame-src 'self' https://www.youtube.com https://player.vimeo.com https://js.stripe.com",
                "base-uri 'self'",
            ];
        }

        $response->headers->set('Content-Security-Policy', implode('; ', $csp));
        
        // Additional security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=(self)');

        // HSTS - Strict Transport Security (Only in production/https)
        if (!app()->environment('local')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
