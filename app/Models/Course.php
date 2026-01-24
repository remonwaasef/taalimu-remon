<?php

namespace App\Models;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Course extends Model
{
    use HasFactory, LogsActivity, \App\Traits\IdentifyTenant;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'price', 'status'])
            ->logOnlyDirty();
    }

    protected $fillable = [
        'tenant_id',
        'instructor_id',
        'title',
        'description',
        'price',
        'sessions_count',
        'image',
        'status',
    ];

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
