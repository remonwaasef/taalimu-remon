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
            $table->unsignedTinyInteger('risk_score')->default(0)->after('status');
            $table->string('risk_level')->default('none')->after('risk_score');
            $table->timestamp('last_risk_check_at')->nullable()->after('risk_level');
            $table->json('risk_reasons')->nullable()->after('last_risk_check_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['risk_score', 'risk_level', 'last_risk_check_at', 'risk_reasons']);
        });
    }
};
