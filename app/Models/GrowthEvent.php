<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class GrowthEvent extends Model
{
    use \App\Traits\BelongsToTenant;

    public $timestamps = false;

    protected $fillable = [
        'eventable_type',
        'eventable_id',
        'event_name',
        'actor_type',
        'actor_id',
        'source',
        'campaign',
        'metadata',
        'created_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the parent model (PublicProfile, Course, etc.).
     */
    public function eventable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Record a growth event.
     */
    public static function record(
        string $eventName,
        Model $eventable,
        ?string $actorType = null,
        ?string $actorId = null,
        ?string $source = null,
        ?string $campaign = null,
        ?array $metadata = null
    ): static {
        return static::create([
            'tenant_id' => $eventable->tenant_id ?? app('tenant')->id,
            'eventable_type' => get_class($eventable),
            'eventable_id' => $eventable->id,
            'event_name' => $eventName,
            'actor_type' => $actorType,
            'actor_id' => $actorId,
            'source' => $source,
            'campaign' => $campaign,
            'metadata' => $metadata,
            'created_at' => now(),
        ]);
    }

    /**
     * Scope to a specific event name.
     */
    public function scopeNamed($query, string $eventName)
    {
        return $query->where('event_name', $eventName);
    }

    /**
     * Scope to a date range.
     */
    public function scopeBetween($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }
}
