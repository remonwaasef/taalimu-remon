<?php

namespace App\Queries;

use App\Models\Student;
use App\Models\Course;
use App\Models\Sale;
use App\Models\Instructor;
use Modules\Center\Models\Attendance;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * CenterAnalyticsQuery — استعلامات تحليلية ثقيلة للوحة التحكم.
 * 
 * جميع النتائج مخزّنة مؤقتاً (cached) لتجنب تكرار JOINs و GROUP BY
 * في كل زيارة للوحة التحكم. يتم إلغاء الكاش تلقائياً عبر ClearsDashboardCache Trait.
 */
class CenterAnalyticsQuery
{
    /**
     * مدة التخزين المؤقت بالدقائق.
     */
    protected const CACHE_TTL_MINUTES = 30;

    /**
     * بادئة مفتاح الكاش — تتضمن tenant_id للعزل.
     */
    protected function cacheKey(string $suffix): string
    {
        $tenantId = app()->bound('tenant') ? app('tenant')->id : 'global';
        return "analytics_{$tenantId}_{$suffix}";
    }

    protected function getDateFormatSql($column)
    {
        return DB::connection()->getDriverName() === 'sqlite' 
            ? "strftime('%Y-%m', {$column})"
            : "DATE_FORMAT({$column},'%Y-%m')";
    }

    /**
     * Get Monthly Revenue for the last $months.
     */
    public function getMonthlyRevenue(int $months = 6)
    {
        return Cache::remember($this->cacheKey("revenue_{$months}"), now()->addMinutes(self::CACHE_TTL_MINUTES), function () use ($months) {
            return Sale::select(
                DB::raw('sum(paid_amount) as sums'), 
                DB::raw($this->getDateFormatSql('created_at') . " as months")
            )
            ->where('created_at', '>=', now()->subMonths($months))
            ->groupBy('months')
            ->orderBy('months')
            ->get();
        });
    }

    /**
     * Get Student Growth for the last $months.
     */
    public function getStudentGrowth(int $months = 6)
    {
        return Cache::remember($this->cacheKey("student_growth_{$months}"), now()->addMinutes(self::CACHE_TTL_MINUTES), function () use ($months) {
            return Student::select(
                DB::raw('count(*) as count'), 
                DB::raw($this->getDateFormatSql('created_at') . " as months")
            )
            ->where('created_at', '>=', now()->subMonths($months))
            ->groupBy('months')
            ->orderBy('months')
            ->get();
        });
    }

    /**
     * Get Attendance Stats grouped by status.
     */
    public function getAttendanceStats()
    {
        return Cache::remember($this->cacheKey('attendance_stats'), now()->addMinutes(self::CACHE_TTL_MINUTES), function () {
            return Attendance::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status');
        });
    }

    /**
     * Get Course Performance (Avg Score using DB aggregation).
     */
    public function getCoursePerformance(int $limit = 5)
    {
        return Cache::remember($this->cacheKey("course_perf_{$limit}"), now()->addMinutes(self::CACHE_TTL_MINUTES), function () use ($limit) {
            return Course::select(
                    'courses.id', 
                    'courses.title as name',
                    DB::raw('ROUND(AVG(quiz_attempts.score), 1) as avg_score'),
                    DB::raw('COUNT(DISTINCT quiz_attempts.id) as total_attempts')
                )
                ->join('sections', 'sections.course_id', '=', 'courses.id')
                ->join('lessons', 'lessons.section_id', '=', 'sections.id')
                ->join('quizzes', 'quizzes.lesson_id', '=', 'lessons.id')
                ->join('quiz_attempts', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
                ->groupBy('courses.id', 'courses.title')
                ->having('total_attempts', '>', 0)
                ->orderByDesc('avg_score')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get Students by Grade Level.
     */
    public function getStudentsByGrade()
    {
        return Cache::remember($this->cacheKey('students_by_grade'), now()->addMinutes(self::CACHE_TTL_MINUTES), function () {
            return Student::select('grade_level', DB::raw('count(*) as count'))
                ->groupBy('grade_level')
                ->orderBy('count', 'desc')
                ->get();
        });
    }

    /**
     * مسح الكاش لمستأجر محدد (يُستدعى من ClearsDashboardCache Trait).
     */
    public static function clearCacheForTenant(int $tenantId): void
    {
        $keys = ['revenue_6', 'student_growth_6', 'attendance_stats', 'course_perf_5', 'students_by_grade'];
        foreach ($keys as $key) {
            Cache::forget("analytics_{$tenantId}_{$key}");
        }
    }
}
