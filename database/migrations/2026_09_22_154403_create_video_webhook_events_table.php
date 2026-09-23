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
        Schema::create('video_webhook_events', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->default('bunny');
            $table->string('event_type'); // video.created, video.encoded, video.failed, etc.
            $table->string('provider_video_id'); // Bunny video ID
            $table->json('payload')->nullable();
            $table->string('status')->default('pending'); // pending, processed, failed
            $table->text('error_message')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->unique(['provider', 'provider_video_id', 'event_type'], 'vid_webhook_idempotency'); // Idempotency
            $table->index(['provider', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_webhook_events');
    }
};