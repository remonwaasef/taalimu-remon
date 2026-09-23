<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VideoPlaybackSession extends Model
{
    use HasFactory, \App\Traits\BelongsToTenant;

    protected $fillable = [
        'video_id',
        'user_id',
        'tenant_id',
        'session_token_hash',
        'ip_address',
        'user_agent',
        'started_at',
        'last_seen_at',
        'expires_at',
        'ended_at',
        'revoked_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'expires_at' => 'datetime',
        'ended_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public const TTL_SECONDS = 300; // 5 minutes

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

    public function watchEvents()
    {
        return $this->hasMany(VideoWatchEvent::class, 'session_id');
    }

    public static function generateToken(): string
    {
        return Str::random(64);
    }

    public static function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isRevoked(): bool
    {
        return $this->revoked_at !== null;
    }

    public function isValid(): bool
    {
        return ! $this->isExpired() && ! $this->isRevoked();
    }

    public function refreshLastSeen(): void
    {
        $this->update(['last_seen_at' => now()]);
    }

    public function end(): void
    {
        $this->update(['ended_at' => now()]);
    }

    public function revoke(): void
    {
        $this->update(['revoked_at' => now()]);
    }

    public static function cleanupExpired(): int
    {
        return self::where('expires_at', '<', now())
            ->whereNull('revoked_at')
            ->whereNull('ended_at')
            ->delete();
    }
}