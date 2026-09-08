<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
        $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }

    private function foreignKeyExists(string $table, string $fkName): bool
    {
        if (DB::getDriverName() === 'sqlite') {
            $fks = DB::connection()->getSchemaBuilder()->getForeignKeys($table);
            foreach ($fks as $fk) {
                if ($fk['name'] === $fkName) {
                    return true;
                }
            }
            return false;
        }
        $db = config('database.connections.mysql.database');
        $result = DB::select(
            "SELECT COUNT(*) as cnt FROM information_schema.TABLE_CONSTRAINTS
             WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = 'FOREIGN KEY'",
            [$db, $table, $fkName]
        );
        return $result[0]->cnt > 0;
    }

    public function up(): void
    {
        // ================================================================
        // 1. Add softDeletes to financial tables for audit trail & recovery
        // ================================================================
        $financialTables = ['invoices', 'payments', 'refunds', 'expenses', 'commissions', 'payouts', 'sale_items', 'payment_reminders'];
        foreach ($financialTables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'deleted_at')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
        }

        // ================================================================
        // 2. Fix remaining cascadeOnDelete → restrictOnDelete for financial data
        // ================================================================
        $cascadeToRestrict = [
            ['table' => 'enrollments',          'col' => 'user_id',   'ref' => 'users',        'fk' => 'enrollments_user_id_foreign'],
            ['table' => 'lesson_progress',      'col' => 'user_id',   'ref' => 'users',        'fk' => 'lesson_progress_user_id_foreign'],
            ['table' => 'lesson_progress',      'col' => 'lesson_id', 'ref' => 'lessons',      'fk' => 'lesson_progress_lesson_id_foreign'],
            ['table' => 'quiz_attempts',        'col' => 'user_id',   'ref' => 'users',        'fk' => 'quiz_attempts_user_id_foreign'],
            ['table' => 'quiz_attempts',        'col' => 'quiz_id',   'ref' => 'quizzes',      'fk' => 'quiz_attempts_quiz_id_foreign'],
            ['table' => 'assignment_submissions','col' => 'user_id',   'ref' => 'users',        'fk' => 'assignment_submissions_user_id_foreign'],
            ['table' => 'assignment_submissions','col' => 'assignment_id','ref' => 'assignments','fk' => 'assignment_submissions_assignment_id_foreign'],
            ['table' => 'point_logs',           'col' => 'user_id',   'ref' => 'users',        'fk' => 'point_logs_user_id_foreign'],
            ['table' => 'certificates',         'col' => 'tenant_id', 'ref' => 'tenants',      'fk' => 'certificates_tenant_id_foreign'],
            ['table' => 'certificates',         'col' => 'student_id','ref' => 'students',     'fk' => 'certificates_student_id_foreign'],
            ['table' => 'certificates',         'col' => 'course_id', 'ref' => 'courses',      'fk' => 'certificates_course_id_foreign'],
            ['table' => 'course_resources',     'col' => 'tenant_id', 'ref' => 'tenants',      'fk' => 'course_resources_tenant_id_foreign'],
            ['table' => 'course_resources',     'col' => 'course_id', 'ref' => 'courses',      'fk' => 'course_resources_course_id_foreign'],
            ['table' => 'assets',               'col' => 'tenant_id', 'ref' => 'tenants',      'fk' => 'assets_tenant_id_foreign'],
        ];

        foreach ($cascadeToRestrict as $c) {
            if (!Schema::hasTable($c['table']) || !Schema::hasColumn($c['table'], $c['col'])) {
                continue;
            }
            try {
                if ($this->foreignKeyExists($c['table'], $c['fk'])) {
                    Schema::table($c['table'], function (Blueprint $t) use ($c) {
                        $t->dropForeign($c['fk']);
                    });
                }
                Schema::table($c['table'], function (Blueprint $t) use ($c) {
                    $t->foreign($c['col'], $c['fk'])
                        ->references('id')->on($c['ref'])
                        ->restrictOnDelete();
                });
            } catch (\Exception $e) {
                \Log::warning("Could not update {$c['table']}.{$c['col']} FK: ".$e->getMessage());
            }
        }

        // ================================================================
        // 3. Add missing database indexes
        // ================================================================
        $indexes = [
            ['table' => 'users',       'columns' => ['tenant_id', 'role'],                    'name' => 'idx_users_tenant_role'],
            ['table' => 'users',       'columns' => ['email'],                                 'name' => 'idx_users_email'],
            ['table' => 'schedules',   'columns' => ['tenant_id'],                             'name' => 'idx_schedules_tenant_id'],
            ['table' => 'sale_items',  'columns' => ['item_type', 'item_id'],                  'name' => 'idx_sale_items_type_id'],
            ['table' => 'refunds',     'columns' => ['sale_id'],                               'name' => 'idx_refunds_sale_id'],
            ['table' => 'certificates','columns' => ['tenant_id'],                             'name' => 'idx_certificates_tenant_id'],
            ['table' => 'instructors', 'columns' => ['user_id'],                               'name' => 'idx_instructors_user_id'],
            ['table' => 'assets',      'columns' => ['tenant_id'],                             'name' => 'idx_assets_tenant_id'],
            ['table' => 'assets',      'columns' => ['asset_type'],                            'name' => 'idx_assets_asset_type'],
            ['table' => 'course_resources','columns' => ['tenant_id'],                         'name' => 'idx_course_resources_tenant_id'],
        ];

        foreach ($indexes as $idx) {
            if (!Schema::hasTable($idx['table'])) {
                continue;
            }
            foreach ($idx['columns'] as $col) {
                if (!Schema::hasColumn($idx['table'], $col)) {
                    continue 2;
                }
            }
            if (!$this->indexExists($idx['table'], $idx['name'])) {
                try {
                    Schema::table($idx['table'], function (Blueprint $t) use ($idx) {
                        $t->index($idx['columns'], $idx['name']);
                    });
                } catch (\Exception $e) {
                    \Log::warning("Could not add index {$idx['name']} on {$idx['table']}: ".$e->getMessage());
                }
            }
        }

        // ================================================================
        // 4. Add missing FK constraint on user_consents
        // ================================================================
        if (Schema::hasTable('user_consents') && Schema::hasColumn('user_consents', 'user_id')) {
            try {
                if (!$this->foreignKeyExists('user_consents', 'user_consents_user_id_foreign')) {
                    Schema::table('user_consents', function (Blueprint $table) {
                        $table->foreign('user_id', 'user_consents_user_id_foreign')
                            ->references('id')->on('users')
                            ->restrictOnDelete();
                    });
                }
            } catch (\Exception $e) {
                \Log::warning('Could not add FK on user_consents.user_id: '.$e->getMessage());
            }
        }
    }

    public function down(): void
    {
        // Drop softDeletes
        $financialTables = ['invoices', 'payments', 'refunds', 'expenses', 'commissions', 'payouts', 'sale_items', 'payment_reminders'];
        foreach ($financialTables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'deleted_at')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropSoftDeletes();
                });
            }
        }

        // Drop indexes
        $indexes = [
            'idx_users_tenant_role', 'idx_users_email', 'idx_schedules_tenant_id',
            'idx_sale_items_type_id', 'idx_refunds_sale_id', 'idx_certificates_tenant_id',
            'idx_instructors_user_id', 'idx_assets_tenant_id', 'idx_assets_asset_type',
            'idx_course_resources_tenant_id',
        ];
        $tableIndexMap = [
            'idx_users_tenant_role' => 'users', 'idx_users_email' => 'users',
            'idx_schedules_tenant_id' => 'schedules', 'idx_sale_items_type_id' => 'sale_items',
            'idx_refunds_sale_id' => 'refunds', 'idx_certificates_tenant_id' => 'certificates',
            'idx_instructors_user_id' => 'instructors', 'idx_assets_tenant_id' => 'assets',
            'idx_assets_asset_type' => 'assets', 'idx_course_resources_tenant_id' => 'course_resources',
        ];

        foreach ($indexes as $idxName) {
            $table = $tableIndexMap[$idxName];
            if (Schema::hasTable($table) && $this->indexExists($table, $idxName)) {
                try {
                    Schema::table($table, function (Blueprint $t) use ($idxName) {
                        $t->dropIndex($idxName);
                    });
                } catch (\Exception $e) {
                    \Log::warning("Could not drop index {$idxName}: ".$e->getMessage());
                }
            }
        }

        // Drop user_consents FK
        if (Schema::hasTable('user_consents') && $this->foreignKeyExists('user_consents', 'user_consents_user_id_foreign')) {
            try {
                try { Schema::table('user_consents', function (Blueprint $table) {
                    $table->dropForeign('user_consents_user_id_foreign');
                }); } catch (\Exception $e) {}
            } catch (\Exception $e) {
                \Log::warning('Could not drop FK on user_consents: '.$e->getMessage());
            }
        }

        // Revert FK changes back to cascadeOnDelete
        $fksToRevert = [
            ['table' => 'enrollments',           'col' => 'user_id',   'ref' => 'users',    'fk' => 'enrollments_user_id_foreign'],
            ['table' => 'lesson_progress',       'col' => 'user_id',   'ref' => 'users',    'fk' => 'lesson_progress_user_id_foreign'],
            ['table' => 'lesson_progress',       'col' => 'lesson_id', 'ref' => 'lessons',  'fk' => 'lesson_progress_lesson_id_foreign'],
            ['table' => 'quiz_attempts',         'col' => 'user_id',   'ref' => 'users',    'fk' => 'quiz_attempts_user_id_foreign'],
            ['table' => 'quiz_attempts',         'col' => 'quiz_id',   'ref' => 'quizzes',  'fk' => 'quiz_attempts_quiz_id_foreign'],
            ['table' => 'assignment_submissions','col' => 'user_id',   'ref' => 'users',    'fk' => 'assignment_submissions_user_id_foreign'],
            ['table' => 'assignment_submissions','col' => 'assignment_id','ref' =>'assignments','fk' => 'assignment_submissions_assignment_id_foreign'],
            ['table' => 'point_logs',            'col' => 'user_id',   'ref' => 'users',    'fk' => 'point_logs_user_id_foreign'],
            ['table' => 'certificates',          'col' => 'tenant_id', 'ref' => 'tenants',  'fk' => 'certificates_tenant_id_foreign'],
            ['table' => 'certificates',          'col' => 'student_id','ref' => 'students', 'fk' => 'certificates_student_id_foreign'],
            ['table' => 'certificates',          'col' => 'course_id', 'ref' => 'courses',  'fk' => 'certificates_course_id_foreign'],
            ['table' => 'course_resources',      'col' => 'tenant_id', 'ref' => 'tenants',  'fk' => 'course_resources_tenant_id_foreign'],
            ['table' => 'course_resources',      'col' => 'course_id', 'ref' => 'courses',  'fk' => 'course_resources_course_id_foreign'],
            ['table' => 'assets',                'col' => 'tenant_id', 'ref' => 'tenants',  'fk' => 'assets_tenant_id_foreign'],
        ];

        foreach ($fksToRevert as $c) {
            if (!Schema::hasTable($c['table']) || !Schema::hasColumn($c['table'], $c['col'])) {
                continue;
            }
            try {
                if ($this->foreignKeyExists($c['table'], $c['fk'])) {
                    Schema::table($c['table'], function (Blueprint $t) use ($c) {
                        $t->dropForeign($c['fk']);
                    });
                }
                Schema::table($c['table'], function (Blueprint $t) use ($c) {
                    $t->foreign($c['col'], $c['fk'])
                        ->references('id')->on($c['ref'])
                        ->cascadeOnDelete();
                });
            } catch (\Exception $e) {
                \Log::warning("Could not revert {$c['table']}.{$c['col']} FK: ".$e->getMessage());
            }
        }
    }
};
