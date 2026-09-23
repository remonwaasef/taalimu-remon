<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoWatchEvent extends Model
{
    use HasFactory, \App\Traits\BelongsToTenant;

    protected $fillable = [
        'video_id',
        'user_id',
        'tenant_id',
        'session_id',
        'event',
        'position_seconds',
        'duration_seconds',
    ];

    protected $casts = [
        'position_seconds' => 'integer',
        'duration_seconds' => 'integer',
    ];

    public const EVENT_PLAY = 'play';
    public const EVENT_PAUSE = 'pause';
    public const EVENT_SEEK = 'seek';
    public const EVENT_HEARTBEAT = 'heartbeat';
    public const EVENT_COMPLETE = 'complete';

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function session()
    {
        return $this->belongsTo(VideoPlaybackSession::class, 'session_id');
    }

    public static function log(string $event, array $data): self
    {
        return self::create([
            'video_id' => $data['video_id'],
            'user_id' => $data['user_id'],
            'tenant_id' => $data['tenant_id'],
            'session_id' => $data['session_id'] ?? null,
            'event' => $event,
            'position_seconds' => $data['position_seconds'] ?? 0,
            'duration_seconds' => $data['duration_seconds'] ?? null,
        ]);
    }
}