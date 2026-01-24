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
        // Add branch_id to users
        Schema::table('users', function (Blueprint $table) {
             if (!Schema::hasColumn('users', 'branch_id')) {
                $table->foreignId('branch_id')->nullable()->after('tenant_id')->constrained('branches')->nullOnDelete();
            }
        });

        // Add branch_id to classrooms
        Schema::table('classrooms', function (Blueprint $table) {
            if (!Schema::hasColumn('classrooms', 'branch_id')) {
                $table->foreignId('branch_id')->nullable()->after('tenant_id')->constrained('branches')->cascadeOnDelete();
            }
        });

        // Add branch_id to schedules
        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'branch_id')) {
                $table->foreignId('branch_id')->nullable()->after('classroom_id')->constrained('branches')->cascadeOnDelete();
            }
        });

        // Add branch_id to sales
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'branch_id')) {
                $table->foreignId('branch_id')->nullable()->after('tenant_id')->constrained('branches')->cascadeOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'branch_id')) {
                if (DB::getDriverName() !== 'sqlite') {
                    $table->dropForeign(['branch_id']);
                }
                $table->dropColumn('branch_id');
            }
        });
        
        Schema::table('classrooms', function (Blueprint $table) {
             if (Schema::hasColumn('classrooms', 'branch_id')) {
                if (DB::getDriverName() !== 'sqlite') {
                    $table->dropForeign(['branch_id']);
                }
                $table->dropColumn('branch_id');
            }
        });

        Schema::table('schedules', function (Blueprint $table) {
             if (Schema::hasColumn('schedules', 'branch_id')) {
                if (DB::getDriverName() !== 'sqlite') {
                    $table->dropForeign(['branch_id']);
                }
                $table->dropColumn('branch_id');
            }
        });

        Schema::table('sales', function (Blueprint $table) {
             if (Schema::hasColumn('sales', 'branch_id')) {
                if (DB::getDriverName() !== 'sqlite') {
                    $table->dropForeign(['branch_id']);
                }
                $table->dropColumn('branch_id');
            }
        });
    }
};
