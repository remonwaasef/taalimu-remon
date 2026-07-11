<?php

namespace App\Jobs;

use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendSystemWhatsAppMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $phone;

    public $message;

    /**
     * Create a new job instance.
     */
    public function __construct(string $phone, string $message)
    {
        $this->phone = $phone;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppService $whatsapp): void
    {
        try {
            $whatsapp->sendSystemMessage($this->phone, $this->message);
        } catch (\Exception $e) {
            Log::warning('Job SendSystemWhatsAppMessage failed: '.$e->getMessage());
        }
    }
}
