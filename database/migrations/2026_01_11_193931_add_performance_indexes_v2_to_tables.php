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
            $table->index('grade_level');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->index(['tenant_id', 'created_at']);
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->index(['user_id', 'course_id']);
            $table->index('status');
        });

        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->index('enrollment_id');
            $table->index('lesson_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'status']);
            $table->dropIndex(['grade_level']);
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'created_at']);
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'course_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->dropIndex(['enrollment_id']);
            $table->dropIndex(['lesson_id']);
        });
    }
};
