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
        if (! Schema::hasTable('demand_aggregations')) {
            Schema::create('demand_aggregations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
                $table->string('subject');
                $table->string('level')->nullable();
                $table->unsignedInteger('demand_count')->default(0);
                $table->json('subjects')->nullable(); // related subjects
                $table->json('metadata')->nullable(); // location, schedule preferences, etc.
                $table->enum('status', ['open', 'processing', 'completed', 'cancelled'])->default('open');
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();

                $table->unique(['tenant_id', 'subject', 'level']);
                $table->index(['tenant_id', 'status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demand_aggregations');
    }
};