<?php

namespace App\Models;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Grade extends Model
{
    use HasFactory, LogsActivity, \App\Traits\IdentifyTenant;

    protected $fillable = [
        'tenant_id',
        'stage_id',
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
        static::saved(fn() => Stage::clearCache());
        static::deleted(fn() => Stage::clearCache());
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function students()
    {
        // This will be useful when we link students to this model
        return $this->hasMany(Student::class, 'grade_id');
    }
}
