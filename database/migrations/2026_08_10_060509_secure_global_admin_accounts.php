<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * SEC-01 data cleanup: global admin accounts must be global (tenant_id = null)
 * and tenant-owned users must never hold the 'admin'/'super_admin' role column,
 * which granted full access to the global admin panel.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $reserved = ['admin', 'super_admin'];

        // 1. Neutralize tenant-owned custom roles named 'admin'/'super_admin'
        //    (created before the RoleController denylist existed). Renaming keeps
        //    the record/deletions safe while stripping its power.
        //    Done in PHP (not CONCAT) so the migration also runs on SQLite tests.
        DB::table('roles')
            ->whereNotNull('tenant_id')
            ->whereIn(DB::raw('LOWER(name)'), $reserved)
            ->get(['id', 'name'])
            ->each(function ($role) {
                DB::table('roles')
                    ->where('id', $role->id)
                    ->update(['name' => $role->name.'-custom']);
            });

        // 2. The canonical platform super admin must stay global — regardless of
        //    how its tenant_id drifted.
        DB::table('users')
            ->where('email', 'admin@admin.com')
            ->whereIn(DB::raw('LOWER(role)'), $reserved)
            ->update(['tenant_id' => null]);

        // 3. Any other tenant user carrying an admin role column is demoted to a
        //    safe non-privileged role; they keep their Spatie permissions if any.
        DB::table('users')
            ->whereNotNull('tenant_id')
            ->whereIn(DB::raw('LOWER(role)'), $reserved)
            ->update(['role' => 'staff']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore renamed tenant roles; user demotions are intentionally not
        // reversed (security win) — re-assign manually if truly needed.
        DB::table('roles')
            ->whereNotNull('tenant_id')
            ->where(DB::raw('LOWER(name)'), 'like', '%-custom')
            ->get(['id', 'name'])
            ->each(function ($role) {
                DB::table('roles')
                    ->where('id', $role->id)
                    ->update(['name' => substr($role->name, 0, -7)]);
            });
    }
};