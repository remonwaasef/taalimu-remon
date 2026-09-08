<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add missing FKs
        try { Schema::table('coupons', function (Blueprint $table) {
            $table->foreign('tenant_id')->references('id')->on('tenants')->nullOnDelete();
        }); } catch (\Throwable $e) {}

        try { Schema::table('notifications', function (Blueprint $table) {
            $table->foreign('tenant_id')->references('id')->on('tenants')->nullOnDelete();
        }); } catch (\Throwable $e) {}

        // 2. Add missing indexes on commonly queried columns
        try { Schema::table('user_consents', function (Blueprint $table) {
            $table->index('tenant_id');
        }); } catch (\Throwable $e) {}

        try { Schema::table('questions', function (Blueprint $table) {
            $table->index('quiz_id');
            $table->index('category_id');
        }); } catch (\Throwable $e) {}

        try { Schema::table('question_options', function (Blueprint $table) {
            $table->index('question_id');
        }); } catch (\Throwable $e) {}

        try { Schema::table('course_resources', function (Blueprint $table) {
            $table->index('lesson_id');
        }); } catch (\Throwable $e) {}

        try { Schema::table('commissions', function (Blueprint $table) {
            $table->index('sale_item_id');
        }); } catch (\Throwable $e) {}

        try { Schema::table('payouts', function (Blueprint $table) {
            $table->index('processed_by');
        }); } catch (\Throwable $e) {}

        try { Schema::table('refunds', function (Blueprint $table) {
            $table->index('processed_by');
        }); } catch (\Throwable $e) {}

        try { Schema::table('online_classes', function (Blueprint $table) {
            $table->index('status');
            $table->index('start_time');
        }); } catch (\Throwable $e) {}

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
        // Wrap each drop in try/catch — some indexes may be required by FKs from other migrations
        try { Schema::table('coupons', function (Blueprint $table) { $table->dropForeign(['tenant_id']); }); } catch (\Exception $e) {}
        try { Schema::table('notifications', function (Blueprint $table) { $table->dropForeign(['tenant_id']); }); } catch (\Exception $e) {}

        // user_consents.tenant_id index may be held by a FK — drop FK first if present
        try { Schema::table('user_consents', function (Blueprint $table) { $table->dropForeign(['tenant_id']); }); } catch (\Exception $e) {}
        try { Schema::table('user_consents', function (Blueprint $table) { $table->dropIndex(['tenant_id']); }); } catch (\Exception $e) {}

        try { Schema::table('questions', function (Blueprint $table) { $table->dropIndex(['quiz_id']); $table->dropIndex(['category_id']); }); } catch (\Exception $e) {}
        try { Schema::table('question_options', function (Blueprint $table) { $table->dropIndex(['question_id']); }); } catch (\Exception $e) {}
        try { Schema::table('course_resources', function (Blueprint $table) { $table->dropIndex(['lesson_id']); }); } catch (\Exception $e) {}
        try { Schema::table('commissions', function (Blueprint $table) { $table->dropIndex(['sale_item_id']); }); } catch (\Exception $e) {}
        try { Schema::table('payouts', function (Blueprint $table) { $table->dropIndex(['processed_by']); }); } catch (\Exception $e) {}
        try { Schema::table('refunds', function (Blueprint $table) { $table->dropIndex(['processed_by']); }); } catch (\Exception $e) {}
        try { Schema::table('online_classes', function (Blueprint $table) { $table->dropIndex(['status']); $table->dropIndex(['start_time']); }); } catch (\Exception $e) {}

        $tables = ['enrollments', 'quiz_attempts', 'assignment_submissions', 'attendances',
                    'bookings', 'certificates', 'tickets', 'subscriptions', 'user_consents'];
        foreach ($tables as $tbl) {
            try {
                Schema::table($tbl, function (Blueprint $table) {
                    $table->dropSoftDeletes();
                });
            } catch (\Exception $e) {}
        }
    }
};
