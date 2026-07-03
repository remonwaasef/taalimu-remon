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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->index()->constrained()->cascadeOnDelete();

            $table->foreignId('quiz_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('question_categories')->nullOnDelete();

            $table->text('content');
            $table->text('explanation')->nullable();

            $table->enum('type', ['mcq', 'true_false'])->default('mcq');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');

            $table->integer('points')->default(1);
            $table->timestamps();

            $table->index(['tenant_id', 'difficulty']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
