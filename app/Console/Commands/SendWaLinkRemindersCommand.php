<?php

namespace App\Console\Commands;

use App\Models\PaymentReminder;
use App\Models\Student;
use App\Models\Tenant;
use App\Services\TelegramService;
use Illuminate\Console\Command;

/**
 * Free WhatsApp payment reminders via Telegram "click-to-send" links.
 *
 * Instead of paying for the Meta WhatsApp Cloud API, this command sends the
 * center's own Telegram chat a daily digest of unpaid students. Each student is
 * an inline button that opens WhatsApp pre-filled (wa.me link) — staff just tap
 * "send". Reminder timing (X days before due + daily while overdue) mirrors the
 * existing reminders:send-payment logic and shares the payment_reminders dedup.
 */
class SendWaLinkRemindersCommand extends Command
{
    protected $signature = 'reminders:send-wa-links {--tenant= : Process a specific tenant ID}';

    protected $description = 'Send each center a Telegram digest of unpaid students with one-tap WhatsApp (wa.me) reminder links.';

    public function handle(TelegramService $telegram): int
    {
        $this->info('Building WhatsApp-link reminder digests...');

        $query = Tenant::query();
        if ($tenantId = $this->option('tenant')) {
            $query->where('id', $tenantId);
        }

        $today = now()->day;
        $year = now()->year;
        $month = now()->month;
        $dateStamp = now()->toDateString();

        foreach ($query->cursor() as $tenant) {
            $settings = $tenant->settings['payment_reminders'] ?? [];

            // Only run for centers that opted in and set a Telegram chat + due day.
            if (empty($settings['wa_telegram_enabled']) || empty($settings['telegram_chat_id']) || empty($settings['default_due_day'])) {
                continue;
            }

            $chatId = (string) $settings['telegram_chat_id'];
            $defaultDueDay = (int) $settings['default_due_day'];
            $defaultFee = $settings['default_monthly_fee'] ?? null;
            $daysBefore = (int) ($settings['wa_days_before'] ?? 3);
            $template = $settings['whatsapp_template'] ?? '';
            $currency = $tenant->settings['currency'] ?? 'ج.م';
            $countryCode = $tenant->settings['whatsapp']['country_code'] ?? '20'; // Egypt default

            $items = [];

            $students = Student::where('tenant_id', $tenant->id)
                ->where('status', 'active')
                ->get();

            // Two queries per tenant instead of two per student: who already paid
            // this month, and which students already got today's digest entry.
            // Dedup rows are recorded as 'queued' (staff still has to tap the link),
            // so match both 'queued' and 'sent' — matching only 'sent' would resend
            // the digest on every re-run of the command.
            $paidStudentIds = $this->paidStudentIdsThisMonth($tenant->id);
            $remindedStudentIds = PaymentReminder::where('tenant_id', $tenant->id)
                ->where('stage', "watg_{$dateStamp}")
                ->whereIn('status', ['queued', 'sent'])
                ->pluck('student_id')
                ->flip();

            foreach ($students as $student) {
                $dueDay = $student->payment_due_day ?: $defaultDueDay;
                $fee = $student->monthly_fee ?: $defaultFee;

                if (! $fee || $fee <= 0) {
                    continue;
                }

                if ($paidStudentIds->has($student->id)) {
                    continue;
                }

                $daysUntilDue = $dueDay - $today;
                $isPreDue = ($daysUntilDue === $daysBefore);
                $isOverdue = ($daysUntilDue < 0);

                // Include only: exactly X days before due, or any day past due (daily).
                if (! $isPreDue && ! $isOverdue) {
                    continue;
                }

                $phone = $this->normalizePhone($student->parent_phone ?: $student->phone, $countryCode);
                if (! $phone) {
                    continue;
                }

                // One entry per student per day (idempotent across re-runs).
                $stage = "watg_{$dateStamp}";
                if ($remindedStudentIds->has($student->id)) {
                    continue;
                }

                $message = $this->buildMessage($tenant, $student, (float) $fee, $dueDay, $currency, $template, $isOverdue);
                $waUrl = "https://wa.me/{$phone}?text=".rawurlencode($message);

                $items[] = [
                    'student_id' => $student->id,
                    'fee' => (float) $fee,
                    'due_day' => $dueDay,
                    'phone' => $phone,
                    'stage' => $stage,
                    'line' => ($isOverdue ? '⚠️ ' : '📅 ').$student->name.' — '.number_format($fee, 2).' '.$currency,
                    'button' => ['text' => '📲 '.$student->name, 'url' => $waUrl],
                ];
            }

            if (empty($items)) {
                continue;
            }

            $this->info("Tenant {$tenant->name}: {$this->pluralize(count($items))} to remind.");

            // Telegram caps message text and keyboard size — send in chunks of 20.
            foreach (array_chunk($items, 20) as $chunk) {
                $header = "<b>💰 تذكيرات الدفع اليوم — {$tenant->name}</b>\n"
                    ."اضغط على زر كل طالب لإرسال التذكير عبر واتساب:\n\n";
                $body = collect($chunk)->pluck('line')->implode("\n");
                $buttons = array_column($chunk, 'button');

                $ok = $telegram->sendToChat($chatId, $header.$body, $buttons);

                // Only record dedup rows once the digest actually went out.
                if ($ok) {
                    foreach ($chunk as $item) {
                        PaymentReminder::create([
                            'tenant_id' => $tenant->id,
                            'student_id' => $item['student_id'],
                            'channel' => 'telegram_wa',
                            'stage' => $item['stage'],
                            'amount' => $item['fee'],
                            'due_day' => $item['due_day'],
                            'reminder_year' => $year,
                            'reminder_month' => $month,
                            'status' => 'queued',
                            'recipients' => json_encode([$item['phone']]),
                        ]);
                    }
                    $this->line('  ✅ Digest sent ('.count($chunk).' students).');
                } else {
                    $this->error('  ❌ Telegram digest failed for '.$tenant->name);
                }
            }
        }

        $this->info('WhatsApp-link reminder digests completed.');

        return self::SUCCESS;
    }

