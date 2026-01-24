<?php

namespace App\Services;

use App\Models\User;
use App\Models\PointLog;
use Illuminate\Support\Facades\DB;

class GamificationService
{
    /**
     * Award points to a user.
     */
    public function awardPoints(User $user, int $points, string $reason, $referenceable = null)
    {
        if ($points === 0) return;

        return DB::transaction(function () use ($user, $points, $reason, $referenceable) {
            // Update user balance
            $user->increment('points', $points);

            // Log the achievement
            return PointLog::create([
                'tenant_id' => $user->tenant_id,
                'user_id' => $user->id,
                'points' => $points,
                'reason' => $reason,
                'referenceable_type' => $referenceable ? get_class($referenceable) : null,
                'referenceable_id' => $referenceable ? $referenceable->id : null,
            ]);
        });
    }

    /**
     * Get leaderboard for a tenant.
     */
    public function getLeaderboard($tenantId, $limit = 10)
    {
        return User::where('tenant_id', $tenantId)
            ->where('role', 'student')
            ->orderByDesc('points')
            ->limit($limit)
            ->get();
    }
}
