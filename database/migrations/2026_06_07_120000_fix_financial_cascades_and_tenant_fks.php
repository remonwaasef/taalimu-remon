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
        // 1. Remove cascadeOnDelete from sales and payments
        try {
            Schema::table('sales', function (Blueprint $table) {
                // Check if the foreign key has cascade, or just drop and recreate
                $table->dropForeign(['tenant_id']);
                $table->foreign('tenant_id')->references('id')->on('tenants')->restrictOnDelete();
            });
        } catch (\Exception $e) {
            \Log::warning('Could not update sales.tenant_id foreign key (might already be updated): '.$e->getMessage());
        }

        try {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropForeign(['tenant_id']);
                $table->foreign('tenant_id')->references('id')->on('tenants')->restrictOnDelete();
            });
        } catch (\Exception $e) {
            \Log::warning('Could not update payments.tenant_id foreign key (might already be updated): '.$e->getMessage());
        }

        // 2. Add tenant_id to user_consents
        try {
            if (! Schema::hasColumn('user_consents', 'tenant_id')) {
                Schema::table('user_consents', function (Blueprint $table) {
                    $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
                    $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
                });
            }
        } catch (\Exception $e) {
            \Log::warning('Could not add tenant_id to user_consents (might already exist): '.$e->getMessage());
        }

        // 3. Fix tenant_id type in classrooms and schedules (Warning: This assumes tenant_id strings were actually numbers)
        // Only doing this if requested by the plan.
        try {
            Schema::table('classrooms', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->change();
            });
        } catch (\Exception $e) {
            \Log::warning('Could not change classrooms.tenant_id type: '.$e->getMessage());
        }

        try {
            Schema::table('classrooms', function (Blueprint $table) {
                // Check if foreign key exists first to avoid duplicate
                $fkExists = collect(DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'classrooms' AND COLUMN_NAME = 'tenant_id' AND REFERENCED_TABLE_NAME IS NOT NULL"))->isNotEmpty();

                if (! $fkExists) {
                    $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
                }
            });
        } catch (\Exception $e) {
            \Log::warning('Could not add foreign key to classrooms.tenant_id: '.$e->getMessage());
        }

        try {
            Schema::table('schedules', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->change();
            });
        } catch (\Exception $e) {
            \Log::warning('Could not change schedules.tenant_id type: '.$e->getMessage());
        }

        try {
            Schema::table('schedules', function (Blueprint $table) {
                $fkExists = collect(DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'schedules' AND COLUMN_NAME = 'tenant_id' AND REFERENCED_TABLE_NAME IS NOT NULL"))->isNotEmpty();

                if (! $fkExists) {
                    $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
                }
            });
        } catch (\Exception $e) {
            \Log::warning('Could not add foreign key to schedules.tenant_id: '.$e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try { Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->string('tenant_id')->change();
        }); } catch (\Exception $e) {}

        try { Schema::table('classrooms', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->string('tenant_id')->change();
            $table->index('tenant_id');
        }); } catch (\Exception $e) {}

        try { Schema::table('user_consents', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        }); } catch (\Exception $e) {}

        try { Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        }); } catch (\Exception $e) {}

        try { Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        }); } catch (\Exception $e) {}
    }
};
