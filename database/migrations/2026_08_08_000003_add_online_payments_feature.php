<?php

use App\Models\Feature;
use App\Models\Package;
use App\Models\PackageFeature;
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
        // 1. Token column on sales — used to verify shared public pay links
        if (! Schema::hasColumn('sales', 'payment_token')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->string('payment_token', 32)->nullable()->after('paid_amount');
            });
        }

        // 2. Feature flag for self-service online payments
        $feature = Feature::updateOrCreate(
            ['code' => 'online_payments'],
            [
                'name' => 'الدفع الإلكتروني الذاتي',
                'name_en' => 'Online Payments',
                'type' => 'boolean',
                'category' => 'finance',
                'is_visible' => true,
                'sort_order' => 120,
            ]
        );

        // Available on paid tiers (+ free trial to showcase); off for the starter/basic plan
        $plans = [
            'free-trial' => 'true',
            'pro' => 'true',
            'enterprise' => 'true',
            'basic' => 'false',
        ];

        foreach ($plans as $slug => $value) {
            $package = Package::where('slug', $slug)->first();
            if ($package) {
                PackageFeature::updateOrCreate(
                    ['package_id' => $package->id, 'feature_id' => $feature->id],
                    ['value' => $value]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('sales', 'payment_token')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->dropColumn('payment_token');
            });
        }

        $feature = Feature::where('code', 'online_payments')->first();
        if ($feature) {
            PackageFeature::where('feature_id', $feature->id)->delete();
            $feature->delete();
        }
    }
};