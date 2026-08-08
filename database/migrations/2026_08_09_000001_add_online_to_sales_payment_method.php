<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Allow 'online' payment method on sales rows (Paymob self-service payments,
     * FinanceService::addPayment(..., 'online', ...) from PaymobWebhookController).
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE sales MODIFY payment_method ENUM('cash', 'card', 'bank_transfer', 'other', 'online') NOT NULL DEFAULT 'cash'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE sales MODIFY payment_method ENUM('cash', 'card', 'bank_transfer', 'other') NOT NULL DEFAULT 'cash'");
        }
    }
};