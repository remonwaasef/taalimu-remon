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
                return static::fetchFromDb($key, $default, $tenantId);
            });
        } catch (\Throwable $e) {
            // Fallback to DB directly if cache fails (e.g., Redis down or serialization issue)
            \Illuminate\Support\Facades\Log::warning("Cache failure in SiteSetting::get({$key}): ".$e->getMessage());
            
            try {
                return static::fetchFromDb($key, $default, $tenantId);
            } catch (\Throwable $ex) {
                return $default;
            }
        }
    }

    /**
     * Helper to fetch setting from database with tenant fallback.
     */
    protected static function fetchFromDb($key, $default = null, $tenantId = null)
    {
        $hasTenantCol = \Illuminate\Support\Facades\Schema::hasColumn('site_settings', 'tenant_id');

        $query = self::where('key', $key);
        if ($hasTenantCol) {
            $query->where('tenant_id', $tenantId);
        }
        $setting = $query->first();

        // Fallback to global setting if tenant-specific setting does not exist
        if (!$setting && $tenantId !== null && $hasTenantCol) {
            $setting = self::where('key', $key)
                ->whereNull('tenant_id')
                ->first();
        }

        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value (and clear cache), supporting tenant override.
     */
    public static function set($key, $value, $group = 'general', $tenantId = null)
    {
        $tenantId = $tenantId ?? (app()->bound('tenant') ? app('tenant')->id : null);
        $cacheKey = $tenantId ? "tenant_{$tenantId}_setting_{$key}" : "setting_{$key}";
        $hasTenantCol = \Illuminate\Support\Facades\Schema::hasColumn('site_settings', 'tenant_id');

        $query = self::where('key', $key);
        if ($hasTenantCol) {
            $query->where('tenant_id', $tenantId);
        }
        $oldSetting = $query->first();
        $oldValue = $oldSetting ? $oldSetting->value : null;

        $matchAttributes = ['key' => $key];
        if ($hasTenantCol) {
            $matchAttributes['tenant_id'] = $tenantId;
        }

        $setting = self::updateOrCreate(
            $matchAttributes,
            ['value' => $value, 'group' => $group]
        );

        \Illuminate\Support\Facades\Cache::forget($cacheKey);

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
