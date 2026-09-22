<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineClassHandRaise extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'online_class_id',
        'student_id',
        'user_id',
        'status',
        'raised_at',
    ];

    protected $casts = [
        'raised_at' => 'datetime',
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
