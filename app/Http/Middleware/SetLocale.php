<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Priority: 1. Database (if authenticated), 2. Session, 3. Default
        $locale = null;
        
        if (auth()->check() && auth()->user()->locale) {
            $locale = auth()->user()->locale;
        } else {
            $locale = Session::get('locale', 'ar');
        }

        // Supported locales
        $supportedLocales = ['ar', 'en', 'fr'];

        // Validate locale
        if (!in_array($locale, $supportedLocales)) {
            $locale = 'ar';
        }

        // Set application locale
        App::setLocale($locale);

        return $next($request);
    }
}
