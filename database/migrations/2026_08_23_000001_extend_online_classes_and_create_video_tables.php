<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Extend the online classes system with live sessions, recordings and
     * secure playback support. Fully reversible.
     */
    public function up(): void
    {
        Schema::table('online_classes', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
            $table->string('access_mode')->default('course')->after('status')->comment('course = enrolled students of the course, selected = explicit allowlist');
            $table->string('recording_status')->default('none')->after('access_mode')->comment('none, processing, available, failed');
            $table->string('zoom_meeting_uuid')->nullable()->after('meeting_id');
            $table->string('zoom_account_id')->nullable()->after('zoom_meeting_uuid');
            $table->boolean('auto_recording')->default(true)->after('zoom_account_id');
            $table->timestamp('started_at')->nullable()->after('auto_recording');
            $table->timestamp('ended_at')->nullable()->after('started_at');
            $table->timestamp('reminder_sent_at')->nullable()->after('ended_at');

            // SDK-driven sessions do not carry an external link; make it optional.
            $table->text('meeting_link')->nullable()->change();
        });

        // Backfill uuids for pre-existing rows.
        DB::table('online_classes')->whereNull('uuid')->orderBy('id')->chunkById(200, function ($classes) {
            foreach ($classes as $class) {
                DB::table('online_classes')->where('id', $class->id)->update(['uuid' => (string) Str::uuid()]);
            }
        });

        Schema::create('online_class_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->foreignId('online_class_id')->constrained('online_classes')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->unsignedInteger('attendance_minutes')->default(0);
            $table->string('status')->default('invited')->comment('invited, joined, left, absent');
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->unique(['online_class_id', 'student_id']);
            $table->index('tenant_id');
        });

        Schema::create('class_recordings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('tenant_id');
            $table->foreignId('online_class_id')->constrained('online_classes')->cascadeOnDelete();
            $table->string('provider')->default('zoom');
            $table->string('external_recording_id');
            $table->string('storage_provider')->nullable()->comment('r2, s3, local');
            $table->text('storage_key')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->string('status')->default('processing')->comment('processing, ready, failed, expired');
            $table->timestamp('available_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            // Idempotency backstop for provider webhooks: one recording per external id.
            $table->unique(['provider', 'external_recording_id']);
            $table->index(['tenant_id', 'online_class_id']);
        });

        Schema::create('video_access_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->foreignId('recording_id')->constrained('class_recordings')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action')->comment('token_issued, playback_denied, download_attempt, session_conflict');
            // Privacy: only hashed identifiers are stored, never raw IP / UA.
            $table->string('ip_hash', 64)->nullable();
            $table->string('user_agent_hash', 64)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->index(['recording_id', 'created_at']);
            $table->index(['tenant_id', 'created_at']);
        });

        Schema::create('video_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->foreignId('recording_id')->constrained('class_recordings')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('last_position_seconds')->default(0);
            $table->unsignedInteger('watched_seconds')->default(0);
            $table->decimal('completion_percentage', 5, 2)->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->unique(['user_id', 'recording_id']);
            $table->index(['tenant_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_progress');
        Schema::dropIfExists('video_access_logs');
        Schema::dropIfExists('class_recordings');
        Schema::dropIfExists('online_class_participants');

        Schema::table('online_classes', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn([
                'uuid', 'access_mode', 'recording_status', 'zoom_meeting_uuid',
                'zoom_account_id', 'auto_recording', 'started_at', 'ended_at', 'reminder_sent_at',
            ]);
        });
    }
};
