<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Enrollment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class CertificateService
{
    /**
     * Check if student qualifies for a certificate and generate if they do.
     */
    public function checkAndGenerate(Enrollment $enrollment)
    {
        if ($enrollment->progress < 100) {
            return null;
        }

        // Check if already issued
        $existing = Certificate::where('student_id', $enrollment->student_id)
            ->where('course_id', $enrollment->course_id)
            ->first();

        if ($existing) {
            return $existing;
        }

        // Create new certificate
        return Certificate::create([
            'tenant_id' => $enrollment->course->tenant_id,
            'student_id' => $enrollment->student_id,
            'course_id' => $enrollment->course_id,
            'uuid' => (string) Str::uuid(),
            'issued_at' => now(),
            'metadata' => [
                'student_name' => $enrollment->student->user->name,
                'course_title' => $enrollment->course->title,
                'tenant_name' => app('tenant')->name ?? 'Edu Platform',
            ],
        ]);
    }

    /**
     * Generate the PDF for a certificate.
     */
    public function generatePdf(Certificate $certificate)
    {
        $data = [
            'certificate' => $certificate,
            'student' => $certificate->student,
            'course' => $certificate->course,
            'tenant' => app('tenant'),
        ];

        return Pdf::loadView('center::certificates.template', $data)
            ->setPaper('a4', 'landscape');
    }
}
