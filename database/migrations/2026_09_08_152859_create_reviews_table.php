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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('enrollment_id')->nullable()->constrained('enrollments')->nullOnDelete();
            $table->string('reviewable_type'); // Instructor or Center
            $table->unsignedBigInteger('reviewable_id');
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->text('response')->nullable(); // tenant response
            $table->boolean('verified')->default(false);
            $table->boolean('approved')->default(true);
            $table->timestamps();

            $table->unique(['tenant_id', 'reviewer_id', 'reviewable_type', 'reviewable_id'], 'review_unique');
            $table->index(['reviewable_type', 'reviewable_id']);
            $table->index(['tenant_id', 'approved']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
