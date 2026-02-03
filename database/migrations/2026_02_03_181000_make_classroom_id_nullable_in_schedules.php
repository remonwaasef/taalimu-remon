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
        Schema::table('schedules', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['classroom_id']);
            
            // Make the column nullable
            $table->foreignId('classroom_id')->nullable()->change();
            
            // Re-add the foreign key with onDelete set null
            $table->foreign('classroom_id')
                  ->references('id')->on('classrooms')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['classroom_id']);
            $table->foreignId('classroom_id')->nullable(false)->change();
            $table->foreign('classroom_id')
                  ->references('id')->on('classrooms')
                  ->onDelete('cascade');
        });
    }
};
