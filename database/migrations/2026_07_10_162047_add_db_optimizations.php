<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add missing FKs
        Schema::table('coupons', function (Blueprint $table) {
            $table->foreign('tenant_id')->references('id')->on('tenants')->nullOnDelete();
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->foreign('tenant_id')->references('id')->on('tenants')->nullOnDelete();
        });

        // 2. Add missing indexes on commonly queried columns
        Schema::table('user_consents', function (Blueprint $table) {
            $table->index('tenant_id');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->index('quiz_id');
            $table->index('category_id');
        });

        Schema::table('question_options', function (Blueprint $table) {
            $table->index('question_id');
        });

        Schema::table('course_resources', function (Blueprint $table) {
            $table->index('lesson_id');
        });

        Schema::table('commissions', function (Blueprint $table) {
            $table->index('sale_item_id');
        });

        Schema::table('payouts', function (Blueprint $table) {
            $table->index('processed_by');
        });

        Schema::table('refunds', function (Blueprint $table) {
            $table->index('processed_by');
        });

        Schema::table('online_classes', function (Blueprint $table) {
            $table->index('status');
            $table->index('start_time');
        });

        // 3. Add softDeletes to tables that need them
        Schema::table('enrollments', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('assignment_submissions', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('user_consents', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
        });

        Schema::table('user_consents', function (Blueprint $table) {
            $table->dropIndex(['tenant_id']);
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex(['quiz_id']);
            $table->dropIndex(['category_id']);
        });

        Schema::table('question_options', function (Blueprint $table) {
            $table->dropIndex(['question_id']);
        });

        Schema::table('course_resources', function (Blueprint $table) {
            $table->dropIndex(['lesson_id']);
        });

        Schema::table('commissions', function (Blueprint $table) {
            $table->dropIndex(['sale_item_id']);
        });

        Schema::table('payouts', function (Blueprint $table) {
            $table->dropIndex(['processed_by']);
        });

        Schema::table('refunds', function (Blueprint $table) {
            $table->dropIndex(['processed_by']);
        });

        Schema::table('online_classes', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['start_time']);
        });

        $tables = ['enrollments', 'quiz_attempts', 'assignment_submissions', 'attendances',
                    'bookings', 'certificates', 'tickets', 'subscriptions', 'user_consents'];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
