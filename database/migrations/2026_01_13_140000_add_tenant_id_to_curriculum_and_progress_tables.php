<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'sections' => 'course_id',
            'lessons' => 'section_id',
            'quizzes' => 'lesson_id',
            'questions' => 'quiz_id',
            'question_options' => 'question_id',
            'assignments' => 'lesson_id',
            'assignment_submissions' => 'assignment_id',
            'lesson_progress' => 'enrollment_id',
            'quiz_attempts' => 'quiz_id',
        ];

        foreach ($tables as $table => $parentColumn) {
            if (!Schema::hasColumn($table, 'tenant_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->foreignId('tenant_id')->nullable()->after('id')->index();
                });
            }
        }

        // Populate tenant_id for curriculum tables (nested joins)
        // Sections
        DB::statement("UPDATE sections SET tenant_id = (SELECT tenant_id FROM courses WHERE courses.id = sections.course_id) WHERE tenant_id IS NULL");
        // Lessons
        DB::statement("UPDATE lessons SET tenant_id = (SELECT tenant_id FROM sections WHERE sections.id = lessons.section_id) WHERE tenant_id IS NULL");
        // Quizzes
        DB::statement("UPDATE quizzes SET tenant_id = (SELECT tenant_id FROM lessons WHERE lessons.id = quizzes.lesson_id) WHERE tenant_id IS NULL");
        // Assignments
        DB::statement("UPDATE assignments SET tenant_id = (SELECT tenant_id FROM lessons WHERE lessons.id = assignments.lesson_id) WHERE tenant_id IS NULL");
        // Questions
        DB::statement("UPDATE questions SET tenant_id = (SELECT tenant_id FROM quizzes WHERE quizzes.id = questions.quiz_id) WHERE tenant_id IS NULL");
        // Options
        DB::statement("UPDATE question_options SET tenant_id = (SELECT tenant_id FROM questions WHERE questions.id = question_options.question_id) WHERE tenant_id IS NULL");
        
        // Progress tables
        // Lesson Progress (from enrollment)
        DB::statement("UPDATE lesson_progress SET tenant_id = (SELECT tenant_id FROM enrollments WHERE enrollments.id = lesson_progress.enrollment_id) WHERE tenant_id IS NULL");
        // Quiz Attempts
        DB::statement("UPDATE quiz_attempts SET tenant_id = (SELECT tenant_id FROM quizzes WHERE quizzes.id = quiz_attempts.quiz_id) WHERE tenant_id IS NULL");
        // Submissions
        DB::statement("UPDATE assignment_submissions SET tenant_id = (SELECT tenant_id FROM assignments WHERE assignments.id = assignment_submissions.assignment_id) WHERE tenant_id IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'sections', 'lessons', 'quizzes', 'questions', 'question_options', 
            'assignments', 'assignment_submissions', 'lesson_progress', 'quiz_attempts'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('tenant_id');
            });
        }
    }
};
