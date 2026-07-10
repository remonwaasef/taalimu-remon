<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    protected static ?Tenant $resolvedTenant = null;

    public function handle(Request $request, Closure $next): Response
    {
        if (self::$resolvedTenant) {
            return $this->setTenantContext(self::$resolvedTenant, $request, $next);
        }

        $mode = config('app.tenancy_mode', 'subdomain');
        $tenant = null;

        if ($mode === 'path') {
            $pathSegments = $request->segments();

            if (count($pathSegments) >= 2 && $pathSegments[0] === 'c') {
                $tenantDomain = $pathSegments[1];
                $tenant = $this->resolveTenant($tenantDomain);
            } else {
                return $next($request);
            }
        } else {
            $host = $request->getHost();
            $mainHost = config('app.tenant_domain') ?: parse_url(config('app.url'), PHP_URL_HOST);

            if ($host === $mainHost || $host === 'www.'.$mainHost || $host === 'localhost') {
                if ($request->route() && $request->route()->hasParameter('tenant')) {
                    URL::defaults(['tenant' => $request->route('tenant')]);
                }

                return $next($request);
            }

            $subdomain = '';
            if (str_ends_with($host, '.'.$mainHost)) {
                $subdomain = str_replace('.'.$mainHost, '', $host);
            } else {
                $parts = explode('.', $host);
                $subdomain = $parts[0];
            }

            if (in_array($subdomain, ['www', 'admin', 'api', 'app'])) {
                return $next($request);
            }

            $tenant = $this->resolveTenant($subdomain);

            if (! $tenant && str_ends_with($host, '.'.$mainHost)) {
                abort(404, 'Center not found.');
            }
        }

        if ($tenant) {
            if ($tenant->status !== 'active') {
                abort(403, 'Center is currently inactive. Please contact support.');
            }

            if ($mode === 'subdomain' && count($request->segments()) >= 2 && $request->segments()[0] === 'c' && $request->segments()[1] === $tenant->domain) {
                $pathSegments = $request->segments();
                $remainingPath = implode('/', array_slice($pathSegments, 2));

                return redirect(tenant_url($remainingPath, $tenant));
            }

            self::$resolvedTenant = $tenant;

            return $this->setTenantContext($tenant, $request, $next);
        }

        if ($mode === 'path' && count($request->segments()) >= 2 && $request->segments()[0] === 'c') {
            abort(404, 'Center not found.');
        }

        return $next($request);
    }

    protected function setTenantContext(Tenant $tenant, Request $request, Closure $next): Response
    {
        app()->instance('tenant', $tenant);
        app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);

        view()->share('tenant', $tenant);
        URL::defaults(['tenant' => $tenant->domain]);

        Log::withContext([
            'tenant_id' => $tenant->id,
            'tenant_domain' => $tenant->domain,
        ]);

        if ($tenant->timezone) {
            config(['app.timezone' => $tenant->timezone]);
        }

        $tenantId = (int) $tenant->id;
        config(['logging.channels.single.path' => storage_path("logs/tenant_{$tenantId}.log")]);
        config(['logging.channels.daily.path' => storage_path("logs/tenant_{$tenantId}.log")]);

        if ($request->route()) {
            $request->route()->forgetParameter('tenant');
        }

        return $next($request);
    }

    protected function resolveTenant(string $domain): ?Tenant
    {
        $loadTenant = fn () => Tenant::with(['currentSubscription.package.features'])
            ->where('domain', $domain)
            ->first();

        try {
            return Cache::remember(
                "taalimu:tenancy:domain:{$domain}",
                3600,
                $loadTenant
            );
        } catch (\Throwable $e) {
            return $loadTenant();
        }
    }
}
