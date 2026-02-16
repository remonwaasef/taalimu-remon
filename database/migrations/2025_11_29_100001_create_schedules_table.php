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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id'); // Kept as string per original, though usually foreignId
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            
            // Nullable classroom with set null
            $table->foreignId('classroom_id')->nullable()->constrained()->onDelete('set null');
            
            $table->unsignedBigInteger('instructor_id')->nullable();
            $table->foreign('instructor_id')->references('id')->on('users')->nullOnDelete();
            
            $table->foreignId('branch_id')->nullable()->constrained('branches')->cascadeOnDelete();

            $table->tinyInteger('day_of_week')->nullable()->comment('0=Sunday, 6=Saturday');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            
            $table->integer('max_students')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'day_of_week']);
            $table->index(['tenant_id', 'course_id', 'day_of_week'], 'sch_tenant_course_day_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
