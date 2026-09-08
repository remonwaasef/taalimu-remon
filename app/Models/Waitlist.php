<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waitlist extends Model
{
    use \App\Traits\BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'course_id',
        'user_id',
        'name',
        'phone',
        'email',
        'position',
        'status',
        'source',
        'notified_at',
        'expires_at',
    ];

    protected $casts = [
        'notified_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForCourse($query, int $courseId)
    {
        return $query->where('course_id', $courseId);
    }
}
