<?php

namespace App\Services;

use App\Events\RiskDetected;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentRiskService
{
    /**
     * Calculate risk score for a single student.
     *
     * Score ranges from 0-100:
     * - 0-29: LOW risk
     * - 30-59: MEDIUM risk
     * - 60-79: HIGH risk
     * - 80-100: CRITICAL risk
     */
    public function calculateRisk(Student $student): array
    {
        $score = 0;
        $reasons = [];

        // 1. Attendance Risk (0-30 points)
        $attendanceResult = $this->calculateAttendanceRisk($student);
        $score += $attendanceResult['score'];
        $reasons = array_merge($reasons, $attendanceResult['reasons']);

        // 2. Academic Performance Risk (0-25 points)
        $academicResult = $this->calculateAcademicRisk($student);
        $score += $academicResult['score'];
        $reasons = array_merge($reasons, $academicResult['reasons']);

        // 3. Payment Risk (0-25 points)
        $paymentResult = $this->calculatePaymentRisk($student);
        $score += $paymentResult['score'];
        $reasons = array_merge($reasons, $paymentResult['reasons']);

        // 4. Engagement Risk (0-20 points)
        $engagementResult = $this->calculateEngagementRisk($student);
        $score += $engagementResult['score'];
        $reasons = array_merge($reasons, $engagementResult['reasons']);

        // Determine risk level
        $level = $this->getRiskLevel($score);

        return [
            'score' => min($score, 100),
            'level' => $level,
            'reasons' => $reasons,
        ];
    }

    /**
     * Calculate attendance risk score (0-30 points).
     */
    private function calculateAttendanceRisk(Student $student): array
    {
        $score = 0;
        $reasons = [];

        // Get attendance statistics for last 30 days
        $attendanceStats = DB::table('attendances')
            ->where('student_id', $student->id)
            ->where('session_date', '>=', now()->subDays(30))
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late
            ')
            ->first();

        if ($attendanceStats && $attendanceStats->total > 0) {
            $absenceRate = ($attendanceStats->absent / $attendanceStats->total) * 100;
            $lateRate = ($attendanceStats->late / $attendanceStats->total) * 100;

            // High absence rate
            if ($absenceRate >= 50) {
                $score += 30;
                $reasons[] = 'غياب متكرر - معدل غياب '.round($absenceRate).'% في آخر 30 يوم';
            } elseif ($absenceRate >= 30) {
                $score += 20;
                $reasons[] = 'غياب متكرر - معدل غياب '.round($absenceRate).'% في آخر 30 يوم';
            } elseif ($absenceRate >= 20) {
                $score += 10;
                $reasons[] = 'زيادة في الغياب - معدل غياب '.round($absenceRate).'% في آخر 30 يوم';
            }

            // Check consecutive absences
            $consecutiveAbsences = $this->getConsecutiveAbsences($student->id);
            if ($consecutiveAbsences >= 3) {
                $score = min($score + 15, 30);
                $reasons[] = 'غياب متتالي لـ '.$consecutiveAbsences.' محاضرات';
            }
        }

        return ['score' => min($score, 30), 'reasons' => $reasons];
    }

    /**
     * Get consecutive absences count.
     */
    private function getConsecutiveAbsences(int $studentId): int
    {
        $attendances = DB::table('attendances')
            ->where('student_id', $studentId)
            ->orderBy('session_date', 'desc')
            ->limit(10)
            ->pluck('status')
            ->toArray();

        $consecutive = 0;
        foreach ($attendances as $status) {
            if ($status === 'absent') {
                $consecutive++;
            } else {
                break;
            }
        }

        return $consecutive;
    }

    /**
     * Calculate academic risk score (0-25 points).
     */
    private function calculateAcademicRisk(Student $student): array
    {
        $score = 0;
        $reasons = [];

        // Get quiz attempts for the student
        $quizStats = DB::table('quiz_attempts')
            ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
            ->where('quiz_attempts.user_id', $student->user_id)
            ->where('quiz_attempts.tenant_id', $student->tenant_id)
            ->where('quiz_attempts.completed_at', '>=', now()->subDays(30))
            ->selectRaw('
                COUNT(*) as total,
                AVG(score) as avg_score,
                SUM(CASE WHEN score < 50 THEN 1 ELSE 0 END) as failed
            ')
            ->first();

        if ($quizStats && $quizStats->total > 0) {
            $failedRate = ($quizStats->failed / $quizStats->total) * 100;

            // High failure rate
            if ($failedRate >= 50) {
                $score += 25;
                $reasons[] = 'أداء أكاديمي ضعيف - فشل في '.round($failedRate).'% من الاختبارات';
            } elseif ($failedRate >= 30) {
                $score += 15;
                $reasons[] = 'تراجع في الأداء - فشل في '.round($failedRate).'% من الاختبارات';
            }

            // Check score drop trend
            $scoreDrop = $this->detectScoreDrop($student);
            if ($scoreDrop['detected']) {
                $score = min($score + 10, 25);
                $reasons[] = 'انخفاض ملحوظ في الدرجات - '.($scoreDrop['description'] ?? '');
            }
        }

        return ['score' => min($score, 25), 'reasons' => $reasons];
    }

    /**
     * Detect significant score drops.
     */
    private function detectScoreDrop(Student $student): array
    {
        $subquery = DB::table('quiz_attempts')
            ->where('user_id', $student->user_id)
            ->where('tenant_id', $student->tenant_id)
            ->select('quiz_attempts.*', DB::raw('ROW_NUMBER() OVER(ORDER BY created_at DESC) as row_num'));

        $result = DB::query()
            ->fromSub($subquery, 'ranked')
            ->selectRaw('
                AVG(CASE WHEN row_num <= 3 THEN score END) as recent_avg,
                AVG(CASE WHEN row_num > 3 AND row_num <= 8 THEN score END) as baseline_avg
            ')
            ->first();

        if ($result && $result->baseline_avg && $result->recent_avg) {
            $dropPercentage = (($result->baseline_avg - $result->recent_avg) / $result->baseline_avg) * 100;
            if ($dropPercentage >= 15) {
                return [
                    'detected' => true,
                    'description' => 'انخفاض '.round($dropPercentage).'% في الدرجات',
                ];
            }
        }

        return ['detected' => false];
    }

    /**
     * Calculate payment risk score (0-25 points).
     */
    private function calculatePaymentRisk(Student $student): array
    {
        $score = 0;
        $reasons = [];

        // Check for overdue payments
        $overdueSales = DB::table('sales')
            ->where('student_id', $student->id)
            ->where('tenant_id', $student->tenant_id)
            ->where('status', 'pending')
            ->where('created_at', '<', now()->subDays(30))
            ->count();

        if ($overdueSales > 0) {
            $score += min($overdueSales * 10, 25);
            $reasons[] = 'تأخر في الدفع - '.$overdueSales.' فواتير متأخرة';
        }

        // Check for partial payments
        $partialSales = DB::table('sales')
            ->where('student_id', $student->id)
            ->where('tenant_id', $student->tenant_id)
            ->where('status', 'partial')
            ->count();

        if ($partialSales > 0) {
            $score = min($score + ($partialSales * 5), 25);
            if ($partialSales > 1) {
                $reasons[] = 'دفع جزئي متكرر - '.$partialSales.' فواتير غير مكتملة';
            }
        }

        return ['score' => min($score, 25), 'reasons' => $reasons];
    }

    /**
     * Calculate engagement risk score (0-20 points).
     */
    private function calculateEngagementRisk(Student $student): array
    {
        $score = 0;
        $reasons = [];

        // Check user activity (last 14 days)
        if ($student->user) {
            try {
                $lastActivity = DB::table('activities')
                    ->where('causer_id', $student->user_id)
                    ->where('created_at', '>=', now()->subDays(14))
                    ->count();

                if ($lastActivity === 0) {
                    $score += 15;
                    $reasons[] = ' لا يوجد نشاط في آخر 14 يوم';
                } elseif ($lastActivity <= 2) {
                    $score += 8;
                    $reasons[] = 'نشاط منخفض - '.($lastActivity).' أنشطة فقط في آخر 14 يوم';
                }
            } catch (\Exception $e) {
                // activities table may not exist, skip this check
            }
        }

        // Check enrollment progress
        $enrollments = DB::table('enrollments')
            ->where('user_id', $student->user_id)
            ->where('tenant_id', $student->tenant_id)
            ->where('status', 'active')
            ->get();

        foreach ($enrollments as $enrollment) {
            if ($enrollment->remaining_sessions <= 0 && $enrollment->progress < 80) {
                $score = min($score + 5, 20);
                $reasons[] = 'تقدم منخفض في الدورة';
                break;
            }
        }

        return ['score' => min($score, 20), 'reasons' => $reasons];
    }

    /**
     * Get risk level based on score.
     */
    private function getRiskLevel(int $score): string
    {
        if ($score >= 80) {
            return 'critical';
        } elseif ($score >= 60) {
            return 'high';
        } elseif ($score >= 30) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    /**
     * Calculate and update risk for all students in a tenant.
     */
    public function calculateRisksForTenant(int $tenantId): array
    {
        $students = Student::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->with('user')
            ->get();

        $results = [
            'total' => $students->count(),
            'critical' => 0,
            'high' => 0,
            'medium' => 0,
            'low' => 0,
            'students' => [],
        ];

        foreach ($students as $student) {
            $risk = $this->calculateRisk($student);

            // Update student record
            $student->update([
                'risk_score' => $risk['score'],
                'risk_level' => $risk['level'],
                'last_risk_check_at' => now(),
                'risk_reasons' => $risk['reasons'],
            ]);

            // Track results
            $results[$risk['level']]++;
            $results['students'][] = [
                'id' => $student->id,
                'name' => $student->name,
                'score' => $risk['score'],
                'level' => $risk['level'],
                'reasons' => $risk['reasons'],
            ];

            // Broadcast event for high/critical risks
            if (in_array($risk['level'], ['high', 'critical'])) {
                event(new RiskDetected(
                    $student->name,
                    $risk['level'],
                    implode(', ', $risk['reasons']),
                    $tenantId
                ));
            }
        }

        return $results;
    }

    /**
     * Get at-risk students for a tenant.
     */
    public function getAtRiskStudents(int $tenantId, string $minLevel = 'medium'): array
    {
        $levelOrder = ['low' => 0, 'medium' => 1, 'high' => 2, 'critical' => 3];
        $minLevelValue = $levelOrder[$minLevel] ?? 1;

        return Student::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->where(function ($query) use ($levelOrder, $minLevelValue) {
                foreach ($levelOrder as $level => $value) {
                    if ($value >= $minLevelValue) {
                        $query->orWhere('risk_level', $level);
                    }
                }
            })
            ->orderByDesc('risk_score')
            ->get()
            ->map(fn ($student) => [
                'id' => $student->id,
                'name' => $student->name,
                'phone' => $student->phone,
                'risk_score' => $student->risk_score,
                'risk_level' => $student->risk_level,
                'risk_reasons' => $student->risk_reasons,
                'last_risk_check_at' => $student->last_risk_check_at,
            ])
            ->toArray();
    }
}
