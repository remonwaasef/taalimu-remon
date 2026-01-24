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
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('guardian_id')->nullable()->constrained()->onDelete('set null')->after('grade_id');
        });

        // Migrate existing data
        $students = DB::table('students')
            ->whereNotNull('parent_name')
            ->whereNotNull('parent_phone')
            ->get();

        foreach ($students as $student) {
            // Find or create guardian based on tenant_id and phone
            $guardianId = DB::table('guardians')->where([
                'tenant_id' => $student->tenant_id,
                'phone' => $student->parent_phone,
            ])->value('id');

            if (!$guardianId) {
                $guardianId = DB::table('guardians')->insertGetId([
                    'tenant_id' => $student->tenant_id,
                    'name' => $student->parent_name,
                    'phone' => $student->parent_phone,
                    'job' => $student->parent_job,
                    'address' => $student->address,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('students')->where('id', $student->id)->update([
                'guardian_id' => $guardianId
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['guardian_id']);
            $table->dropColumn('guardian_id');
        });
    }
};
