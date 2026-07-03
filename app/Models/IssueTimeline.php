<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssueTimeline extends Model
{
    use \App\Traits\BelongsToTenant;
    use HasFactory;

    protected $table = 'issue_timeline';

    protected $fillable = [
        'issue_id',
        'user_id',
        'type',
        'old_value',
        'new_value',
        'comment',
    ];

    protected $casts = [
        'old_value' => 'array',
        'new_value' => 'array',
    ];

    // ==================== Relationships ====================

    public function issue(): BelongsTo
    {
        return $this->belongsTo(OperationIssue::class, 'issue_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ==================== Helpers ====================

    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'created' => '🆕',
            'status_changed' => '🔄',
            'assigned' => '👤',
            'commented' => '💬',
            'priority_changed' => '⚡',
            'severity_changed' => '🎚️',
            'merged' => '🔗',
            'attachment_added' => '📎',
            'resolved' => '✅',
            'reopened' => '🔓',
            'muted' => '🔇',
            'unmuted' => '🔔',
            default => '📝',
        };
    }

    public function getTypeTextAttribute(): string
    {
        return match ($this->type) {
            'created' => __('Issue created'),
            'status_changed' => __('Status changed'),
            'assigned' => __('Assigned to'),
            'commented' => __('Comment added'),
            'priority_changed' => __('Priority changed'),
            'severity_changed' => __('Severity changed'),
            'merged' => __('Merged with another issue'),
            'attachment_added' => __('Attachment added'),
            'resolved' => __('Issue resolved'),
            'reopened' => __('Issue reopened'),
            'muted' => __('Issue muted'),
            'unmuted' => __('Issue unmuted'),
            default => __('Activity recorded'),
        };
    }

    public function getDescriptionAttribute(): string
    {
        $userName = $this->user?->name ?? __('System');

        return match ($this->type) {
            'status_changed' => sprintf(
                '%s changed status from "%s" to "%s"',
                $userName,
                $this->old_value['status'] ?? 'unknown',
                $this->new_value['status'] ?? 'unknown'
            ),
            'assigned' => sprintf(
                '%s assigned this issue to %s',
                $userName,
                $this->new_value['name'] ?? 'unknown'
            ),
            'resolved' => sprintf(
                '%s resolved this issue (%s)',
                $userName,
                $this->new_value['resolution_type'] ?? 'fixed'
            ),
            default => $this->comment ?? $this->type_text,
        };
    }
}
