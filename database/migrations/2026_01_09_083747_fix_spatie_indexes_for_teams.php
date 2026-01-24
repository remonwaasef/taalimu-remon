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
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teamForeignKey = $columnNames['team_foreign_key'] ?? 'tenant_id';

        // 2. Fix model_has_roles
        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($columnNames, $teamForeignKey, $tableNames) {
            
            // Try to drop FK
            if (DB::getDriverName() !== 'sqlite') {
                try {
                    $table->dropForeign($tableNames['model_has_roles'] . '_role_id_foreign');
                } catch (\Exception $e) {}
            }
            
            // Try to drop PK
            try {
                $table->dropPrimary($tableNames['model_has_roles'] . '_role_model_type_primary');
            } catch (\Exception $e) {}
            
            // Try to drop any existing primary if name is different (generic)
            // Hard to do without raw SQL, skipping.

            // Add UNIQUE index including tenant_id
            try {
                $table->unique([
                    $teamForeignKey, 
                    config('permission.column_names.role_pivot_key') ?? 'role_id', 
                    $columnNames['model_morph_key'], 
                    'model_type'
                ], 'model_has_roles_tenant_unique');
            } catch (\Exception $e) {}
            
            // Add explicit index for role_id
            try {
                $table->index(config('permission.column_names.role_pivot_key') ?? 'role_id');
            } catch (\Exception $e) {}
            
            // Restore Foreign Key
            try {
                $table->foreign(config('permission.column_names.role_pivot_key') ?? 'role_id')
                    ->references('id')
                    ->on($tableNames['roles'])
                    ->onDelete('cascade');
            } catch (\Exception $e) {}
        });

        // 3. Fix model_has_permissions
        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($columnNames, $teamForeignKey, $tableNames) {
             // Drop foreign key
            if (DB::getDriverName() !== 'sqlite') {
                try {
                    $table->dropForeign($tableNames['model_has_permissions'] . '_permission_id_foreign');
                } catch (\Exception $e) {}
            }
            
            // Drop old primary key
            try {
                $table->dropPrimary($tableNames['model_has_permissions'] . '_permission_model_type_primary');
            } catch (\Exception $e) {}

            // Add new unique index including tenant_id
            try {
                 $table->unique([
                    $teamForeignKey, 
                    config('permission.column_names.permission_pivot_key') ?? 'permission_id', 
                    $columnNames['model_morph_key'], 
                    'model_type'
                ], 'model_has_permissions_tenant_unique');
            } catch (\Exception $e) {}
            
            // Add explicit index for permission_id
            try {
                $table->index(config('permission.column_names.permission_pivot_key') ?? 'permission_id');
            } catch (\Exception $e) {}
            
            // Restore Foreign Key
            try {
                $table->foreign(config('permission.column_names.permission_pivot_key') ?? 'permission_id')
                    ->references('id')
                    ->on($tableNames['permissions'])
                    ->onDelete('cascade');
            } catch (\Exception $e) {}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not implemented
    }
};
