<?php

namespace Modules\Center\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use \App\Traits\BelongsToTenant, \App\Traits\ClearsDashboardCache, HasFactory;

    protected $fillable = [
        'tenant_id',
        'student_id',
        'course_id',
        'schedule_id',
        'session_date',
        'check_in_time',
        'status',
        'late_minutes',
        'late_label',
    ];

    protected $casts = [
        'session_date' => 'date',
        'check_in_time' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(\App\Models\Student::class);
    }

    public function course()
    {
        return $this->belongsTo(\App\Models\Course::class);
    }

    public function schedule()
    {
        return $this->belongsTo(\App\Models\Schedule::class);
    }
}
