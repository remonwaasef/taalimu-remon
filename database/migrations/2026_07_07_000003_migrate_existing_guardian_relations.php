<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fetch all students with a guardian
        $students = DB::table('students')
            ->whereNotNull('guardian_id')
            ->select('id', 'guardian_id', 'parent_relation')
            ->get();

        foreach ($students as $student) {
            DB::table('guardian_student')->insertOrIgnore([
                'student_id' => $student->id,
                'guardian_id' => $student->guardian_id,
                'relation' => $student->parent_relation ?: 'parent',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down migration is needed since we only copied the data and don't delete it
    }
};
