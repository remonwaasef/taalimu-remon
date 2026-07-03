<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use \App\Traits\BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'course_id',
        'enrolled_at',
        'status',
        'progress',
        'remaining_sessions',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class, 'course_id', 'course_id')
            ->join('students', 'students.id', '=', 'certificates.student_id')
            ->whereColumn('students.user_id', 'enrollments.user_id');
    }
}
