<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

// Added for Atomic Counters

class Course extends Model
{
    use \App\Traits\BelongsToTenant, \App\Traits\ClearsDashboardCache, HasFactory, LogsActivity, Searchable, SoftDeletes;

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
     * Determine the data sent to the search index.
     *
     * `tenant_id` is intentionally included so every search can be
     * hard-filtered by tenant, guaranteeing cross-tenant isolation.
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => (int) $this->tenant_id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => (int) ($this->created_at?->timestamp ?? 0),
        ];
    }

    /**
     * Get the public registration URL for this course/group
     */
    public function getRegistrationUrl()
    {
        if (! $this->registration_token) {
            return null;
        }

        $params = ['token' => $this->registration_token];

        // The "group.register" route lives on the tenant subdomain; resolve the
        // tenant domain explicitly so the URL also works outside that context
        // (e.g. generated from the main domain or from queued jobs).
        $tenant = app()->bound('tenant') ? app('tenant') : ($this->tenant_id ? Tenant::find($this->tenant_id) : null);
        if ($tenant && ! isset($params['tenant'])) {
            $params['tenant'] = $tenant->domain;
        }

        return route('group.register', $params);
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
