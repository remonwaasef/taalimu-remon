<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SiteSetting extends Model
{
    use LogsActivity;

    protected $fillable = ['key', 'value', 'group'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('setting');
    }

    /**
     * Get a setting value by key (with Redis Cache).
     */
    public static function get($key, $default = null)
    {
        return \Illuminate\Support\Facades\Cache::rememberForever("setting_{$key}", function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value (and clear cache).
     */
    public static function set($key, $value, $group = 'general')
    {
        $oldSetting = self::where('key', $key)->first();
        $oldValue = $oldSetting ? $oldSetting->value : null;

        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
        
        \Illuminate\Support\Facades\Cache::forget("setting_{$key}");

        // Security Alert for sensitive keys
        $sensitiveKeys = ['stripe_secret', 'stripe_key', 'stripe_webhook_secret', 'admin_email', 'maintenance_mode', 'currency_symbol'];
        if ($oldValue !== $value && (in_array($key, $sensitiveKeys) || $group === 'payment' || $group === 'security')) {
            try {
                if (auth()->check()) {
                    app(\App\Services\TelegramService::class)->sendSettingChangeAlert(auth()->user(), $key, $oldValue, $value);
                }
            } catch (\Throwable $e) {}
        }
        
        return $setting;
    }
}
