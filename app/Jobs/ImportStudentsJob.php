<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ImportStudentsJob implements ShouldQueue
{
    use Queueable;

    protected $csvData;
    protected $tenantId;
    protected $adminId;
    
    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 3600; // 1 hour for large imports

    /**
     * Indicate if the job should fail if the timeout is exceeded.
     *
     * @var bool
     */
    public $failOnTimeout = true;

    /**
     * Create a new job instance.
     */
    public function __construct(string $filePath, int $tenantId, int $adminId)
    {
        $this->filePath = $filePath;
        $this->tenantId = $tenantId;
        $this->adminId = $adminId;
    }

    /**
     * Execute the job.
     */
    public function handle(\App\Services\StudentService $studentService): void
    {
        $tenant = \App\Models\Tenant::find($this->tenantId);
        if (!$tenant) return;

        // Set tenant context for the job
        app()->instance('tenant', $tenant);

        // Read CSV from file
        $path = storage_path('app/' . $this->filePath);
        if (!file_exists($path)) return;

        $totalSuccess = 0;
        $allErrors = [];

        // Use LazyCollection for memory efficient reading
        \Illuminate\Support\LazyCollection::make(function () use ($path) {
            $handle = fopen($path, 'r');
            if ($handle === false) return;

            // Skip header if present (heuristic)
            $header = fgetcsv($handle, 1000, ",");
            // If it DOESN'T look like a header (e.g. contains '@'), prepend it back effectively or just yield it
            // Simple heuristic: if 'email' is in the first row, skip it.
            if ($header && !in_array('email', array_map('strtolower', $header))) {
                yield $header; 
            }

            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                yield $data;
            }
            fclose($handle);
        })
        ->chunk(500) // Process in chunks of 500
        ->each(function ($chunk) use ($studentService, &$totalSuccess, &$allErrors) {
            $result = $studentService->importStudents($chunk->toArray());
            $totalSuccess += $result['success_count'];
            $allErrors = array_merge($allErrors, $result['errors']);
        });

        // Cleanup file
        @unlink($path);

        // Notify the admin who started the import
        $admin = \App\Models\User::find($this->adminId);
        if ($admin) {
            $admin->notify(new \App\Notifications\GeneralNotification(
                'import_completed',
                "تمت عملية استيراد الطلاب بنجاح: تم استيراد {$totalSuccess} طالب.",
                route('center.students.index', ['tenant' => $tenant->domain]),
                'fas fa-file-import',
                'System'
            ));
        }
    }
}
