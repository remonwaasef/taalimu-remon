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
        Schema::table('instructors', function (Blueprint $table) {
            $table->string('status')->default('active')->after('specialization');
            $table->decimal('commission_rate', 5, 2)->default(0)->after('status');
            $table->string('national_id')->nullable()->after('commission_rate');
            $table->enum('gender', ['male', 'female'])->nullable()->after('national_id');
            $table->date('hiring_date')->nullable()->after('gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instructors', function (Blueprint $table) {
            $table->dropColumn(['status', 'commission_rate', 'national_id', 'gender', 'hiring_date']);
        });
    }
};
