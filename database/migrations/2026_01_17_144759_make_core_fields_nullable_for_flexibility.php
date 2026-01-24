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
            $table->string('name')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('domain')->nullable()->change();
        });

        Schema::table('students', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
        });

        Schema::table('instructors', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
            $table->foreignId('instructor_id')->nullable()->change();
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
        });

        Schema::table('classrooms', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->tinyInteger('day_of_week')->nullable()->change();
            $table->time('start_time')->nullable()->change();
            $table->time('end_time')->nullable()->change();
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->string('category')->nullable()->change();
            $table->decimal('amount', 10, 2)->nullable()->change();
            $table->date('date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy way to rollback to NOT NULL consistently without knowing original states, 
        // but typically these were NOT NULL.
    }
};
