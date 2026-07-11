<?php

namespace App\Jobs;

use App\Models\Student;
use App\Models\Tenant;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendDebtReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tenant;

    public $student;

    public $totalDebt;

    /**
     * Create a new job instance.
     */
    public function __construct(Tenant $tenant, Student $student, float $totalDebt)
    {
        $this->tenant = $tenant;
        $this->student = $student;
        $this->totalDebt = $totalDebt;
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppService $whatsapp): void
    {
        try {
            $whatsapp->sendDebtReminder($this->tenant, $this->student, $this->totalDebt);
        } catch (\Exception $e) {
            Log::warning('Job SendDebtReminderJob failed: '.$e->getMessage());
        }
    }
}
