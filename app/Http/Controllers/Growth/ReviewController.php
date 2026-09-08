<?php

namespace App\Http\Controllers\Growth;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\PublicProfile;
use App\Services\ReviewService;

class ReviewController extends Controller
{
    public function __construct(protected ReviewService $reviewService) {}

    public function index(string $slug)
    {
        $profile = PublicProfile::where('slug', $slug)
            ->where('published', true)
            ->firstOrFail();

        $reviews = $this->reviewService->getReviews(
            $profile->profilable_type,
            $profile->profilable_id
        );

        $averageRating = $this->reviewService->getAverageRating(
            $profile->profilable_type,
            $profile->profilable_id
        );

        $distribution = $this->reviewService->getRatingDistribution(
            $profile->profilable_type,
            $profile->profilable_id
        );

        return view('growth.public.reviews', compact('profile', 'reviews', 'averageRating', 'distribution'));
    }

    public function store(string $slug)
    {
        $profile = PublicProfile::where('slug', $slug)
            ->where('published', true)
            ->firstOrFail();

        $data = request()->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $existingReview = \App\Models\Review::where('tenant_id', $profile->tenant_id)
            ->where('reviewer_id', auth()->id())
            ->where('reviewable_type', $profile->profilable_type)
            ->where('reviewable_id', $profile->profilable_id)
            ->first();

        if ($existingReview) {
            return redirect()->route('growth.reviews.index', $slug)
                ->with('error', __('You have already reviewed this profile.'));
        }

        $this->reviewService->createReview([
            'reviewer_id' => auth()->id(),
            'reviewable_type' => $profile->profilable_type,
            'reviewable_id' => $profile->profilable_id,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ], $profile->tenant_id);

        return redirect()->route('growth.reviews.index', $slug)
            ->with('success', __('Review submitted successfully.'));
    }
}
