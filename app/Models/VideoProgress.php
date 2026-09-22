<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoProgress extends Model
{
    use \App\Traits\BelongsToTenant;

    protected $fillable = [
        'recording_id',
        'user_id',
        'last_position_seconds',
        'watched_seconds',
        'completion_percentage',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function recording()
    {
        return $this->belongsTo(ClassRecording::class, 'recording_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Upsert playback progress for a user/recording pair.
     */
    public static function saveProgress(ClassRecording $recording, int $userId, int $positionSeconds, int $watchedDelta = 0): self
    {
        $duration = max(1, (int) ($recording->duration_seconds ?? 0));
        $position = max(0, $positionSeconds);
        $completion = min(100.0, round(($position / $duration) * 100, 2));
        $completedAt = $completion >= 95.0 ? now() : null;

        /** @var VideoProgress|null $progress */
        $progress = static::query()
            ->where('recording_id', $recording->id)
            ->where('user_id', $userId)
            ->first();

        if (! $progress) {
            return static::create([
                'tenant_id' => $recording->tenant_id,
                'recording_id' => $recording->id,
                'user_id' => $userId,
                'last_position_seconds' => $position,
                'watched_seconds' => max(0, $watchedDelta),
                'completion_percentage' => $completion,
                'completed_at' => $completedAt,
            ]);
        }

        $progress->fill([
            'last_position_seconds' => $position,
            'watched_seconds' => $progress->watched_seconds + max(0, $watchedDelta),
            'completion_percentage' => max($progress->completion_percentage, $completion),
            'completed_at' => $completedAt ?? $progress->completed_at,
        ]);
        $progress->save();

        return $progress;
    }
}
