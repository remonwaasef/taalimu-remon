<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Stage extends Model
{
    use \App\Traits\ClearsDashboardCache, \App\Traits\IdentifyTenant, HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'order',
    ];

    /**
     * Get the translated stage name if it is a translation key.
     */
    public function getNameAttribute($value)
    {
        if (str_contains((string) $value, '::')) {
            $translated = __($value);

            return $translated !== $value ? $translated : $value;
        }

        return $value;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('academic_structure');
    }

    protected static function booted(): void
    {
        // Trait handle booted/static events
    }

    public static function clearCache()
    {
        $tenantId = app('tenant')->id ?? 0;
        cache()->forget("tenant_{$tenantId}_stages");

        // Use the trait's method for dashboard-wide clearing
        static::clearDashboardCache();
    }

    public static function getCached()
    {
        $tenantId = app('tenant')->id ?? 0;

        return cache()->remember("tenant_{$tenantId}_stages", 3600, function () {
            return static::with('grades')->orderBy('order')->get();
        });
    }

    public function grades()
    {
        return $this->hasMany(Grade::class)->orderBy('order');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
