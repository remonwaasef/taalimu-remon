<?php

namespace App\Http\Middleware;

use App\Services\GeoIPService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function __construct(
        protected GeoIPService $geoIP
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $locale = null;

        if (auth()->check() && auth()->user()->locale) {
            $locale = auth()->user()->locale;
        } else {
            $locale = Session::get('locale');

            if (! $locale && config('app.env') !== 'testing') {
                $locale = $this->guessLocaleFromBrowser($request);

                if (! $locale) {
                    $countryCode = $this->geoIP->getCountryCode($request->ip());
                    $locale = $this->geoIP->getLocaleFromCountry($countryCode);
                }

                Session::put('locale', $locale);
            }
        }

        $supportedLocales = ['ar', 'en', 'fr'];

        if (! $locale || ! in_array($locale, $supportedLocales)) {
            $locale = 'ar';
        }

        App::setLocale($locale);

        if (! Session::has('suggested_currency')) {
            $countryCode = Session::get('user_country_code') ?: $this->geoIP->getCountryCode($request->ip());
            $currency = $this->geoIP->getCurrencyFromCountryCode($countryCode);
            Session::put('suggested_currency', $currency);
            Session::put('user_country_code', $countryCode);
        }

        return $next($request);
    }

    protected function guessLocaleFromBrowser(Request $request): ?string
    {
        $acceptLanguage = $request->header('Accept-Language');

        if (! $acceptLanguage) {
            return null;
        }

        $locales = [];
        foreach (explode(',', $acceptLanguage) as $entry) {
            $parts = explode(';', trim($entry));
            $lang = strtolower(explode('-', $parts[0])[0]);
            $q = isset($parts[1]) ? (float) str_replace('q=', '', $parts[1]) : 1.0;
            $locales[$lang] = max($locales[$lang] ?? 0, $q);
        }

        arsort($locales);

        $langMap = [
            'ar' => 'ar',
            'fr' => 'fr',
        ];

        foreach ($locales as $lang => $q) {
            if (isset($langMap[$lang])) {
                return $langMap[$lang];
            }
        }

        return null;
    }
}
