<?php

namespace App\Traits;

trait HasLocaleResolution
{
    /**
     * Determine the best locale to use for the recipient.
     */
    protected function getTargetLocale($tenant, $student = null)
    {
        // 1. Check Student's user preference
        if ($student && $student->user && $student->user->locale) {
            return $student->user->locale;
        }

        // 2. Check Tenant's general setting
        if ($tenant && isset($tenant->settings['locale']) && $tenant->settings['locale']) {
            return $tenant->settings['locale'];
        }

        // 3. Fallback to App default
        return app()->getLocale();
    }
}
