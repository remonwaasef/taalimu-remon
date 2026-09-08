<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('public_profiles', function (Blueprint $table) {
            $table->string('referral_code', 8)->unique()->nullable()->after('slug');
        });
    }

    public function down(): void
    {
        Schema::table('public_profiles', function (Blueprint $table) {
            $table->dropColumn('referral_code');
        });
    }
};
