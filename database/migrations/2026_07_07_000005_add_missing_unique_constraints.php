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
        Schema::table('enrollments', function (Blueprint $table) {
            $table->unique(['user_id', 'course_id'], 'enrollments_user_course_unique');
        });

        // 2. Bookings: prevent double booking in same schedule
        Schema::table('bookings', function (Blueprint $table) {
            $table->unique(['student_id', 'schedule_id'], 'bookings_student_schedule_unique');
        });

        // 3. Attendances: prevent duplicate attendance record per session
        Schema::table('attendances', function (Blueprint $table) {
            $table->unique(
                ['student_id', 'schedule_id', 'session_date'],
                'attendances_student_schedule_date_unique'
            );
        });
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
