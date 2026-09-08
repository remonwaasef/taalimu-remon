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
        Schema::table('enrollments', function (Blueprint $table) {
            // Prevent duplicate enrollments: same student cannot be enrolled in same course within same tenant
            $table->unique(['tenant_id', 'user_id', 'course_id'], 'enr_tenant_user_course_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try { Schema::table('enrollments', function (Blueprint $table) {
            $table->dropUnique('enr_tenant_user_course_unique');
        }); } catch (\Exception $e) {}
    }
};
