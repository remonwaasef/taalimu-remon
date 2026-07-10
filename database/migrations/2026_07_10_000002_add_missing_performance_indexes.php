<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // invoices - status, due_date, paid_at, subscription_id
        try {
            Schema::table('invoices', function (Blueprint $table) {
                $table->index('status', 'idx_invoices_status');
                $table->index('due_date', 'idx_invoices_due_date');
                $table->index('paid_at', 'idx_invoices_paid_at');
                $table->index('subscription_id', 'idx_invoices_subscription_id');
            });
        } catch (\Exception $e) {}

        // payments - sale_id, received_by, paid_at, payment_method
        try {
            Schema::table('payments', function (Blueprint $table) {
                $table->index('sale_id', 'idx_payments_sale_id');
                $table->index('received_by', 'idx_payments_received_by');
                $table->index('paid_at', 'idx_payments_paid_at');
                $table->index('payment_method', 'idx_payments_payment_method');
            });
        } catch (\Exception $e) {}

        // attendances - status + composite [student_id, session_date]
        try {
            Schema::table('attendances', function (Blueprint $table) {
                $table->index('status', 'idx_attendances_status');
                $table->index(['student_id', 'session_date'], 'idx_attendances_student_session');
            });
        } catch (\Exception $e) {}

        // quiz_attempts - quiz_id, passed, score
        try {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                $table->index('quiz_id', 'idx_quiz_attempts_quiz_id');
                $table->index('passed', 'idx_quiz_attempts_passed');
                $table->index('score', 'idx_quiz_attempts_score');
            });
        } catch (\Exception $e) {}

        // assignments - due_date
        try {
            Schema::table('assignments', function (Blueprint $table) {
                $table->index('due_date', 'idx_assignments_due_date');
            });
        } catch (\Exception $e) {}

        // quizzes - lesson_id, category_id
        try {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->index('lesson_id', 'idx_quizzes_lesson_id');
                $table->index('category_id', 'idx_quizzes_category_id');
            });
        } catch (\Exception $e) {}

        // lesson_progress - completed_at
        try {
            Schema::table('lesson_progress', function (Blueprint $table) {
                $table->index('completed_at', 'idx_lesson_progress_completed_at');
            });
        } catch (\Exception $e) {}
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex('idx_invoices_status');
            $table->dropIndex('idx_invoices_due_date');
            $table->dropIndex('idx_invoices_paid_at');
            $table->dropIndex('idx_invoices_subscription_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('idx_payments_sale_id');
            $table->dropIndex('idx_payments_received_by');
            $table->dropIndex('idx_payments_paid_at');
            $table->dropIndex('idx_payments_payment_method');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex('idx_attendances_status');
            $table->dropIndex('idx_attendances_student_session');
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropIndex('idx_quiz_attempts_quiz_id');
            $table->dropIndex('idx_quiz_attempts_passed');
            $table->dropIndex('idx_quiz_attempts_score');
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropIndex('idx_assignments_due_date');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropIndex('idx_quizzes_lesson_id');
            $table->dropIndex('idx_quizzes_category_id');
        });

        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->dropIndex('idx_lesson_progress_completed_at');
        });
    }
};
