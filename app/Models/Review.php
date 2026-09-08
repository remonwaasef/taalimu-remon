<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Review extends Model
{
    use \App\Traits\BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'reviewer_id',
        'enrollment_id',
        'reviewable_type',
        'reviewable_id',
        'rating',
        'comment',
        'response',
        'verified',
        'approved',
    ];

    protected $casts = [
        'rating' => 'integer',
        'verified' => 'boolean',
        'approved' => 'boolean',
    ];

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function reviewable()
    {
        return $this->morphTo();
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    public function scopeForSubject($query, string $type, int $id)
    {
        return $query->where('reviewable_type', $type)
            ->where('reviewable_id', $id);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->enrollment_id) {
                $model->verified = true;
            }
        });
    }
}
