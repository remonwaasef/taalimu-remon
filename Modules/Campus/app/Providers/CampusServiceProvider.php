<?php

namespace Modules\Campus\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

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
