<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class PublicProfile extends Model
{
    use \App\Traits\BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'profilable_type',
        'profilable_id',
        'slug',
        'referral_code',
        'title',
        'headline',
        'bio',
        'photo',
        'cover_photo',
        'location',
        'experience_years',
        'specializations',
        'teaching_levels',
        'delivery_modes',
        'social_links',
        'visibility',
        'verification_status',
        'verified_at',
        'published',
        'meta_title',
        'meta_description',
        'og_image',
    ];

    protected $casts = [
        'specializations' => 'array',
        'teaching_levels' => 'array',
        'delivery_modes' => 'array',
        'social_links' => 'array',
        'visibility' => 'array',
        'verified_at' => 'datetime',
        'experience_years' => 'integer',
        'published' => 'boolean',
    ];

    protected $hidden = [
        'tenant_id',
        'visibility',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (PublicProfile $profile) {
            if (empty($profile->referral_code)) {
                $profile->referral_code = strtoupper(Str::random(8));
            }
        });
    }

    /**
     * Get the parent model (Instructor or Tenant).
     */
    public function profilable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get growth events for this profile.
     */
    public function growthEvents()
    {
        return $this->morphMany(GrowthEvent::class, 'eventable');
    }

    /**
     * Check if a field is visible on the public profile.
     */
    public function isFieldVisible(string $field): bool
    {
        $visibility = $this->visibility ?? [];

        return $visibility[$field] ?? true;
    }

    /**
     * Check if the profile is published and visible to the public.
     */
    public function isPubliclyVisible(): bool
    {
        return $this->published && !$this->isFieldVisible('published');
    }

    /**
     * Get the public URL for this profile.
     */
    public function getUrl(): string
    {
        $tenant = $this->tenant ?? \App\Models\Tenant::find($this->tenant_id);
        if ($tenant) {
            return tenant_url('', $tenant);
        }

        if ($this->profilable_type === Instructor::class) {
            return route('growth.teacher.show', $this->slug);
        }

        return route('growth.center.show', $this->slug);
    }

    /**
     * Get profile completeness percentage.
     */
    public function getCompletenessAttribute(): int
    {
        $fields = ['title', 'headline', 'bio', 'photo', 'location', 'experience_years', 'specializations'];
        $filled = collect($fields)->filter(fn ($field) => !empty($this->{$field}))->count();

        return round(($filled / count($fields)) * 100);
    }

    /**
     * Get the photo URL with fallback.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }

        if ($this->profilable_type === Instructor::class && $this->profilable?->image) {
            return asset('storage/' . $this->profilable->image);
        }

        return null;
    }

    /**
     * Scope to find by slug within a tenant.
     */
    public function scopeForTenantAndSlug($query, int $tenantId, string $slug)
    {
        return $query->where('tenant_id', $tenantId)
            ->where('slug', $slug)
            ->where('published', true);
    }

    /**
     * Scope to published profiles only.
     */
    public function scopePublished($query)
    {
        return $query->where('published', true);
    }
}
