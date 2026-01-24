<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            // Check if tenant already has a subscription
            if (!Subscription::where('tenant_id', $tenant->id)->exists()) {
                Subscription::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'default',
                    'stripe_id' => 'sub_' . \Illuminate\Support\Str::random(10),
                    'stripe_status' => 'active',
                    'stripe_price' => 'price_' . \Illuminate\Support\Str::random(10),
                    'quantity' => 1,
                    'ends_at' => now()->addDays(30),
                    'status' => 'active',
                ]);

                $this->command->info("Created subscription for tenant: {$tenant->name}");
            }
        }
    }
}
