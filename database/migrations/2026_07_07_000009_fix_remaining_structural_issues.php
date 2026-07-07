<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إصلاح FK في online_classes من users إلى instructors،
     * وتغيير subscriptions.tenant_id إلى restrictOnDelete،
     * وإضافة tenant_id للكوبونات،
     * وإضافة قيد تفرد لتسليمات الواجبات.
     */
    public function up(): void
    {
        // 1. Fix online_classes.instructor_id FK: users → instructors
        try {
            Schema::table('online_classes', function (Blueprint $table) {
                $table->dropForeign(['instructor_id']);
                $table->foreign('instructor_id')->references('id')->on('instructors')->nullOnDelete();
            });
        } catch (\Exception $e) {
            \Log::warning('Could not update online_classes.instructor_id FK: '.$e->getMessage());
        }

        // 2. Fix subscriptions.tenant_id cascadeOnDelete → restrictOnDelete
        try {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropForeign(['tenant_id']);
                $table->foreign('tenant_id')->references('id')->on('tenants')->restrictOnDelete();
            });
        } catch (\Exception $e) {
            \Log::warning('Could not update subscriptions.tenant_id FK: '.$e->getMessage());
        }

        // 3. Add tenant_id to coupons for tenant isolation
        if (! Schema::hasColumn('coupons', 'tenant_id')) {
            Schema::table('coupons', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
                $table->index('tenant_id');
            });
        }

        // 4. Add unique constraint to assignment_submissions
        try {
            Schema::table('assignment_submissions', function (Blueprint $table) {
                $table->unique(['assignment_id', 'user_id'], 'submissions_assignment_user_unique');
            });
        } catch (\Exception $e) {
            \Log::warning('Could not add unique to assignment_submissions: '.$e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert assignment_submissions unique
        try {
            Schema::table('assignment_submissions', function (Blueprint $table) {
                $table->dropUnique('submissions_assignment_user_unique');
            });
        } catch (\Exception $e) {
            \Log::warning('Could not drop assignment_submissions unique: '.$e->getMessage());
        }

        // Revert coupons tenant_id
        if (Schema::hasColumn('coupons', 'tenant_id')) {
            Schema::table('coupons', function (Blueprint $table) {
                $table->dropIndex(['tenant_id']);
                $table->dropColumn('tenant_id');
            });
        }

        // Revert subscriptions FK
        try {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropForeign(['tenant_id']);
                $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            });
        } catch (\Exception $e) {
            \Log::warning('Could not revert subscriptions.tenant_id FK: '.$e->getMessage());
        }

        // Revert online_classes FK
        try {
            Schema::table('online_classes', function (Blueprint $table) {
                $table->dropForeign(['instructor_id']);
                $table->foreign('instructor_id')->references('id')->on('users')->nullOnDelete();
            });
        } catch (\Exception $e) {
            \Log::warning('Could not revert online_classes.instructor_id FK: '.$e->getMessage());
        }
    }
};
