<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->timestamp('onboarding_completed_at')->nullable()->after('timezone');
        });

        // Mark all existing tenants as onboarded so they are not affected
        \App\Models\Tenant::query()->update([
            'onboarding_completed_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('onboarding_completed_at');
        });
    }
};
