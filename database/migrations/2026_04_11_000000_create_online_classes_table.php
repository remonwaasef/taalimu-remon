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
        Schema::dropIfExists('online_classes');

        Schema::create('online_classes', function (Blueprint $table) {
            $table->id();

            // Multi-Tenancy Column
            $table->unsignedBigInteger('tenant_id')->nullable();

            $table->unsignedBigInteger('instructor_id')->nullable();

            // Optional relation to a course
            $table->unsignedBigInteger('course_id')->nullable();

            $table->string('title');
            $table->string('platform')->default('zoom')->comment('zoom, meet, custom');
            $table->text('meeting_link');
            $table->string('meeting_id')->nullable();
            $table->string('meeting_password')->nullable();
            $table->timestamp('start_time');
            $table->integer('duration_minutes')->default(60);

            $table->string('status')->default('scheduled')->comment('scheduled, live, completed, cancelled');

            $table->timestamps();

            // Setup basic foreign keys if needed. To avoid strict constraint issues during multi-tenancy,
            // we will index them without strict foreign key constraints if needed, or with them:
            $table->foreign('tenant_id')->references('id')->on('tenants')->nullOnDelete();
            $table->foreign('instructor_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('course_id')->references('id')->on('courses')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_classes');
    }
};
