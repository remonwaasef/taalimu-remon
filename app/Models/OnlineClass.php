<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineClass extends Model
{
    use \App\Traits\BelongsToTenant, HasFactory;

    protected $fillable = [
        'instructor_id',
        'course_id',
        'title',
        'description',
        'platform',
        'meeting_link',
        'meeting_id',
        'meeting_password',
        'start_time',
        'duration_minutes',
        'status',
        'access_mode',
        'recording_status',
        'zoom_meeting_uuid',
        'zoom_account_id',
        'auto_recording',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'meeting_password' => 'encrypted',
        'auto_recording' => 'boolean',
    ];

    public const STATUS_SCHEDULED = 'scheduled';

    public const STATUS_LIVE = 'in_progress';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    protected static function booted(): void
    {
        static::creating(function (self $class) {
            $class->uuid ??= (string) \Illuminate\Support\Str::uuid();
        });
    }

    /**
     * Get the tenant that owns the online class.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * The instructor profile that owns the class. The `instructor_id` column
     * stores the id of the `instructors` profile row (matching the FK added by
     * 2026_07_07_000009), not the users table.
     */
    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

    /**
     * Get the course that the online class belongs to.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function participants()
    {
        return $this->hasMany(OnlineClassParticipant::class);
    }

    public function recordings()
    {
        return $this->hasMany(ClassRecording::class);
    }

    public function messages()
    {
        return $this->hasMany(OnlineClassMessage::class)->latest();
    }

    public function questions()
    {
        return $this->hasMany(OnlineClassQuestion::class)->orderByDesc('upvotes_count')->latest();
    }

    public function handRaises()
    {
        return $this->hasMany(OnlineClassHandRaise::class)->where('status', 'raised')->latest();
    }

    public function readyRecording()
    {
        return $this->hasOne(ClassRecording::class)->where('status', ClassRecording::STATUS_READY)->latestOfMany();
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_SCHEDULED)
            ->where('start_time', '>=', now()->subMinutes(15));
    }

    public function scopeLive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_LIVE);
    }

    /**
     * Whether the scheduled window is currently active (may start now).
     */
    public function isJoinableNow(): bool
    {
        if (! $this->start_time) {
            return false;
        }

        $endsAt = $this->start_time->copy()->addMinutes($this->duration_minutes + 15);

        return now()->between($this->start_time->copy()->subMinutes(15), $endsAt);
    }

    /**
     * Users allowed to join / watch: enrolled users of the course, or the
     * explicit participant allowlist.
     */
    public function allowedUserIds(): \Illuminate\Support\Collection
    {
        if ($this->access_mode === 'selected') {
            return OnlineClassParticipant::where('online_class_id', $this->id)
                ->pluck('user_id')
                ->filter();
        }

        return Enrollment::where('course_id', $this->course_id)
            ->where('status', 'active')
            ->where('tenant_id', $this->tenant_id)
            ->pluck('user_id');
    }

    public function hasAccess(User $user): bool
    {
        // Center admins and the owning instructor always have access.
        if ($user->hasAnyRole(['center_admin', 'admin'])) {
            return true;
        }

        if ($user->instructor && (int) $user->instructor->id === (int) $this->instructor_id) {
            return true;
        }

        return $this->allowedUserIds()->contains((int) $user->id);
    }
}
