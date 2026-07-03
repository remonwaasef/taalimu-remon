<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                // Logic for Tenant Subdomains
                $host = $request->getHost();
                $mainHost = config('app.tenant_domain') ?: parse_url(config('app.url'), PHP_URL_HOST);

                // Check if we are on a tenant subdomain
                if ($host !== $mainHost && $host !== 'www.'.$mainHost && $host !== 'localhost') {
                    $tenantDomain = null;
                    if (str_ends_with($host, '.'.$mainHost)) {
                        $tenantDomain = str_replace('.'.$mainHost, '', $host);
                    } else {
                        $parts = explode('.', $host);
                        $tenantDomain = $parts[0];
                    }

                    if ($tenantDomain && ! in_array($tenantDomain, ['www', 'admin', 'api', 'app'])) {
                        $user = Auth::user();
                        if ($user->role === 'student') {
                            return redirect()->route('campus.index', ['tenant' => $tenantDomain]);
                        }

                        // Redirect to Center Dashboard for admins/others
                        return redirect()->route('center.dashboard', ['tenant' => $tenantDomain]);
                    }
                }

                // Logic for Main Domain
                $user = Auth::user();
                if ($user->role === 'super_admin') {
                    return redirect()->route('admin.dashboard');
                }

                if ($user->tenant_id) {
                    $tenant = \App\Models\Tenant::find($user->tenant_id);
                    if ($tenant) {
                        if ($user->role === 'student') {
                            return redirect()->away(tenant_url('campus', $tenant));
                        }
                        if ($user->role === 'instructor' || $tenant->type === 'instructor') {
                            return redirect()->away(tenant_url('instructor', $tenant));
                        }

                        return redirect()->away(tenant_url('dashboard', $tenant));
                    }
                }

                // Default Home
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
