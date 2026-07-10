<?php

namespace App\Services\Student;

use App\Models\Student;
use App\Models\User;
use App\Services\AdminNotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentImportService
{
    protected $notificationService;

    protected $adminNotificationService;

    public function __construct(
        StudentNotificationService $notificationService,
        AdminNotificationService $adminNotificationService
    ) {
        $this->notificationService = $notificationService;
        $this->adminNotificationService = $adminNotificationService;
    }

    public function importStudents(array $csvData)
    {
        $tenantId = \Modules\Tenancy\Services\TenantResolver::get()->id;
        $successCount = 0;
        $errors = [];
        $creator = auth()->user();

        $inputEmails = collect($csvData)->pluck(1)->filter()->unique()->toArray();
        $inputPhones = collect($csvData)->pluck(2)->filter()->unique()->toArray();
        $inputGradeNames = collect($csvData)->pluck(3)->filter()->unique()->toArray();

        $existingEmails = User::whereIn('email', $inputEmails)->pluck('email')->flip();
        $existingPhones = User::whereIn('phone', $inputPhones)->pluck('phone')->flip();

        $grades = \App\Models\Grade::where('tenant_id', $tenantId)
            ->where(function ($q) use ($inputGradeNames) {
                $q->whereIn('name', $inputGradeNames)
                    ->orWhereIn('id', $inputGradeNames);
            })->get();

        $gradesMap = $grades->pluck('id', 'name')->union($grades->pluck('id', 'id'));

        $usersToInsert = [];
        $studentsToInsert = [];
        $seenEmails = [];
        $seenPhones = [];
        $now = now();
        $passwordHash = Hash::make(Str::random(12));

        foreach ($csvData as $index => $row) {
            $rowIndex = $index + 1;

            $name = $row[0] ?? null;
            $email = $row[1] ?? null;
            $phone = $row[2] ?? null;
            $gradeLevel = $row[3] ?? null;

            if (! $name || ! $email || ! $phone) {
                $errors[] = "Row {$rowIndex}: Missing required fields.";

                continue;
            }

            foreach ([$name, $email, $phone, $gradeLevel] as $field) {
                if ($field && in_array(substr((string) $field, 0, 1), ['=', '+', '-', '@'])) {
                    $errors[] = "Row {$rowIndex}: Unsafe characters detected.";

                    continue 2;
                }
            }

            if ($existingEmails->has($email) || isset($seenEmails[$email])) {
                $errors[] = "Row {$rowIndex}: Email {$email} already exists or is duplicated in file.";

                continue;
            }

            if ($existingPhones->has($phone) || isset($seenPhones[$phone])) {
                $errors[] = "Row {$rowIndex}: Phone {$phone} already exists or is duplicated in file.";

                continue;
            }

            $seenEmails[$email] = true;
            $seenPhones[$phone] = true;
            $gradeId = $gradesMap->get($gradeLevel);

            $usersToInsert[] = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => $passwordHash,
                'role' => 'student',
                'tenant_id' => $tenantId,
                'must_change_password' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $studentsToInsert[] = [
                'grade_id' => $gradeId,
                'grade_level' => $gradeLevel,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'tenant_id' => $tenantId,
                'status' => 'active',
                'joined_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
                'parent_phone' => null, 'address' => null, 'birth_date' => null,
                'gender' => null, 'parent_name' => null, 'parent_job' => null,
                'parent_relation' => null, 'emergency_phone' => null, 'school_name' => null,
                'section_type' => null, 'profile_photo' => null,
            ];

            $successCount++;
        }

        if (! empty($usersToInsert)) {
            DB::transaction(function () use ($usersToInsert, $studentsToInsert) {
                User::insert($usersToInsert);
                $insertedUsers = User::whereIn('email', collect($usersToInsert)->pluck('email'))
                    ->pluck('id', 'email');

                foreach ($studentsToInsert as &$student) {
                    if (isset($insertedUsers[$student['email']])) {
                        $student['user_id'] = $insertedUsers[$student['email']];
                    }
                }

                foreach (array_chunk($studentsToInsert, 500) as $chunk) {
                    Student::insert($chunk);
                }
            });

            $this->adminNotificationService->notifyAdmins(
                'bulk_import',
                'تم استيراد '.$successCount.' طالب بنجاح',
                route('center.students.index', ['tenant' => \Modules\Tenancy\Services\TenantResolver::get()->domain]),
                'fas fa-file-import',
                $creator ? $creator->name : 'System'
            );

            $this->notificationService->sendBulkWelcomeEmails(
                collect($studentsToInsert)->pluck('email')->toArray(),
                $passwordHash
            );
        }

        return [
            'success_count' => $successCount,
            'errors' => $errors,
        ];
    }

    /**
     * Build the downloadable import template as Excel-compatible HTML,
     * seeded with the tenant's real grade names when available.
     */
    public function buildTemplateHtml(int $tenantId): string
    {
        $grades = \App\Models\Grade::where('tenant_id', $tenantId)->limit(3)->get();

        $sampleData = [];
        if ($grades->isEmpty()) {
            $sampleData[] = ['Ahmed Ali', 'ahmed1@example.com', '01012345678', 'Primary 1'];
            $sampleData[] = ['Sara Khaled', 'sara2@example.com', '01023456789', 'Primary 2'];
        } else {
            $sampleData[] = ['Ahmed Ali', 'ahmed1@example.com', '01012345678', $grades->first()->name];
            if ($grades->count() > 1) {
                $sampleData[] = ['Sara Khaled', 'sara2@example.com', '01023456789', $grades->skip(1)->first()->name];
            }
        }

        // Generate HTML table that Excel reads natively with full Arabic support
        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel">';
        $html .= '<head><meta charset="UTF-8">';
        $html .= '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
        $html .= '<x:Name>Students</x:Name>';
        $html .= '<x:WorksheetOptions><x:DisplayRightToLeft/><x:DisplayGridlines/></x:WorksheetOptions>';
        $html .= '</x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
        $html .= '<style>td{mso-number-format:\@;padding:5px;border:1px solid #ccc;font-family:Arial,sans-serif;font-size:12pt;} th{background:#4CAF50;color:#fff;padding:8px;border:1px solid #388E3C;font-family:Arial,sans-serif;font-size:12pt;font-weight:bold;}</style>';
        $html .= '</head><body>';
        $html .= '<table>';

        $html .= '<tr>';
        foreach (['name', 'email', 'phone', 'grade_level'] as $header) {
            $html .= '<th>'.e($header).'</th>';
        }
        $html .= '</tr>';

        foreach ($sampleData as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td>'.e($cell).'</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</table></body></html>';

        return $html;
    }

    /**
     * Convert pasted Excel/CSV rows into a stored CSV import file.
     * Returns the storage-relative path, or null when no usable rows exist.
     */
    public function convertPasteToCsv(string $pasteData): ?string
    {
        $lines = array_filter(explode("\n", $pasteData), fn ($line) => trim($line) !== '');

        if (empty($lines)) {
            return null;
        }

        $csvPath = 'temp/imports/'.uniqid('paste_').'.csv';
        $fullCsvPath = storage_path('app/'.$csvPath);

        if (! is_dir(dirname($fullCsvPath))) {
            mkdir(dirname($fullCsvPath), 0755, true);
        }

        $fp = fopen($fullCsvPath, 'w');
        fputcsv($fp, ['name', 'email', 'phone', 'grade_level']);

        foreach ($lines as $line) {
            $line = trim($line);
            // Split by tab (Excel clipboard default) or comma
            $cols = str_contains($line, "\t") ? explode("\t", $line) : str_getcsv($line);
            $cols = array_map('trim', $cols);

            // Skip header rows
            if (isset($cols[0]) && strtolower($cols[0]) === 'name') {
                continue;
            }

            if (count($cols) >= 2 && ! empty($cols[0]) && ! empty($cols[1])) {
                fputcsv($fp, [
                    $cols[0] ?? '',        // name
                    $cols[1] ?? '',        // email
                    $cols[2] ?? '',        // phone
                    $cols[3] ?? '',        // grade_level
                ]);
            }
        }
        fclose($fp);

        return $csvPath;
    }
}
