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
        if (! Schema::hasTable('opportunity_groups')) {
            Schema::create('opportunity_groups', function (Blueprint $table) {
                $table->id();
                $table->foreignId('opportunity_id')->constrained('opportunities')->cascadeOnDelete();
                $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
                $table->unsignedInteger('student_count')->default(0);
                $table->enum('status', ['forming', 'filled', 'cancelled'])->default('forming');
                $table->timestamps();

                $table->index(['opportunity_id', 'status']);
                $table->index(['course_id', 'status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opportunity_groups');
    }
};