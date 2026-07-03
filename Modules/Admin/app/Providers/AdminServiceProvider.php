<?php

namespace Modules\Admin\Providers;

use App\Providers\BaseModuleServiceProvider;

class AdminServiceProvider extends BaseModuleServiceProvider
{
    protected function getModuleName(): string
    {
        return 'Admin';
    }

    protected function getModuleNameLower(): string
    {
        return 'admin';
    }
}
