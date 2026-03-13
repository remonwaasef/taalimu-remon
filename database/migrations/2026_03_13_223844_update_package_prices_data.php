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
        $updates = [
            'basic' => [
                'price' => 450, 
                'term_price' => 1450, 
                'yearly_price' => 2500,
                'regional_prices' => json_encode([
                    'default' => ['amount' => 15, 'currency' => 'USD', 'term_price' => 49, 'yearly_price' => 85],
                    'EG' => ['amount' => 450, 'currency' => 'EGP', 'term_price' => 1450, 'yearly_price' => 2500],
                ])
            ],
            'pro' => [
                'price' => 950, 
                'term_price' => 3450, 
                'yearly_price' => 6000,
                'regional_prices' => json_encode([
                    'default' => ['amount' => 30, 'currency' => 'USD', 'term_price' => 99, 'yearly_price' => 170],
                    'EG' => ['amount' => 950, 'currency' => 'EGP', 'term_price' => 3450, 'yearly_price' => 6000],
                ])
            ],
            'enterprise' => [
                'price' => 1950, 
                'term_price' => 6950, 
                'yearly_price' => 12000,
                'regional_prices' => json_encode([
                    'default' => ['amount' => 60, 'currency' => 'USD', 'term_price' => 199, 'yearly_price' => 340],
                    'EG' => ['amount' => 1950, 'currency' => 'EGP', 'term_price' => 6950, 'yearly_price' => 12000],
                ])
            ],
        ];

        foreach ($updates as $slug => $data) {
            \Illuminate\Support\Facades\DB::table('packages')->where('slug', $slug)->update($data);
        }

        \Illuminate\Support\Facades\Cache::forget('subscription_packages_full');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
