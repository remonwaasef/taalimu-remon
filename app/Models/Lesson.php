<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory, \App\Traits\IdentifyTenant;

    protected $fillable = [
        'section_id',
        'tenant_id',
        'title',
        'content',
        'video_url',
        'duration',
        'is_preview',
        'sort_order',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }

    public function assignment()
    {
        return $this->hasOne(Assignment::class);
    }
}
