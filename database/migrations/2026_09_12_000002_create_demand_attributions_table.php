<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Per-demand conversion ledger: the exact DemandRequest → Opportunity →
     * Enrollment graph.
     *
     * A demand converts ONLY when its specific student enrolls (matched by
     * user/email/phone at enrollment time). Bulk-flipping every demand of an
     * opportunity to "converted" would over-claim conversion, so each demand
     * converts at most once (unique demand_request_id) with full provenance.
     */
    public function up(): void
    {
        Schema::create('demand_attributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('demand_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('opportunity_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('enrollment_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('converted_at')->useCurrent();
            $table->timestamps();

            $table->unique('demand_request_id');
            $table->index(['tenant_id', 'opportunity_id']);
            $table->index(['tenant_id', 'demand_request_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demand_attributions');
    }
};
