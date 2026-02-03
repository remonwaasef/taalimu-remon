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
        Schema::table('classrooms', function (Blueprint $table) {
            $table->string('type')->default('hall')->after('capacity'); // hall, lab, virtual
            $table->string('color')->default('#435ebe')->after('type');
            $table->boolean('is_active')->default(true)->after('color');
            $table->text('facilities_summary')->nullable()->after('is_active'); // Simple JSON/Text summary until Assets are linked
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropColumn(['type', 'color', 'is_active', 'facilities_summary']);
        });
    }
};
