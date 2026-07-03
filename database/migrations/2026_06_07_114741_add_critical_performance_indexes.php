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
        try {
            Schema::table('commissions', function (Blueprint $table) {
                $table->index(['instructor_id', 'status'], 'idx_comm_inst_status');
            });
        } catch (\Exception $e) {
        }

        try {
            Schema::table('payments', function (Blueprint $table) {
                $table->index(['tenant_id', 'paid_at'], 'idx_pay_tenant_paid');
            });
        } catch (\Exception $e) {
        }

        try {
            Schema::table('attendances', function (Blueprint $table) {
                $table->index(['tenant_id', 'student_id', 'session_date'], 'idx_att_tenant_stu_date');
            });
        } catch (\Exception $e) {
        }

        try {
            Schema::table('bookings', function (Blueprint $table) {
                $table->index(['schedule_id', 'status'], 'idx_book_sch_status');
            });
        } catch (\Exception $e) {
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropIndex('idx_book_sch_status');
            });
        } catch (\Exception $e) {
        }

        try {
            Schema::table('attendances', function (Blueprint $table) {
                $table->dropIndex('idx_att_tenant_stu_date');
            });
        } catch (\Exception $e) {
        }

        try {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropIndex('idx_pay_tenant_paid');
            });
        } catch (\Exception $e) {
        }

        try {
            Schema::table('commissions', function (Blueprint $table) {
                $table->dropIndex('idx_comm_inst_status');
            });
        } catch (\Exception $e) {
        }
    }
};
