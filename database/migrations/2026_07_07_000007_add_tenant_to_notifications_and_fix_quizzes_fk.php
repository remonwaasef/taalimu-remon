<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إضافة tenant_id للإشعارات وإصلاح FK المفقود في الاختبارات.
     */
    public function up(): void
    {
        // 1. Add tenant_id to notifications for tenant isolation
        if (! Schema::hasColumn('notifications', 'tenant_id')) {
            try {
                Schema::table('notifications', function (Blueprint $table) {
                    $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
                    $table->index('tenant_id');
                });
            } catch (\Throwable $e) {}
        }

        // 2. Fix quizzes.tenant_id - add FK constraint
        try {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            });
        } catch (\Exception $e) {
            \Log::warning('Could not add FK to quizzes.tenant_id: '.$e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try { Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['tenant_id']);
            $table->dropColumn('tenant_id');
        }); } catch (\Exception $e) {}

        try {
            try { Schema::table('quizzes', function (Blueprint $table) {
                $table->dropForeign(['tenant_id']);
            }); } catch (\Exception $e) {}
        } catch (\Exception $e) {
            \Log::warning('Could not drop FK from quizzes.tenant_id: '.$e->getMessage());
        }
    }
};
