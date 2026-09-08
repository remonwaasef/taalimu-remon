<?php

namespace App\Services;

use App\Models\Instructor;
use App\Models\PublicProfile;
use App\Models\Tenant;
use Illuminate\Support\Str;

class GrowthProfileService
{
    /**
     * Get or create a public profile for an instructor.
     */
    public function getOrCreateForInstructor(Instructor $instructor): PublicProfile
    {
        $profile = PublicProfile::where('tenant_id', $instructor->tenant_id)
            ->where('profilable_type', Instructor::class)
            ->where('profilable_id', $instructor->id)
            ->first();

        if (! $profile) {
            $profile = $this->createForInstructor($instructor);
        }

        return $profile;
    }

    /**
     * Get or create a public profile for a center (tenant).
     */
    public function getOrCreateForCenter(Tenant $tenant): PublicProfile
    {
        $profile = PublicProfile::where('tenant_id', $tenant->id)
            ->where('profilable_type', Tenant::class)
            ->where('profilable_id', $tenant->id)
            ->first();

        if (! $profile) {
            $profile = $this->createForCenter($tenant);
        }

        return $profile;
    }

    /**
     * Create a new public profile for an instructor.
     */
    public function createForInstructor(Instructor $instructor): PublicProfile
    {
        return PublicProfile::create([
            'tenant_id' => $instructor->tenant_id,
            'profilable_type' => Instructor::class,
            'profilable_id' => $instructor->id,
            'slug' => $this->generateUniqueSlug($instructor->name, $instructor->tenant_id),
            'title' => $instructor->name,
            'headline' => $instructor->specialization,
            'bio' => $instructor->bio,
            'photo' => $instructor->image,
            'visibility' => $this->defaultVisibility(),
            'published' => false,
        ]);
    }

    /**
     * Create a new public profile for a center (tenant).
     */
    public function createForCenter(Tenant $tenant): PublicProfile
    {
        return PublicProfile::create([
            'tenant_id' => $tenant->id,
            'profilable_type' => Tenant::class,
            'profilable_id' => $tenant->id,
            'slug' => $this->generateUniqueSlug($tenant->name, $tenant->id),
            'title' => $tenant->name,
            'headline' => $tenant->description ? Str::limit(strip_tags($tenant->description), 100) : null,
            'photo' => $tenant->logo,
            'visibility' => $this->defaultVisibility(),
            'published' => false,
        ]);
    }

    /**
     * Update a public profile.
     */
    public function update(PublicProfile $profile, array $data): PublicProfile
    {
        if (isset($data['slug'])) {
            $data['slug'] = $this->sanitizeSlug($data['slug']);
            $this->validateSlugUnique($data['slug'], $profile->tenant_id, $profile->id);
        }

        $profile->update($data);

        return $profile->fresh();
    }

    /**
     * Publish a profile.
     */
    public function publish(PublicProfile $profile): PublicProfile
    {
        $profile->update(['published' => true]);

        return $profile->fresh();
    }

    /**
     * Unpublish a profile.
     */
    public function unpublish(PublicProfile $profile): PublicProfile
    {
        $profile->update(['published' => false]);

        return $profile->fresh();
    }

    /**
     * Find a published profile by slug and type.
     */
    public function findPublishedBySlug(string $slug, string $type): ?PublicProfile
    {
        return PublicProfile::where('slug', $slug)
            ->where('profilable_type', $type)
            ->where('published', true)
            ->with('profilable')
            ->first();
    }

    /**
     * Generate a unique slug for a tenant.
     */
    protected function generateUniqueSlug(string $name, int $tenantId): string
    {
        $slug = $this->sanitizeSlug($name);
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug, $tenantId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Sanitize a slug string.
     */
    protected function sanitizeSlug(string $value): string
    {
        $slug = Str::slug($value);

        if (empty($slug)) {
            $slug = 'profile-' . Str::random(8);
        }

        return $slug;
    }

    /**
     * Check if a slug already exists for a tenant.
     */
    protected function slugExists(string $slug, int $tenantId, ?int $excludeId = null): bool
    {
        $query = PublicProfile::where('tenant_id', $tenantId)
            ->where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Validate slug uniqueness and throw if duplicate.
     */
    protected function validateSlugUnique(string $slug, int $tenantId, ?int $excludeId = null): void
    {
        if ($this->slugExists($slug, $tenantId, $excludeId)) {
            throw new \InvalidArgumentException("The slug '{$slug}' is already taken.");
        }
    }

    /**
     * Get default visibility settings.
     */
    protected function defaultVisibility(): array
    {
        return [
            'name' => true,
            'email' => false,
            'phone' => false,
            'specialization' => true,
            'bio' => true,
            'image' => true,
            'location' => true,
            'experience_years' => true,
            'published' => true,
        ];
    }
}
