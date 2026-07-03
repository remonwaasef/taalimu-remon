<?php

namespace App\Services\Student;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentImportService
{
    protected $notificationService;

    public function __construct(StudentNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
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

            app(\App\Services\AdminNotificationService::class)->notifyAdmins(
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
}
