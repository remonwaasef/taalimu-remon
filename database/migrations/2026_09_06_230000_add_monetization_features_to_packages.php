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
        $features = [
            [
                'code' => 'online_classes',
                'name' => 'الحصص المباشرة (أونلاين)',
                'name_en' => 'Online Classes',
                'type' => 'boolean',
                'category' => 'academic',
                'is_visible' => true,
                'sort_order' => 130,
                'plans' => [
                    'basic' => 'false',
                    'pro' => 'true',
                    'enterprise' => 'true',
                    'free-trial' => 'true',
                ],
            ],
            [
                'code' => 'asset_management',
                'name' => 'إدارة العهد والأصول',
                'name_en' => 'Asset & Inventory Management',
                'type' => 'boolean',
                'category' => 'core',
                'is_visible' => true,
                'sort_order' => 140,
                'plans' => [
                    'basic' => 'false',
                    'pro' => 'false',
                    'enterprise' => 'true',
                    'free-trial' => 'true',
                ],
            ],
            [
                'code' => 'audit_logs',
                'name' => 'سجل تدقيق النشاطات والأمان',
                'name_en' => 'Audit & Activity Logs',
                'type' => 'boolean',
                'category' => 'core',
                'is_visible' => true,
                'sort_order' => 150,
                'plans' => [
                    'basic' => 'false',
                    'pro' => 'false',
                    'enterprise' => 'true',
                    'free-trial' => 'true',
                ],
            ],
        ];

        foreach ($features as $data) {
            $plans = $data['plans'];
            unset($data['plans']);

            $feature = Feature::updateOrCreate(
                ['code' => $data['code']],
                $data
            );

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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $codes = ['online_classes', 'asset_management', 'audit_logs'];

        foreach ($codes as $code) {
            $feature = Feature::where('code', $code)->first();
            if ($feature) {
                PackageFeature::where('feature_id', $feature->id)->delete();
                $feature->delete();
            }
        }
    }
};
