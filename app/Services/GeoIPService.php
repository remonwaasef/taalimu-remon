<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GeoIPService
{
    /**
     * Get the country code for an IP address.
     * 
     * @param string $ip
     * @return string|null (ISO 3166-1 alpha-2)
     */
    public function getCountryCode($ip)
    {
        // Skip local or reserved IPs
        if ($ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return 'EG'; // Default for local dev if needed
        }

        try {
            // Cache results for 24 hours to reduce API load
            return Cache::remember("geoip_country_{$ip}", 86400, function () use ($ip) {
                $response = Http::timeout(2)->get("https://ip-api.com/json/{$ip}?fields=status,countryCode");
                
                if ($response->successful() && $response->json('status') === 'success') {
                    return strtoupper($response->json('countryCode'));
                }

                return null;
            });
        } catch (\Exception $e) {
            Log::warning("GeoIP Lookup failed for {$ip}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Map country code to application locale.
     * 
     * @param string|null $countryCode
     * @return string
     */
    public function getLocaleFromCountry($countryCode)
    {
        if (!$countryCode) {
            return 'ar'; // Default
        }

        $arabicCountries = [
            'EG', 'SA', 'AE', 'JO', 'LB', 'KW', 'QA', 'BH', 'OM', 'IQ', 
            'YE', 'SY', 'PS', 'LY', 'SD', 'MA', 'DZ', 'TN', 'MR', 'DJ', 'KM'
        ];

        $frenchCountries = [
            'FR', 'BE', 'MC', 'LU', 'CH', 'CA', 'SN', 'ML', 'CI', 'BF', 'NE', 'TG', 'BJ', 'GN'
        ];

        if (in_array($countryCode, $arabicCountries)) {
            return 'ar';
        }

        if (in_array($countryCode, $frenchCountries)) {
            return 'fr';
        }

        return 'en'; // Global default
    }

    /**
     * Map country code to application currency.
     * 
     * @param string|null $countryCode
     * @return string
     */
    public function getCurrencyFromCountryCode($countryCode = null)
    {
        if (!$countryCode) {
            return 'USD';
        }

        if ($countryCode === 'EG') {
            return 'EGP';
        }

        if ($countryCode === 'SA') {
            return 'SAR';
        }

        if ($countryCode === 'AE') {
            return 'AED';
        }

        $euroCountries = ['FR', 'BE', 'MC', 'LU', 'CH', 'DE', 'IT', 'ES', 'NL', 'AT', 'PT', 'IE', 'GR', 'FI'];
        if (in_array($countryCode, $euroCountries)) {
            return 'EUR';
        }

        return 'USD'; // Global default
    }
}
