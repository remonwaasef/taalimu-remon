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
        Schema::table('students', function (Blueprint $table) {
            $indexes = collect(DB::select(DB::getDriverName() === 'sqlite' ? "PRAGMA index_list('students')" : "SHOW INDEXES FROM students"))->pluck(DB::getDriverName() === 'sqlite' ? 'name' : 'Key_name')->all();
            
            if (!in_array('students_tenant_id_status_index', $indexes)) {
               $table->index(['tenant_id', 'status']);
            }
            if (!in_array('students_tenant_id_grade_id_index', $indexes)) {
               $table->index(['tenant_id', 'grade_id']);
            }
        });

        Schema::table('courses', function (Blueprint $table) {
             $indexes = collect(DB::select(DB::getDriverName() === 'sqlite' ? "PRAGMA index_list('courses')" : "SHOW INDEXES FROM courses"))->pluck(DB::getDriverName() === 'sqlite' ? 'name' : 'Key_name')->all();
             if (!in_array('courses_tenant_id_status_index', $indexes)) {
                $table->index(['tenant_id', 'status']);
             }
        });

        Schema::table('enrollments', function (Blueprint $table) {
             $indexes = collect(DB::select(DB::getDriverName() === 'sqlite' ? "PRAGMA index_list('enrollments')" : "SHOW INDEXES FROM enrollments"))->pluck(DB::getDriverName() === 'sqlite' ? 'name' : 'Key_name')->all();
             if (!in_array('enr_tenant_course_user_idx', $indexes)) {
                $table->index(['tenant_id', 'course_id', 'user_id'], 'enr_tenant_course_user_idx');
             }
        });

        Schema::table('schedules', function (Blueprint $table) {
             $indexes = collect(DB::select(DB::getDriverName() === 'sqlite' ? "PRAGMA index_list('schedules')" : "SHOW INDEXES FROM schedules"))->pluck(DB::getDriverName() === 'sqlite' ? 'name' : 'Key_name')->all();
             if (!in_array('sch_tenant_course_day_idx', $indexes)) {
                $table->index(['tenant_id', 'course_id', 'day_of_week'], 'sch_tenant_course_day_idx');
             }
        });
        
        Schema::table('sales', function (Blueprint $table) {
             $indexes = collect(DB::select(DB::getDriverName() === 'sqlite' ? "PRAGMA index_list('sales')" : "SHOW INDEXES FROM sales"))->pluck(DB::getDriverName() === 'sqlite' ? 'name' : 'Key_name')->all();
             if (!in_array('sales_tenant_id_status_created_at_index', $indexes)) {
                $table->index(['tenant_id', 'status', 'created_at']);
             }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'status']);
            $table->dropIndex(['tenant_id', 'grade_id']);
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'status']);
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex('enr_tenant_course_user_idx');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropIndex('sch_tenant_course_day_idx');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'status', 'created_at']);
        });
    }
};
