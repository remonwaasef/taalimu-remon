<?php

namespace Modules\Campus\Providers;

use App\Providers\BaseModuleServiceProvider;

class CampusServiceProvider extends BaseModuleServiceProvider
{
    protected function getModuleName(): string
    {
        return 'Campus';
    }

    protected function getModuleNameLower(): string
    {
        return 'campus';
    }
}
