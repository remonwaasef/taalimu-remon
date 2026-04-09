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
        // Priority: 1. Database (if authenticated), 2. Session, 3. GeoIP Fallback, 4. Default
        $locale = null;
        
        if (auth()->check() && auth()->user()->locale) {
            $locale = auth()->user()->locale;
        } else {
            $locale = Session::get('locale');
            
            // If No session locale, try Geo-IP detection
            if (!$locale && config('app.env') !== 'testing') {
                $geoIP = app(\App\Services\GeoIPService::class);
                $countryCode = $geoIP->getCountryCode($request->ip());
                $locale = $geoIP->getLocaleFromCountry($countryCode);
                
                // Store in session so we don't hit the API on every click
                Session::put('locale', $locale);
            }
        }

        // Supported locales
        $supportedLocales = ['ar', 'en', 'fr'];

        // Final Fallback and Validation
        if (!$locale || !in_array($locale, $supportedLocales)) {
            $locale = 'ar';
        }

        // Set application locale
        App::setLocale($locale);

        // Determine Suggested Currency based on Locale + GeoIP
        if (!Session::has('suggested_currency')) {
            $geoIP = app(\App\Services\GeoIPService::class);
            $countryCode = $geoIP->getCountryCode($request->ip());
            $currency = $geoIP->getCurrencyFromLocale($locale, $countryCode);
            Session::put('suggested_currency', $currency);
            Session::put('user_country_code', $countryCode);
        }

        return $next($request);
    }
}
