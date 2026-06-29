<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\URL;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $mode = config('app.tenancy_mode', 'subdomain');
        $tenant = null;
        
        if ($mode === 'path') {
            // Path-based tenancy: Extract tenant from URL path /c/{tenant}/
            $pathSegments = $request->segments();
            
            // Check if first segment is 'c' and second segment exists
            if (count($pathSegments) >= 2 && $pathSegments[0] === 'c') {
                $tenantDomain = $pathSegments[1];
                

                
                // Max Optimization: Load full context (Tenant + Subscription + Package + Features)
                $loadRelations = ['currentSubscription.package.features'];

                try {
                    if (extension_loaded('redis')) {
                        $tenant = \Illuminate\Support\Facades\Cache::store('redis')->remember("taalimu:tenancy:domain:{$tenantDomain}", 3600, function () use ($tenantDomain, $loadRelations) {
                            return Tenant::with($loadRelations)
                                ->where('domain', $tenantDomain)
                                ->first();
                        });
                    } else {
                        throw new \Exception("Redis extension not loaded");
                    }
                } catch (\Throwable $e) {
                    // Failover to DB with same eager loading for performance
                    $tenant = Tenant::with($loadRelations)
                        ->where('domain', $tenantDomain)
                        ->first();
                }
            } else {
                // Not a tenant path, skip tenant identification
                return $next($request);
            }
        } else {
            // Subdomain-based tenancy (original logic)
            $host = $request->getHost();
            
            // Use the configured tenant domain (e.g., yourdomain.com)
            $mainHost = config('app.tenant_domain') ?: parse_url(config('app.url'), PHP_URL_HOST);
            
            // Skip if it's 'www' or exactly the main domain
            if ($host === $mainHost || $host === 'www.' . $mainHost || $host === 'localhost') {
                 // Even if we skip deeper tenant identification, if the route matched a {tenant} group,
                 // we should ensure URL generation doesn't break for these routes.
                 if ($request->route() && $request->route()->hasParameter('tenant')) {
                     URL::defaults(['tenant' => $request->route('tenant')]);
                 }
                 return $next($request);
            }

            // Logic to extract subdomain
            // If host is tenant.yourdomain.com, we want 'tenant'
            $subdomain = '';
            if (str_ends_with($host, '.' . $mainHost)) {
                $subdomain = str_replace('.' . $mainHost, '', $host);
            } else {
                // Fallback for cases where it's not following the standard pattern
                $parts = explode('.', $host);
                $subdomain = $parts[0];
            }

            // Avoid identifying common prefixes as tenants
            if (in_array($subdomain, ['www', 'admin', 'api', 'app'])) {
                return $next($request);
            }

            // Optimized Tenant Resolution with Redis & Failover (Zero DB Hits Strategy)
            try {
                if (extension_loaded('redis')) {
                    $tenant = \Illuminate\Support\Facades\Cache::store('redis')->remember("taalimu:tenancy:domain:{$subdomain}", 3600, function () use ($subdomain) {
                        return Tenant::with(['currentSubscription.package.features'])
                            ->where('domain', $subdomain)
                            ->first();
                    });
                } else {
                    throw new \Exception("Redis extension not loaded");
                }
            } catch (\Throwable $e) {
                // Fallback to DB if Redis fails or extension missing
                $tenant = Tenant::with(['currentSubscription.package.features'])
                    ->where('domain', $subdomain)
                    ->first();
            }
            

            
            // If this is a tenant-only domain (checked by str_ends_with) and no tenant found, 404
            if (!$tenant && str_ends_with($host, '.' . $mainHost)) {
                abort(404, 'Center not found.');
            }
        }

        // If tenant found and active, set it in the container
        if ($tenant) {
            if ($tenant->status !== 'active') {
                abort(403, 'Center is currently inactive. Please contact support.');
            }

            // Compatibility: If in subdomain mode but accessed via path /c/tenant, redirect to subdomain
            if ($mode === 'subdomain' && count($request->segments()) >= 2 && $request->segments()[0] === 'c' && $request->segments()[1] === $tenant->domain) {
                $pathSegments = $request->segments();
                $remainingPath = implode('/', array_slice($pathSegments, 2));
                return redirect(tenant_url($remainingPath, $tenant));
            }

            app()->instance('tenant', $tenant);
            
            // Set Spatie Team ID to the current tenant so that user assignments (pivot table) are found.
            app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
            
            view()->share('tenant', $tenant);
            URL::defaults(['tenant' => $tenant->domain]);
            
            // Add tenant context to logs
            \Illuminate\Support\Facades\Log::withContext([
                'tenant_id' => $tenant->id,
                'tenant_domain' => $tenant->domain
            ]);

            // Set timezone dynamically for multi-region support
            if ($tenant->timezone) {
                config(['app.timezone' => $tenant->timezone]);
                // If using Carbon, set its default timezone dynamically for the current request context
                if (class_exists(\Carbon\Carbon::class)) {
                    \Carbon\Carbon::setTestNow(); // Reset any test time and let it use the current config
                }
            }

            // Dynamically set log file for this tenant
            config(['logging.channels.single.path' => storage_path("logs/tenant_{$tenant->id}.log")]);
            config(['logging.channels.daily.path' => storage_path("logs/tenant_{$tenant->id}.log")]);
            
            if ($request->route()) {
                $request->route()->forgetParameter('tenant');
            }

            return $next($request);
        }

        // No tenant found
        if ($mode === 'path' && count($request->segments()) >= 2 && $request->segments()[0] === 'c') {
            abort(404, 'Center not found.');
        }

        return $next($request);
    }
}
