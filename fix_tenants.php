<?php
$tenants = \App\Models\Tenant::whereIn('domain', ['remonq', 'remonj', 'ra3y'])->get();
foreach ($tenants as $tenant) {
    $sub = $tenant->activeSubscription();
    if ($sub && $sub->billing_cycle === 'monthly') {
        // Fix the ends_at to be exactly 30 days from creation
        $sub->ends_at = $sub->created_at->addDays(30);
        $sub->save();
        echo "Fixed {$tenant->domain} ends_at to {$sub->ends_at}\n";
    }
}
echo "Done.\n";
