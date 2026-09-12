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
        // For SQLite, we need to recreate the table since it doesn't support ALTER TABLE ... MODIFY COLUMN for enums
        // For MySQL/PostgreSQL, we can use DB::statement to alter the enum
        if (config('database.default') === 'sqlite') {
            Schema::table('demand_requests', function (Blueprint $table) {
                // SQLite doesn't support modifying enums directly, so we'll handle this in the application layer
                // The enum constraint is enforced at the application level for SQLite
            });
        } else {
            DB::statement("ALTER TABLE demand_requests MODIFY COLUMN status ENUM('new', 'contacted', 'converted', 'declined', 'aggregated') DEFAULT 'new'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (config('database.default') === 'sqlite') {
            // No-op for SQLite
        } else {
            DB::statement("ALTER TABLE demand_requests MODIFY COLUMN status ENUM('new', 'contacted', 'converted', 'declined') DEFAULT 'new'");
        }
    }
};