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
        Schema::table('students', function (Blueprint $table) {
            $table->index(['tenant_id', 'grade_id'], 'students_tenant_grade_idx');
        });

        // 2. Users Table Indexes
        Schema::table('users', function (Blueprint $table) {
            $table->index(['tenant_id', 'email'], 'users_tenant_email_idx');
        });

        // 3. Attendances Table Indexes
        Schema::table('attendances', function (Blueprint $table) {
            $table->index(['student_id', 'session_date'], 'attendances_student_date_idx');
        });

        // 4. Payments Table Indexes
        Schema::table('payments', function (Blueprint $table) {
            $table->index(['tenant_id', 'paid_at'], 'payments_tenant_paid_at_idx');
            $table->index(['sale_id', 'paid_at'], 'payments_sale_paid_at_idx');
        });

        // 5. Subscriptions Table Indexes
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->index(['tenant_id', 'status'], 'subscriptions_tenant_status_idx');
            $table->index(['ends_at', 'status'], 'subscriptions_ends_at_status_idx');
        });

        // 6. Invoices Table Indexes
        Schema::table('invoices', function (Blueprint $table) {
            $table->index(['tenant_id', 'created_at'], 'invoices_tenant_created_at_idx');
        });

        // 7. Schedules Table Indexes
        Schema::table('schedules', function (Blueprint $table) {
            $table->index(['tenant_id', 'classroom_id'], 'schedules_tenant_classroom_idx');
        });

        // 8. Tickets Table Indexes
        Schema::table('tickets', function (Blueprint $table) {
            $table->index(['tenant_id', 'status'], 'tickets_tenant_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex('tickets_tenant_status_idx');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropIndex('schedules_tenant_classroom_idx');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex('invoices_tenant_created_at_idx');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex('subscriptions_tenant_status_idx');
            $table->dropIndex('subscriptions_ends_at_status_idx');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('payments_tenant_paid_at_idx');
            $table->dropIndex('payments_sale_paid_at_idx');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex('attendances_student_date_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_tenant_email_idx');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('students_tenant_grade_idx');
        });
    }
};
