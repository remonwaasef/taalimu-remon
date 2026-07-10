<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // students.user_id - critical for attendance & enrollment lookups
        Schema::table('students', function (Blueprint $table) {
            $table->index('user_id', 'idx_students_user_id');
        });

        // sales.student_id - critical for financial queries
        Schema::table('sales', function (Blueprint $table) {
            $table->index('student_id', 'idx_sales_student_id');
        });

        // Composite indexes for common query patterns
        Schema::table('sales', function (Blueprint $table) {
            $table->index(['tenant_id', 'student_id'], 'idx_sales_tenant_student');
            $table->index(['tenant_id', 'status', 'created_at'], 'idx_sales_tenant_status_created');
        });

        // commissions.sale_id + status - used in refund processing
        Schema::table('commissions', function (Blueprint $table) {
            $table->index(['sale_id', 'status'], 'idx_commissions_sale_status');
        });

        // operation_issues.fingerprint - used in duplicate detection
        Schema::table('operation_issues', function (Blueprint $table) {
            $table->index('fingerprint', 'idx_operation_issues_fingerprint');
        });

        // payment_reminders - used in send payment reminders command
        Schema::table('payment_reminders', function (Blueprint $table) {
            $table->index(['tenant_id', 'reminder_year', 'reminder_month', 'status'], 'idx_payment_reminders_tenant_year_month_status');
        });

        // point_logs.user_id - used in GDPR export and gamification
        Schema::table('point_logs', function (Blueprint $table) {
            $table->index('user_id', 'idx_point_logs_user_id');
        });

        // activity_log - used in GDPR export
        Schema::table('activity_log', function (Blueprint $table) {
            $table->index(['causer_id', 'causer_type'], 'idx_activity_log_causer');
        });

        // enrollments - used in attendance and progress queries
        Schema::table('enrollments', function (Blueprint $table) {
            $table->index(['user_id', 'course_id'], 'idx_enrollments_user_course');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('idx_students_user_id');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex('idx_sales_student_id');
            $table->dropIndex('idx_sales_tenant_student');
            $table->dropIndex('idx_sales_tenant_status_created');
        });

        Schema::table('commissions', function (Blueprint $table) {
            $table->dropIndex('idx_commissions_sale_status');
        });

        Schema::table('operation_issues', function (Blueprint $table) {
            $table->dropIndex('idx_operation_issues_fingerprint');
        });

        Schema::table('payment_reminders', function (Blueprint $table) {
            $table->dropIndex('idx_payment_reminders_tenant_year_month_status');
        });

        Schema::table('point_logs', function (Blueprint $table) {
            $table->dropIndex('idx_point_logs_user_id');
        });

        Schema::table('activity_log', function (Blueprint $table) {
            $table->dropIndex('idx_activity_log_causer');
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex('idx_enrollments_user_course');
        });
    }
};
