<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Carbon\CarbonInterval;

class OperationIssue extends Model
{
    use HasFactory, SoftDeletes, \App\Traits\IdentifyTenant;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'user_id',
        'user_agent',
        'ip_address',
        'title',
        'action',
        'url',
        'method',
        'message',
        'payload',
        'context',
        'stack_trace',
        'severity',
        'category',
        'exception_class',
        'exception_code',
        'file_path',
        'line_number',
        'fingerprint',
        'occurrence_count',
        'last_occurrence_at',
        'status',
        'assigned_to',
        'priority',
        'acknowledged_at',
        'first_response_at',
        'resolved_at',
        'resolution_time_minutes',
        'sla_breached',
        'resolution_notes',
        'resolution_type',
        'resolved_by',
        'tags',
        'is_muted',
        'is_recurring',
    ];

    protected $casts = [
        'payload' => 'array',
        'context' => 'array',
        'tags' => 'array',
        'last_occurrence_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'first_response_at' => 'datetime',
        'resolved_at' => 'datetime',
        'sla_breached' => 'boolean',
        'is_muted' => 'boolean',
        'is_recurring' => 'boolean',
    ];

    // ==================== Relationships ====================

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function timeline(): HasMany
    {
        return $this->hasMany(IssueTimeline::class, 'issue_id')->orderBy('created_at', 'desc');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(IssueAttachment::class, 'issue_id');
    }

    // ==================== Scopes ====================

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['new', 'acknowledged', 'in_progress']);
    }

    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeUnresolved($query)
    {
        return $query->whereNotIn('status', ['resolved', 'closed', 'wont_fix']);
    }

    public function scopeSlaBreached($query)
    {
        return $query->where('sla_breached', true);
    }

    public function scopeRecurring($query)
    {
        return $query->where('is_recurring', true);
    }

    public function scopeNotMuted($query)
    {
        return $query->where('is_muted', false);
    }

    // ==================== Actions ====================

    public function acknowledge(User $user): void
    {
        $oldStatus = $this->status;
        
        $this->update([
            'status' => 'acknowledged',
            'acknowledged_at' => now(),
            'first_response_at' => $this->first_response_at ?? now(),
        ]);

        $this->recordTimeline('status_changed', $user, [
            'old_value' => ['status' => $oldStatus],
            'new_value' => ['status' => 'acknowledged'],
        ]);
    }

    public function assignTo(User $assignee, User $assignedBy): void
    {
        $oldAssignee = $this->assigned_to;
        
        $this->update([
            'assigned_to' => $assignee->id,
            'status' => $this->status === 'new' ? 'acknowledged' : $this->status,
            'first_response_at' => $this->first_response_at ?? now(),
        ]);

        $this->recordTimeline('assigned', $assignedBy, [
            'old_value' => ['assigned_to' => $oldAssignee],
            'new_value' => ['assigned_to' => $assignee->id, 'name' => $assignee->name],
        ]);
    }

    public function updateStatus(string $status, User $user, ?string $comment = null): void
    {
        $oldStatus = $this->status;
        
        $updateData = ['status' => $status];
        
        if ($status === 'in_progress' && !$this->first_response_at) {
            $updateData['first_response_at'] = now();
        }
        
        $this->update($updateData);

        $this->recordTimeline('status_changed', $user, [
            'old_value' => ['status' => $oldStatus],
            'new_value' => ['status' => $status],
            'comment' => $comment,
        ]);
    }

    public function resolve(string $type, string $notes, User $user): void
    {
        $oldStatus = $this->status;
        $resolvedAt = now();
        $resolutionMinutes = $this->created_at->diffInMinutes($resolvedAt);
        
        $this->update([
            'status' => 'resolved',
            'resolution_type' => $type,
            'resolution_notes' => $notes,
            'resolved_by' => $user->id,
            'resolved_at' => $resolvedAt,
            'resolution_time_minutes' => $resolutionMinutes,
        ]);

        $this->recordTimeline('resolved', $user, [
            'old_value' => ['status' => $oldStatus],
            'new_value' => [
                'status' => 'resolved',
                'resolution_type' => $type,
                'resolution_time_minutes' => $resolutionMinutes,
            ],
            'comment' => $notes,
        ]);
    }

    public function addComment(string $comment, User $user): IssueTimeline
    {
        return $this->recordTimeline('commented', $user, [
            'comment' => $comment,
        ]);
    }

    public function mute(): void
    {
        $this->update(['is_muted' => true]);
        
        if (auth()->check()) {
            $this->recordTimeline('muted', auth()->user());
        }
    }

    public function unmute(): void
    {
        $this->update(['is_muted' => false]);
        
        if (auth()->check()) {
            $this->recordTimeline('unmuted', auth()->user());
        }
    }

    public function mergeWith(OperationIssue $target): void
    {
        // Move timeline entries to target
        $this->timeline()->update(['issue_id' => $target->id]);
        
        // Increment occurrence count
        $target->increment('occurrence_count', $this->occurrence_count);
        $target->update([
            'last_occurrence_at' => max($this->last_occurrence_at ?? $this->created_at, $target->last_occurrence_at ?? $target->created_at),
            'is_recurring' => true,
        ]);
        
        // Soft delete this issue
        $this->delete();
    }

    public function incrementOccurrence(): void
    {
        $this->increment('occurrence_count');
        $this->update([
            'last_occurrence_at' => now(),
            'is_recurring' => $this->occurrence_count > 1,
        ]);
    }

    // ==================== Helpers ====================

    protected function recordTimeline(string $type, ?User $user, array $data = []): IssueTimeline
    {
        return $this->timeline()->create([
            'user_id' => $user?->id,
            'type' => $type,
            'old_value' => $data['old_value'] ?? null,
            'new_value' => $data['new_value'] ?? null,
            'comment' => $data['comment'] ?? null,
        ]);
    }

    public function getSeverityColorAttribute(): string
    {
        return match($this->severity) {
            'critical' => 'red',
            'high' => 'orange',
            'medium' => 'yellow',
            'low' => 'blue',
            default => 'gray',
        };
    }

    public function getSeverityBadgeClassAttribute(): string
    {
        return match($this->severity) {
            'critical' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            'high' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
            'medium' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'low' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'new' => 'bg-red-100 text-red-800',
            'acknowledged' => 'bg-yellow-100 text-yellow-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'resolved' => 'bg-green-100 text-green-800',
            'closed' => 'bg-gray-100 text-gray-800',
            'wont_fix' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function isOverdue(): bool
    {
        if ($this->status === 'resolved' || $this->status === 'closed') {
            return false;
        }

        $slaConfig = config("issues.sla.{$this->severity}", ['resolution' => 1440]);
        $slaMinutes = $slaConfig['resolution'];
        
        return $this->created_at->addMinutes($slaMinutes)->isPast();
    }

    public function timeToResolve(): ?CarbonInterval
    {
        if (!$this->resolved_at) {
            return null;
        }

        return $this->created_at->diffAsCarbonInterval($this->resolved_at);
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    // ==================== Static ====================

    public static function generateFingerprint(\Throwable $e): string
    {
        $signature = implode('|', [
            get_class($e),
            $e->getFile(),
            $e->getLine(),
            substr($e->getMessage(), 0, 100),
        ]);

        return md5($signature);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($issue) {
            if (empty($issue->uuid)) {
                $issue->uuid = (string) Str::uuid();
            }
        });
    }
}
