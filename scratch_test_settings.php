<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tenant = App\Models\Tenant::where('domain', 'ra3y')->first() ?? App\Models\Tenant::first();
$user = App\Models\User::where('tenant_id', $tenant->id)->first() ?? App\Models\User::first();

echo "Testing view rendering for Tenant ID: {$tenant->id}, User ID: {$user->id}\n";

app()->instance('tenant', $tenant);
view()->share('tenant', $tenant);
view()->share('errors', new Illuminate\Support\ViewErrorBag());
auth()->login($user);

try {
    $stages = App\Models\Stage::with('grades')->orderBy('order')->get();
    $templates = config('academic.templates', []);
    $html = view('center::settings.index', compact('stages', 'templates'))->render();
    echo "SUCCESS! View rendered without errors. Length: " . strlen($html) . " characters.\n";
} catch (\Throwable $e) {
    echo "VIEW RENDERING EXCEPTION: " . get_class($e) . ": " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}
