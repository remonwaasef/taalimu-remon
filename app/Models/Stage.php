<?php

namespace App\Models;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Stage extends Model
{
    use HasFactory, LogsActivity, \App\Traits\IdentifyTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'order',
    ];

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
        static::saved(fn() => static::clearCache());
        static::deleted(fn() => static::clearCache());
    }

    public static function clearCache()
    {
        $tenantId = app('tenant')->id ?? 0;
        cache()->forget("tenant_{$tenantId}_stages");
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
