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
        // 1. Drop existing unique index if it exists
        try {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->dropUnique('site_settings_key_unique');
            });
        } catch (\Throwable $e) {}

        try {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->dropUnique(['key']);
            });
        } catch (\Throwable $e) {}

        // 2. Add tenant_id column if not exists
        if (! Schema::hasColumn('site_settings', 'tenant_id')) {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            });
        }

        // 3. Create composite unique key
        try {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->unique(['tenant_id', 'key']);
            });
        } catch (\Throwable $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try { Schema::table('site_settings', function (Blueprint $table) {
            $table->dropUnique(['tenant_id', 'key']);
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
            
            $table->unique('key');
        }); } catch (\Exception $e) {}
    }
};
