<?php

namespace App\Services;

use App\Models\Instructor;
use App\Models\NetworkIdentity;
use App\Models\PublicProfile;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class DiscoveryService
{
    public function searchTeachers(array $filters, int $perPage = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = NetworkIdentity::discoverablePublic()
            ->teachers()
            ->whereHas('profilable', fn ($q) => $q->where('status', 'active'))
            ->with('profilable');

        if (! empty($filters['subject'])) {
            $query->whereHas('profilable', fn ($q) => $q->where('specialization', 'LIKE', '%' . $filters['subject'] . '%'));
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('public_slug', 'LIKE', "%{$search}%")
                    ->orWhere('headline', 'LIKE', "%{$search}%");
            });
        }

        $identities = $query->get();

        $scored = $identities->map(function ($identity) {
            $profile = $identity->publicProfile;
            return [
                'identity' => $identity,
                'profile' => $profile,
                'score' => $profile ? $this->calculateDiscoveryScore($profile) : 0,
                'average_rating' => $profile ? $this->getRating($profile) : null,
                'review_count' => $profile ? $this->getReviewCount($profile) : 0,
            ];
        });

        $sorted = $scored->sortByDesc('score')->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $sorted->slice(($filters['page'] ?? 1) - 1, $perPage),
            $sorted->count(),
            $perPage,
            $filters['page'] ?? 1
        );
    }

    public function searchCenters(array $filters, int $perPage = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = NetworkIdentity::discoverablePublic()
            ->centers()
            ->whereHas('profilable', fn ($q) => $q->where('status', 'active'))
            ->with('profilable');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('public_slug', 'LIKE', "%{$search}%")
                    ->orWhere('headline', 'LIKE', "%{$search}%");
            });
        }

        $identities = $query->get();

        $scored = $identities->map(function ($identity) {
            $profile = $identity->publicProfile;
            return [
                'identity' => $identity,
                'profile' => $profile,
                'score' => $profile ? $this->calculateDiscoveryScore($profile) : 0,
                'average_rating' => $profile ? $this->getRating($profile) : null,
                'review_count' => $profile ? $this->getReviewCount($profile) : 0,
            ];
        });

        $sorted = $scored->sortByDesc('score')->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $sorted->slice(($filters['page'] ?? 1) - 1, $perPage),
            $sorted->count(),
            $perPage,
            $filters['page'] ?? 1
        );
    }

    protected function calculateDiscoveryScore(PublicProfile $profile): int
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