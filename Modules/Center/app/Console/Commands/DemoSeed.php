<?php

namespace Modules\Center\Console\Commands;

use App\Services\DemoDataService;
use Illuminate\Console\Command;

class DemoSeed extends Command
{
    protected $signature = 'demo:seed {tenant? : The tenant domain to seed demo data for} {--fresh : Remove existing demo data first}';
    protected $description = 'Seed demo data for a tenant (instructors, courses, students, enrollments, invoices)';

    protected $demoService;

    public function __construct(DemoDataService $demoService)
    {
        parent::__construct();
        $this->demoService = $demoService;
    }

    public function handle(): int
    {
        $tenantDomain = $this->argument('tenant');
        
        if ($tenantDomain) {
            $tenant = \App\Models\Tenant::where('domain', $tenantDomain)->first();
            if (! $tenant) {
                $this->error("Tenant not found: {$tenantDomain}");
                return Command::FAILURE;
            }
            app()->instance('tenant', $tenant);
        } elseif (! app()->bound('tenant')) {
            $this->error('No tenant context. Run from a tenant subdomain or provide --tenant argument.');
            return Command::FAILURE;
        }

        $tenant = app('tenant');
        $this->info("Seeding demo data for tenant: {$tenant->name} ({$tenant->domain})");

        if ($this->option('fresh')) {
            $this->info('Removing existing demo data...');
            $this->demoService->removeDemoDataForTenant($tenant);
        }

        $this->demoService->seedForTenant($tenant);

        $this->info('Done! Demo data created successfully.');
        return Command::SUCCESS;
    }
}