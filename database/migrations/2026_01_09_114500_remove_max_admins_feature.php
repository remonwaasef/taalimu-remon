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
        // Remove 'max_admins' from package_features for all packages
        $feature = DB::table('features')->where('code', 'max_admins')->first();
        
        if ($feature) {
            DB::table('package_features')->where('feature_id', $feature->id)->delete();
            // Also optionally delete the feature itself if not used elsewhere, but safe to keep just in case
             DB::table('features')->where('id', $feature->id)->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add max_admins if needed (simplified rollback)
        $featureId = DB::table('features')->insertGetId([
            'code' => 'max_admins',
            'name' => 'المستخدمين الإداريين',
            'type' => 'limit',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Re-attach to packages would be complex without knowing previous values, defaulting to unlimited
        $packages = DB::table('packages')->get();
        foreach ($packages as $package) {
             DB::table('package_features')->insert([
                'package_id' => $package->id,
                'feature_id' => $featureId,
                'value' => '-1',
                'created_at' => now(),
            ]);
        }
    }
};
