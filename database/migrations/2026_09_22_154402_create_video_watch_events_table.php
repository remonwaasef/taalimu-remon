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
        Schema::create('video_watch_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->constrained('videos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('session_id')->nullable()->constrained('video_playback_sessions')->nullOnDelete();
            $table->string('event'); // play, pause, seek, heartbeat, complete
            $table->unsignedInteger('position_seconds')->default(0);
            $table->unsignedInteger('duration_seconds')->nullable(); // For seek events: delta
            $table->timestamp('created_at')->useCurrent();

            $table->index(['video_id', 'user_id', 'created_at']);
            $table->index(['tenant_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_watch_events');
    }
};