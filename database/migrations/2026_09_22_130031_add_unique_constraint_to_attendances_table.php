<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, remove any duplicate attendances that would violate the unique constraint
        // Keep the earliest record for each (tenant_id, student_id, schedule_id, session_date)
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('
                DELETE FROM attendances
                WHERE id NOT IN (
                    SELECT MIN(id)
                    FROM attendances
                    GROUP BY tenant_id, student_id, schedule_id, session_date
                )
            ');
        } else {
            DB::statement('
                DELETE a1 FROM attendances a1
                INNER JOIN attendances a2
                WHERE a1.id > a2.id
                AND a1.tenant_id = a2.tenant_id
                AND a1.student_id = a2.student_id
                AND a1.schedule_id = a2.schedule_id
                AND a1.session_date = a2.session_date
            ');
        }

        // Add unique constraint
        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->unique(['tenant_id', 'student_id', 'schedule_id', 'session_date'], 'attendances_tenant_student_schedule_date_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->dropUnique('attendances_tenant_student_schedule_date_unique');
            });
        }
    }
};