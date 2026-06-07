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
        // 1. Remove cascadeOnDelete from sales and payments
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->restrictOnDelete();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->restrictOnDelete();
        });

        // 2. Add tenant_id to user_consents
        Schema::table('user_consents', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });

        // 3. Fix tenant_id type in classrooms and schedules (Warning: This assumes tenant_id strings were actually numbers)
        // Only doing this if requested by the plan.
        Schema::table('classrooms', function (Blueprint $table) {
            // Drop index first if it exists
            $table->dropIndex(['tenant_id']);
            $table->unsignedBigInteger('tenant_id')->change();
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });

        Schema::table('schedules', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['tenant_id', 'day_of_week']);
            $table->dropIndex('sch_tenant_course_day_idx');
            
            $table->unsignedBigInteger('tenant_id')->change();
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            
            // Re-add indexes
            $table->index(['tenant_id', 'day_of_week']);
            $table->index(['tenant_id', 'course_id', 'day_of_week'], 'sch_tenant_course_day_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->string('tenant_id')->change();
        });

        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->string('tenant_id')->change();
            $table->index('tenant_id');
        });

        Schema::table('user_consents', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });
    }
};
