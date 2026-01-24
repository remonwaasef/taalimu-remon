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
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasIndex('courses', 'idx_courses_tenant_status')) {
                 $table->index(['tenant_id', 'status'], 'idx_courses_tenant_status');
            }
        });

        Schema::table('enrollments', function (Blueprint $table) {
             if (!Schema::hasIndex('enrollments', 'idx_enrollments_course_user')) {
                $table->index(['course_id', 'user_id'], 'idx_enrollments_course_user');
             }
        });

        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasIndex('sales', 'idx_sales_tenant_created')) {
                $table->index(['tenant_id', 'created_at'], 'idx_sales_tenant_created');
            }
            if (!Schema::hasIndex('sales', 'idx_sales_tenant_status')) {
                $table->index(['tenant_id', 'status'], 'idx_sales_tenant_status');
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasIndex('payments', 'idx_payments_sale_created')) {
                $table->index(['sale_id', 'created_at'], 'idx_payments_sale_created');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex('idx_courses_tenant_status');
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex('idx_enrollments_course_user');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex('idx_sales_tenant_created');
            $table->dropIndex('idx_sales_tenant_status');
        });

        Schema::table('payments', function (Blueprint $table) {
             $table->dropIndex('idx_payments_sale_created');
        });
    }
};
