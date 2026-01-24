<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tableNames = config('permission.table_names');

        // Use raw SQL to ensure it works without Doctrine DBAL and forces the change
        // MySQL specific syntax
        
        $tables = [
            $tableNames['roles'],
            $tableNames['model_has_roles'],
            $tableNames['model_has_permissions']
        ];

        if (DB::getDriverName() === 'mysql') {
            foreach ($tables as $table) {
                DB::statement("ALTER TABLE `{$table}` MODIFY COLUMN `tenant_id` BIGINT UNSIGNED NULL DEFAULT NULL");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting to NOT NULL is dangerous if NULLs exist
    }
};
