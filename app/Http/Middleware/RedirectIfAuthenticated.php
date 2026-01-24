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
                $parts = explode('.', $host);

                // Check if we are on a tenant subdomain (e.g. ra3y.localhost)
                if (count($parts) > 1 && $parts[0] !== 'www') {
                    $user = Auth::user();
                    if ($user->role === 'student') {
                        return redirect()->route('campus.index', ['tenant' => $parts[0]]);
                    }
                    // Redirect to Center Dashboard for admins/others
                    return redirect()->route('center.dashboard', ['tenant' => $parts[0]]);
                }
                
                // Logic for Main Domain
                if (Auth::user()->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                }

                // Default Home
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
