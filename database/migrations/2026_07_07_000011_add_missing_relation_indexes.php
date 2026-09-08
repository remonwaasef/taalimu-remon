<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Pivot Table: guardian_student indexes
        try { Schema::table('guardian_student', function (Blueprint $table) {
            $table->index(['student_id', 'guardian_id'], 'guardian_student_reverse_idx');
        }); } catch (\Throwable $e) {}

        // 2. Pivot Table: course_instructor indexes
        try { Schema::table('course_instructor', function (Blueprint $table) {
            $table->index('instructor_id', 'course_instructor_inst_idx');
        }); } catch (\Throwable $e) {}

        // 3. Sections & Lessons Table indexes
        try { Schema::table('sections', function (Blueprint $table) {
            $table->index('course_id', 'sections_course_id_idx');
        }); } catch (\Throwable $e) {}

        try { Schema::table('lessons', function (Blueprint $table) {
            $table->index('section_id', 'lessons_section_id_idx');
        }); } catch (\Throwable $e) {}

        // 4. Bookings & Attendances Table missing indexes
        try { Schema::table('bookings', function (Blueprint $table) {
            $table->index('schedule_id', 'bookings_schedule_id_idx');
        }); } catch (\Throwable $e) {}

        try { Schema::table('attendances', function (Blueprint $table) {
            $table->index('schedule_id', 'attendances_schedule_id_idx');
        }); } catch (\Throwable $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try { Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex('attendances_schedule_id_idx');
        }); } catch (\Exception $e) {}

        try { Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('bookings_schedule_id_idx');
        }); } catch (\Exception $e) {}

        try { Schema::table('lessons', function (Blueprint $table) {
            $table->dropIndex('lessons_section_id_idx');
        }); } catch (\Exception $e) {}

        try { Schema::table('sections', function (Blueprint $table) {
            $table->dropIndex('sections_course_id_idx');
        }); } catch (\Exception $e) {}

        try { Schema::table('course_instructor', function (Blueprint $table) {
            $table->dropIndex('course_instructor_inst_idx');
        }); } catch (\Exception $e) {}

        try { Schema::table('guardian_student', function (Blueprint $table) {
            $table->dropIndex('guardian_student_reverse_idx');
        }); } catch (\Exception $e) {}
    }
};
