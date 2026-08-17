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

class SendWhatsAppNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tenant;

    public $student;

    public $course;

    // SEC-10: transient channel failures are retried with backoff; the job is
    // only marked failed after consecutive attempts, and failed() then alerts
    // the ops chat so a dead WhatsApp channel is noticed quickly.
    public $tries = 3;

    public $backoff = [10, 60, 300];

    /**
     * Create a new job instance.
     */
    public function __construct(Tenant $tenant, Student $student, $course)
    {
        $this->tenant = $tenant;
        $this->student = $student;
        $this->course = $course;
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppService $whatsAppService): void
    {
        // Set tenant context for the job to enable global scopes
        app()->instance('tenant', $this->tenant);

        $sent = $whatsAppService->sendAttendanceNotification($this->tenant, $this->student, $this->course);

        if (! $sent) {
            if ($this->attempts() >= $this->tries) {
                $this->alertChannelDown();
            }

            // In sync mode (tests, local dev) a thrown exception would bubble
            // into the caller; fail softly there — the channel is already logged.
            if (config('queue.default') === 'sync') {
                return;
            }

            // Give the provider a chance to recover and release back to the queue.
            throw new \RuntimeException('WhatsApp channel unavailable');
        }
    }

    /**
     * Notify ops (via Telegram fallback) that the WhatsApp channel is down.
     */
    protected function alertChannelDown(): void
    {
        try {
            app(\App\Services\TelegramService::class)
                ->sendAdminNotificationDirectly('⚠️ فشل إرسال إشعار WhatsApp للطالب #'.$this->student->id
                    .' في المركز #'.$this->tenant->id.' بعد ثلاث محاولات.');
        } catch (\Throwable $e) {
            // Never let the alerting itself break the pipeline.
        }
    }
}
