<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        try {
            Schema::table('public_profiles', function (Blueprint $table) {
                $table->index('slug');
            });
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        try { Schema::table('public_profiles', function (Blueprint $table) {
            $table->dropIndex('public_profiles_slug_index');
        }); } catch (\Exception $e) {}
    }
};
