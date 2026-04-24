<?php

namespace App\Console\Commands;

use App\Mail\PaymentReminderMail;
use App\Models\PaymentReminder;
use App\Models\Student;
use App\Models\Tenant;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPaymentRemindersCommand extends Command
{
    protected $signature = 'reminders:send-payment {--tenant= : Process a specific tenant ID}';

    protected $description = 'Send automated payment reminders (email before due date, WhatsApp after due date)';

    public function handle(WhatsAppService $whatsappService): int
    {
        $this->info('Starting payment reminder processing...');

        $query = Tenant::query();
        if ($tenantId = $this->option('tenant')) {
            $query->where('id', $tenantId);
        }

        $processedCount = 0;

        foreach ($query->cursor() as $tenant) {
            $settings = $tenant->settings['payment_reminders'] ?? [];

            // Skip tenants that haven't configured reminders
            if (empty($settings) || empty($settings['default_due_day'])) {
                continue;
            }

            $this->info("Processing tenant: {$tenant->name} (ID: {$tenant->id})");

            $defaultDueDay = (int) $settings['default_due_day'];
            $defaultFee = $settings['default_monthly_fee'] ?? null;
            $emailReminders = $settings['email_reminders'] ?? [];
            $whatsappReminders = $settings['whatsapp_reminders'] ?? [];
            $whatsappBeforeDue = $settings['whatsapp_before_due'] ?? false;
            
            // Legacy templates fallback
            $whatsappTemplate = $settings['whatsapp_template'] ?? '';
            
            // New unified email templates
            $emailSettings = $tenant->settings['email_templates'] ?? [];
            if (!($emailSettings['notif_payment_reminder_enabled'] ?? false)) {
                // If the new email toggle is off, don't send emails even if the cron runs
                $emailReminders = []; 
            }
            $emailTemplate = $emailSettings['notif_payment_reminder_body'] ?? ($settings['email_template'] ?? '');
            $emailSubject = $emailSettings['notif_payment_reminder_subject'] ?? 'تذكير بموعد الدفع';

            $today = now()->day;
            $currentYear = now()->year;
            $currentMonth = now()->month;

            // Get active students with unpaid balances
            $students = Student::where('tenant_id', $tenant->id)
                ->where('status', 'active')
                ->get();

            foreach ($students as $student) {
                $dueDay = $student->payment_due_day ?: $defaultDueDay;
                $fee = $student->monthly_fee ?: $defaultFee;

                if (!$fee || $fee <= 0) {
                    continue; // No fee configured
                }

                // Check if student already paid this month
                if ($this->hasStudentPaidThisMonth($student, $currentYear, $currentMonth)) {
                    continue;
                }

                $daysUntilDue = $dueDay - $today;

                // Pre-due reminders (Email - free)
                foreach ($emailReminders as $reminder) {
                    if (!($reminder['enabled'] ?? false)) {
                        continue;
                    }

                    $daysBefore = (int) ($reminder['days_before'] ?? 0);
                    $stage = $daysBefore === 0 ? 'due_day' : "pre_due_{$daysBefore}d";

                    if ($daysUntilDue === $daysBefore) {
                        $this->sendEmailReminder($tenant, $student, $fee, $dueDay, $stage, $emailTemplate, $emailSubject, $currentYear, $currentMonth);
                        $processedCount++;
                    }
                }

                // WhatsApp before due (only if user explicitly enabled it — it costs money)
                if ($whatsappBeforeDue && $daysUntilDue > 0) {
                    foreach ($whatsappReminders as $reminder) {
                        if (!($reminder['enabled'] ?? false)) {
                            continue;
                        }
                        // Check if this is a "before due" whatsapp reminder
                        $daysBefore = (int) ($reminder['days_before'] ?? -1);
                        if ($daysBefore > 0 && $daysUntilDue === $daysBefore) {
                            $stage = "wa_pre_due_{$daysBefore}d";
                            $this->sendWhatsAppReminder($whatsappService, $tenant, $student, $fee, $dueDay, $stage, $whatsappTemplate, $currentYear, $currentMonth);
                            $processedCount++;
                        }
                    }
                }

                // Post-due reminders (WhatsApp — after due date only)
                if ($daysUntilDue < 0) {
                    $daysOverdue = abs($daysUntilDue);

                    foreach ($whatsappReminders as $reminder) {
                        if (!($reminder['enabled'] ?? false)) {
                            continue;
                        }

                        $daysAfter = (int) ($reminder['days_after'] ?? 0);
                        if ($daysAfter > 0 && $daysOverdue === $daysAfter) {
                            $stage = "overdue_{$daysAfter}d";

                            // Also send email for overdue
                            $this->sendEmailReminder($tenant, $student, $fee, $dueDay, $stage, $emailTemplate, $emailSubject, $currentYear, $currentMonth);

                            // Send WhatsApp
                            $this->sendWhatsAppReminder($whatsappService, $tenant, $student, $fee, $dueDay, $stage, $whatsappTemplate, $currentYear, $currentMonth);
                            $processedCount++;
                        }
                    }
                }
            }
        }

        $this->info("Payment reminder processing completed. Total actions: {$processedCount}");
        return self::SUCCESS;
    }

    /**
     * Check if the student has any payments recorded this month.
     */
    protected function hasStudentPaidThisMonth(Student $student, int $year, int $month): bool
    {
        return $student->sales()
            ->where('status', 'paid')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->exists();
    }

    /**
     * Send an email reminder and log it.
     */
    protected function sendEmailReminder(Tenant $tenant, Student $student, float $fee, int $dueDay, string $stage, string $template, string $subject, int $year, int $month): void
    {
        // Prevent duplicate
        if (PaymentReminder::alreadySent($tenant->id, $student->id, "email_{$stage}", $year, $month)) {
            $this->line("  ⏭ Email already sent: {$student->name} / {$stage}");
            return;
        }

        // Collect emails
        $emails = collect();
        $studentEmail = $student->email ?? ($student->user ? $student->user->email : null);
        if ($studentEmail && !preg_match('/^std\d+\..+@taalimu\.com$/', $studentEmail)) {
            $emails->push($studentEmail);
        }
        if ($student->parent_email) {
            $emails->push($student->parent_email);
        }
        // Also check guardian relationship
        if ($student->guardian && $student->guardian->email) {
            $emails->push($student->guardian->email);
        }

        $emails = $emails->unique()->filter();

        if ($emails->isEmpty()) {
            $this->line("  ⚠ No email found for: {$student->name}");
            return;
        }

        try {
            // Transform variables to match the new template structure
            $variables = [
                'اسم_الطالب' => $student->name,
                'اسم_المركز' => $tenant->name,
                'المبلغ' => $fee,
                'تاريخ_الاستحقاق' => $dueDay . ' من كل شهر',
                'رابط_الدخول' => url('/login'),
            ];
            
            // Reusing NotifGroupEnrollmentMail or a dedicated generic one. 
            // We will use a generic mailer since PaymentReminderMail was built for the legacy system.
            $mailable = new \App\Mail\NotifGroupEnrollmentMail($subject, $template, $variables, $tenant->name, $student->name);

            Mail::to($emails->toArray())->send($mailable);

            PaymentReminder::create([
                'tenant_id' => $tenant->id,
                'student_id' => $student->id,
                'channel' => 'email',
                'stage' => "email_{$stage}",
                'amount' => $fee,
                'due_day' => $dueDay,
                'reminder_year' => $year,
                'reminder_month' => $month,
                'status' => 'sent',
                'recipients' => json_encode($emails->toArray()),
            ]);

            $this->line("  ✅ Email sent to {$student->name} ({$stage})");
        } catch (\Exception $e) {
            Log::error("Payment reminder email failed for student {$student->id}: " . $e->getMessage());

            PaymentReminder::create([
                'tenant_id' => $tenant->id,
                'student_id' => $student->id,
                'channel' => 'email',
                'stage' => "email_{$stage}",
                'amount' => $fee,
                'due_day' => $dueDay,
                'reminder_year' => $year,
                'reminder_month' => $month,
                'status' => 'failed',
                'channel_response' => $e->getMessage(),
                'recipients' => json_encode($emails->toArray()),
            ]);

            $this->error("  ❌ Email failed for {$student->name}: " . $e->getMessage());
        }
    }

    /**
     * Send a WhatsApp reminder and log it.
     */
    protected function sendWhatsAppReminder(WhatsAppService $whatsappService, Tenant $tenant, Student $student, float $fee, int $dueDay, string $stage, string $template, int $year, int $month): void
    {
        // Prevent duplicate
        if (PaymentReminder::alreadySent($tenant->id, $student->id, "whatsapp_{$stage}", $year, $month)) {
            $this->line("  ⏭ WhatsApp already sent: {$student->name} / {$stage}");
            return;
        }

        $phone = $student->parent_phone ?: $student->phone;
        if (!$phone) {
            $this->line("  ⚠ No phone for: {$student->name}");
            return;
        }

        // Build message
        $currency = $tenant->settings['currency'] ?? 'ج.م';
        if (!empty($template)) {
            $message = strtr($template, [
                ':student_name' => $student->name,
                ':amount' => number_format($fee, 2),
                ':due_day' => $dueDay,
                ':tenant_name' => $tenant->name,
                ':month' => now()->translatedFormat('F'),
                ':currency' => $currency,
            ]);
        } else {
            // Default WhatsApp message
            if (str_starts_with($stage, 'overdue')) {
                $message = "⚠️ تنبيه من {$tenant->name}\n\nالسلام عليكم،\nنود إبلاغكم بأن مصروفات الطالب/ة {$student->name} بمبلغ " . number_format($fee, 2) . " {$currency} قد تأخر سدادها.\nنرجو التواصل مع الإدارة لتسوية المبلغ.\n\nشكراً لتعاونكم.";
            } else {
                $message = "📋 تذكير من {$tenant->name}\n\nالسلام عليكم،\nنذكّركم بأن مصروفات الطالب/ة {$student->name} بمبلغ " . number_format($fee, 2) . " {$currency} مستحقة يوم {$dueDay} من الشهر الحالي.\n\nشكراً لتعاونكم.";
            }
        }

        try {
            $result = $whatsappService->sendMessageByTenant($tenant, $phone, $message);

            PaymentReminder::create([
                'tenant_id' => $tenant->id,
                'student_id' => $student->id,
                'channel' => 'whatsapp',
                'stage' => "whatsapp_{$stage}",
                'amount' => $fee,
                'due_day' => $dueDay,
                'reminder_year' => $year,
                'reminder_month' => $month,
                'status' => $result ? 'sent' : 'failed',
                'recipients' => json_encode([$phone]),
            ]);

            if ($result) {
                $this->line("  ✅ WhatsApp sent to {$student->name} ({$stage})");
            } else {
                $this->line("  ⚠ WhatsApp returned false for {$student->name}");
            }
        } catch (\Exception $e) {
            Log::error("Payment reminder WhatsApp failed for student {$student->id}: " . $e->getMessage());

            PaymentReminder::create([
                'tenant_id' => $tenant->id,
                'student_id' => $student->id,
                'channel' => 'whatsapp',
                'stage' => "whatsapp_{$stage}",
                'amount' => $fee,
                'due_day' => $dueDay,
                'reminder_year' => $year,
                'reminder_month' => $month,
                'status' => 'failed',
                'channel_response' => $e->getMessage(),
                'recipients' => json_encode([$phone]),
            ]);

            $this->error("  ❌ WhatsApp failed for {$student->name}: " . $e->getMessage());
        }
    }
}
