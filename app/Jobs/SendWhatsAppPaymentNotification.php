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

class SendWhatsAppPaymentNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $backoff = [30, 120];

    public $tenant;

    public $student;

    public $amount;

    public $balance;

    public function __construct(Tenant $tenant, Student $student, $amount, $balance)
    {
        $this->tenant = $tenant;
        $this->student = $student;
        $this->amount = $amount;
        $this->balance = $balance;
    }

    public function handle(WhatsAppService $whatsAppService): void
    {
        app()->instance('tenant', $this->tenant);
        $whatsAppService->sendPaymentNotification($this->tenant, $this->student, $this->amount, $this->balance);
    }
}
