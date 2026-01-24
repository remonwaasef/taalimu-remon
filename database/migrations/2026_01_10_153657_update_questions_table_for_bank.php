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
        Schema::table('questions', function (Blueprint $table) {
            $table->foreignId('tenant_id')->after('id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('quiz_id')->nullable()->change();
            $table->foreignId('category_id')->nullable()->after('quiz_id')->constrained('question_categories')->nullOnDelete();
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium')->after('category_id');
            $table->text('explanation')->nullable()->after('content');
            
            $table->index(['tenant_id', 'difficulty']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            //
        });
    }
};
