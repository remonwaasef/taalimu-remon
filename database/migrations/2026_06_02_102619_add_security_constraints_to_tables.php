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

        $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }

    /**
     * Check if a given foreign key exists on a table.
     */
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

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Unique constraint on enrollments(user_id, course_id)
        if (Schema::hasTable('enrollments') && !$this->indexExists('enrollments', 'enrollments_user_id_course_id_unique')) {
            if (DB::getDriverName() === 'sqlite') {
                DB::statement('DELETE FROM enrollments WHERE id NOT IN (SELECT min(id) FROM enrollments GROUP BY user_id, course_id)');
            } else {
                DB::statement('DELETE e1 FROM enrollments e1 INNER JOIN enrollments e2 WHERE e1.id < e2.id AND e1.user_id = e2.user_id AND e1.course_id = e2.course_id');
            }
            Schema::table('enrollments', function (Blueprint $table) {
                $table->unique(['user_id', 'course_id']);
            });
        }

        // 2. Unique constraint on certificates(student_id, course_id)
        if (Schema::hasTable('certificates') && !$this->indexExists('certificates', 'certificates_student_id_course_id_unique')) {
            if (DB::getDriverName() === 'sqlite') {
                DB::statement('DELETE FROM certificates WHERE id NOT IN (SELECT min(id) FROM certificates GROUP BY student_id, course_id)');
            } else {
                DB::statement('DELETE c1 FROM certificates c1 INNER JOIN certificates c2 WHERE c1.id < c2.id AND c1.student_id = c2.student_id AND c1.course_id = c2.course_id');
            }
            Schema::table('certificates', function (Blueprint $table) {
                $table->unique(['student_id', 'course_id']);
            });
        }

        // 3. Unique constraint on commissions(sale_id, instructor_id)
        if (Schema::hasTable('commissions') && !$this->indexExists('commissions', 'commissions_sale_id_instructor_id_unique')) {
            if (DB::getDriverName() === 'sqlite') {
                DB::statement('DELETE FROM commissions WHERE id NOT IN (SELECT min(id) FROM commissions GROUP BY sale_id, instructor_id)');
            } else {
                DB::statement('DELETE c1 FROM commissions c1 INNER JOIN commissions c2 WHERE c1.id < c2.id AND c1.sale_id = c2.sale_id AND c1.instructor_id = c2.instructor_id');
            }
            Schema::table('commissions', function (Blueprint $table) {
                $table->unique(['sale_id', 'instructor_id']);
            });
        }

        // 4. Unique constraint on bookings(student_id, schedule_id)
        if (Schema::hasTable('bookings') && !$this->indexExists('bookings', 'bookings_student_id_schedule_id_unique')) {
            if (DB::getDriverName() === 'sqlite') {
                DB::statement('DELETE FROM bookings WHERE id NOT IN (SELECT min(id) FROM bookings GROUP BY student_id, schedule_id)');
            } else {
                DB::statement('DELETE b1 FROM bookings b1 INNER JOIN bookings b2 WHERE b1.id < b2.id AND b1.student_id = b2.student_id AND b1.schedule_id = b2.schedule_id');
            }
            Schema::table('bookings', function (Blueprint $table) {
                $table->unique(['student_id', 'schedule_id']);
            });
        }

        // 5. Unique constraints on students (tenant_id + email/phone)
        if (Schema::hasTable('students')) {
            DB::statement("UPDATE students SET email = NULL WHERE email = ''");
            DB::statement("UPDATE students SET phone = NULL WHERE phone = ''");
            if (DB::getDriverName() === 'sqlite') {
                DB::statement('DELETE FROM students WHERE email IS NOT NULL AND id NOT IN (SELECT min(id) FROM students WHERE email IS NOT NULL GROUP BY tenant_id, email)');
                DB::statement('DELETE FROM students WHERE phone IS NOT NULL AND id NOT IN (SELECT min(id) FROM students WHERE phone IS NOT NULL GROUP BY tenant_id, phone)');
            } else {
                DB::statement('DELETE s1 FROM students s1 INNER JOIN students s2 WHERE s1.id < s2.id AND s1.tenant_id = s2.tenant_id AND s1.email = s2.email AND s1.email IS NOT NULL');
                DB::statement('DELETE s1 FROM students s1 INNER JOIN students s2 WHERE s1.id < s2.id AND s1.tenant_id = s2.tenant_id AND s1.phone = s2.phone AND s1.phone IS NOT NULL');
            }

            if (!$this->indexExists('students', 'students_tenant_id_email_unique')) {
                Schema::table('students', function (Blueprint $table) {
                    $table->unique(['tenant_id', 'email']);
                });
            }
            if (!$this->indexExists('students', 'students_tenant_id_phone_unique')) {
                Schema::table('students', function (Blueprint $table) {
                    $table->unique(['tenant_id', 'phone']);
                });
            }
        }

        // 6. Unique constraints on instructors (tenant_id + email/phone)
        if (Schema::hasTable('instructors')) {
            DB::statement("UPDATE instructors SET email = NULL WHERE email = ''");
            DB::statement("UPDATE instructors SET phone = NULL WHERE phone = ''");
            if (DB::getDriverName() === 'sqlite') {
                DB::statement('DELETE FROM instructors WHERE email IS NOT NULL AND id NOT IN (SELECT min(id) FROM instructors WHERE email IS NOT NULL GROUP BY tenant_id, email)');
                DB::statement('DELETE FROM instructors WHERE phone IS NOT NULL AND id NOT IN (SELECT min(id) FROM instructors WHERE phone IS NOT NULL GROUP BY tenant_id, phone)');
            } else {
                DB::statement('DELETE i1 FROM instructors i1 INNER JOIN instructors i2 WHERE i1.id < i2.id AND i1.tenant_id = i2.tenant_id AND i1.email = i2.email AND i1.email IS NOT NULL');
                DB::statement('DELETE i1 FROM instructors i1 INNER JOIN instructors i2 WHERE i1.id < i2.id AND i1.tenant_id = i2.tenant_id AND i1.phone = i2.phone AND i1.phone IS NOT NULL');
            }

            if (!$this->indexExists('instructors', 'instructors_tenant_id_email_unique')) {
                Schema::table('instructors', function (Blueprint $table) {
                    $table->unique(['tenant_id', 'email']);
                });
            }
            if (!$this->indexExists('instructors', 'instructors_tenant_id_phone_unique')) {
                Schema::table('instructors', function (Blueprint $table) {
                    $table->unique(['tenant_id', 'phone']);
                });
            }
        }

        // 7. Foreign key constraints with restrictOnDelete
        $constraints = [
            ['table' => 'payments',  'column' => 'sale_id',       'ref_table' => 'sales',       'fk_name' => 'payments_sale_id_foreign'],
            ['table' => 'refunds',   'column' => 'sale_id',       'ref_table' => 'sales',       'fk_name' => 'refunds_sale_id_foreign'],
            ['table' => 'payouts',   'column' => 'instructor_id', 'ref_table' => 'instructors', 'fk_name' => 'payouts_instructor_id_foreign'],
            ['table' => 'expenses',  'column' => 'tenant_id',     'ref_table' => 'tenants',     'fk_name' => 'expenses_tenant_id_foreign'],
        ];

        foreach ($constraints as $c) {
            if (!Schema::hasTable($c['table']) || !Schema::hasColumn($c['table'], $c['column'])) {
                continue;
            }

            // Drop existing FK if present, then re-add with restrictOnDelete
            if ($this->foreignKeyExists($c['table'], $c['fk_name'])) {
                Schema::table($c['table'], function (Blueprint $t) use ($c) {
                    $t->dropForeign($c['fk_name']);
                });
            }

            Schema::table($c['table'], function (Blueprint $t) use ($c) {
                $t->foreign($c['column'], $c['fk_name'])
                  ->references('id')
                  ->on($c['ref_table'])
                  ->restrictOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $uniqueIndexes = [
            'enrollments'  => 'enrollments_user_id_course_id_unique',
            'certificates' => 'certificates_student_id_course_id_unique',
            'commissions'  => 'commissions_sale_id_instructor_id_unique',
            'bookings'     => 'bookings_student_id_schedule_id_unique',
        ];

        foreach ($uniqueIndexes as $table => $index) {
            if (Schema::hasTable($table) && $this->indexExists($table, $index)) {
                Schema::table($table, function (Blueprint $t) use ($index) {
                    $t->dropUnique($index);
                });
            }
        }

        $tenantUniques = [
            'students'    => ['students_tenant_id_email_unique', 'students_tenant_id_phone_unique'],
            'instructors' => ['instructors_tenant_id_email_unique', 'instructors_tenant_id_phone_unique'],
        ];

        foreach ($tenantUniques as $table => $indexes) {
            if (Schema::hasTable($table)) {
                foreach ($indexes as $index) {
                    if ($this->indexExists($table, $index)) {
                        Schema::table($table, function (Blueprint $t) use ($index) {
                            $t->dropUnique($index);
                        });
                    }
                }
            }
        }

        // Foreign keys: drop restrictOnDelete versions (re-adding cascadeOnDelete would require knowing the originals)
        $fks = [
            'payments'  => 'payments_sale_id_foreign',
            'refunds'   => 'refunds_sale_id_foreign',
            'payouts'   => 'payouts_instructor_id_foreign',
            'expenses'  => 'expenses_tenant_id_foreign',
        ];

        foreach ($fks as $table => $fkName) {
            if (Schema::hasTable($table) && $this->foreignKeyExists($table, $fkName)) {
                Schema::table($table, function (Blueprint $t) use ($fkName) {
                    $t->dropForeign($fkName);
                });
            }
        }
    }
};
