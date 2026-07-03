<?php

namespace App\Models;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class OperationIssue extends Model
{
    use \App\Traits\BelongsToTenant, HasFactory, SoftDeletes;

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

    // ==================== Actions & Helpers ====================
    // Logic delegated to OperationIssueService to promote single responsibility principle.

    public function getSeverityColorAttribute(): string
    {
        return match ($this->severity) {
            'critical' => 'red',
            'high' => 'orange',
            'medium' => 'yellow',
            'low' => 'blue',
            default => 'gray',
        };
    }

    public function getSeverityBadgeClassAttribute(): string
    {
        return match ($this->severity) {
            'critical' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            'high' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
            'medium' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'low' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
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
        if (! $this->resolved_at) {
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

        // Note: bootBelongsToTenant is booted automatically by Laravel's bootTraits() mechanism
        static::creating(function ($issue) {
            if (empty($issue->uuid)) {
                $issue->uuid = (string) Str::uuid();
            }
        });
    }
}
