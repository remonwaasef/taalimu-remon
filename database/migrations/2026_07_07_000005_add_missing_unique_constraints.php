<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إضافة قيود التفرد المفقودة لمنع التسجيل المزدوج والحجز المزدوج والحضور المزدوج.
     */
    public function up(): void
    {
        // 1. Enrollments: prevent double enrollment in same course
        try {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->unique(['user_id', 'course_id'], 'enrollments_user_course_unique');
            });
        } catch (\Throwable $e) {}

        // 2. Bookings: prevent double booking in same schedule
        try {
            Schema::table('bookings', function (Blueprint $table) {
                $table->unique(['student_id', 'schedule_id'], 'bookings_student_schedule_unique');
            });
        } catch (\Throwable $e) {}

        // 3. Attendances: prevent duplicate attendance record per session
        try {
            Schema::table('attendances', function (Blueprint $table) {
                $table->unique(
                    ['student_id', 'schedule_id', 'session_date'],
                    'attendances_student_schedule_date_unique'
                );
            });
        } catch (\Throwable $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try { Schema::table('enrollments', function (Blueprint $table) {
            $table->dropUnique('enrollments_user_course_unique');
        }); } catch (\Exception $e) {}

        try { Schema::table('bookings', function (Blueprint $table) {
            $table->dropUnique('bookings_student_schedule_unique');
        }); } catch (\Exception $e) {}

        try { Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique('attendances_student_schedule_date_unique');
        }); } catch (\Exception $e) {}
    }
};
