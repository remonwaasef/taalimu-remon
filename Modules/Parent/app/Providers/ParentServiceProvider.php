<?php

namespace Modules\Parent\Providers;

use App\Providers\BaseModuleServiceProvider;

class ParentServiceProvider extends BaseModuleServiceProvider
{
    protected function getModuleName(): string
    {
        return 'Parent';
    }

    protected function getModuleNameLower(): string
    {
        return 'parent';
    }
}