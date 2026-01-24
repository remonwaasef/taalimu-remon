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
                
                \Illuminate\Support\Facades\Log::info('IdentifyTenant [Path Mode]', [
                    'tenant_domain' => $tenantDomain,
                    'path' => $request->path()
                ]);
                
                $tenant = \Illuminate\Support\Facades\Cache::remember("tenant_lookup_{$tenantDomain}", 3600, function () use ($tenantDomain) {
                    return Tenant::where('domain', $tenantDomain)->first();
                });
            } else {
                // Not a tenant path, skip tenant identification
                \Illuminate\Support\Facades\Log::info('IdentifyTenant [Path Mode] - Skipped', ['path' => $request->path()]);
                return $next($request);
            }
        } else {
            // Subdomain-based tenancy (original logic)
            $host = $request->getHost();
            \Illuminate\Support\Facades\Log::info('IdentifyTenant [Subdomain Mode]', ['host' => $host]);
            
            // Use the configured tenant domain (e.g., yourdomain.com)
            $mainHost = config('app.tenant_domain') ?: parse_url(config('app.url'), PHP_URL_HOST);
            
            // Skip if it's 'www' or exactly the main domain
            if ($host === $mainHost || $host === 'www.' . $mainHost || $host === 'localhost') {
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

            $tenant = \Illuminate\Support\Facades\Cache::remember("tenant_lookup_{$subdomain}", 3600, function () use ($subdomain) {
                return Tenant::where('domain', $subdomain)->first();
            });
            
            \Illuminate\Support\Facades\Log::info('Tenant lookup [Subdomain]', ['subdomain' => $subdomain, 'found' => $tenant ? 'yes' : 'no']);
            
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
