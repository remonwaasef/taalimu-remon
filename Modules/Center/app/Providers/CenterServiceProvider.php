<?php

namespace Modules\Center\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Center\Console\Commands\FixMissingInvoices;
use Modules\Center\Console\Commands\DemoSeed;

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

    protected function registerCommands(): void
    {
        $this->commands([
            FixMissingInvoices::class,
            DemoSeed::class,
        ]);
    }
}
