<?php

namespace App\Jobs;

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

    public $user;
    public $points;
    public $reason;
    public $referenceable;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, int $points, string $reason, $referenceable = null)
    {
        $this->user = $user;
        $this->points = $points;
        $this->reason = $reason;
        $this->referenceable = $referenceable;
    }

    /**
     * Execute the job.
     */
    public function handle(GamificationService $gamificationService): void
    {
        $gamificationService->awardPoints($this->user, $this->points, $this->reason, $this->referenceable);
    }
}
