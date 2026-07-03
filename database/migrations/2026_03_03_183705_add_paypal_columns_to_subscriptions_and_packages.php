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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('paypal_id')->nullable()->unique()->after('stripe_id');
            $table->string('paypal_status')->nullable()->after('stripe_status');
            $table->string('paypal_plan_id')->nullable()->after('stripe_price');
            $table->string('gateway')->default('stripe')->after('tenant_id');

            // Stripe ID should be nullable if we use PayPal
            $table->string('stripe_id')->nullable()->change();
            $table->string('stripe_status')->nullable()->change();
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->string('paypal_plan_id')->nullable()->after('stripe_price_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('paypal_plan_id');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['paypal_id', 'paypal_status', 'paypal_plan_id', 'gateway']);
            $table->string('stripe_id')->nullable(false)->change();
            $table->string('stripe_status')->nullable(false)->change();
        });
    }
};
