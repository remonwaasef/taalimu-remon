<?php

namespace App\Services;

use App\Models\Instructor;
use App\Models\PublicProfile;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    public function createReview(array $data, int $tenantId): Review
    {
        return Review::create([
            'tenant_id' => $tenantId,
            'reviewer_id' => $data['reviewer_id'],
            'enrollment_id' => $data['enrollment_id'] ?? null,
            'reviewable_type' => $data['reviewable_type'],
            'reviewable_id' => $data['reviewable_id'],
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);
    }

    public function respondToReview(Review $review, string $response): bool
    {
        if ($review->response !== null) {
            return false;
        }

        $review->update(['response' => $response]);

        return true;
    }

    public function getAverageRating(string $type, int $id): ?float
    {
        return Review::where('reviewable_type', $type)
            ->where('reviewable_id', $id)
            ->approved()
            ->avg('rating');
    }

    public function getRatingDistribution(string $type, int $id): array
    {
        $reviews = Review::where('reviewable_type', $type)
            ->where('reviewable_id', $id)
            ->approved()
            ->select('rating', DB::raw('COUNT(*) as count'))
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        $distribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        foreach ($reviews as $rating => $count) {
            $distribution[$rating] = $count;
        }

        return $distribution;
    }

    public function getReviews(string $type, int $id, int $perPage = 10)
    {
        return Review::where('reviewable_type', $type)
            ->where('reviewable_id', $id)
            ->approved()
            ->with('reviewer')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function getRecentReviews(int $tenantId, int $limit = 5): \Illuminate\Support\Collection
    {
        return Review::where('tenant_id', $tenantId)
            ->approved()
            ->with('reviewer')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}
