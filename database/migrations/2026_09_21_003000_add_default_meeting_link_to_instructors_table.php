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
        if (Schema::hasTable('instructors') && ! Schema::hasColumn('instructors', 'default_meeting_link')) {
            Schema::table('instructors', function (Blueprint $table) {
                $table->string('default_meeting_link', 500)->nullable()->after('bio');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('instructors') && Schema::hasColumn('instructors', 'default_meeting_link')) {
            Schema::table('instructors', function (Blueprint $table) {
                $table->dropColumn('default_meeting_link');
            });
        }
    }
};
