<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Package;
use Illuminate\Database\Seeder;

class GrowthFeaturesSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            [
                'name' => 'الملف العام',
                'name_en' => 'Public Profile',
                'code' => 'growth_profile',
                'type' => 'boolean',
                'category' => 'growth',
                'sort_order' => 100,
                'is_visible' => true,
            ],
            [
                'name' => 'تحليلات النمو',
                'name_en' => 'Growth Analytics',
                'code' => 'growth_analytics',
                'type' => 'boolean',
                'category' => 'growth',
                'sort_order' => 101,
                'is_visible' => true,
            ],
            [
                'name' => 'النمو المتقدم',
                'name_en' => 'Advanced Growth',
                'code' => 'growth_advanced',
                'type' => 'boolean',
                'category' => 'growth',
                'sort_order' => 102,
                'is_visible' => true,
            ],
        ];

        foreach ($features as $featureData) {
            $feature = Feature::updateOrCreate(
                ['code' => $featureData['code']],
                $featureData
            );

            // Assign to packages: basic gets profile only, pro gets analytics, enterprise gets all
            $packageValues = [
                'basic' => $featureData['code'] === 'growth_profile' ? 'true' : 'false',
                'pro' => in_array($featureData['code'], ['growth_profile', 'growth_analytics']) ? 'true' : 'false',
                'enterprise' => 'true',
            ];

            foreach ($packageValues as $slug => $value) {
                $package = Package::where('slug', $slug)->first();
                if ($package) {
                    $package->features()->syncWithoutDetaching([
                        $feature->id => ['value' => $value],
                    ]);
                }
            }
        }
    }
}
