<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OpportunityGroup extends Model
{
    use HasFactory, \App\Traits\BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'opportunity_id',
        'course_id',
        'student_count',
        'status',
    ];

    protected $casts = [
        'student_count' => 'integer',
    ];

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function campaignKey(): string
    {
        return 'opportunity:' . $this->opportunity_id;
    }

    public function enrollments(): HasMany
    {
        // Enrollments are keyed by (tenant, user, course) with Phase 3 attribution
        // stored in source/campaign (see GroupFormationService). The enrollments
        // table has no opportunity_id/student_id columns.
        //
        // NOTE: only static constraints are allowed here. Laravel builds eager-load
        // queries from a NEW EMPTY model instance, so any $this->attribute based
        // where() would bind NULL under load()/with(). Group scoping by campaign
        // therefore happens in groupEnrollments() (in-memory) and in explicit
        // lazy queries built on a persisted instance.
        return $this->hasMany(Enrollment::class, 'course_id', 'course_id')
            ->where('source', \App\Services\GroupFormationService::ATTRIBUTION_SOURCE);
    }

    /**
     * Enrollments belonging to THIS group (same course + this group's campaign).
     */
    public function groupEnrollments(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->enrollments
            ->where('campaign', $this->campaignKey())
            ->values();
    }

    public function groupEnrollmentsQuery(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        // Lazy query on a persisted instance: $this->campaignKey() is safe here.
        return $this->enrollments()->where('campaign', $this->campaignKey());
    }

    public function students(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        // Students link to enrollments through users (students.user_id = enrollments.user_id).
        // Campaign scoping is applied in memory via groupStudents() for the reason above.
        return $this->belongsToMany(
            Student::class,
            'enrollments',
            'course_id',
            'user_id',
            'course_id',
            'user_id'
        )
            ->wherePivot('source', \App\Services\GroupFormationService::ATTRIBUTION_SOURCE)
            ->wherePivot('status', 'active')
            ->withPivot(['enrolled_at', 'status', 'campaign'])
            ->withTimestamps();
    }

    /**
     * Students belonging to THIS group.
     */
    public function groupStudents(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->students
            ->filter(fn ($student) => $student->pivot?->campaign === $this->campaignKey())
            ->values();
    }

    public function scopeForming($query)
    {
        return $query->where('status', 'forming');
    }

    public function scopeFilled($query)
    {
        return $query->where('status', 'filled');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['forming', 'filled']);
    }

    public function isForming(): bool
    {
        return $this->status === 'forming';
    }

    public function isFilled(): bool
    {
        return $this->status === 'filled';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function getFillPercentage(): float
    {
        $capacity = $this->course?->capacity ?? 0;
        if ($capacity === 0) {
            return 0;
        }

        return round(($this->student_count / $capacity) * 100, 1);
    }

    public function getAvailableSeats(): int
    {
        $capacity = $this->course?->capacity ?? 0;
        return max(0, $capacity - $this->student_count);
    }

    public function isFull(): bool
    {
        return $this->getAvailableSeats() === 0;
    }

    public function canAddStudent(): bool
    {
        return $this->isForming() && !$this->isFull();
    }
}