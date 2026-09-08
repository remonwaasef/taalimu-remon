<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('growth_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('eventable_type');
            $table->unsignedBigInteger('eventable_id');
            $table->string('event_name');
            $table->string('actor_type')->nullable();
            $table->string('actor_id')->nullable();
            $table->string('source')->nullable();
            $table->string('campaign')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['tenant_id', 'event_name', 'created_at']);
            $table->index(['tenant_id', 'eventable_type', 'eventable_id']);
            $table->index(['tenant_id', 'source', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('growth_events');
    }
};
