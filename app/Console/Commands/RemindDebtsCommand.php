<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sale;
use App\Models\Student;
use App\Models\Tenant;
use App\Services\WhatsAppService;
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

        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $this->info("Processing tenant: {$tenant->name}");

            // Find students with debt from invoices older than 3 days
            $studentsWithDebt = Sale::where('tenant_id', $tenant->id)
                ->where('status', '!=', 'paid')
                ->where('created_at', '<', now()->subDays(3))
                ->select('student_id', DB::raw('SUM(total_amount - paid_amount) as total_debt'))
                ->groupBy('student_id')
                ->having('total_debt', '>', 0)
                ->get();

            $count = 0;
            foreach ($studentsWithDebt as $record) {
                $student = Student::find($record->student_id);
                if ($student && $student->phone) {
                    $whatsappService->sendDebtReminder($tenant, $student, $record->total_debt);
                    $this->line("Sent reminder to {$student->name} for {$record->total_debt} amount.");
                    $count++;
                }
            }
            $this->info("Reminders sent for tenant {$tenant->name}: {$count}");
        }

        $this->info('Completed automated debt reminders.');
    }
}
