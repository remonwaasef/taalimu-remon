<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;

/**
 * Base Controller for Center Module
 * Provides global variables and shared logic for all center controllers.
 */
class CenterBaseController extends Controller
{
    /**
     * The current tenant (center) instance.
     * @var \App\Models\Tenant
     */
    protected $tenant;

    public function __construct()
    {
        // Use middleware to ensure the tenant is resolved before accessing it
        $this->middleware(function ($request, $next) {
            $this->tenant = app('tenant');

            // Share the tenant globally with all views in the center module
            if ($this->tenant) {
                view()->share('tenant', $this->tenant);
            }

            return $next($request);
        });
    }
}
