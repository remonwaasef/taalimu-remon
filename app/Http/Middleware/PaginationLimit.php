<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PaginationLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  int  $max
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $max = 100)
    {
        if ($request->has('per_page')) {
            $perPage = (int) $request->get('per_page');
            if ($perPage > $max || $perPage <= 0) {
                $request->merge(['per_page' => $max]);
            }
        }

        return $next($request);
    }
}