    /**
     * IDs of students with a paid sale this month, as a keyed set.
     * whereBetween keeps the created_at index usable (whereYear/whereMonth don't).
     */
    protected function paidStudentIdsThisMonth(int $tenantId): \Illuminate\Support\Collection
    {
        return \App\Models\Sale::where('tenant_id', $tenantId)
            ->where('status', 'paid')
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->distinct()
            ->pluck('student_id')
            ->flip();
    }

    /**
     * Build the reminder message (reuses the tenant's {placeholder} template, or a default).
     */
    protected function buildMessage(Tenant $tenant, Student $student, float $fee, int $dueDay, string $currency, string $template, bool $isOverdue): string
    {
        if (! empty($template)) {
            $variables = [
                'student_name' => $student->name,
                'center_name' => $tenant->name,
                'amount' => number_format($fee, 2).' '.$currency,
                'due_date' => $dueDay.' من كل شهر',
                'remaining' => number_format($fee, 2).' '.$currency,
                'login_link' => url('/login'),
            ];

            $message = $template;
            foreach ($variables as $key => $value) {
                $message = str_replace('{'.$key.'}', (string) $value, $message);
            }

            return $message;
        }

        if ($isOverdue) {
            return "⚠️ تنبيه من {$tenant->name}\n\nالسلام عليكم،\nنود إبلاغكم بأن مصروفات الطالب/ة {$student->name} بمبلغ "
                .number_format($fee, 2)." {$currency} قد تأخر سدادها.\nنرجو التواصل مع الإدارة لتسويتها.\n\nشكراً لتعاونكم.";
        }

        return "📋 تذكير من {$tenant->name}\n\nالسلام عليكم،\nنذكّركم بأن مصروفات الطالب/ة {$student->name} بمبلغ "
            .number_format($fee, 2)." {$currency} مستحقة يوم {$dueDay} من الشهر الحالي.\n\nشكراً لتعاونكم.";
    }

    /**
     * Normalize a phone number to international digits (no +) for wa.me links.
     */
    protected function normalizePhone(?string $raw, string $countryCode): ?string
    {
        if (! $raw) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $raw);
        $cc = preg_replace('/\D+/', '', $countryCode);

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }

        if ($cc && str_starts_with($digits, '0')) {
            $digits = $cc.substr($digits, 1);
        } elseif ($cc && ! str_starts_with($digits, $cc)) {
            $digits = $cc.$digits;
        }

        return $digits;
    }

    private function pluralize(int $count): string
    {
        return $count.' student'.($count === 1 ? '' : 's');
    }
}
