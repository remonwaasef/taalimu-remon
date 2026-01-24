<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    use HasFactory, \App\Traits\IdentifyTenant;

    protected $fillable = [
        'tenant_id',
        'question_id',
        'content',
        'is_correct',
    ];


    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
