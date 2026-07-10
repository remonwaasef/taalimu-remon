<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ImportStudentsJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 3600;

    public $failOnTimeout = true;

    public $tries = 3;

    public $backoff = [60, 300];

    protected $filePath;

    protected $tenantId;

    protected $adminId;

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
        if (! $tenant) {
            return;
        }

        // Set tenant context for the job
        app()->instance('tenant', $tenant);

        // Read CSV from file
        $disk = \Illuminate\Support\Facades\Storage::disk('local');
        $path = $disk->path($this->filePath);
        if (! file_exists($path)) {
            return;
        }

        $totalSuccess = 0;
        $allErrors = [];

        // Use LazyCollection for memory efficient reading
        \Illuminate\Support\LazyCollection::make(function () use ($path) {
            $handle = fopen($path, 'r');
            if ($handle === false) {
                return;
            }

            // Auto-detect delimiter
            $firstLine = fgets($handle);

            // Handle Excel's sep=, hint
            if (strpos(trim($firstLine), 'sep=') === 0) {
                // The separator is explicitly defined, extract it
                $delimiter = substr(trim($firstLine), 4, 1);
                $firstLine = fgets($handle); // Read the actual first line (header)
            } else {
                $delimiter = strpos($firstLine, ';') !== false ? ';' : ',';
            }

            // Go back to the beginning to read properly with fgetcsv
            rewind($handle);

            // Skip the sep= line if we found it
            if (strpos(trim(fgets($handle)), 'sep=') !== 0) {
                rewind($handle); // If no sep=, go back to start
            }

            // Skip header if present (heuristic)
            $header = fgetcsv($handle, 1000, $delimiter);

            // Clean BOM from first header item if exists
            if ($header && isset($header[0])) {
                $header[0] = preg_replace('/^[\xEF\xBB\xBF]+/', '', $header[0]);
            }

            // Simple heuristic: if 'email' is in the first row, skip it.
            if ($header && ! in_array('email', array_map('strtolower', $header))) {
                yield $header;
            }

            while (($data = fgetcsv($handle, 1000, $delimiter)) !== false) {
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

        // Log errors for debugging if any
        if (! empty($allErrors)) {
            \Illuminate\Support\Facades\Log::warning('Import Students Errors:', $allErrors);
        }

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
