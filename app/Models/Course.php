<?php

namespace App\Models;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\Tenant; // Added for Atomic Counters

class Course extends Model
{
    use HasFactory, LogsActivity, SoftDeletes, \App\Traits\IdentifyTenant, \App\Traits\ClearsDashboardCache;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (empty($course->registration_token)) {
                $course->registration_token = \Illuminate\Support\Str::random(16);
            }
        });

        static::created(function ($course) {
            if ($course->tenant_id) {
                $tenant = app()->bound('tenant') ? app('tenant') : Tenant::find($course->tenant_id);
                if ($tenant && $tenant->id == $course->tenant_id) {
                    app(\App\Services\SubscriptionService::class)->incrementUsage($tenant, 'max_courses');
                }
            }
        });

        static::deleted(function ($course) {
            if ($course->tenant_id) {
                $tenant = app()->bound('tenant') ? app('tenant') : Tenant::find($course->tenant_id);
                if ($tenant && $tenant->id == $course->tenant_id) {
                    app(\App\Services\SubscriptionService::class)->decrementUsage($tenant, 'max_courses');
                }
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        $options = LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();

        if (config('app.performance_mode')) {
            $options->disableLogging();
        }

        return $options;
    }

    protected $fillable = [
        'tenant_id',
        'instructor_id',
        'title',
        'description',
        'price',
        'sessions_count',
        'image',
        'registration_token',
        'status',
    ];

    /**
     * Get the public registration URL for this course/group
     */
    public function getRegistrationUrl()
    {
        if (!$this->registration_token) {
            return null;
        }

        return route('group.register', ['token' => $this->registration_token]);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function instructors()
    {
        return $this->belongsToMany(Instructor::class, 'course_instructor')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function coInstructors()
    {
        return $this->instructors()->wherePivot('role', 'co-instructor');
    }

    public function graders()
    {
        return $this->instructors()->wherePivot('role', 'grader');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('sort_order');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function resources()
    {
        return $this->hasMany(CourseResource::class);
    }
}
