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
            $table->string('code')->after('tenant_id')->nullable(); // Mandatory logically, but nullable for existing data
            $table->string('national_id')->after('code')->nullable();
            
            $table->unique(['tenant_id', 'code']);
            $table->unique(['tenant_id', 'national_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique(['tenant_id', 'code']);
            $table->dropUnique(['tenant_id', 'national_id']);
            $table->dropColumn(['code', 'national_id']);
        });
    }
};
