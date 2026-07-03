<?php

namespace App\Services;

use App\DTOs\StudentData;
use App\Models\Student;
use App\Models\User;
use App\Services\Student\StudentImportService;
use App\Services\Student\StudentPasswordService;
use App\Services\Student\StudentProfileService;
use App\Services\Student\StudentRegistrationService;

/**
 * Facade for Student operations.
 * This class delegates tasks to specialized, single-responsibility services.
 */
class StudentService
{
    protected $registrationService;

    protected $profileService;

    protected $importService;

    protected $passwordService;

    public function __construct(
        StudentRegistrationService $registrationService,
        StudentProfileService $profileService,
        StudentImportService $importService,
        StudentPasswordService $passwordService
    ) {
        $this->registrationService = $registrationService;
        $this->profileService = $profileService;
        $this->importService = $importService;
        $this->passwordService = $passwordService;
    }

    public function registerStudent(StudentData $data, User $creator, bool $notify = true)
    {
        return $this->registrationService->registerStudent($data, $creator, $notify);
    }

    public function updateStudent(Student $student, StudentData $data, User $modifier)
    {
        return $this->profileService->updateStudent($student, $data, $modifier);
    }

    public function getExportData()
    {
        return $this->profileService->getExportData();
    }

    public function getProfileData(Student $student)
    {
        return $this->profileService->getProfileData($student);
    }

    public function deleteStudent(Student $student, User $deleter)
    {
        return $this->profileService->deleteStudent($student, $deleter);
    }

    public function importStudents(array $csvData)
    {
        return $this->importService->importStudents($csvData);
    }

    public function resetPassword(User $user)
    {
        return $this->passwordService->resetPassword($user);
    }

    // Proxy for any internal code still requesting this directly
    public function generateUniqueCode()
    {
        return $this->registrationService->generateUniqueCode();
    }

    public function generateUniqueEmail()
    {
        return $this->registrationService->generateUniqueEmail();
    }
}
