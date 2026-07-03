<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The dashboard "recent activities" widget filters the ever-growing activity_log
 * table by the tenant id stored in the JSON `properties` column and orders by
 * created_at. Without an index on created_at this becomes a full-table scan that
 * degrades as the log grows. This index (combined with the recent-window bound
 * added to the query) keeps that lookup bounded on any engine.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('activity_log') && ! $this->hasIndex('activity_log', 'activity_log_created_at_index')) {
            Schema::table('activity_log', function (Blueprint $table) {
                $table->index('created_at', 'activity_log_created_at_index');
            });
        }
    }

    public function down(): void
    {
        if ($this->hasIndex('activity_log', 'activity_log_created_at_index')) {
            Schema::table('activity_log', function (Blueprint $table) {
                $table->dropIndex('activity_log_created_at_index');
            });
        }
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        foreach (Schema::getIndexes($table) as $index) {
            if ($index['name'] === $indexName) {
                return true;
            }
        }

        return false;
    }
};
