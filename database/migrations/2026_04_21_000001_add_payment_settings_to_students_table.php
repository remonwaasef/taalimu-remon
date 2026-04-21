<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds monthly payment settings per student for automated reminders.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->decimal('monthly_fee', 10, 2)->nullable()->after('status')
                ->comment('Monthly fee for this student. NULL = use tenant default or course price.');
            $table->unsignedTinyInteger('payment_due_day')->nullable()->after('monthly_fee')
                ->comment('Day of month payment is due. NULL = use tenant default.');
            $table->string('parent_email')->nullable()->after('parent_phone')
                ->comment('Guardian email for payment reminders.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['monthly_fee', 'payment_due_day', 'parent_email']);
        });
    }
};
