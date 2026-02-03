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
        Schema::create('operation_issues', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // Context
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('user_agent')->nullable();
            $table->string('ip_address', 45)->nullable();
            
            // Issue Details
            $table->string('title');
            $table->string('action');
            $table->string('url', 2048)->nullable();
            $table->string('method', 10)->default('N/A');
            $table->text('message');
            $table->json('payload')->nullable();
            $table->json('context')->nullable();
            $table->mediumText('stack_trace')->nullable();
            
            // Classification
            $table->enum('severity', ['critical', 'high', 'medium', 'low'])->default('medium');
            $table->string('category')->default('unknown');
            $table->string('exception_class')->nullable();
            $table->string('exception_code')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('line_number')->nullable();
            
            // Grouping (for duplicate detection)
            $table->string('fingerprint')->index();
            $table->unsignedInteger('occurrence_count')->default(1);
            $table->timestamp('last_occurrence_at')->nullable();
            
            // Status Management
            $table->enum('status', ['new', 'acknowledged', 'in_progress', 'resolved', 'closed', 'wont_fix'])->default('new');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('priority', ['urgent', 'high', 'normal', 'low'])->default('normal');
            
            // SLA Tracking
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamp('first_response_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->integer('resolution_time_minutes')->nullable();
            $table->boolean('sla_breached')->default(false);
            
            // Resolution
            $table->text('resolution_notes')->nullable();
            $table->string('resolution_type')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            
            // Tags (JSON array)
            $table->json('tags')->nullable();
            
            // Flags
            $table->boolean('is_muted')->default(false);
            $table->boolean('is_recurring')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Performance Indexes
            $table->index(['tenant_id', 'status', 'created_at']);
            $table->index(['severity', 'status']);
            $table->index(['category', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_issues');
    }
};
