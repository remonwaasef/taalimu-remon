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
        if (! Schema::hasTable('opportunities')) {
            Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('demand_aggregation_id')->nullable()->constrained('demand_aggregations')->nullOnDelete();
            $table->string('title');
            $table->string('subject');
            $table->string('level')->nullable();
            $table->text('description')->nullable();
            $table->text('explanation')->nullable(); // explainable matching reason
            $table->unsignedInteger('demand_volume')->default(0);
            $table->decimal('score', 5, 2)->default(0);
            $table->json('score_breakdown')->nullable();
            $table->json('metadata')->nullable(); // capacity, schedule_match, etc.
            $table->enum('status', ['open', 'matched', 'group_forming', 'group_formed', 'filled', 'expired', 'cancelled'])->default('open');
            $table->foreignId('matched_teacher_id')->nullable()->constrained('instructors')->nullOnDelete();
            $table->foreignId('matched_course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->timestamp('matched_at')->nullable();
            $table->timestamp('group_formed_at')->nullable();
            $table->timestamp('filled_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'subject', 'status']);
            $table->index(['matched_teacher_id', 'status']);
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};