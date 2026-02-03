<?php

namespace Tests\Feature\Security;

use Tests\TestCase;
use Illuminate\Support\Facades\File;
use ReflectionClass;

class ModelIsolationTest extends TestCase
{
    /**
     * Ensure all models in app/Models use the IdentifyTenant trait,
     * except for core models that shouldn't be isolated (like Tenant itself).
     */
    public function test_all_models_use_identify_tenant_trait()
    {
        $modelPath = app_path('Models');
        $files = File::allFiles($modelPath);

        $excludedModels = [
            'Tenant',
            'Feature',
            'Package',
            'PackageFeature',
            'Subscription',
            'Role', 
            'Coupon', // System-wide coupons for subscriptions
            'SiteSetting', // Global platform settings
            'SaleItem', // Child of Sale (implicitly isolated)
        ];

        $failures = [];

        foreach ($files as $file) {
            $className = 'App\\Models\\' . str_replace(['.php', '/'], ['', '\\'], $file->getRelativePathname());

            if (!class_exists($className)) {
                continue;
            }

            $reflection = new ReflectionClass($className);
            
            // Skip non-models or abstract classes
            if (!$reflection->isInstantiable() || !$reflection->isSubclassOf('Illuminate\\Database\\Eloquent\\Model')) {
                continue;
            }

            $shortName = $reflection->getShortName();
            if (in_array($shortName, $excludedModels)) {
                continue;
            }

            $traits = array_keys($reflection->getTraits());
            if (!in_array('App\\Traits\\IdentifyTenant', $traits)) {
                $failures[] = $className;
            }
        }

        $this->assertEmpty($failures, "The following models are missing the IdentifyTenant trait: " . implode(', ', $failures));
    }
}
