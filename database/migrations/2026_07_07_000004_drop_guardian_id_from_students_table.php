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
        if (Schema::hasColumn('students', 'guardian_id')) {
            try {
                Schema::table('students', function (Blueprint $table) {
                    $table->dropForeign(['guardian_id']);
                });
            } catch (\Throwable $e) {}

            try {
                Schema::table('students', function (Blueprint $table) {
                    $table->dropColumn('guardian_id');
                });
            } catch (\Throwable $e) {}
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('guardian_id')->nullable()->constrained()->nullOnDelete();
        });
    }
};
