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
        Schema::table('students', function (Blueprint $table) {
            // Optimize "Sort by Latest" and "Growth Chart" queries
            $table->index(['tenant_id', 'created_at']);
        });

        Schema::table('sales', function (Blueprint $table) {
            // Optimize "Total Revenue" calculation
            $table->index(['tenant_id', 'status']);
        });

        if (Schema::hasTable('guardians')) {
             Schema::table('guardians', function (Blueprint $table) {
                // Optimize "Guardian Lookup" by phone
                $table->index(['tenant_id', 'phone']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'created_at']);
        });

        Schema::table('sales', function (Blueprint $table) {
             $table->dropIndex(['tenant_id', 'status']);
        });
        
        if (Schema::hasTable('guardians')) {
            Schema::table('guardians', function (Blueprint $table) {
                $table->dropIndex(['tenant_id', 'phone']);
            });
        }
    }
};
