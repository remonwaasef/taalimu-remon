<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the payment_reminders log table.
     * Records every reminder sent (email or WhatsApp) to prevent duplicates and provide audit trail.
     */
    public function up(): void
    {
        Schema::create('payment_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->enum('channel', ['email', 'whatsapp'])->comment('Delivery channel');
            $table->string('stage')->comment('e.g. pre_due_7d, pre_due_3d, due_day, overdue_1d, overdue_3d');
            $table->decimal('amount', 10, 2)->comment('Amount due at time of reminder');
            $table->unsignedTinyInteger('due_day')->comment('The due day this reminder was for');
            $table->year('reminder_year')->comment('Year of the billing cycle');
            $table->unsignedTinyInteger('reminder_month')->comment('Month of the billing cycle');
            $table->enum('status', ['sent', 'failed'])->default('sent');
            $table->text('channel_response')->nullable()->comment('API response for debugging');
            $table->text('recipients')->nullable()->comment('JSON: emails/phones that received this reminder');
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamps();

            // Prevent duplicate reminders for the same student/stage/month
            $table->unique(['tenant_id', 'student_id', 'stage', 'reminder_year', 'reminder_month'], 'unique_reminder_per_stage');
            $table->index(['tenant_id', 'reminder_year', 'reminder_month'], 'idx_reminders_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_reminders');
    }
};
