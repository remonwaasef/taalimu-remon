<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * SEC-A4: Scope password reset tokens by tenant.
 *
 * users.email is unique per tenant (2026_05_02_184749), so reset tokens keyed
 * by email alone allow cross-tenant account takeover. Add tenant_id to the
 * token rows and make (email, tenant_id) the primary key on MySQL/MariaDB.
 * SQLite (local dev) cannot drop the email primary key via ALTER; lookups are
 * still tenant-filtered there and a single row per email is the dev limit.
 */
return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->string('tenant_id')->default('global')->after('email');
        });

        if (in_array($driver, ['mysql', 'mariadb'])) {
            DB::statement('ALTER TABLE password_reset_tokens DROP PRIMARY KEY');
            DB::statement('ALTER TABLE password_reset_tokens ADD PRIMARY KEY (email, tenant_id)');
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'])) {
            DB::statement('ALTER TABLE password_reset_tokens DROP PRIMARY KEY');
            DB::statement('ALTER TABLE password_reset_tokens ADD PRIMARY KEY (email)');
        }

        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
        });
    }
};
