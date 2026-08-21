<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Services\StudentRiskService;
use Illuminate\Console\Command;

class CalculateStudentRisks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:calculate-risks {--tenant= : Tenant ID to calculate for (all tenants if not specified)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate risk scores for all active students';

    /**
     * Execute the console command.
     */
    public function handle(StudentRiskService $riskService): int
    {
        $tenantId = $this->option('tenant');

        if ($tenantId) {
            $tenants = Tenant::where('id', $tenantId)->get();
        } else {
            $tenants = Tenant::where('status', 'active')->get();
        }

        $totalStudents = 0;
        $totalCritical = 0;
        $totalHigh = 0;

        $this->info('Calculating student risk scores...');

        foreach ($tenants as $tenant) {
            $this->info("Processing tenant: {$tenant->name} (ID: {$tenant->id})");

            $results = $riskService->calculateRisksForTenant($tenant->id);

            $totalStudents += $results['total'];
            $totalCritical += $results['critical'];
            $totalHigh += $results['high'];

            $this->info("  - Total: {$results['total']}, Critical: {$results['critical']}, High: {$results['high']}, Medium: {$results['medium']}, Low: {$results['low']}");
        }

        $this->newLine();
        $this->info("Calculation complete!");
        $this->info("Total students processed: {$totalStudents}");
        $this->info("Critical risk: {$totalCritical}");
        $this->info("High risk: {$totalHigh}");

        return Command::SUCCESS;
    }
}
