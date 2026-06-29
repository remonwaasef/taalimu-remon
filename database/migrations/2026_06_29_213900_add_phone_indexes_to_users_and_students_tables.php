<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Check if a given index exists on a table.
     */
    private function indexExists(string $table, string $indexName): bool
    {
        if (DB::getDriverName() === 'sqlite') {
            $indexes = DB::connection()->getSchemaBuilder()->getIndexes($table);
            foreach ($indexes as $index) {
                if ($index['name'] === $indexName) {
                    return true;
                }
            }
            return false;
        }

        try {
            $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
            return count($indexes) > 0;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if any index on 'phone' column already exists (handles both auto-generated and named indexes)
        $usersHasPhoneIndex = collect(Schema::getIndexes('users'))->contains(function ($index) {
            return in_array('phone', $index['columns']);
        });

        if (!$usersHasPhoneIndex) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('phone', 'users_phone_index');
            });
        }

        $studentsHasPhoneIndex = collect(Schema::getIndexes('students'))->contains(function ($index) {
            return in_array('phone', $index['columns']);
        });

        if (!$studentsHasPhoneIndex) {
            Schema::table('students', function (Blueprint $table) {
                $table->index('phone', 'students_phone_index');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->indexExists('users', 'users_phone_index')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('users_phone_index');
            });
        }

        if ($this->indexExists('students', 'students_phone_index')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropIndex('students_phone_index');
            });
        }
    }
};
