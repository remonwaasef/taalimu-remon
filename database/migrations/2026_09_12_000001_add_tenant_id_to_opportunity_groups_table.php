<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Give opportunity_groups direct tenant isolation.
     *
     * Groups were previously tenant-scoped only indirectly through their
     * opportunity/course relationships, which violates the platform invariant
     * (see Tests\Feature\Security\ModelIsolationTest) that every tenant-owned
     * model carries its own tenant_id with the BelongsToTenant trait.
     *
     * Fully additive and non-destructive: adds a nullable column, backfills it
     * from the parent opportunity, then indexes it. No columns dropped.
     */
    public function up(): void
    {
        Schema::table('opportunity_groups', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('course_id')->constrained()->cascadeOnDelete();
        });

        // Backfill from parent opportunities (no-op on fresh installs).
        // Portable chunked loop: SQLite does not support UPDATE..JOIN.
        DB::table('opportunity_groups')
            ->whereNull('tenant_id')
            ->orderBy('id')
            ->chunkById(200, function ($groups) {
                foreach ($groups as $group) {
                    $tenantId = DB::table('opportunities')
                        ->where('id', $group->opportunity_id)
                        ->value('tenant_id');

                    if ($tenantId) {
                        DB::table('opportunity_groups')
                            ->where('id', $group->id)
                            ->update(['tenant_id' => $tenantId]);
                    }
                }
            });

        Schema::table('opportunity_groups', function (Blueprint $table) {
            $table->index(['tenant_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opportunity_groups', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'status']);
            $table->dropConstrainedForeignId('tenant_id');
        });
    }
};
