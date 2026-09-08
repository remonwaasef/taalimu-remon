<?php

namespace App\Services;

use App\Models\MarketplaceListing;

class MarketplaceService
{
    public function createListing(array $data, int $tenantId): MarketplaceListing
    {
        return MarketplaceListing::create([
            'tenant_id' => $tenantId,
            'user_id' => $data['user_id'],
            'subject' => $data['subject'],
            'level' => $data['level'] ?? null,
            'description' => $data['description'] ?? null,
            'location' => $data['location'] ?? null,
            'budget_range' => $data['budget_range'] ?? null,
            'preferred_schedule' => $data['preferred_schedule'] ?? null,
            'status' => 'active',
            'expires_at' => now()->addDays(30),
        ]);
    }

    public function getActiveListings(array $filters = [], int $perPage = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = MarketplaceListing::active()
            ->with('user');

        if (! empty($filters['subject'])) {
            $query->forSubject($filters['subject']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function getListing(int $id): ?MarketplaceListing
    {
        return MarketplaceListing::with('user')->find($id);
    }

    public function incrementView(MarketplaceListing $listing): void
    {
        $listing->incrementView();
    }

    public function closeListing(MarketplaceListing $listing): bool
    {
        if ($listing->status !== 'active') {
            return false;
        }

        $listing->update(['status' => 'closed']);

        return true;
    }

    public function expireStaleListings(): int
    {
        return MarketplaceListing::where('status', 'active')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);
    }

    public function getUserListings(int $userId, int $tenantId): \Illuminate\Support\Collection
    {
        return MarketplaceListing::where('user_id', $userId)
            ->where('tenant_id', $tenantId)
            ->orderByDesc('created_at')
            ->get();
    }
}
