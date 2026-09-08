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
            Schema::table('payments', function (Blueprint $table) {
                $table->index('sale_id');
            });
        } catch (\Throwable $e) {}

        try {
            Schema::table('commissions', function (Blueprint $table) {
                $table->index('sale_id');
            });
        } catch (\Throwable $e) {}

        try {
            Schema::table('users', function (Blueprint $table) {
                $table->index(['tenant_id', 'role']);
            });
        } catch (\Throwable $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try { Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['sale_id']);
        }); } catch (\Exception $e) {}

        try { Schema::table('commissions', function (Blueprint $table) {
            $table->dropIndex(['sale_id']);
        }); } catch (\Exception $e) {}

        try { Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'role']);
        }); } catch (\Exception $e) {}
    }
};
