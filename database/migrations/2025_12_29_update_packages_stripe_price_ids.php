<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update stripe_price_id for existing packages
        DB::table('packages')->where('slug', 'basic')->update([
            'stripe_price_id' => env('STRIPE_PRICE_BASIC', 'price_test_basic')
        ]);

        DB::table('packages')->where('slug', 'pro')->update([
            'stripe_price_id' => env('STRIPE_PRICE_PRO', 'price_test_pro')
        ]);
        
        // Free plan doesn't need a stripe_price_id (it's null)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('packages')->whereIn('slug', ['basic', 'pro'])->update([
            'stripe_price_id' => null
        ]);
    }
};
