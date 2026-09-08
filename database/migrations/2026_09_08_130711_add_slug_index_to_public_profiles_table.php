<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('public_profiles', function (Blueprint $table) {
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::table('public_profiles', function (Blueprint $table) {
            $table->dropIndex('public_profiles_slug_index');
        });
    }
};
