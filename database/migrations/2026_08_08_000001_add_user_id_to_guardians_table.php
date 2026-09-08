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
        Schema::table('guardians', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('tenant_id')->constrained()->nullOnDelete();
            $table->index(['tenant_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try { Schema::table('guardians', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'user_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        }); } catch (\Exception $e) {}
    }
};