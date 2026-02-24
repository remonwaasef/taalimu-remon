<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Feature;
use App\Models\Package;
use App\Models\PackageFeature;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create the feature if it doesn't exist
        $feature = Feature::updateOrCreate(
            ['code' => 'student_portal'],
            [
                'name' => 'بوابة الطالب',
                'name_en' => 'Student Portal',
                'type' => 'boolean',
                'category' => 'core',
                'is_visible' => true,
                'sort_order' => 100, // Put it at the end
            ]
        );

        // 2. Associate with packages
        $plans = [
             'free-trial' => 'true',
             'growth' => 'true',
             'institution' => 'true',
             'starter' => 'false'
        ];

        foreach($plans as $slug => $value) {
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
        $feature = Feature::where('code', 'student_portal')->first();
        if ($feature) {
            PackageFeature::where('feature_id', $feature->id)->delete();
            $feature->delete();
        }
    }
};
