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
        Schema::table('students', function (Blueprint $table) {
            // Q-1: idempotency markers for the recurring debt reminders —
            // when the student was last reminded and how many times total.
            $table->timestamp('last_reminded_at')->nullable()->after('status');
            $table->unsignedTinyInteger('reminder_count')->default(0)->after('last_reminded_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['last_reminded_at', 'reminder_count']);
        });
    }
};