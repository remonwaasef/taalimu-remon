<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Tenant;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tenants = Tenant::all();
        foreach ($tenants as $tenant) {
            $settings = $tenant->settings;
            if (isset($settings['contact'])) {
                $tenant->phone = $settings['contact']['phone'] ?? $tenant->phone;
                $tenant->address = $settings['contact']['address'] ?? $tenant->address;
                $tenant->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse as the data is already in the JSON as well
    }
};
