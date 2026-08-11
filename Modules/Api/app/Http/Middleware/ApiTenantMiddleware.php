<?php

namespace Modules\Api\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Modules\Tenancy\Services\TenantResolver;

class ApiTenantMiddleware
{
    /**
     * Handle an incoming API request and resolve the tenant from Headers.
     */
    public function handle(Request $request, Closure $next)
    {
        $tenantDomain = strtolower(trim((string) $request->header('X-Tenant-Domain')));

        // Normalize the same way the web layer does: 'www.' is never a tenant.
        if (str_starts_with($tenantDomain, 'www.')) {
            $tenantDomain = substr($tenantDomain, 4);
        }

        if ($tenantDomain === '' || strlen($tenantDomain) > 255) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid X-Tenant-Domain header.',
            ], 400);
        }

        $resolveTenant = fn () => Tenant::where('domain', $tenantDomain)->where('status', 'active')->first();

        try {
            $tenant = \Illuminate\Support\Facades\Cache::remember(
                "tenant_api_domain_{$tenantDomain}",
                3600,
                $resolveTenant
            );
        } catch (\Throwable $e) {
            // Failover to DB if the cache backend is down
            $tenant = $resolveTenant();
        }

        if (! $tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not found or inactive.',
            ], 404);
        }

        // Inject into Resolver
        TenantResolver::set($tenant);

        // Scope permissions
        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);

        // Security check: Prevent Cross-Tenant token usage
        if ($user = $request->user('sanctum')) {
            if ($user->tenant_id !== $tenant->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized cross-tenant access.',
                ], 403);
            }
        }

        return $next($request);
    }
}
