<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
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
     * @param  string  ...$guards
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                
                // Logic for Tenant Subdomains
                $host = $request->getHost();
                $mainHost = config('app.tenant_domain') ?: parse_url(config('app.url'), PHP_URL_HOST);

                // Check if we are on a tenant subdomain (e.g. ra3y.localhost)
                if ($host !== $mainHost && $host !== 'www.' . $mainHost && $host !== 'localhost') {
                    $parts = explode('.', $host);
                    $subdomain = $parts[0];
                    
                    // Avoid identifying common prefixes or the main domain parts as tenants
                    if (!in_array($subdomain, ['www', 'admin', 'api', 'app'])) {
                        $user = Auth::user();
                        if ($user->role === 'student') {
                            return redirect()->route('campus.index', ['tenant' => $subdomain]);
                        }
                        // Redirect to Center Dashboard for admins/others
                        return redirect()->route('center.dashboard', ['tenant' => $subdomain]);
                    }
                }
                
                // Logic for Main Domain
                if (Auth::user()->role === 'super_admin') {
                    return redirect()->route('admin.dashboard');
                }

                // Default Home
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
