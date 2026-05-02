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
        Schema::table('users', function (Blueprint $table) {
            // Drop global unique constraints
            $table->dropUnique('users_email_unique');
            $table->dropUnique('users_phone_unique');

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
        Schema::table('users', function (Blueprint $table) {
            // Drop tenant-specific unique constraints
            $table->dropUnique('tenant_email_unique');
            $table->dropUnique('tenant_phone_unique');

            // Restore global unique constraints
            $table->unique('email', 'users_email_unique');
            $table->unique('phone', 'users_phone_unique');
        });
    }
};
