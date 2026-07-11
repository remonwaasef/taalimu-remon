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
                'font-src * data:',
                'img-src * data: blob:',
                'connect-src *',
                "frame-ancestors 'none'",
            ];

            $response->headers->set('X-CSP-Debug', 'true');
        } else {
            // نسخة الإنتاج: قائمة بيضاء صريحة بالنطاقات بدل السماح لأي https،
            // حتى لا يُفرَّغ الـ CSP من قيمته ضد XSS. inline scripts ما زالت مسموحة
            // مؤقتاً لأن القوالب تعتمد عليها (خطة لاحقة: نقلها إلى Vite + nonce).
            $cdn = 'cdn.jsdelivr.net cdnjs.cloudflare.com unpkg.com';
            $fonts = 'fonts.googleapis.com fonts.gstatic.com fonts.bunny.net';

            $csp = [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' {$cdn}",
                "style-src 'self' 'unsafe-inline' {$cdn} {$fonts}",
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
