<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * BugReport Model
 * Stores bug reports submitted by center users during beta testing.
 * Each report captures technical context (URL, browser, tenant) automatically.
 */
class BugReport extends Model
{
    use \App\Traits\BelongsToTenant;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'priority',
        'page_url',
        'browser_info',
        'screenshot',
        'status',
        'admin_notes',
        'resolved_at',
    ];

    protected $casts = [
        'browser_info' => 'array',
        'resolved_at' => 'datetime',
    ];

    // -- Relationships --

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // -- Scopes --

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeByTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    // -- Helpers --

    /**
     * Get a human-readable category label.
     */
    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'bug' => '🐛 Bug',
            'suggestion' => '💡 Suggestion',
            'ui_issue' => '🎨 UI Issue',
            'performance' => '⚡ Performance',
            'other' => '📝 Other',
            default => $this->category,
        };
    }

    /**
     * Get a human-readable priority label.
     */
    public function getPriorityBadgeAttribute(): string
    {
        return match ($this->priority) {
            'critical' => 'danger',
            'high' => 'warning',
            'medium' => 'info',
            'low' => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Get a human-readable status label.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'open' => 'danger',
            'in_progress' => 'warning',
            'resolved' => 'success',
            'closed' => 'secondary',
            default => 'secondary',
        };
    }
}
