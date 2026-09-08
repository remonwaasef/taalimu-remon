<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waitlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->integer('position')->default(1);
            $table->enum('status', ['pending', 'notified', 'enrolled', 'expired'])->default('pending');
            $table->string('source')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'course_id', 'status']);
            $table->index(['tenant_id', 'user_id']);
            $table->unique(['tenant_id', 'course_id', 'user_id'], 'waitlist_unique_per_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waitlists');
    }
};
