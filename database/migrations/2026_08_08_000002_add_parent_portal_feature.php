<?php

use App\Models\Feature;
use App\Models\Package;
use App\Models\PackageFeature;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create the feature if it doesn't exist
        $feature = Feature::updateOrCreate(
            ['code' => 'parent_portal'],
            [
                'name' => 'بوابة ولي الأمر',
                'name_en' => 'Parent Portal',
                'type' => 'boolean',
                'category' => 'core',
                'is_visible' => true,
                'sort_order' => 110,
            ]
        );

        // 2. Associate with packages (slugs: basic=starter, pro=growth, enterprise=institution)
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
        $feature = Feature::where('code', 'parent_portal')->first();
        if ($feature) {
            PackageFeature::where('feature_id', $feature->id)->delete();
            $feature->delete();
        }
    }
};