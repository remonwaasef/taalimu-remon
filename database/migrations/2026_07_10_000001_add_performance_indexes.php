<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // students.user_id - critical for attendance & enrollment lookups
        try { Schema::table('students', function (Blueprint $table) {
            $table->index('user_id', 'idx_students_user_id');
        }); } catch (\Throwable $e) {}

        // sales.student_id - critical for financial queries
        try { Schema::table('sales', function (Blueprint $table) {
            $table->index('student_id', 'idx_sales_student_id');
        }); } catch (\Throwable $e) {}

        // Composite indexes for common query patterns
        try { Schema::table('sales', function (Blueprint $table) {
            $table->index(['tenant_id', 'student_id'], 'idx_sales_tenant_student');
            $table->index(['tenant_id', 'status', 'created_at'], 'idx_sales_tenant_status_created');
        }); } catch (\Throwable $e) {}

        // commissions.sale_id + status - used in refund processing
        try { Schema::table('commissions', function (Blueprint $table) {
            $table->index(['sale_id', 'status'], 'idx_commissions_sale_status');
        }); } catch (\Throwable $e) {}

        // operation_issues.fingerprint - used in duplicate detection
        try { Schema::table('operation_issues', function (Blueprint $table) {
            $table->index('fingerprint', 'idx_operation_issues_fingerprint');
        }); } catch (\Throwable $e) {}

        // payment_reminders - used in send payment reminders command
        try { Schema::table('payment_reminders', function (Blueprint $table) {
            $table->index(['tenant_id', 'reminder_year', 'reminder_month', 'status'], 'idx_payment_reminders_tenant_year_month_status');
        }); } catch (\Throwable $e) {}

        // point_logs.user_id - used in GDPR export and gamification
        try { Schema::table('point_logs', function (Blueprint $table) {
            $table->index('user_id', 'idx_point_logs_user_id');
        }); } catch (\Throwable $e) {}

        // activity_log - used in GDPR export
        try { Schema::table('activity_log', function (Blueprint $table) {
            $table->index(['causer_id', 'causer_type'], 'idx_activity_log_causer');
        }); } catch (\Throwable $e) {}

        // enrollments - used in attendance and progress queries
        try { Schema::table('enrollments', function (Blueprint $table) {
            $table->index(['user_id', 'course_id'], 'idx_enrollments_user_course');
        }); } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        try { Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('idx_students_user_id');
        }); } catch (\Exception $e) {}

        try { Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex('idx_sales_student_id');
            $table->dropIndex('idx_sales_tenant_student');
            $table->dropIndex('idx_sales_tenant_status_created');
        }); } catch (\Exception $e) {}

        try { Schema::table('commissions', function (Blueprint $table) {
            $table->dropIndex('idx_commissions_sale_status');
        }); } catch (\Exception $e) {}

        try { Schema::table('operation_issues', function (Blueprint $table) {
            $table->dropIndex('idx_operation_issues_fingerprint');
        }); } catch (\Exception $e) {}

        try { Schema::table('payment_reminders', function (Blueprint $table) {
            $table->dropIndex('idx_payment_reminders_tenant_year_month_status');
        }); } catch (\Exception $e) {}

        try { Schema::table('point_logs', function (Blueprint $table) {
            $table->dropIndex('idx_point_logs_user_id');
        }); } catch (\Exception $e) {}

        try { Schema::table('activity_log', function (Blueprint $table) {
            $table->dropIndex('idx_activity_log_causer');
        }); } catch (\Exception $e) {}

        try { Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex('idx_enrollments_user_course');
        }); } catch (\Exception $e) {}
    }
};
