<?php

namespace App\Jobs;

use App\Models\Tenant;
use App\Models\User;
use App\Services\GamificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AwardGamificationPoints implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $backoff = [10, 60];

    public $user;

    public $points;

    public $reason;

    public $referenceable;

    public function __construct(User $user, int $points, string $reason, $referenceable = null)
    {
        $this->user = $user;
        $this->points = $points;
        $this->reason = $reason;
        $this->referenceable = $referenceable;
    }

    public function handle(GamificationService $gamificationService): void
    {
        $tenant = Tenant::find($this->user->tenant_id);
        if ($tenant) {
            app()->instance('tenant', $tenant);
        }

        $gamificationService->awardPoints($this->user, $this->points, $this->reason, $this->referenceable);
    }
}
