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
            // Identity
            $table->date('birth_date')->nullable()->after('email');
            $table->enum('gender', ['male', 'female'])->nullable()->after('birth_date');
            $table->string('profile_photo')->nullable()->after('gender');
            $table->string('address')->nullable()->after('profile_photo');
            
            // Parent Info
            $table->string('parent_job')->nullable()->after('parent_phone');
            $table->string('parent_relation')->nullable()->after('parent_job');
            $table->string('emergency_phone')->nullable()->after('parent_relation');
            
            // Academic
            $table->string('school_name')->nullable()->after('grade_id');
            $table->string('section_type')->nullable()->after('school_name'); // e.g. Scientific, Literary
            $table->date('joined_at')->nullable()->after('section_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'birth_date', 'gender', 'profile_photo', 'address',
                'parent_job', 'parent_relation', 'emergency_phone',
                'school_name', 'section_type', 'joined_at'
            ]);
        });
    }
};
