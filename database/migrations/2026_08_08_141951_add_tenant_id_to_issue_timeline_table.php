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
        Schema::table('issue_timeline', function (Blueprint $table) {
            $table->foreignId('tenant_id')
                ->nullable()
                ->after('issue_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->index(['tenant_id', 'issue_id'], 'idx_issue_timeline_tenant_issue');
        });

        // Backfill tenant_id from the parent issue for existing rows
        \Illuminate\Support\Facades\DB::table('issue_timeline')
            ->whereNull('tenant_id')
            ->update([
                'tenant_id' => \Illuminate\Support\Facades\DB::raw(
                    '(SELECT tenant_id FROM operation_issues WHERE operation_issues.id = issue_timeline.issue_id)'
                ),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try { Schema::table('issue_timeline', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropIndex('idx_issue_timeline_tenant_issue');
            $table->dropColumn('tenant_id');
        }); } catch (\Exception $e) {}
    }
};
