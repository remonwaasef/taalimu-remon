<?php

namespace App\Observers;

use App\Models\PublicProfile;
use App\Services\NetworkIdentityService;

class PublicProfileObserver
{
    public function __construct(
        protected NetworkIdentityService $networkIdentityService
    ) {}

    public function created(PublicProfile $publicProfile): void
    {
        // Only sync if NetworkIdentity doesn't already exist to avoid cycles
        $exists = \App\Models\NetworkIdentity::where('tenant_id', $publicProfile->tenant_id)
            ->where('profilable_type', $publicProfile->profilable_type)
            ->where('profilable_id', $publicProfile->profilable_id)
            ->exists();

        if (! $exists) {
            $this->networkIdentityService->syncFromPublicProfile($publicProfile);
        }
    }

    public function updated(PublicProfile $publicProfile): void
    {
        // Only sync relevant fields to avoid unnecessary updates
        $dirty = $publicProfile->getDirty();
        $syncFields = ['slug', 'published', 'headline', 'bio', 'title'];

        if (array_intersect(array_keys($dirty), $syncFields)) {
            $this->networkIdentityService->syncFromPublicProfile($publicProfile);
        }
    }
}