<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineClassParticipant extends Model
{
    use \App\Traits\BelongsToTenant;

    protected $fillable = [
        'online_class_id',
        'student_id',
        'user_id',
        'joined_at',
        'left_at',
        'attendance_minutes',
        'status',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
    ];

    public function onlineClass()
    {
        return $this->belongsTo(OnlineClass::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
