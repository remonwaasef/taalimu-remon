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
        // 1. Update FK for branch deletion in classrooms (cascadeOnDelete -> restrictOnDelete)
        try {
            Schema::table('classrooms', function (Blueprint $table) {
                $table->dropForeign(['branch_id']);
                $table->foreign('branch_id')->references('id')->on('branches')->restrictOnDelete();
            });
        } catch (\Exception $e) {
            \Log::warning('Could not update classrooms.branch_id FK: '.$e->getMessage());
        }

        // 2. Update FK for branch & course deletion in schedules
        try {
            Schema::table('schedules', function (Blueprint $table) {
                $table->dropForeign(['branch_id']);
                $table->foreign('branch_id')->references('id')->on('branches')->restrictOnDelete();
            });
        } catch (\Exception $e) {
            \Log::warning('Could not update schedules.branch_id FK: '.$e->getMessage());
        }

        try {
            Schema::table('schedules', function (Blueprint $table) {
                $table->dropForeign(['course_id']);
                $table->foreign('course_id')->references('id')->on('courses')->restrictOnDelete();
            });
        } catch (\Exception $e) {
            \Log::warning('Could not update schedules.course_id FK: '.$e->getMessage());
        }

        // 3. Update FK for course deletion in enrollments (cascadeOnDelete -> restrictOnDelete)
        try {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->dropForeign(['course_id']);
                $table->foreign('course_id')->references('id')->on('courses')->restrictOnDelete();
            });
        } catch (\Exception $e) {
            \Log::warning('Could not update enrollments.course_id FK: '.$e->getMessage());
        }

        // 4. Add Database Check Constraints for financial values
        try {
            DB::statement('ALTER TABLE students ADD CONSTRAINT chk_students_monthly_fee CHECK (monthly_fee >= 0)');
        } catch (\Exception $e) {
            \Log::warning('Could not add check constraint to students.monthly_fee: '.$e->getMessage());
        }

        try {
            DB::statement('ALTER TABLE courses ADD CONSTRAINT chk_courses_price CHECK (price >= 0)');
        } catch (\Exception $e) {
            \Log::warning('Could not add check constraint to courses.price: '.$e->getMessage());
        }

        try {
            DB::statement('ALTER TABLE commissions ADD CONSTRAINT chk_commissions_rate CHECK (rate >= 0 AND rate <= 100)');
        } catch (\Exception $e) {
            \Log::warning('Could not add check constraint to commissions.rate: '.$e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert Constraints
        try {
            DB::statement('ALTER TABLE commissions DROP CONSTRAINT chk_commissions_rate');
        } catch (\Exception $e) {
            \Log::warning('Could not drop check constraint from commissions.rate: '.$e->getMessage());
        }

        try {
            DB::statement('ALTER TABLE courses DROP CONSTRAINT chk_courses_price');
        } catch (\Exception $e) {
            \Log::warning('Could not drop check constraint from courses.price: '.$e->getMessage());
        }

        try {
            DB::statement('ALTER TABLE students DROP CONSTRAINT chk_students_monthly_fee');
        } catch (\Exception $e) {
            \Log::warning('Could not drop check constraint from students.monthly_fee: '.$e->getMessage());
        }

        // Revert FK changes (putting back cascade)
        try {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->dropForeign(['course_id']);
                $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
            });
        } catch (\Exception $e) {
            \Log::warning('Could not revert enrollments.course_id FK: '.$e->getMessage());
        }

        try {
            Schema::table('schedules', function (Blueprint $table) {
                $table->dropForeign(['course_id']);
                $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
            });
        } catch (\Exception $e) {
            \Log::warning('Could not revert schedules.course_id FK: '.$e->getMessage());
        }

        try {
            Schema::table('schedules', function (Blueprint $table) {
                $table->dropForeign(['branch_id']);
                $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnDelete();
            });
        } catch (\Exception $e) {
            \Log::warning('Could not revert schedules.branch_id FK: '.$e->getMessage());
        }

        try {
            Schema::table('classrooms', function (Blueprint $table) {
                $table->dropForeign(['branch_id']);
                $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnDelete();
            });
        } catch (\Exception $e) {
            \Log::warning('Could not revert classrooms.branch_id FK: '.$e->getMessage());
        }
    }
};
