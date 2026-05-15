<?php

namespace Modules\Admin\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

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
