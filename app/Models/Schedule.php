<?php

namespace App\Models;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Schedule extends Model
{
    use HasFactory, LogsActivity, \App\Traits\IdentifyTenant;

    protected $fillable = [
        'tenant_id',
        'course_id',
        'classroom_id',
        'location',
        'instructor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'max_students',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            $model->clearCache();
        });

        static::deleted(function ($model) {
            $model->clearCache();
        });
    }

    public function clearCache()
    {
        cache()->forget('schedules_' . $this->tenant_id);
    }

    public static function getCachedSchedules()
    {
        $tenantId = app('tenant')->id;
        return cache()->remember('schedules_' . $tenantId, now()->addDay(), function () use ($tenantId) {
            return self::where('tenant_id', $tenantId)
                ->with(['course', 'classroom', 'instructor'])
                ->get();
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['day_of_week', 'start_time', 'end_time', 'classroom_id', 'instructor_id'])
            ->logOnlyDirty();
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
