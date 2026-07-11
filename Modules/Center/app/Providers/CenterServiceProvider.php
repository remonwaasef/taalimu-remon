<?php

namespace Modules\Center\Providers;

use App\Providers\BaseModuleServiceProvider;

class CenterServiceProvider extends BaseModuleServiceProvider
{
    protected function getModuleName(): string
    {
        return 'Center';
    }

    protected function getModuleNameLower(): string
    {
        return 'center';
    }
}
