<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إصلاح الحذف المتتالي في الجداول المالية الحساسة لمنع فقدان الأرشيف المالي
     * عند حذف المستأجر أو المستخدم.
     */
    public function up(): void
    {
        $financialTables = ['invoices', 'expenses', 'commissions', 'refunds', 'payouts'];

        foreach ($financialTables as $tableName) {
            try {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign(['tenant_id']);
                    $table->foreign('tenant_id')->references('id')->on('tenants')->restrictOnDelete();
                });
            } catch (\Exception $e) {
                \Log::warning("Could not update {$tableName}.tenant_id FK: ".$e->getMessage());
            }
        }

        // Fix processed_by cascadeOnDelete in refunds → nullOnDelete
        try {
            Schema::table('refunds', function (Blueprint $table) {
                $table->dropForeign(['processed_by']);
                $table->foreign('processed_by')->references('id')->on('users')->nullOnDelete();
            });
        } catch (\Exception $e) {
            \Log::warning('Could not update refunds.processed_by FK: '.$e->getMessage());
        }

        // Fix processed_by cascadeOnDelete in payouts → nullOnDelete
        try {
            Schema::table('payouts', function (Blueprint $table) {
                $table->dropForeign(['processed_by']);
                $table->foreign('processed_by')->references('id')->on('users')->nullOnDelete();
            });
        } catch (\Exception $e) {
            \Log::warning('Could not update payouts.processed_by FK: '.$e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $financialTables = ['invoices', 'expenses', 'commissions', 'refunds', 'payouts'];

        foreach ($financialTables as $tableName) {
            try {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign(['tenant_id']);
                    $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
                });
            } catch (\Exception $e) {
                \Log::warning("Could not revert {$tableName}.tenant_id FK: ".$e->getMessage());
            }
        }

        try {
            Schema::table('refunds', function (Blueprint $table) {
                $table->dropForeign(['processed_by']);
                $table->foreign('processed_by')->references('id')->on('users')->cascadeOnDelete();
            });
        } catch (\Exception $e) {
            \Log::warning('Could not revert refunds.processed_by FK: '.$e->getMessage());
        }

        try {
            Schema::table('payouts', function (Blueprint $table) {
                $table->dropForeign(['processed_by']);
                $table->foreign('processed_by')->references('id')->on('users')->cascadeOnDelete();
            });
        } catch (\Exception $e) {
            \Log::warning('Could not revert payouts.processed_by FK: '.$e->getMessage());
        }
    }
};
