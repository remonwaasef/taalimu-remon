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
        Schema::create('online_checkouts', function (Blueprint $table) {
            $table->id();

            // The Paymob order id — the ONLY cross-boundary key that is
            // covered by the webhook HMAC, so it can safely authorize
            // subscription activation (PAY-2).
            $table->string('paymob_order_id', 64)->unique();

            $table->foreignId('tenant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('package_slug', 50)->nullable();
            $table->string('billing_cycle', 20)->default('monthly');
            $table->boolean('is_change')->default(false);
            $table->unsignedBigInteger('amount_cents')->default(0);
            $table->string('merchant_order_id', 191)->nullable();
            $table->enum('status', ['pending', 'processed', 'failed'])->default('pending');

            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_checkouts');
    }
};