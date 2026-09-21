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
        if (!Schema::hasTable('online_class_messages')) {
            Schema::create('online_class_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
                $table->foreignId('online_class_id')->constrained('online_classes')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->text('message');
                $table->string('type')->default('text')->comment('text, announcement, system');
                $table->timestamps();

                $table->index(['tenant_id', 'online_class_id', 'created_at'], 'idx_ocm_tenant_class_time');
            });
        }

        if (!Schema::hasTable('online_class_questions')) {
            Schema::create('online_class_questions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
                $table->foreignId('online_class_id')->constrained('online_classes')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
                $table->text('question');
                $table->unsignedInteger('upvotes_count')->default(0);
                $table->boolean('is_answered')->default(false);
                $table->timestamp('answered_at')->nullable();
                $table->timestamps();

                $table->index(['tenant_id', 'online_class_id'], 'idx_ocq_tenant_class');
                $table->index(['online_class_id', 'is_answered'], 'idx_ocq_class_answered');
            });
        }

        if (!Schema::hasTable('online_class_hand_raises')) {
            Schema::create('online_class_hand_raises', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
                $table->foreignId('online_class_id')->constrained('online_classes')->cascadeOnDelete();
                $table->foreignId('student_id')->nullable()->constrained('students')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('status')->default('raised')->comment('raised, answered, lowered');
                $table->timestamp('raised_at')->nullable();
                $table->timestamps();

                $table->index(['tenant_id', 'online_class_id', 'status'], 'idx_ochr_tenant_class_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_class_hand_raises');
        Schema::dropIfExists('online_class_questions');
        Schema::dropIfExists('online_class_messages');
    }
};
