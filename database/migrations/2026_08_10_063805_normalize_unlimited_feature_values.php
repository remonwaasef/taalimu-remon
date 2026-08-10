<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PR-1: standardize 'unlimited' feature values to the numeric sentinel -1
 * that SubscriptionService#checkLimit understands.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('package_features')
            ->where('value', 'unlimited')
            ->update(['value' => '-1']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('package_features')
            ->where('value', '-1')
            ->update(['value' => 'unlimited']);
    }
};