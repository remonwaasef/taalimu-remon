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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();
            $table->string('provider')->default('bunny');
            $table->string('provider_library_id')->nullable(); // Bunny library/video container ID
            $table->string('provider_video_id')->nullable();   // Bunny video ID
            $table->string('title')->nullable();
            $table->string('status')->default('pending'); // pending, uploading, processing, ready, failed, deleted
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedBigInteger('storage_size_bytes')->nullable();
            $table->unsignedInteger('encode_progress')->default(0);
            $table->string('thumbnail_url')->nullable();
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'lesson_id']);
            $table->index(['tenant_id', 'status']);
            $table->unique(['provider', 'provider_video_id']); // Idempotency for webhooks
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};