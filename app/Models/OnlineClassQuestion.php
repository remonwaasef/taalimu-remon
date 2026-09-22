<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineClassQuestion extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'online_class_id',
        'user_id',
        'student_id',
        'question',
        'upvotes_count',
        'is_answered',
        'answered_at',
    ];

    protected $casts = [
        'is_answered' => 'boolean',
        'answered_at' => 'datetime',
        'upvotes_count' => 'integer',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function onlineClass()
    {
        return $this->belongsTo(OnlineClass::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
