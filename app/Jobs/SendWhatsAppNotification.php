<?php

namespace App\Jobs;

use App\Models\User;
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
        
        $whatsAppService->sendAttendanceNotification($this->tenant, $this->student, $this->course);
    }
}
