<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Opportunity extends Model
{
    use HasFactory, SoftDeletes, \App\Traits\BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'demand_aggregation_id',
        'title',
        'subject',
        'level',
        'description',
        'explanation',
        'demand_volume',
        'score',
        'score_breakdown',
        'metadata',
        'status',
        'matched_teacher_id',
        'matched_course_id',
        'matched_at',
        'group_formed_at',
        'filled_at',
        'expires_at',
        'cancelled_at',
    ];

    protected $casts = [
        'score_breakdown' => 'array',
        'metadata' => 'array',
        'matched_at' => 'datetime',
        'group_formed_at' => 'datetime',
        'filled_at' => 'datetime',
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function demandAggregation(): BelongsTo
    {
        return $this->belongsTo(DemandAggregation::class);
    }

    public function matchedTeacher(): BelongsTo
    {
        return $this->belongsTo(Instructor::class, 'matched_teacher_id');
    }

    public function matchedCourse(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'matched_course_id');
    }

    /**
     * Formation scaffolding for this opportunity.
     *
     * DELETION CONTRACT: groups are pipeline scaffolding and are removed with
     * the opportunity (FK cascade), but enrollments are durable course-level
     * financial records and MUST survive: students stay enrolled, course
     * counters stay intact, and demand attributions keep their
     * demand+enrollment links (opportunity_id nulls via nullOnDelete).
     * See test_opportunity_deletion_preserves_enrollments.
     */
    public function groups()
    {
        return $this->hasMany(OpportunityGroup::class);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeMatched($query)
    {
        return $query->where('status', 'matched');
    }

    public function scopeGroupForming($query)
    {
        return $query->where('status', 'group_forming');
    }

    public function scopeGroupFormed($query)
    {
        return $query->where('status', 'group_formed');
    }

    public function scopeFilled($query)
    {
        return $query->where('status', 'filled');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['open', 'matched', 'group_forming', 'group_formed']);
    }

    public function scopeForTenant($query, int $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isMatched(): bool
    {
        return $this->status === 'matched';
    }

    public function isGroupForming(): bool
    {
        return $this->status === 'group_forming';
    }

    public function isGroupFormed(): bool
    {
        return $this->status === 'group_formed';
    }

    public function isFilled(): bool
    {
        return $this->status === 'filled';
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['open', 'matched', 'group_forming', 'group_formed']);
    }

    public function canBeMatched(): bool
    {
        return $this->status === 'open';
    }

    public function canFormGroup(): bool
    {
        return $this->status === 'matched';
    }

    public function markAsMatched(int $teacherId, ?int $courseId = null): void
    {
        if (!$this->canBeMatched()) {
            throw new \InvalidArgumentException('Opportunity cannot be matched in current state');
        }

        $this->update([
            'status' => 'matched',
            'matched_teacher_id' => $teacherId,
            'matched_course_id' => $courseId,
            'matched_at' => now(),
        ]);
    }

    public function startGroupFormation(): void
    {
        if (!$this->canFormGroup()) {
            throw new \InvalidArgumentException('Opportunity cannot form group in current state');
        }

        $this->update([
            'status' => 'group_forming',
        ]);
    }

    public function completeGroupFormation(): void
    {
        if ($this->status !== 'group_forming') {
            throw new \InvalidArgumentException('Opportunity cannot complete group formation in current state');
        }

        $this->update([
            'status' => 'group_formed',
            'group_formed_at' => now(),
        ]);
    }

    public function markAsFilled(): void
    {
        if ($this->status !== 'group_formed') {
            throw new \InvalidArgumentException('Opportunity cannot be filled in current state');
        }

        $this->update([
            'status' => 'filled',
            'filled_at' => now(),
        ]);
    }

    public function expire(): void
    {
        if (!$this->isActive()) {
            throw new \InvalidArgumentException('Opportunity cannot expire in current state');
        }

        $this->update([
            'status' => 'expired',
        ]);
    }

    public function cancel(): void
    {
        if (!$this->isActive()) {
            throw new \InvalidArgumentException('Opportunity cannot be cancelled in current state');
        }

        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
    }

    public function getExplanation(): string
    {
        return $this->explanation ?? $this->generateDefaultExplanation();
    }

    protected function generateDefaultExplanation(): string
    {
        $parts = [];

        if ($this->metadata['demand_volume'] ?? 0 >= 10) {
            $parts[] = 'High demand';
        } elseif (($this->metadata['demand_volume'] ?? 0) >= 5) {
            $parts[] = 'Moderate demand';
        }

        if (($this->metadata['available_capacity'] ?? 0) > 0) {
            $parts[] = 'available capacity';
        }

        if (($this->metadata['conversion_rate'] ?? 0) > 30) {
            $parts[] = 'strong conversion history';
        }

        return implode(' + ', $parts) . '.';
    }
}