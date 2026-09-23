<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use \App\Traits\BelongsToTenant, HasFactory, SoftDeletes;

    protected $fillable = [
        'lesson_id',
        'provider',
        'provider_library_id',
        'provider_video_id',
        'title',
        'status',
        'duration_seconds',
        'width',
        'height',
        'storage_size_bytes',
        'encode_progress',
        'thumbnail_url',
        'uploaded_at',
        'processed_at',
        'failed_at',
        'error_message',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'storage_size_bytes' => 'integer',
        'encode_progress' => 'integer',
        'uploaded_at' => 'datetime',
        'processed_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_UPLOADING = 'uploading';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_READY = 'ready';
    public const STATUS_FAILED = 'failed';
    public const STATUS_DELETED = 'deleted';

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function playbackSessions()
    {
        return $this->hasMany(VideoPlaybackSession::class);
    }

    public function watchEvents()
    {
        return $this->hasMany(VideoWatchEvent::class);
    }

    public function webhookEvents()
    {
        return $this->hasMany(VideoWebhookEvent::class, 'provider_video_id', 'provider_video_id');
    }

    public function isReady(): bool
    {
        return $this->status === self::STATUS_READY;
    }

    public function isProcessing(): bool
    {
        return in_array($this->status, [self::STATUS_UPLOADING, self::STATUS_PROCESSING], true);
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function markUploading(): void
    {
        $this->update(['status' => self::STATUS_UPLOADING, 'uploaded_at' => now()]);
    }

    public function markProcessing(): void
    {
        $this->update(['status' => self::STATUS_PROCESSING]);
    }

    public function markReady(array $metadata = []): void
    {
        $this->update(array_merge([
            'status' => self::STATUS_READY,
            'processed_at' => now(),
            'duration_seconds' => $metadata['duration'] ?? null,
            'width' => $metadata['width'] ?? null,
            'height' => $metadata['height'] ?? null,
            'storage_size_bytes' => $metadata['size'] ?? null,
            'thumbnail_url' => $metadata['thumbnail'] ?? null,
            'encode_progress' => 100,
        ], $metadata));
    }

    public function markFailed(string $error): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'failed_at' => now(),
            'error_message' => $error,
        ]);
    }

    public function scopeReady($query)
    {
        return $query->where('status', self::STATUS_READY);
    }

    public function scopeProcessing($query)
    {
        return $query->whereIn('status', [self::STATUS_UPLOADING, self::STATUS_PROCESSING]);
    }
}