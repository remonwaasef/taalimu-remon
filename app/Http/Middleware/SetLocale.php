<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Priority: 1. Explicit Session Locale, 2. Authenticated User Locale (if custom), 3. GeoIP Fallback, 4. Default ('ar')
        $locale = Session::get('locale');

        if (! $locale && auth()->check() && auth()->user()->locale && auth()->user()->locale !== 'en') {
            $locale = auth()->user()->locale;
        }

        // If No session locale, try Geo-IP detection
        if (! $locale && config('app.env') !== 'testing') {
            $geoIP = app(\App\Services\GeoIPService::class);
            $countryCode = $geoIP->getCountryCode($request->ip());
            $locale = $geoIP->getLocaleFromCountry($countryCode);

            // Store in session so we don't hit the API on every click
            Session::put('locale', $locale);

            if (auth()->check()) {
                auth()->user()->update(['locale' => $locale]);
            }
        }

        // Supported locales
        $supportedLocales = ['ar', 'en', 'fr'];

        // Final Fallback and Validation (Default to 'ar' if null or invalid)
        if (! $locale || ! in_array($locale, $supportedLocales)) {
            $locale = 'ar';
        }

        // Set application locale
        App::setLocale($locale);

        // Determine Suggested Currency based on GeoIP
        // Only calculate if not set yet. We no longer change currency when language changes!
        if (! Session::has('suggested_currency')) {
            $geoIP = app(\App\Services\GeoIPService::class);
            $countryCode = Session::get('user_country_code') ?: $geoIP->getCountryCode($request->ip());
            $currency = $geoIP->getCurrencyFromCountryCode($countryCode);
            Session::put('suggested_currency', $currency);
            Session::put('user_country_code', $countryCode);
        }

        return $next($request);
    }
}
