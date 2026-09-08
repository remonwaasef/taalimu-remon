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
        // 1. Students Table Indexes
        try { Schema::table('students', function (Blueprint $table) {
            $table->index(['tenant_id', 'grade_id'], 'students_tenant_grade_idx');
        }); } catch (\Throwable $e) {}

        // 2. Users Table Indexes
        try { Schema::table('users', function (Blueprint $table) {
            $table->index(['tenant_id', 'email'], 'users_tenant_email_idx');
        }); } catch (\Throwable $e) {}

        // 3. Attendances Table Indexes
        try { Schema::table('attendances', function (Blueprint $table) {
            $table->index(['student_id', 'session_date'], 'attendances_student_date_idx');
        }); } catch (\Throwable $e) {}

        // 4. Payments Table Indexes
        try { Schema::table('payments', function (Blueprint $table) {
            $table->index(['tenant_id', 'paid_at'], 'payments_tenant_paid_at_idx');
            $table->index(['sale_id', 'paid_at'], 'payments_sale_paid_at_idx');
        }); } catch (\Throwable $e) {}

        // 5. Subscriptions Table Indexes
        try { Schema::table('subscriptions', function (Blueprint $table) {
            $table->index(['tenant_id', 'status'], 'subscriptions_tenant_status_idx');
            $table->index(['ends_at', 'status'], 'subscriptions_ends_at_status_idx');
        }); } catch (\Throwable $e) {}

        // 6. Invoices Table Indexes
        try { Schema::table('invoices', function (Blueprint $table) {
            $table->index(['tenant_id', 'created_at'], 'invoices_tenant_created_at_idx');
        }); } catch (\Throwable $e) {}

        // 7. Schedules Table Indexes
        try { Schema::table('schedules', function (Blueprint $table) {
            $table->index(['tenant_id', 'classroom_id'], 'schedules_tenant_classroom_idx');
        }); } catch (\Throwable $e) {}

        // 8. Tickets Table Indexes
        try { Schema::table('tickets', function (Blueprint $table) {
            $table->index(['tenant_id', 'status'], 'tickets_tenant_status_idx');
        }); } catch (\Throwable $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try { Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex('tickets_tenant_status_idx');
        }); } catch (\Exception $e) {}

        try { Schema::table('schedules', function (Blueprint $table) {
            $table->dropIndex('schedules_tenant_classroom_idx');
        }); } catch (\Exception $e) {}

        try { Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex('invoices_tenant_created_at_idx');
        }); } catch (\Exception $e) {}

        try { Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex('subscriptions_tenant_status_idx');
            $table->dropIndex('subscriptions_ends_at_status_idx');
        }); } catch (\Exception $e) {}

        try { Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('payments_tenant_paid_at_idx');
            $table->dropIndex('payments_sale_paid_at_idx');
        }); } catch (\Exception $e) {}

        try { Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex('attendances_student_date_idx');
        }); } catch (\Exception $e) {}

        try { Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_tenant_email_idx');
        }); } catch (\Exception $e) {}

        try { Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('students_tenant_grade_idx');
        }); } catch (\Exception $e) {}
    }
};
