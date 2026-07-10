<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SiteSetting extends Model
{
    use LogsActivity;

    protected $fillable = ['tenant_id', 'key', 'value', 'group'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('setting');
    }

    /**
     * Get a setting value by key (with Redis Cache), supporting tenant override.
     */
    public static function get($key, $default = null, $tenantId = null)
    {
        $tenantId = $tenantId ?? (app()->bound('tenant') ? app('tenant')->id : null);
        $cacheKey = $tenantId ? "tenant_{$tenantId}_setting_{$key}" : "setting_{$key}";

        try {
            return \Illuminate\Support\Facades\Cache::rememberForever($cacheKey, function () use ($key, $default, $tenantId) {
                $setting = self::where('key', $key)
                    ->where('tenant_id', $tenantId)
                    ->first();

                // Fallback to global setting if tenant-specific setting does not exist
                if (! $setting && $tenantId !== null) {
                    $setting = self::where('key', $key)
                        ->whereNull('tenant_id')
                        ->first();
                }

                return $setting ? $setting->value : $default;
            });
        } catch (\Throwable $e) {
            // Fallback to DB if cache fails (e.g., file permissions or Redis down)
            \Illuminate\Support\Facades\Log::warning("Cache failure in SiteSetting::get({$key}): ".$e->getMessage());

            $setting = self::where('key', $key)
                ->where('tenant_id', $tenantId)
                ->first();

            if (! $setting && $tenantId !== null) {
                $setting = self::where('key', $key)
                    ->whereNull('tenant_id')
                    ->first();
            }

            return $setting ? $setting->value : $default;
        }
    }

    /**
     * Set a setting value (and clear cache), supporting tenant override.
     */
    public static function set($key, $value, $group = 'general', $tenantId = null)
    {
        $tenantId = $tenantId ?? (app()->bound('tenant') ? app('tenant')->id : null);
        $cacheKey = $tenantId ? "tenant_{$tenantId}_setting_{$key}" : "setting_{$key}";

        $oldSetting = self::where('key', $key)->where('tenant_id', $tenantId)->first();
        $oldValue = $oldSetting ? $oldSetting->value : null;

        $setting = self::updateOrCreate(
            ['key' => $key, 'tenant_id' => $tenantId],
            ['value' => $value, 'group' => $group]
        );

        \Illuminate\Support\Facades\Cache::forget($cacheKey);
        \Illuminate\Support\Facades\Cache::forget('site_settings_all');

        // Security Alert for sensitive keys
        $sensitiveKeys = ['stripe_secret', 'stripe_key', 'stripe_webhook_secret', 'admin_email', 'maintenance_mode', 'currency_symbol'];
        if ($oldValue !== $value && (in_array($key, $sensitiveKeys) || $group === 'payment' || $group === 'security')) {
            try {
                if (auth()->check()) {
                    app(\App\Services\TelegramService::class)->sendSettingChangeAlert(auth()->user(), $key, $oldValue, $value);
                }
            } catch (\Throwable $e) {
            }
        }

        return $setting;
    }
}
