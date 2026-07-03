<?php

namespace App\Console\Commands;

use App\Mail\TenantOnboardingMail;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendOnboardingEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'onboarding:send-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send onboarding sequence emails to new tenants (Draft Mode)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if onboarding emails are enabled via .env
        if (! config('app.enable_onboarding_emails', false)) {
            $this->info('Onboarding emails are disabled in draft mode (ENABLE_ONBOARDING_EMAILS=false).');

            return;
        }

        $this->info('Starting onboarding emails sequence...');

        // Get all active tenants
        $tenants = Tenant::with('users')->where('status', 'active')->get();

        foreach ($tenants as $tenant) {
            $admin = $tenant->users->whereIn('role', ['center_admin', 'instructor'])->first();
            if (! $admin) {
                continue;
            }

            $daysSinceCreation = Carbon::parse($tenant->created_at)->startOfDay()->diffInDays(now()->startOfDay());

            $stepToSend = null;

            // Simple logic based on exact days passed since registration
            if ($daysSinceCreation == 1) {
                $stepToSend = 2; // Email 2: Dashboard Activation
            } elseif ($daysSinceCreation == 2) {
                $stepToSend = 3; // Email 3: Automatisation Facturation
            } elseif ($daysSinceCreation == 3) {
                $stepToSend = 4; // Email 4: WhatsApp & Portail
            } elseif ($daysSinceCreation == 5) {
                $stepToSend = 5; // Email 5: Upgrade
            }

            if ($stepToSend) {
                try {
                    Mail::to($admin->email)->send(new TenantOnboardingMail($tenant, $admin, $stepToSend));
                    $this->info("Sent Onboarding Email Step {$stepToSend} to {$admin->email} ({$tenant->domain})");
                } catch (\Exception $e) {
                    $this->error("Failed to send Email Step {$stepToSend} to {$admin->email}: ".$e->getMessage());
                }
            }
        }

        $this->info('Onboarding emails sequence completed.');
    }
}
