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
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('subtotal_amount', 10, 2)->after('student_id')->default(0);
            $table->decimal('discount_amount', 10, 2)->after('subtotal_amount')->default(0);
            $table->decimal('tax_amount', 10, 2)->after('discount_amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['subtotal_amount', 'discount_amount', 'tax_amount']);
        });
    }
};
