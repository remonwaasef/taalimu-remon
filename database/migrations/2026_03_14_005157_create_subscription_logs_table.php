<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('operation_type')->default('subscription'); // subscription, upgrade, renewal
            $table->string('package_slug')->nullable();
            $table->string('package_name')->nullable();
            $table->string('billing_cycle')->nullable();
            $table->string('gateway')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('transaction_id')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();

            $table->index('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_logs');
    }
};
