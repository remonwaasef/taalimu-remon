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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->index(); // From tenant_id migration
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            
            // Expanded enum with 'assignment'
            $table->enum('type', ['video', 'text', 'quiz', 'assignment'])->default('video');
            
            $table->text('content')->nullable(); // URL for video or HTML for text
            $table->integer('duration')->default(0); // in minutes
            $table->integer('sort_order')->default(0);
            $table->boolean('is_free')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
