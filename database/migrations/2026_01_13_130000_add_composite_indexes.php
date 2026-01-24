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
        Schema::table('users', function (Blueprint $table) {
            // Optimizes: looking up users by email within a tenant (login, registration checks)
            // and filtering users by role within a tenant (admin lists)
            $table->index(['tenant_id', 'email']); 
            $table->index(['tenant_id', 'role']);
        });

        Schema::table('students', function (Blueprint $table) {
            // Optimizes: Searching students by email/phone within a tenant
            $table->index(['tenant_id', 'email']);
            $table->index(['tenant_id', 'phone']);
            // Optimizes: Filtering students by grade level
            $table->index(['tenant_id', 'grade_level']);
        });

        Schema::table('enrollments', function (Blueprint $table) {
            // Optimizes: "My Courses" page, checking if a user has a specific course validity
            $table->index(['tenant_id', 'user_id']);
            $table->index(['tenant_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'email']);
            $table->dropIndex(['tenant_id', 'role']);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'email']);
            $table->dropIndex(['tenant_id', 'phone']);
            $table->dropIndex(['tenant_id', 'grade_level']);
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'user_id']);
            $table->dropIndex(['tenant_id', 'course_id']);
        });
    }
};
