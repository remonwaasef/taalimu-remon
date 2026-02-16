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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('slug')->unique();
            $table->decimal('price', 12, 2);
            $table->decimal('old_price', 10, 2)->nullable();
            $table->decimal('yearly_price', 10, 2)->nullable();
            $table->json('regional_prices')->nullable();
            $table->string('stripe_price_id')->nullable();
            $table->string('discount_label')->nullable();
            $table->integer('duration_in_days');
            $table->text('description')->nullable();
            $table->text('description_en')->nullable();
            $table->json('display_features')->nullable();
            $table->string('badge')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_default')->default(false);
            $table->integer('sort_order')->default(0);
            $table->string('custom_cta_link')->nullable();
            $table->string('custom_cta_text')->nullable();
            $table->integer('trial_days')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
