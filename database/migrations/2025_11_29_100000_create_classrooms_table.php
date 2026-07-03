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
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->cascadeOnDelete();

            $table->string('name')->nullable();
            $table->integer('capacity')->nullable();

            $table->string('type')->default('hall'); // hall, lab, virtual
            $table->string('color')->default('#435ebe');
            $table->boolean('is_active')->default(true);
            $table->text('facilities_summary')->nullable();

            $table->timestamps();

            $table->index('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
