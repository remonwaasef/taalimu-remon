<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoAccessLog extends Model
{
    use \App\Traits\BelongsToTenant;

    public $timestamps = false;

    protected $fillable = [
        'recording_id',
        'user_id',
        'action',
        'ip_hash',
        'user_agent_hash',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Record an access event. Only hashed IP / user agent are persisted
     * (privacy-first logging, see spec §17).
     */
    public static function record(
        ClassRecording $recording,
        ?int $userId,
        string $action,
        array $metadata = []
    ): self {
        return static::create([
            'tenant_id' => $recording->tenant_id,
            'recording_id' => $recording->id,
            'user_id' => $userId,
            'action' => $action,
            'ip_hash' => hash('sha256', request()?->ip() ?? 'unknown'),
            'user_agent_hash' => hash('sha256', substr((string) request()?->userAgent(), 0, 512)),
            'metadata' => $metadata ?: null,
            'created_at' => now(),
        ]);
    }
}
