<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "Testing NetworkIdentityService...\n";

$tenant = \App\Models\Tenant::first();
$instructor = \App\Models\Instructor::first();

if (! $tenant) {
    echo "No tenant found\n";
    exit(1);
}

if (! $instructor) {
    echo "No instructor found\n";
    exit(1);
}

echo "Tenant: {$tenant->name}\n";
echo "Instructor: {$instructor->name}\n";

$service = app(\App\Services\NetworkIdentityService::class);

// Test create for instructor
$identity = $service->createForInstructor($instructor, [
    'status' => \App\Models\NetworkIdentity::STATUS_PUBLISHED,
]);

echo "Created instructor identity: {$identity->public_slug}\n";
echo "Public URL: {$identity->getPublicUrl()}\n";
echo "Status: {$identity->status}\n";
echo "Profile type: {$identity->profile_type}\n";
echo "Profilable type: {$identity->profilable_type}\n";
echo "Profilable ID: {$identity->profilable_id}\n";

// Test getOrCreate for instructor
$identity2 = $service->getOrCreateForInstructor($instructor);
echo "GetOrCreate returned same identity: " . ($identity->id === $identity2->id ? 'yes' : 'no') . "\n";

// Test create for center
$centerIdentity = $service->createForCenter($tenant, [
    'status' => \App\Models\NetworkIdentity::STATUS_PUBLISHED,
]);

echo "Created center identity: {$centerIdentity->public_slug}\n";
echo "Public URL: {$centerIdentity->getPublicUrl()}\n";

// Test getOrCreate for center
$centerIdentity2 = $service->getOrCreateForCenter($tenant);
echo "GetOrCreate center returned same identity: " . ($centerIdentity->id === $centerIdentity2->id ? 'yes' : 'no') . "\n";

// Test publish/unpublish
$service->unpublish($identity);
$identity->refresh();
echo "After unpublish - status: {$identity->status}, isVisible: " . ($identity->isVisible() ? 'yes' : 'no') . "\n";

$service->publish($identity);
$identity->refresh();
echo "After publish - status: {$identity->status}, isVisible: " . ($identity->isVisible() ? 'yes' : 'no') . "\n";

// Test suspend
$service->suspend($identity);
$identity->refresh();
echo "After suspend - status: {$identity->status}, isVisible: " . ($identity->isVisible() ? 'yes' : 'no') . ", isDiscoverable: " . ($identity->isDiscoverable() ? 'yes' : 'no') . "\n";

// Test archive
$service->archive($identity);
$identity->refresh();
echo "After archive - status: {$identity->status}, isVisible: " . ($identity->isVisible() ? 'yes' : 'no') . "\n";

// Test find by slug
$found = $service->findBySlug($identity->public_slug, \App\Models\NetworkIdentity::PROFILE_TYPE_TEACHER);
echo "Find by slug: " . ($found ? 'found' : 'not found') . "\n";

// Test find publicly accessible
$found = $service->findPubliclyAccessibleBySlug($identity->public_slug, \App\Models\NetworkIdentity::PROFILE_TYPE_TEACHER);
echo "Find publicly accessible: " . ($found ? 'found' : 'not found') . " (should be not found since archived)\n";

// Test find discoverable
$found = $service->findDiscoverableBySlug($centerIdentity->public_slug, \App\Models\NetworkIdentity::PROFILE_TYPE_CENTER);
echo "Find discoverable center: " . ($found ? 'found' : 'not found') . "\n";

// Test slug generation
$slug = $service->generateSlugFromName('Ahmed Mohamed');
echo "Generated slug: $slug\n";

$slug2 = $service->ensureUniqueSlug('ahmed-mohamed', \App\Models\NetworkIdentity::PROFILE_TYPE_TEACHER);
echo "Ensure unique slug: $slug2\n";

echo "\nAll tests passed!\n";