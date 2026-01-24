<?php

namespace Modules\Center\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\IdentifyTenant;

class Attendance extends Model
{
    use HasFactory, IdentifyTenant;

    protected $fillable = [
        'tenant_id',
        'student_id',
        'course_id',
        'schedule_id',
        'session_date',
        'check_in_time',
        'status',
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
