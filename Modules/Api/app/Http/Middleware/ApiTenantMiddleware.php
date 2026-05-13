<?php

namespace Modules\Api\app\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Modules\Tenancy\Services\TenantResolver;

class ApiTenantMiddleware
{
    /**
     * Handle an incoming API request and resolve the tenant from Headers.
     */
    public function handle(Request $request, Closure $next)
    {
        $tenantDomain = $request->header('X-Tenant-Domain');

        if (!$tenantDomain) {
            return response()->json([
                'success' => false,
                'message' => 'Missing X-Tenant-Domain header.',
            ], 400);
        }

        // Add proper caching for API resolution if necessary
        $tenant = \Illuminate\Support\Facades\Cache::remember("tenant_api_domain_{$tenantDomain}", 3600, function () use ($tenantDomain) {
            return Tenant::where('domain', $tenantDomain)->where('status', 'active')->first();
        });

        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not found or inactive.',
            ], 404);
        }

        // Inject into Resolver
        TenantResolver::set($tenant);
        
        // Scope permissions
        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);

        return $next($request);
    }
}
