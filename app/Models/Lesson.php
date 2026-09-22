<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use \App\Traits\BelongsToTenant, HasFactory;

    protected $fillable = [
        'section_id',
        'title',
        'content',
        'video_url',
        'duration',
        'is_preview',
        'sort_order',
    ];

    /**
     * Lesson content safe for raw ({!! !!}) output. Content is already
     * purified on write, but legacy rows may predate that, so purify on
     * read as well.
     */
    public function sanitizedContent(): string
    {
        return \Mews\Purifier\Facades\Purifier::clean((string) $this->content, 'lesson');
    }

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
