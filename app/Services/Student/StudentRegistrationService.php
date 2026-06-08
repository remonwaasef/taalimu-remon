<?php

namespace App\Services\Student;

use App\Models\User;
use App\Models\Student;
use App\Models\Guardian;
use App\DTOs\StudentData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\FinanceService;

class StudentRegistrationService
{
    protected $financeService;
    protected $notificationService;

    public function __construct(FinanceService $financeService, StudentNotificationService $notificationService)
    {
        $this->financeService = $financeService;
        $this->notificationService = $notificationService;
    }

    public function registerStudent(StudentData $data, User $creator, bool $notify = true)
    {
        $result = DB::transaction(function () use ($data, $creator, $notify) {
            $generatedPassword = Str::random(12);
            $profilePhotoPath = $data->profile_photo;

            $email = $data->email ?? $this->generateUniqueEmail();
            $code = $data->code ?? $this->generateUniqueCode();

            $existingUser = User::where('email', $email)->first();
            if (!$existingUser && !empty($data->phone)) {
                $existingUser = User::where('phone', $data->phone)->first();
            }

            if ($existingUser) {
                $student = Student::where('user_id', $existingUser->id)->first();
                if ($student) {
                    $student->update([
                        'grade_id' => $data->grade_id,
                        'name' => $data->name,
                        'phone' => $data->phone,
                    ]);
                    return [
                        'user' => $existingUser,
                        'student' => $student,
                        'generated_password' => null,
                    ];
                }
                $user = $existingUser;
            } else {
                $user = User::create([
                    'name' => $data->name,
                    'email' => $email,
                    'phone' => $data->phone,
                    'password' => Hash::make($generatedPassword),
                    'role' => 'student',
                    'tenant_id' => \Modules\Tenancy\Services\TenantResolver::get()->id,
                    'must_change_password' => true,
                ]);
            }

            $guardianId = null;
            if ($data->parent_phone) {
                $guardian = Guardian::updateOrCreate(
                    ['tenant_id' => \Modules\Tenancy\Services\TenantResolver::get()->id, 'phone' => $data->parent_phone],
                    [
                        'name' => $data->parent_name ?? 'N/A',
                        'job' => $data->parent_job,
                        'address' => $data->address,
                        'email' => $data->parent_email ?? null,
                    ]
                );
                $guardianId = $guardian->id;
            }

            $student = Student::forceCreate([
                'tenant_id' => \Modules\Tenancy\Services\TenantResolver::get()->id,
                'user_id' => $user->id,
                'grade_id' => $data->grade_id,
                'guardian_id' => $guardianId,
                'grade_level' => $data->grade_level,
                'code' => $code,
                'national_id' => $data->national_id,
                'name' => $data->name,
                'email' => $email,
                'phone' => $data->phone,
                'parent_phone' => $data->parent_phone,
                'parent_email' => $data->parent_email,
                'status' => 'active',
                'birth_date' => $data->birth_date,
                'gender' => $data->gender,
                'address' => $data->address,
                'parent_name' => $data->parent_name,
                'parent_job' => $data->parent_job,
                'parent_relation' => $data->parent_relation,
                'emergency_phone' => $data->emergency_phone,
                'school_name' => $data->school_name,
                'section_type' => $data->section_type,
                'profile_photo' => $profilePhotoPath,
                'joined_at' => now(),
            ]);

            $result = [
                'user' => $user,
                'student' => $student,
                'generated_password' => $generatedPassword,
            ];

            if (!empty($data->course_ids)) {
                $items = [];
                $courses = \App\Models\Course::whereIn('id', $data->course_ids)->get();
                foreach ($courses as $course) {
                    $items[] = ['id' => $course->id, 'price' => $course->price];
                }

                if (!empty($items)) {
                    $this->financeService->createSale([
                        'student_id' => $student->id,
                        'items' => $items,
                        'payment_method' => 'cash',
                        'paid_amount' => 0,
                    ]);
                }
            }

            if ($notify) {
                $this->notificationService->notifyAdminsAboutRegistration($student, $creator);
            }

            return $result;
        });

        if (isset($result['student'])) {
            $this->notificationService->sendWelcomeEmails($result['student'], $result['generated_password']);
            if (!empty($data->course_ids)) {
                $this->notificationService->sendGroupEnrollmentEmails($result['student'], $data->course_ids);
            }
        }

        return $result;
    }

    public function generateUniqueCode()
    {
        $tenantId = \Modules\Tenancy\Services\TenantResolver::get()->id;
        $prefix = 'S-' . ($tenantId % 1000);
        
        $lastStudent = Student::where('tenant_id', $tenantId)
            ->where('code', 'like', $prefix . '%')
            ->latest('id')
            ->first();

        $counter = 1001;

        if ($lastStudent && preg_match('/-(\d+)$/', $lastStudent->code, $matches)) {
            $counter = intval($matches[1]) + 1;
        }

        $code = "{$prefix}-{$counter}";

        while (Student::where('tenant_id', $tenantId)->where('code', $code)->exists()) {
            $counter++;
            $code = "{$prefix}-{$counter}";
        }

        return $code;
    }

    public function generateUniqueEmail()
    {
        $tenant = \Modules\Tenancy\Services\TenantResolver::get();
        $subdomain = $tenant->domain;
        
        $lastStudent = User::where('tenant_id', $tenant->id)
            ->where('role', 'student')
            ->where('email', 'like', "std%.{$subdomain}@taalimu.com")
            ->latest('id')
            ->first();

        $counter = 1;

        if ($lastStudent && preg_match('/std(\d+)\./', $lastStudent->email, $matches)) {
            $counter = intval($matches[1]) + 1;
        } else {
            $counter = User::where('tenant_id', $tenant->id)->where('role', 'student')->count() + 1;
        }

        $email = "std{$counter}.{$subdomain}@taalimu.com";

        while (User::withoutGlobalScopes()->where('email', $email)->exists()) {
            $counter++;
            $email = "std{$counter}.{$subdomain}@taalimu.com";
        }

        return $email;
    }
}


