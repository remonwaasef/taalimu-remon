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
        Schema::create('marketplace_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('subject');
            $table->string('level')->nullable();
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('budget_range')->nullable();
            $table->string('preferred_schedule')->nullable();
            $table->enum('status', ['active', 'matched', 'expired', 'closed'])->default('active');
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index(['tenant_id', 'status', 'subject']);
            $table->index(['status', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_listings');
    }
};
