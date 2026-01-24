<?php

namespace App\Queries;

use App\Models\Student;
use App\Models\Course;
use App\Models\Sale;
use App\Models\Instructor;
use Modules\Center\Models\Attendance;
use Illuminate\Support\Facades\DB;

class CenterAnalyticsQuery
{
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
        return Sale::select(
            DB::raw('sum(paid_amount) as sums'), 
            DB::raw($this->getDateFormatSql('created_at') . " as months")
        )
        ->where('created_at', '>=', now()->subMonths($months))
        ->groupBy('months')
        ->orderBy('months')
        ->get();
    }

    /**
     * Get Student Growth for the last $months.
     */
    public function getStudentGrowth(int $months = 6)
    {
        return Student::select(
            DB::raw('count(*) as count'), 
            DB::raw($this->getDateFormatSql('created_at') . " as months")
        )
        ->where('created_at', '>=', now()->subMonths($months))
        ->groupBy('months')
        ->orderBy('months')
        ->get();
    }

    /**
     * Get Attendance Stats grouped by status.
     */
    public function getAttendanceStats()
    {
        return Attendance::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
    }

    /**
     * Get Course Performance (Avg Score using DB aggregation).
     */
    public function getCoursePerformance(int $limit = 5)
    {
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
    }

    /**
     * Get Students by Grade Level.
     */
    public function getStudentsByGrade()
    {
        return Student::select('grade_level', DB::raw('count(*) as count'))
            ->groupBy('grade_level')
            ->orderBy('count', 'desc')
            ->get();
    }
}
