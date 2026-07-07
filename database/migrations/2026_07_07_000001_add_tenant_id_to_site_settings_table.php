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
        Schema::table('site_settings', function (Blueprint $table) {
            // Drop unique index for key
            $table->dropUnique('site_settings_key_unique');

            // Add tenant_id column
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->cascadeOnDelete();

            // Create composite unique key
            $table->unique(['tenant_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropUnique(['tenant_id', 'key']);
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
            
            $table->unique('key');
        });
    }
};
