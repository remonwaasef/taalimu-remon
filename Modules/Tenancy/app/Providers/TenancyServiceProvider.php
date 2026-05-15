<?php

namespace Modules\Tenancy\Providers;

use App\Providers\BaseModuleServiceProvider;

class TenancyServiceProvider extends BaseModuleServiceProvider
{
    protected function getModuleName(): string
    {
        return 'Tenancy';
    }

    protected function getModuleNameLower(): string
    {
        return 'tenancy';
    }
}
