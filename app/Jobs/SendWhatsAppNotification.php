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

    public $tries = 3;

    public $backoff = [30, 120];

    public $tenant;

    public $student;

    public $course;

    public function __construct(Tenant $tenant, Student $student, $course)
    {
        $this->tenant = $tenant;
        $this->student = $student;
        $this->course = $course;
    }

    public function handle(WhatsAppService $whatsAppService): void
    {
        app()->instance('tenant', $this->tenant);
        $whatsAppService->sendAttendanceNotification($this->tenant, $this->student, $this->course);
    }
}
