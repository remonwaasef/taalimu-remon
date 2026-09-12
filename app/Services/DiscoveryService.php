<?php

namespace App\Services;

use App\Models\Instructor;
use App\Models\NetworkIdentity;
use App\Models\PublicProfile;
use App\Models\Review;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class DiscoveryService
{
    public function searchTeachers(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->buildTeacherQuery();

        if (! empty($filters['subject'])) {
            $query->whereHas('profilable', fn (Builder $q) => $q->where('specialization', 'LIKE', '%' . $filters['subject'] . '%'));
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('public_slug', 'LIKE', "%{$search}%")
                    ->orWhere('headline', 'LIKE', "%{$search}%");
            });
        }

        $query->with(['profilable', 'publicProfile.profilable'])
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');

        $paginator = $query->paginate($perPage, ['*'], 'page', $filters['page'] ?? 1);

        // Transform to include profile data for backward compatibility with views
        $transformed = $paginator->getCollection()->map(function ($identity) {
            return [
                'identity' => $identity,
                'profile' => $identity->publicProfile,
                'score' => $identity->publicProfile ? $this->calculateDiscoveryScore($identity->publicProfile) : 0,
                'average_rating' => $identity->publicProfile ? $this->getRating($identity->publicProfile) : null,
                'review_count' => $identity->publicProfile ? $this->getReviewCount($identity->publicProfile) : 0,
            ];
        });

        return new Paginator(
            $transformed,
            $paginator->total(),
            $paginator->perPage(),
            $paginator->currentPage(),
            ['path' => $paginator->path(), 'query' => request()->query()]
        );
    }

    public function searchCenters(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->buildCenterQuery();

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('public_slug', 'LIKE', "%{$search}%")
                    ->orWhere('headline', 'LIKE', "%{$search}%");
            });
        }

        $query->with(['profilable', 'publicProfile.profilable'])
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');

        $paginator = $query->paginate($perPage, ['*'], 'page', $filters['page'] ?? 1);

        $transformed = $paginator->getCollection()->map(function ($identity) {
            return [
                'identity' => $identity,
                'profile' => $identity->publicProfile,
                'score' => $identity->publicProfile ? $this->calculateDiscoveryScore($identity->publicProfile) : 0,
                'average_rating' => $identity->publicProfile ? $this->getRating($identity->publicProfile) : null,
                'review_count' => $identity->publicProfile ? $this->getReviewCount($identity->publicProfile) : 0,
            ];
        });

        return new Paginator(
            $transformed,
            $paginator->total(),
            $paginator->perPage(),
            $paginator->currentPage(),
            ['path' => $paginator->path(), 'query' => request()->query()]
        );
    }

    protected function buildTeacherQuery(): Builder
    {
        // Discovery is intentionally cross-tenant: public profiles are visible
        // network-wide, so the tenant scope (and the nested Instructor scope)
        // must be bypassed explicitly here.
        return NetworkIdentity::withoutGlobalScope(\App\Scopes\TenantScope::class)
            ->discoverablePublic()
            ->teachers()
            ->whereHas('profilable', fn (Builder $q) => $q->withoutGlobalScope(\App\Scopes\TenantScope::class)->where('status', 'active'));
    }

    protected function buildCenterQuery(): Builder
    {
        return NetworkIdentity::withoutGlobalScope(\App\Scopes\TenantScope::class)
            ->discoverablePublic()
            ->centers()
            ->whereHas('profilable', fn (Builder $q) => $q->withoutGlobalScope(\App\Scopes\TenantScope::class)->where('status', 'active'));
    }

    public function calculateDiscoveryScore(PublicProfile $profile): int
    {
        $score = 0;

        $hasHeadline = ! empty($profile->headline);
        $score += $hasHeadline ? 10 : 0;

        $hasAbout = ! empty($profile->bio);
        $score += $hasAbout ? 10 : 0;

        $hasPhoto = ! empty($profile->photo);
        $score += $hasPhoto ? 10 : 0;

        $hasCourses = \App\Models\Course::where('tenant_id', $profile->tenant_id)
            ->where('published', true)
            ->exists();
        $score += $hasCourses ? 15 : 0;

        $rating = $this->getRating($profile);
        if ($rating) {
            $score += (int) ($rating * 5);
        }

        $recentActivity = \App\Models\GrowthEvent::where('tenant_id', $profile->tenant_id)
            ->where('created_at', '>=', now()->subDays(30))
            ->exists();
        $score += $recentActivity ? 15 : 0;

        return $score;
    }

    protected function getRating(PublicProfile $profile): ?float
    {
        return Review::where('reviewable_type', $profile->profilable_type)
            ->where('reviewable_id', $profile->profilable_id)
            ->approved()
            ->avg('rating');
    }

    protected function getReviewCount(PublicProfile $profile): int
    {
        return Review::where('reviewable_type', $profile->profilable_type)
            ->where('reviewable_id', $profile->profilable_id)
            ->approved()
            ->count();
    }
}