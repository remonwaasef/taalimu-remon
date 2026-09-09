<?php

namespace App\Services;

use App\Models\Instructor;
use App\Models\NetworkIdentity;
use App\Models\PublicProfile;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class NetworkIdentityService
{
    protected ?GrowthProfileService $growthProfileService = null;

    public function __construct() {}

    protected function getGrowthProfileService(): GrowthProfileService
    {
        if (! $this->growthProfileService) {
            $this->growthProfileService = app(GrowthProfileService::class);
        }
        return $this->growthProfileService;
    }

    public function createForInstructor(Instructor $instructor, array $data = []): NetworkIdentity
    {
        return DB::transaction(function () use ($instructor, $data) {
            $profileType = NetworkIdentity::PROFILE_TYPE_TEACHER;
            $slug = $data['slug'] ?? $this->generateSlugFromName($instructor->name);

            $networkIdentity = NetworkIdentity::create([
                'tenant_id' => $instructor->tenant_id,
                'profilable_type' => Instructor::class,
                'profilable_id' => $instructor->id,
                'public_slug' => $this->ensureUniqueSlug($slug, $profileType),
                'profile_type' => $profileType,
                'status' => $data['status'] ?? NetworkIdentity::STATUS_DRAFT,
                'published_at' => isset($data['status']) && $data['status'] === NetworkIdentity::STATUS_PUBLISHED ? now() : null,
                'network_visible' => $data['network_visible'] ?? true,
                'discovery_enabled' => $data['discovery_enabled'] ?? true,
                'headline' => $data['headline'] ?? $instructor->specialization,
                'short_description' => $data['short_description'] ?? $instructor->bio,
                'city_id' => $data['city_id'] ?? null,
                'area_id' => $data['area_id'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
            ]);

            if (isset($data['public_profile_id'])) {
                $networkIdentity->public_profile_id = $data['public_profile_id'];
                $networkIdentity->save();
            }

            return $networkIdentity->fresh();
        });
    }

    public function createForCenter(Tenant $tenant, array $data = []): NetworkIdentity
    {
        return DB::transaction(function () use ($tenant, $data) {
            $profileType = NetworkIdentity::PROFILE_TYPE_CENTER;
            $slug = $data['slug'] ?? $this->generateSlugFromName($tenant->name);

            $networkIdentity = NetworkIdentity::create([
                'tenant_id' => $tenant->id,
                'profilable_type' => Tenant::class,
                'profilable_id' => $tenant->id,
                'public_slug' => $this->ensureUniqueSlug($slug, $profileType),
                'profile_type' => $profileType,
                'status' => $data['status'] ?? NetworkIdentity::STATUS_DRAFT,
                'published_at' => isset($data['status']) && $data['status'] === NetworkIdentity::STATUS_PUBLISHED ? now() : null,
                'network_visible' => $data['network_visible'] ?? true,
                'discovery_enabled' => $data['discovery_enabled'] ?? true,
                'headline' => $data['headline'] ?? ($tenant->description ? Str::limit(strip_tags($tenant->description), 100) : null),
                'short_description' => $data['short_description'] ?? $tenant->description,
                'city_id' => $data['city_id'] ?? null,
                'area_id' => $data['area_id'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
            ]);

            if (isset($data['public_profile_id'])) {
                $networkIdentity->public_profile_id = $data['public_profile_id'];
                $networkIdentity->save();
            }

            return $networkIdentity->fresh();
        });
    }

    public function create(Model $profilable, array $data = []): NetworkIdentity
    {
        $profileType = NetworkIdentity::getProfileTypeFromProfilable($profilable);

        if (! $profileType) {
            throw new InvalidArgumentException('Unsupported profilable type: ' . get_class($profilable));
        }

        if ($profilable instanceof Instructor) {
            return $this->createForInstructor($profilable, $data);
        }

        if ($profilable instanceof Tenant) {
            return $this->createForCenter($profilable, $data);
        }

        throw new InvalidArgumentException('Unsupported profilable type');
    }

    public function update(NetworkIdentity $identity, array $data): NetworkIdentity
    {
        if (isset($data['public_slug'])) {
            $data['public_slug'] = $this->sanitizeSlug($data['public_slug']);
            $this->validateSlugUnique($data['public_slug'], $identity->profile_type, $identity->id);
        }

        if (isset($data['status']) && $data['status'] !== $identity->status) {
            if ($data['status'] === NetworkIdentity::STATUS_PUBLISHED && ! $identity->published_at) {
                $data['published_at'] = now();
            }
        }

        $identity->update($data);

        return $identity->fresh();
    }

    public function publish(NetworkIdentity $identity): NetworkIdentity
    {
        return $this->update($identity, [
            'status' => NetworkIdentity::STATUS_PUBLISHED,
            'published_at' => $identity->published_at ?? now(),
        ]);
    }

    public function unpublish(NetworkIdentity $identity): NetworkIdentity
    {
        return $this->update($identity, [
            'status' => NetworkIdentity::STATUS_DRAFT,
        ]);
    }

    public function suspend(NetworkIdentity $identity): NetworkIdentity
    {
        return $this->update($identity, [
            'status' => NetworkIdentity::STATUS_SUSPENDED,
            'network_visible' => false,
            'discovery_enabled' => false,
        ]);
    }

    public function archive(NetworkIdentity $identity): NetworkIdentity
    {
        return $this->update($identity, [
            'status' => NetworkIdentity::STATUS_ARCHIVED,
            'network_visible' => false,
            'discovery_enabled' => false,
        ]);
    }

    public function generateSlugFromName(string $name): string
    {
        $slug = Str::slug($name);

        if (empty($slug)) {
            $slug = 'profile-' . Str::random(8);
        }

        return $slug;
    }

    public function ensureUniqueSlug(string $slug, string $profileType, ?int $excludeId = null): string
    {
        $slug = $this->sanitizeSlug($slug);
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug, $profileType, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    protected function sanitizeSlug(string $value): string
    {
        $slug = Str::slug($value);

        if (empty($slug)) {
            $slug = 'profile-' . Str::random(8);
        }

        return $slug;
    }

    protected function slugExists(string $slug, string $profileType, ?int $excludeId = null): bool
    {
        $query = NetworkIdentity::where('profile_type', $profileType)
            ->where('public_slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    protected function validateSlugUnique(string $slug, string $profileType, ?int $excludeId = null): void
    {
        if ($this->slugExists($slug, $profileType, $excludeId)) {
            throw new InvalidArgumentException("The slug '{$slug}' is already taken for this profile type.");
        }
    }

    public function getPublicUrl(NetworkIdentity $identity): string
    {
        return $identity->getPublicUrl();
    }

    public function isPubliclyVisible(NetworkIdentity $identity): bool
    {
        return $identity->isVisible();
    }

    public function isDiscoverable(NetworkIdentity $identity): bool
    {
        return $identity->isDiscoverable();
    }

    public function findBySlug(string $slug, string $profileType): ?NetworkIdentity
    {
        return NetworkIdentity::where('public_slug', $slug)
            ->where('profile_type', $profileType)
            ->first();
    }

    public function findPubliclyAccessibleBySlug(string $slug, string $profileType): ?NetworkIdentity
    {
        return NetworkIdentity::publiclyAccessible()
            ->where('public_slug', $slug)
            ->where('profile_type', $profileType)
            ->first();
    }

    public function findDiscoverableBySlug(string $slug, string $profileType): ?NetworkIdentity
    {
        return NetworkIdentity::discoverablePublic()
            ->where('public_slug', $slug)
            ->where('profile_type', $profileType)
            ->first();
    }

    public function getOrCreateForInstructor(Instructor $instructor): NetworkIdentity
    {
        $existing = NetworkIdentity::where('profilable_type', Instructor::class)
            ->where('profilable_id', $instructor->id)
            ->first();

        if ($existing) {
            return $existing;
        }

        // Create PublicProfile directly to avoid circular dependency
        $publicProfile = PublicProfile::where('tenant_id', $instructor->tenant_id)
            ->where('profilable_type', Instructor::class)
            ->where('profilable_id', $instructor->id)
            ->first();

        if (! $publicProfile) {
            $slug = $this->generateSlugFromName($instructor->name);
            $originalSlug = $slug;
            $counter = 1;
            while (PublicProfile::where('tenant_id', $instructor->tenant_id)->where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $publicProfile = PublicProfile::create([
                'tenant_id' => $instructor->tenant_id,
                'profilable_type' => Instructor::class,
                'profilable_id' => $instructor->id,
                'slug' => $slug,
                'title' => $instructor->name,
                'headline' => $instructor->specialization,
                'bio' => $instructor->bio,
                'photo' => $instructor->image,
                'visibility' => [
                    'name' => true,
                    'email' => false,
                    'phone' => false,
                    'specialization' => true,
                    'bio' => true,
                    'image' => true,
                    'location' => true,
                    'experience_years' => true,
                    'published' => true,
                ],
                'published' => true,
            ]);
        }

        return $this->createForInstructor($instructor, [
            'public_profile_id' => $publicProfile->id,
            'status' => $publicProfile->published ? NetworkIdentity::STATUS_PUBLISHED : NetworkIdentity::STATUS_DRAFT,
        ]);
    }

    public function getOrCreateForCenter(Tenant $tenant): NetworkIdentity
    {
        $existing = NetworkIdentity::where('profilable_type', Tenant::class)
            ->where('profilable_id', $tenant->id)
            ->first();

        if ($existing) {
            return $existing;
        }

        // Create PublicProfile directly to avoid circular dependency
        $publicProfile = PublicProfile::where('tenant_id', $tenant->id)
            ->where('profilable_type', Tenant::class)
            ->where('profilable_id', $tenant->id)
            ->first();

        if (! $publicProfile) {
            $slug = $this->generateSlugFromName($tenant->name);
            $originalSlug = $slug;
            $counter = 1;
            while (PublicProfile::where('tenant_id', $tenant->id)->where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $publicProfile = PublicProfile::create([
                'tenant_id' => $tenant->id,
                'profilable_type' => Tenant::class,
                'profilable_id' => $tenant->id,
                'slug' => $slug,
                'title' => $tenant->name,
                'headline' => $tenant->description ? Str::limit(strip_tags($tenant->description), 100) : null,
                'photo' => $tenant->logo,
                'visibility' => [
                    'name' => true,
                    'email' => false,
                    'phone' => false,
                    'specialization' => true,
                    'bio' => true,
                    'image' => true,
                    'location' => true,
                    'experience_years' => true,
                    'published' => true,
                ],
                'published' => true,
            ]);
        }

        return $this->createForCenter($tenant, [
            'public_profile_id' => $publicProfile->id,
            'status' => $publicProfile->published ? NetworkIdentity::STATUS_PUBLISHED : NetworkIdentity::STATUS_DRAFT,
        ]);
    }

    public function linkPublicProfile(NetworkIdentity $identity, PublicProfile $publicProfile): void
    {
        $identity->public_profile_id = $publicProfile->id;
        $identity->save();
    }

    public function syncFromPublicProfile(PublicProfile $publicProfile): ?NetworkIdentity
    {
        if (! $publicProfile->profilable) {
            return null;
        }

        $identity = NetworkIdentity::where('profilable_type', $publicProfile->profilable_type)
            ->where('profilable_id', $publicProfile->profilable_id)
            ->first();

        if (! $identity) {
            $profilable = $publicProfile->profilable;

            if ($profilable instanceof Instructor) {
                $identity = $this->createForInstructor($profilable, [
                    'public_profile_id' => $publicProfile->id,
                    'slug' => $publicProfile->slug,
                    'status' => $publicProfile->published ? NetworkIdentity::STATUS_PUBLISHED : NetworkIdentity::STATUS_DRAFT,
                    'headline' => $publicProfile->headline,
                    'short_description' => $publicProfile->bio,
                ]);
            } elseif ($profilable instanceof Tenant) {
                $identity = $this->createForCenter($profilable, [
                    'public_profile_id' => $publicProfile->id,
                    'slug' => $publicProfile->slug,
                    'status' => $publicProfile->published ? NetworkIdentity::STATUS_PUBLISHED : NetworkIdentity::STATUS_DRAFT,
                    'headline' => $publicProfile->headline,
                    'short_description' => $publicProfile->bio,
                ]);
            }
        } else {
            $identity->update([
                'public_slug' => $publicProfile->slug,
                'status' => $publicProfile->published ? NetworkIdentity::STATUS_PUBLISHED : NetworkIdentity::STATUS_DRAFT,
                'headline' => $publicProfile->headline,
                'short_description' => $publicProfile->bio,
            ]);
        }

        return $identity;
    }
}