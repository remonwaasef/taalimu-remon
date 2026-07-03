<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use \App\Traits\IdentifyTenant, HasFactory;

    protected $fillable = [
        'lesson_id',
        'tenant_id',
        'title',
        'description',
        'time_limit',
        'duration_minutes',
        'passing_score',
        'is_randomized',
        'random_questions_count',
    ];

    protected $casts = [
        'is_randomized' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(QuestionCategory::class, 'category_id');
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
