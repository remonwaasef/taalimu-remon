<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add 'suspended' status to enrollments table status enum.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE enrollments MODIFY status ENUM('active', 'suspended', 'completed', 'expired') NOT NULL DEFAULT 'active'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            // Revert suspended back to active before dropping suspended from enum to avoid truncation
            DB::table('enrollments')->where('status', 'suspended')->update(['status' => 'active']);
            DB::statement("ALTER TABLE enrollments MODIFY status ENUM('active', 'completed', 'expired') NOT NULL DEFAULT 'active'");
        }
    }
};
