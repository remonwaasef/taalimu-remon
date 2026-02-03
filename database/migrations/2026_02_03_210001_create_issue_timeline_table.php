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
        Schema::create('issue_timeline', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_id')->constrained('operation_issues')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', [
                'created',
                'status_changed',
                'assigned',
                'commented',
                'priority_changed',
                'severity_changed',
                'merged',
                'attachment_added',
                'resolved',
                'reopened',
                'muted',
                'unmuted'
            ]);
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
            
            $table->index(['issue_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_timeline');
    }
};
