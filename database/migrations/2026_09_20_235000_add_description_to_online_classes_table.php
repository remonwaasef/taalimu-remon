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
        if (Schema::hasTable('online_classes') && ! Schema::hasColumn('online_classes', 'description')) {
            Schema::table('online_classes', function (Blueprint $table) {
                $table->text('description')->nullable()->after('title');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('online_classes') && Schema::hasColumn('online_classes', 'description')) {
            Schema::table('online_classes', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
    }
};
