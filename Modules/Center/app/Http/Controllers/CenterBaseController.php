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
    /**
     * The current tenant (center) instance.
     * @var \App\Models\Tenant
     */
    protected $tenant;

    public function __construct()
    {
        // Reserved for future base initialization.
        // Properties depending on middleware (like tenant) are initialized in callAction.
    }

    public function callAction($method, $parameters)
    {
        // Tenant is already bound by the IdentifyTenant middleware before the controller method executes.
        if (app()->bound('tenant')) {
            $this->tenant = app('tenant');
            view()->share('tenant', $this->tenant);
        }

        return $this->{$method}(...array_values($parameters));
    }
}
