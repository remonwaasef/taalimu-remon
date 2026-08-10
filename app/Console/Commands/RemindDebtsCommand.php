<?php

namespace App\Console\Commands;

use App\Models\Sale;
use App\Models\Student;
use App\Models\Tenant;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemindDebtsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'finance:remind-debts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automate sending WhatsApp payment reminders to students with overdue debts';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppService $whatsappService)
    {
        $this->info('Starting automated debt reminders...');

        foreach (Tenant::cursor() as $tenant) {
            $this->info("Processing tenant: {$tenant->name}");

            $reminderSettings = $tenant->settings['payment_reminders'] ?? [];
            $repeatEnabled = (bool) ($reminderSettings['overdue_repeat_enabled'] ?? false);
            $repeatInterval = max(1, (int) ($reminderSettings['overdue_repeat_interval'] ?? 7));
            $maxReminders = ($reminderSettings['overdue_max_reminders'] ?? null)
                ? (int) $reminderSettings['overdue_max_reminders']
                : null;

            // Only debts older than 3 days, grouped per student.
            $studentsWithDebt = Sale::where('tenant_id', $tenant->id)
                ->where('status', '!=', 'paid')
                ->where('created_at', '<', now()->subDays(3))
                ->select('student_id', DB::raw('SUM(total_amount - paid_amount) as total_debt'))
                ->groupBy('student_id')
                ->having('total_debt', '>', 0)
                ->get();

            $count = 0;
            foreach ($studentsWithDebt as $record) {
                $student = Student::where('tenant_id', $tenant->id)->find($record->student_id);
                if (! $student || ! $student->phone) {
                    continue;
                }

                if ($maxReminders !== null && $student->reminder_count >= $maxReminders) {
                    $this->line("Skipped {$student->name}: reached max reminders ({$maxReminders}).");

                    continue;
                }

                // Q-1: claim the reminder atomically so concurrent runs (or a
                // manual `php artisan finance:remind-debts` alongside the
                // scheduler) can never send the same student two messages.
                $cutoff = now()->subDays($repeatInterval);
                $claimed = DB::table('students')
                    ->where('id', $student->id)
                    ->where(function ($q) use ($repeatEnabled, $cutoff) {
                        if (! $repeatEnabled) {
                            // One-shot mode: remind only if never reminded.
                            $q->whereNull('last_reminded_at');
                        } else {
                            // Repeat mode: null or older than the interval.
                            $q->whereNull('last_reminded_at')
                                ->orWhere('last_reminded_at', '<', $cutoff);
                        }
                    })
                    ->update([
                        'last_reminded_at' => now(),
                        'reminder_count' => DB::raw('reminder_count + 1'),
                    ]);

                if (! $claimed) {
                    $this->line("Skipped {$student->name}: already reminded recently.");

                    continue;
                }

                $sent = $whatsappService->sendDebtReminder($tenant, $student, $record->total_debt);
                if (! $sent) {
                    // Delivery failed — roll back the claim so the next run retries.
                    $student->forceFill([
                        'last_reminded_at' => null,
                        'reminder_count' => max(0, $student->reminder_count - 1),
                    ])->save();

                    $this->warn("Failed to send reminder to {$student->name}, will retry.");

                    continue;
                }

                $this->line("Sent reminder to {$student->name} for {$record->total_debt} amount.");
                $count++;
            }
            $this->info("Reminders sent for tenant {$tenant->name}: {$count}");
        }

        $this->info('Completed automated debt reminders.');
    }
}