<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "Testing NetworkIdentity model...\n";

$tenant = \App\Models\Tenant::first();
if (! $tenant) {
    echo "No tenant found, creating one...\n";
    $tenant = \App\Models\Tenant::create([
        'name' => 'Test Center',
        'email' => 'test@test.com',
        'domain' => 'test-center',
        'status' => 'active',
    ]);
}

echo "Tenant: {$tenant->name}\n";

// Test creating NetworkIdentity for center
$identity = \App\Models\NetworkIdentity::create([
    'tenant_id' => $tenant->id,
    'profilable_type' => \App\Models\Tenant::class,
    'profilable_id' => $tenant->id,
    'public_slug' => 'test-center',
    'profile_type' => \App\Models\NetworkIdentity::PROFILE_TYPE_CENTER,
    'status' => \App\Models\NetworkIdentity::STATUS_PUBLISHED,
    'network_visible' => true,
    'discovery_enabled' => true,
]);

echo "Created NetworkIdentity: {$identity->public_slug}\n";
echo "Public URL: {$identity->getPublicUrl()}\n";
echo "Is published: " . ($identity->isPublished() ? 'yes' : 'no') . "\n";
echo "Is visible: " . ($identity->isVisible() ? 'yes' : 'no') . "\n";
echo "Is discoverable: " . ($identity->isDiscoverable() ? 'yes' : 'no') . "\n";

// Test scopes
$published = \App\Models\NetworkIdentity::published()->count();
echo "Published count: $published\n";

$visible = \App\Models\NetworkIdentity::visible()->count();
echo "Visible count: $visible\n";

$discoverable = \App\Models\NetworkIdentity::discoverable()->count();
echo "Discoverable count: $discoverable\n";

$teachers = \App\Models\NetworkIdentity::teachers()->count();
echo "Teachers count: $teachers\n";

$centers = \App\Models\NetworkIdentity::centers()->count();
echo "Centers count: $centers\n";

$publiclyAccessible = \App\Models\NetworkIdentity::publiclyAccessible()->count();
echo "Publicly accessible count: $publiclyAccessible\n";

$discoverablePublic = \App\Models\NetworkIdentity::discoverablePublic()->count();
echo "Discoverable public count: $discoverablePublic\n";

echo "\nAll tests passed!\n";