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

class SendDebtReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $backoff = [30, 120];

    public $tenant;

    public $student;

    public $totalDebt;

    public function __construct(Tenant $tenant, Student $student, float $totalDebt)
    {
        $this->tenant = $tenant;
        $this->student = $student;
        $this->totalDebt = $totalDebt;
    }

    public function handle(WhatsAppService $whatsapp): void
    {
        $whatsapp->sendDebtReminder($this->tenant, $this->student, $this->totalDebt);
    }
}
