<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseResource extends Model
{
    use \App\Traits\IdentifyTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'course_id',
        'lesson_id',
        'title',
        'file_path',
        'file_type',
        'file_size',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
