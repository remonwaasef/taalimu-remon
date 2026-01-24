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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('instructor_id')->nullable()->after('tenant_id');
            $table->index('instructor_id');
        });

        // Sync existing data
        $instructors = DB::table('instructors')->whereNotNull('user_id')->get();
        foreach ($instructors as $instructor) {
            DB::table('users')
                ->where('id', $instructor->user_id)
                ->update(['instructor_id' => $instructor->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('instructor_id');
        });
    }
};
