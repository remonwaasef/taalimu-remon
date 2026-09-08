<?php

namespace App\Services;

use App\Models\PublicProfile;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReferralService
{
    public function trackReferral(int $tenantId, int $referrerId, int $referredId, string $code): ?Referral
    {
        $profile = PublicProfile::where('tenant_id', $tenantId)
            ->where('referral_code', $code)
            ->first();

        if (! $profile) {
            return null;
        }

        if ($referrerId === $referredId) {
            return null;
        }

        $existing = Referral::where('tenant_id', $tenantId)
            ->where('referrer_id', $referrerId)
            ->where('referred_id', $referredId)
            ->first();

        if ($existing) {
            return null;
        }

        return Referral::create([
            'tenant_id' => $tenantId,
            'referrer_id' => $referrerId,
            'referred_id' => $referredId,
            'code_used' => $code,
            'status' => 'pending',
        ]);
    }

    public function completeReferral(Referral $referral, int $enrollmentId): bool
    {
        if ($referral->status !== 'pending') {
            return false;
        }

        $referral->update([
            'status' => 'completed',
            'enrollment_id' => $enrollmentId,
            'rewarded_at' => now(),
        ]);

        return true;
    }

    public function getReferralStats(int $tenantId, int $userId): array
    {
        $totalReferrals = Referral::where('tenant_id', $tenantId)
            ->where('referrer_id', $userId)
            ->count();

        $completedReferrals = Referral::where('tenant_id', $tenantId)
            ->where('referrer_id', $userId)
            ->where('status', 'completed')
            ->count();

        $pendingReferrals = Referral::where('tenant_id', $tenantId)
            ->where('referrer_id', $userId)
            ->where('status', 'pending')
            ->count();

        return [
            'total' => $totalReferrals,
            'completed' => $completedReferrals,
            'pending' => $pendingReferrals,
            'conversion_rate' => $totalReferrals > 0
                ? round(($completedReferrals / $totalReferrals) * 100, 1)
                : 0,
        ];
    }

    public function getRecentReferrals(int $tenantId, int $userId, int $limit = 10): \Illuminate\Support\Collection
    {
        return Referral::where('tenant_id', $tenantId)
            ->where('referrer_id', $userId)
            ->with('referred')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}
