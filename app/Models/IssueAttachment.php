<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class IssueAttachment extends Model
{
    use \App\Traits\IdentifyTenant;
    use HasFactory;

    protected $fillable = [
        'issue_id',
        'user_id',
        'filename',
        'original_filename',
        'mime_type',
        'size',
        'path',
        'description',
    ];

    protected $appends = ['url', 'size_formatted'];

    // ==================== Relationships ====================

    public function issue(): BelongsTo
    {
        return $this->belongsTo(OperationIssue::class, 'issue_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ==================== Accessors ====================

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    public function getSizeFormattedAttribute(): string
    {
        $bytes = $this->size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2).' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' KB';
        }

        return $bytes.' bytes';
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    // ==================== Boot ====================

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($attachment) {
            Storage::disk('public')->delete($attachment->path);
        });
    }
}
