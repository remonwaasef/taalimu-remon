<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRecording extends Model
{
    use \App\Traits\BelongsToTenant;

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_READY = 'ready';

    public const STATUS_FAILED = 'failed';

    public const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'online_class_id',
        'provider',
        'external_recording_id',
        'storage_provider',
        'storage_key',
        'file_size',
        'duration_seconds',
        'status',
        'available_at',
        'expires_at',
    ];

    protected $casts = [
        'available_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $recording) {
            $recording->uuid ??= (string) \Illuminate\Support\Str::uuid();
        });
    }

    public function onlineClass()
    {
        return $this->belongsTo(OnlineClass::class);
    }

    public function accessLogs()
    {
        return $this->hasMany(VideoAccessLog::class, 'recording_id');
    }

    public function progress()
    {
        return $this->hasMany(VideoProgress::class, 'recording_id');
    }

    /**
     * Progress of a specific user (for resume playback).
     */
    public function progressFor(int $userId): ?VideoProgress
    {
        /** @var VideoProgress|null $progress */
        $progress = $this->progress()->where('user_id', $userId)->first();

        return $progress;
    }
}
