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
        Schema::create('video_playback_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->constrained('videos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('session_token_hash', 64); // SHA-256 hash of playback token
            $table->string('ip_address', 45)->nullable(); // IPv4/IPv6
            $table->string('user_agent', 512)->nullable();
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();

            $table->index(['video_id', 'user_id']);
            $table->index(['tenant_id', 'expires_at']);
            $table->index('session_token_hash'); // For token validation
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_playback_sessions');
    }
};