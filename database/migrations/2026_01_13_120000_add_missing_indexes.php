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
        // Add performance indexes for heavy queries identified in audit
        
        if (!Schema::hasIndex('sales', 'sales_student_id_index')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->index('student_id');
            });
        }

        if (!Schema::hasIndex('attendances', 'attendances_student_id_index')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->index('student_id');
            });
        }

        if (!Schema::hasIndex('quiz_attempts', 'quiz_attempts_user_id_index')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                $table->index('user_id');
            });
        }

        if (!Schema::hasIndex('students', 'students_name_index')) {
            Schema::table('students', function (Blueprint $table) {
                $table->index('name');
            });
        }
        
        if (!Schema::hasIndex('students', 'students_email_index')) {
            Schema::table('students', function (Blueprint $table) {
                $table->index('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex(['student_id']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['student_id']);
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });



        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['email']);
        });
    }
};
