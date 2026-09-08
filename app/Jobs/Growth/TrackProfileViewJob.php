<?php

namespace App\Jobs\Growth;

use App\Models\GrowthEvent;
use App\Models\PublicProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TrackProfileViewJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        public int $profileId,
        public ?string $source = null,
        public ?string $campaign = null,
        public ?string $visitorId = null
    ) {}

    public function handle(): void
    {
        $profile = PublicProfile::find($this->profileId);

        if (! $profile) {
            return;
        }

        GrowthEvent::record(
            'profile_viewed',
            $profile,
            'visitor',
            $this->visitorId,
            $this->source,
            $this->campaign
        );
    }
}
