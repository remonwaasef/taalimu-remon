<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('email')->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->string('logo')->nullable()->after('address');
            $table->string('favicon')->nullable()->after('logo');
            $table->text('description')->nullable()->after('favicon');
            $table->string('facebook_url')->nullable()->after('description');
            $table->string('instagram_url')->nullable()->after('facebook_url');
            $table->string('twitter_url')->nullable()->after('instagram_url');
            $table->string('youtube_url')->nullable()->after('twitter_url');
            $table->string('linkedin_url')->nullable()->after('youtube_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'phone',
                'address',
                'logo',
                'favicon',
                'description',
                'facebook_url',
                'instagram_url',
                'twitter_url',
                'youtube_url',
                'linkedin_url',
            ]);
        });
    }
};
