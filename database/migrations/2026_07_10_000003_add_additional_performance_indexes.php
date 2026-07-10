<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->index('user_id', 'idx_quiz_attempts_user_id');
        });

        Schema::table('point_logs', function (Blueprint $table) {
            $table->index('created_at', 'idx_point_logs_created_at');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->index(['day_of_week', 'instructor_id', 'course_id'], 'idx_schedules_day_instructor_course');
        });

        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->index(['enrollment_id', 'lesson_id'], 'idx_lesson_progress_enrollment_lesson');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->index('registration_token', 'idx_courses_registration_token');
        });

        if (Schema::hasTable('activity_log')) {
            $tableName = config('activitylog.table_name', 'activity_log');
            Schema::table($tableName, function (Blueprint $table) {
                $table->index(['subject_id', 'subject_type'], 'idx_activity_log_subject');
            });
        }
    }

    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropIndex('idx_quiz_attempts_user_id');
        });

        Schema::table('point_logs', function (Blueprint $table) {
            $table->dropIndex('idx_point_logs_created_at');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropIndex('idx_schedules_day_instructor_course');
        });

        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->dropIndex('idx_lesson_progress_enrollment_lesson');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex('idx_courses_registration_token');
        });

        if (Schema::hasTable('activity_log')) {
            $tableName = config('activitylog.table_name', 'activity_log');
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropIndex('idx_activity_log_subject');
            });
        }
    }
};
