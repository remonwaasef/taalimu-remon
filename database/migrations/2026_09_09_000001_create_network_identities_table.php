<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('network_identities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('public_profile_id')->nullable()->constrained('public_profiles')->nullOnDelete();
            $table->string('profilable_type');
            $table->unsignedBigInteger('profilable_id');
            $table->string('public_slug');
            $table->string('profile_type')->index(); // 'teacher' | 'center'
            $table->string('status')->default('draft'); // draft, published, suspended, archived
            $table->timestamp('published_at')->nullable();
            $table->boolean('network_visible')->default(true);
            $table->boolean('discovery_enabled')->default(true);
            $table->string('headline')->nullable();
            $table->text('short_description')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['profile_type', 'public_slug']);
            $table->index(['profilable_type', 'profilable_id'], 'net_id_profilable_idx');
            $table->index(['tenant_id', 'status'], 'net_id_tenant_status_idx');
            $table->index(['status', 'network_visible', 'discovery_enabled'], 'net_id_vis_idx');
            $table->index(['profile_type', 'status', 'network_visible'], 'net_id_type_vis_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('network_identities');
    }
};