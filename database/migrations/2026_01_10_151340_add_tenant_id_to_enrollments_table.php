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
        // 1. Add nullable tenant_id column first
        Schema::table('enrollments', function (Blueprint $table) {
            if (!Schema::hasColumn('enrollments', 'tenant_id')) {
                $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->nullOnDelete();
            }
        });

        // 2. Populate tenant_id from courses
        // Universal update query compatible with MySQL and SQLite
        DB::statement("
            UPDATE enrollments
            SET tenant_id = (
                SELECT tenant_id 
                FROM courses 
                WHERE courses.id = enrollments.course_id
            )
            WHERE tenant_id IS NULL
        ");

        // 3. Make tenant_id not null (optional, depending on if we want to enforce it strict)
        // Since it's a multi-tenant app, we likely want it required.
        // However, if there are orphaned enrollments (no course), this might fail.
        // For safety, we'll keep it nullable but add the index.
        // If we really want to enforce it, we would delete orphaned rows first.
        
        // Let's verify if we can make it not null. If there are nulls left, we leave it nullable.
        $hasNulls = DB::table('enrollments')->whereNull('tenant_id')->exists();

        if (!$hasNulls) {
            Schema::table('enrollments', function (Blueprint $table) {
                // Drop the foreign key that requires nullable (set null on delete)
                $table->dropForeign(['tenant_id']);
                
                // Change column to not null
                $table->foreignId('tenant_id')->nullable(false)->change();
                
                // Re-add foreign key with cascade on delete (appropriate for tenant-scoped data)
                $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
             if (Schema::hasColumn('enrollments', 'tenant_id')) {
                // We need to drop FK first usually, assuming standard naming
                // $table->dropForeign(['tenant_id']); 
                // But let's just use dropColumn which handles it in newer Laravel versions often, or be explicit
                $table->dropForeign(['tenant_id']);
                $table->dropColumn('tenant_id');
            }
        });
    }
};
