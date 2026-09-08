<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('profilable_type');
            $table->unsignedBigInteger('profilable_id');
            $table->string('slug');
            $table->string('title')->nullable();
            $table->string('headline')->nullable();
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->string('cover_photo')->nullable();
            $table->string('location')->nullable();
            $table->unsignedInteger('experience_years')->nullable();
            $table->json('specializations')->nullable();
            $table->json('teaching_levels')->nullable();
            $table->json('delivery_modes')->nullable();
            $table->json('social_links')->nullable();
            $table->json('visibility')->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->boolean('published')->default(false);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_image')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'slug']);
            $table->index(['tenant_id', 'published']);
            $table->index(['profilable_type', 'profilable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_profiles');
    }
};
