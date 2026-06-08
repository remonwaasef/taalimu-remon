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

class SendWhatsAppPaymentNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tenant;
    public $student;
    public $amount;
    public $balance;

    /**
     * Create a new job instance.
     */
    public function __construct(Tenant $tenant, Student $student, $amount, $balance)
    {
        $this->tenant = $tenant;
        $this->student = $student;
        $this->amount = $amount;
        $this->balance = $balance;
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppService $whatsAppService): void
    {
        try {
            // Set tenant context for the job
            app()->instance('tenant', $this->tenant);
            
            $whatsAppService->sendPaymentNotification($this->tenant, $this->student, $this->amount, $this->balance);
        } catch (\Exception $e) {
            Log::error('SendWhatsAppPaymentNotification failed: ' . $e->getMessage());
        }
    }
}
