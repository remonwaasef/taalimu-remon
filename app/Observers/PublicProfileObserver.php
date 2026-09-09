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
        $this->networkIdentityService->syncFromPublicProfile($publicProfile);
    }

    public function updated(PublicProfile $publicProfile): void
    {
        $this->networkIdentityService->syncFromPublicProfile($publicProfile);
    }
}