<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;

class DiagnoseTenants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'diagnose:tenants {domains?* : Optional list of domains to filter by}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnose and display tenant subscription statuses';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $domains = $this->argument('domains');
        
        $query = Tenant::query();
        
        if (!empty($domains)) {
            $query->whereIn('domain', $domains);
        }

        $tenants = $query->get();

        if ($tenants->isEmpty()) {
            $this->info('No tenants found.');
            return;
        }

        $headers = ['Domain', 'Status', 'Created At', 'Sub Status', 'Sub Created', 'Sub Ends', 'Billing Cycle'];
        $rows = [];

        foreach ($tenants as $tenant) {
            $sub = $tenant->activeSubscription();
            $rows[] = [
                $tenant->domain,
                $tenant->status,
                $tenant->created_at ? $tenant->created_at->format('Y-m-d H:i') : 'N/A',
                $sub ? 'Active' : 'Inactive/None',
                $sub && $sub->created_at ? $sub->created_at->format('Y-m-d') : 'N/A',
                $sub && $sub->ends_at ? $sub->ends_at->format('Y-m-d') : 'N/A',
                $sub ? $sub->billing_cycle : 'N/A',
            ];
        }

        $this->table($headers, $rows);
        $this->info("Total Tenants checked: " . count($tenants));
    }
}
