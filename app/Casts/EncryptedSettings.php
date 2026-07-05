<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;

/**
 * Casts the tenant `settings` JSON column while transparently encrypting
 * sensitive credential values (API tokens, secrets) at rest.
 *
 * - On write: values at SENSITIVE_PATHS are encrypted with the app key.
 * - On read: encrypted values are decrypted; legacy plaintext values are
 *   returned as-is (and get encrypted the next time the model is saved),
 *   so no data migration is required.
 */
class EncryptedSettings implements CastsAttributes
{
    /**
     * Dot-notation paths inside the settings array whose values must be
     * encrypted at rest. Add new paths here when storing new credentials.
     */
    private const SENSITIVE_PATHS = [
        'whatsapp.access_token',
        'whatsapp.token', // legacy UltraMsg-style key
    ];

    private const PREFIX = 'enc-v1:';

    public function get(Model $model, string $key, mixed $value, array $attributes): ?array
    {
        if ($value === null) {
            return null;
        }

        $settings = json_decode($value, true);

        if (! is_array($settings)) {
            return null;
        }

        foreach (self::SENSITIVE_PATHS as $path) {
            $current = Arr::get($settings, $path);

            if (is_string($current) && str_starts_with($current, self::PREFIX)) {
                try {
                    Arr::set($settings, $path, Crypt::decryptString(substr($current, strlen(self::PREFIX))));
                } catch (\Throwable $e) {
                    // Wrong APP_KEY or corrupted payload — fail closed on the
                    // credential without breaking the rest of the settings.
                    Arr::set($settings, $path, null);
                    \Illuminate\Support\Facades\Log::error(
                        "EncryptedSettings: failed to decrypt '{$path}' for {$model->getTable()} #{$model->getKey()}"
                    );
                }
            }
            // Plaintext legacy values pass through unchanged.
        }

        return $settings;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if (! is_array($value)) {
            $value = (array) $value;
        }

        foreach (self::SENSITIVE_PATHS as $path) {
            $current = Arr::get($value, $path);

            if (is_string($current) && $current !== '' && ! str_starts_with($current, self::PREFIX)) {
                Arr::set($value, $path, self::PREFIX.Crypt::encryptString($current));
            }
        }

        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
