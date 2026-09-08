<?php

namespace App\Http\Controllers\Growth;

use App\Http\Controllers\Controller;
use App\Models\PublicProfile;
use App\Services\ReferralService;

class ReferralController extends Controller
{
    public function __construct(protected ReferralService $referralService) {}

    public function index()
    {
        $tenant = app('tenant');
        $user = auth()->user();

        $profile = PublicProfile::where('tenant_id', $tenant->id)
            ->where('profilable_type', \App\Models\Instructor::class)
            ->first();

        $stats = $this->referralService->getReferralStats($tenant->id, $user->id);
        $recentReferrals = $this->referralService->getRecentReferrals($tenant->id, $user->id);

        $referralLink = $profile
            ? route('growth.public.teacher', $profile->slug) . '?ref=' . $profile->referral_code
            : null;

        return view('growth.dashboard.referrals', compact('stats', 'recentReferrals', 'referralLink', 'profile'));
    }
}
