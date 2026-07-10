<?php

namespace Modules\Center\Console\Commands;

use Modules\Center\Services\OnboardingService;
use Illuminate\Console\Command;

class FixMissingInvoices extends Command
{
    protected $signature = 'onboarding:fix-invoices {tenant? : The tenant domain to fix invoices for}';
    protected $description = 'Create pending invoices for students who were enrolled via old onboarding (no finance record)';

    protected $onboarding;

    public function __construct(OnboardingService $onboarding)
    {
        parent::__construct();
        $this->onboarding = $onboarding;
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
        $this->info("Fixing missing invoices for tenant: {$tenant->name} ({$tenant->domain})");

        $fixed = $this->onboarding->createMissingInvoices($tenant);

        $this->info('Done! Created ' . count($fixed) . ' invoices.');
        return Command::SUCCESS;
    }
}