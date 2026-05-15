<?php

namespace Modules\Instructor\Providers;

use App\Providers\BaseModuleServiceProvider;

class InstructorServiceProvider extends BaseModuleServiceProvider
{
    protected function getModuleName(): string
    {
        return 'Instructor';
    }

    protected function getModuleNameLower(): string
    {
        return 'instructor';
    }
}
