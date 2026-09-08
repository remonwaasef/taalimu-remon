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
        // Drop global unique constraint on email safely (handles different index names and ignores if not exists)
        try {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE users DROP INDEX users_email_unique');
        } catch (\Exception $e) {
            // ignore
        }
        try {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE users DROP INDEX email');
        } catch (\Exception $e) {
            // ignore
        }

        Schema::table('users', function (Blueprint $table) {
            // Add tenant-specific unique constraints
            $table->unique(['tenant_id', 'email'], 'tenant_email_unique');
            $table->unique(['tenant_id', 'phone'], 'tenant_phone_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try { Schema::table('users', function (Blueprint $table) {
            // Drop tenant-specific unique constraints
            $table->dropUnique('tenant_email_unique');
            $table->dropUnique('tenant_phone_unique');

            // Restore global unique constraints
            $table->unique('email', 'users_email_unique');
        }); } catch (\Exception $e) {}
    }
};
