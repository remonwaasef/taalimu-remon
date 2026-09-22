<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use \App\Traits\BelongsToTenant, HasFactory;

    protected $fillable = [
        'quiz_id',
        'category_id',
        'content',
        'explanation',
        'type',
        'points',
        'difficulty',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function category()
    {
        return $this->belongsTo(QuestionCategory::class);
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }
}
