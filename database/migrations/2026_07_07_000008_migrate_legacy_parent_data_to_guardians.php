<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * ترحيل بيانات أولياء الأمور القديمة من جدول students إلى جدول guardians
     * وربطها عبر الجدول الوسيط guardian_student.
     * لا يتم حذف الأعمدة القديمة بعد - فقط ترحيل البيانات.
     */
    public function up(): void
    {
        // Find students with parent_name data but no guardian linked
        $orphanStudents = DB::table('students')
            ->whereNotNull('parent_name')
            ->where('parent_name', '!=', '')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('guardian_student')
                    ->whereColumn('guardian_student.student_id', 'students.id');
            })
            ->select('id', 'tenant_id', 'parent_name', 'parent_phone', 'parent_job', 'parent_relation', 'emergency_phone')
            ->orderBy('id')
            ->get();

        foreach ($orphanStudents as $student) {
            // Check if a guardian with same name+phone already exists for this tenant
            $existingGuardian = DB::table('guardians')
                ->where('tenant_id', $student->tenant_id)
                ->where('name', $student->parent_name)
                ->when($student->parent_phone, function ($q) use ($student) {
                    return $q->where('phone', $student->parent_phone);
                })
                ->first();

            if ($existingGuardian) {
                $guardianId = $existingGuardian->id;
            } else {
                $guardianId = DB::table('guardians')->insertGetId([
                    'tenant_id' => $student->tenant_id,
                    'name' => $student->parent_name,
                    'phone' => $student->parent_phone,
                    'job' => $student->parent_job,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Link via pivot table (avoid duplicates)
            $alreadyLinked = DB::table('guardian_student')
                ->where('guardian_id', $guardianId)
                ->where('student_id', $student->id)
                ->exists();

            if (! $alreadyLinked) {
                DB::table('guardian_student')->insert([
                    'guardian_id' => $guardianId,
                    'student_id' => $student->id,
                    'relation' => $student->parent_relation,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Data migration - no structural changes to reverse
    }
};
