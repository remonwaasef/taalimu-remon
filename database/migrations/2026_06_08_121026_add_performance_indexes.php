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
        Schema::table('payments', function (Blueprint $table) {
            $table->index('sale_id');
        });

        Schema::table('commissions', function (Blueprint $table) {
            $table->index('sale_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index(['tenant_id', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['sale_id']);
        });

        Schema::table('commissions', function (Blueprint $table) {
            $table->dropIndex(['sale_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'role']);
        });
    }
};
