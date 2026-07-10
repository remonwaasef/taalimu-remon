<?php

namespace Modules\Center\Services;

/**
 * @deprecated Moved to \App\Services\SettingsService (shared app layer) because
 *             it is consumed by multiple modules. This subclass is kept only so
 *             existing type-hints/container resolutions keep working; new code
 *             must inject \App\Services\SettingsService directly.
 */
class SettingsService extends \App\Services\SettingsService {}
