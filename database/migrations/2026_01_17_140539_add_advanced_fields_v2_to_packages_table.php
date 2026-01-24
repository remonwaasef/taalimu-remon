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
        Schema::table('packages', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->text('description_en')->nullable()->after('description');
            $table->decimal('yearly_price', 10, 2)->nullable()->after('price');
            $table->decimal('old_price', 10, 2)->nullable()->after('price');
            $table->string('discount_label')->nullable()->after('old_price'); // e.g. "OFF 20%"
            $table->string('custom_cta_link')->nullable();
            $table->string('custom_cta_text')->nullable();
            $table->boolean('is_default')->default(false);
            $table->integer('trial_days')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'name_en', 
                'description_en', 
                'yearly_price', 
                'old_price', 
                'discount_label', 
                'custom_cta_link', 
                'custom_cta_text', 
                'is_default', 
                'trial_days'
            ]);
        });
    }
};
