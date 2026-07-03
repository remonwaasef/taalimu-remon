<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineClass extends Model
{
    use \App\Traits\BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'instructor_id',
        'course_id',
        'title',
        'platform',
        'meeting_link',
        'meeting_id',
        'meeting_password',
        'start_time',
        'duration_minutes',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'meeting_password' => 'encrypted',
    ];

    /**
     * Get the tenant that owns the online class.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the instructor that owns the online class.
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the course that the online class belongs to.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
