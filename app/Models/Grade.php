<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Grade extends Model
{
    use \App\Traits\BelongsToTenant, HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
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
        static::saved(fn () => Stage::clearCache());
        static::deleted(fn () => Stage::clearCache());
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
