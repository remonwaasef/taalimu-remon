<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketplaceListing extends Model
{
    use \App\Traits\BelongsToTenant, HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'level',
        'description',
        'location',
        'budget_range',
        'preferred_schedule',
        'status',
        'view_count',
        'expires_at',
    ];

    protected $casts = [
        'view_count' => 'integer',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('expires_at', '>', now());
    }

    public function scopeForSubject($query, string $subject)
    {
        return $query->where('subject', $subject);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function incrementView(): void
    {
        $this->increment('view_count');
    }
}
