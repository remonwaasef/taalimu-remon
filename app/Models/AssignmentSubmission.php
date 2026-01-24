<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    use HasFactory, \App\Traits\IdentifyTenant;

    protected $fillable = [
        'tenant_id',
        'assignment_id',
        'user_id',
        'file_path',
        'grade',
        'feedback',
        'submitted_at',
    ];


    protected $casts = [
        'user_id' => 'integer',
        'assignment_id' => 'integer',
        'submitted_at' => 'datetime',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
