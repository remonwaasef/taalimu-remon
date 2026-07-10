<?php

namespace App\Helpers;

class PhoneHelper
{
    /**
     * Get phone number variations (with/without country code, leading zero, etc.)
     */
    public static function getVariations(string $phone): array
    {
        $clean = preg_replace('/[^0-9+]/', '', $phone);
        $digits = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($digits) < 8) {
            return [];
        }

        $variations = [$clean, $digits];

        if (str_starts_with($digits, '0')) {
            $variations[] = substr($digits, 1);
        } elseif (str_starts_with($digits, '20')) {
            $variations[] = '0' . substr($digits, 2);
            $variations[] = substr($digits, 2);
        }

        if (strlen($digits) === 10 && ! str_starts_with($digits, '0')) {
            $variations[] = '0' . $digits;
        }

        return array_values(array_filter(array_unique($variations)));
    }

    /**
     * Clean phone number for WhatsApp links
     */
    public static function sanitizeForWhatsApp(?string $phone, string $countryCode = '20'): string
    {
        if (! $phone) {
            return '';
        }

        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = $countryCode . substr($phone, 1);
        }

        return $phone;
    }

    /**
     * Validate phone has minimum digits
     */
    public static function isValid(string $phone): bool
    {
        return strlen(preg_replace('/[^0-9]/', '', $phone)) >= 8;
    }

    /**
     * Strip all non-numeric characters
     */
    public static function strip(string $phone): string
    {
        return preg_replace('/[^0-9+]/', '', $phone);
    }
}
