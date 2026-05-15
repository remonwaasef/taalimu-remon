<?php

namespace Modules\Api\Providers;

use App\Providers\BaseModuleServiceProvider;

class ApiServiceProvider extends BaseModuleServiceProvider
{
    protected function getModuleName(): string
    {
        return 'Api';
    }

    protected function getModuleNameLower(): string
    {
        return 'api';
    }
}
