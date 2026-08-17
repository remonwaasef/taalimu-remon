<?php

namespace App\Jobs;

use App\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTelegramNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $message;

    // SEC-10: retry transient Telegram outages before failing.
    public $tries = 3;

    public $backoff = [10, 60, 300];

    /**
     * Create a new job instance.
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(TelegramService $telegramService): void
    {
        $sent = $telegramService->sendAdminNotificationDirectly($this->message);

        if (! $sent && $this->attempts() < $this->tries) {
            // Provider unreachable (network, 5xx): release with backoff instead
            // of burning the attempt.
            throw new \RuntimeException('Telegram channel unavailable');
        }

        if (! $sent) {
            \Illuminate\Support\Facades\Log::critical(
                'Telegram channel down after '.$this->tries.' attempts — message not delivered.'
            );
        }
    }
}
