<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Define the missing limit features
        $limitFeatures = [
            [
                'code' => 'max_students',
                'name' => 'عدد الطلاب',
                'type' => 'limit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'max_instructors',
                'name' => 'المستخدمين الإداريين',
                'type' => 'limit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'max_courses',
                'name' => 'عدد الدروات',
                'type' => 'limit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'max_classrooms',
                'name' => 'عدد الفصول',
                'type' => 'limit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'max_branches',
                'name' => 'عدد الفروع',
                'type' => 'limit',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($limitFeatures as $featureData) {
            // Insert feature if it doesn't exist
            $exists = DB::table('features')->where('code', $featureData['code'])->exists();
            
            if (!$exists) {
                $featureId = DB::table('features')->insertGetId($featureData);
                
                // Associate with all existing packages (defaulting to -1 which means Unlimited)
                $packages = DB::table('packages')->get();
                
                foreach ($packages as $package) {
                    DB::table('package_features')->insert([
                        'package_id' => $package->id,
                        'feature_id' => $featureId,
                        'value' => '-1', // Default to unlimited
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $codes = ['max_students', 'max_instructors', 'max_courses', 'max_classrooms', 'max_branches'];
        
        foreach ($codes as $code) {
            $feature = DB::table('features')->where('code', $code)->first();
            if ($feature) {
                // Delete pivot records first
                DB::table('package_features')->where('feature_id', $feature->id)->delete();
                // Delete feature
                DB::table('features')->where('id', $feature->id)->delete();
            }
        }
    }
};
