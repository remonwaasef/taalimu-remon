<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add performance indexes on hot columns identified in security audit.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Tenant domain index — used on EVERY request in IdentifyTenant middleware
        if (! $this->hasIndex('tenants', 'tenants_domain_index')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->index('domain', 'tenants_domain_index');
            });
        }

        // Users: tenant_id used in all tenant-scoped queries
        if (! $this->hasIndex('users', 'users_tenant_id_index')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('tenant_id', 'users_tenant_id_index');
            });
        }

        // Users: email used in login
        if (! $this->hasIndex('users', 'users_email_index')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('email', 'users_email_index');
            });
        }

        // Students: tenant_id for tenant-scoped student queries
        if (! $this->hasIndex('students', 'students_tenant_id_index')) {
            Schema::table('students', function (Blueprint $table) {
                $table->index('tenant_id', 'students_tenant_id_index');
            });
        }

        // Enrollments: already has (user_id, course_id) index from creation migration — skipped

        // Sales: tenant+student for finance queries
        if (! $this->hasIndex('sales', 'sales_tenant_student_index')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->index(['tenant_id', 'student_id'], 'sales_tenant_student_index');
            });
        }

        // Courses: tenant_id for tenant-scoped course queries
        if (! $this->hasIndex('courses', 'courses_tenant_id_index')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->index('tenant_id', 'courses_tenant_id_index');
            });
        }

        // Schedules: tenant_id for tenant-scoped schedule queries
        if (! $this->hasIndex('schedules', 'schedules_tenant_id_index')) {
            Schema::table('schedules', function (Blueprint $table) {
                $table->index('tenant_id', 'schedules_tenant_id_index');
            });
        }

        // Subscriptions: tenant_id + status for subscription lookups
        if (! $this->hasIndex('subscriptions', 'subscriptions_tenant_status_index')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->index(['tenant_id', 'status'], 'subscriptions_tenant_status_index');
            });
        }
    }

    public function down(): void
    {
        $indexes = [
            'tenants' => 'tenants_domain_index',
            'users' => ['users_tenant_id_index', 'users_email_index'],
            'students' => 'students_tenant_id_index',
            'sales' => 'sales_tenant_student_index',
            'courses' => 'courses_tenant_id_index',
            'schedules' => 'schedules_tenant_id_index',
            'subscriptions' => 'subscriptions_tenant_status_index',
        ];

        foreach ($indexes as $table => $indexNames) {
            $indexNames = (array) $indexNames;
            foreach ($indexNames as $indexName) {
                if ($this->hasIndex($table, $indexName)) {
                    Schema::table($table, function (Blueprint $table) use ($indexName) {
                        $table->dropIndex($indexName);
                    });
                }
            }
        }
    }

    /**
     * Check if an index already exists to handle idempotent migrations.
     */
    private function hasIndex(string $table, string $indexName): bool
    {
        $indexes = Schema::getIndexes($table);
        foreach ($indexes as $index) {
            if ($index['name'] === $indexName) {
                return true;
            }
        }

        return false;
    }
};
