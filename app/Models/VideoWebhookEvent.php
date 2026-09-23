<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoWebhookEvent extends Model
{
    use HasFactory, \App\Traits\BelongsToTenant;

    protected $fillable = [
        'provider',
        'event_type',
        'provider_video_id',
        'payload',
        'status',
        'error_message',
        'processed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'processed_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSED = 'processed';
    public const STATUS_FAILED = 'failed';

    public const EVENT_CREATED = 'video.created';
    public const EVENT_ENCODED = 'video.encoded';
    public const EVENT_FAILED = 'video.failed';
    public const EVENT_DELETED = 'video.deleted';

    public function video()
    {
        return Video::where('provider_video_id', $this->provider_video_id)->first();
    }

    public function markProcessed(): void
    {
        $this->update([
            'status' => self::STATUS_PROCESSED,
            'processed_at' => now(),
        ]);
    }

    public function markFailed(string $error): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'error_message' => $error,
            'processed_at' => now(),
        ]);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
}