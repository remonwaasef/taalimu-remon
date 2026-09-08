<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * PAY-3: race-proof idempotency for gateway payments.
 *
 * A unique index on payments.reference_number turns the webhook's
 * check-then-insert into an atomic operation (a concurrent duplicate
 * violates the constraint instead of double-posting money).
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Safety net: if legacy data ever contains duplicate references,
            // keep the earliest row and clear the newer ones so the unique
            // index can be applied without manual intervention.
            $dupes = DB::table('payments')
                ->select('reference_number')
                ->whereNotNull('reference_number')
                ->groupBy('reference_number')
                ->havingRaw('COUNT(*) > 1')
                ->pluck('reference_number');

            foreach ($dupes as $referenceNumber) {
                DB::table('payments')
                    ->where('reference_number', $referenceNumber)
                    ->where('id', '!=', DB::raw(
                        '(SELECT MIN(id) FROM (SELECT id, reference_number FROM payments) t WHERE t.reference_number = payments.reference_number)'
                    ))
                    ->update(['reference_number' => null]);
            }

            $table->unique('reference_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try { Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique('payments_reference_number_unique');
        }); } catch (\Exception $e) {}
    }
};