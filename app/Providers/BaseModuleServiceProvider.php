<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

abstract class BaseModuleServiceProvider extends ServiceProvider
{
    use PathNamespace;

    /**
     * Get the name of the module.
     * Must be implemented by the child provider.
     */
    abstract protected function getModuleName(): string;

    /**
     * Get the lowercased name of the module.
     * Must be implemented by the child provider.
     */
    abstract protected function getModuleNameLower(): string;

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->getModuleName(), 'database/migrations'));
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        // Check if EventServiceProvider exists in the module
        $eventProviderClass = 'Modules\\' . $this->getModuleName() . '\Providers\EventServiceProvider';
        if (class_exists($eventProviderClass)) {
            $this->app->register($eventProviderClass);
        }

        // Check if RouteServiceProvider exists in the module
        $routeProviderClass = 'Modules\\' . $this->getModuleName() . '\Providers\RouteServiceProvider';
        if (class_exists($routeProviderClass)) {
            $this->app->register($routeProviderClass);
        }
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        // $this->commands([]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // Optional implementation
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/' . $this->getModuleNameLower());

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->getModuleNameLower());
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $moduleLangPath = module_path($this->getModuleName(), 'lang');
            if (!is_dir($moduleLangPath)) {
                $moduleLangPath = module_path($this->getModuleName(), 'resources/lang');
            }
            $this->loadTranslationsFrom($moduleLangPath, $this->getModuleNameLower());
            $this->loadJsonTranslationsFrom($moduleLangPath);
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $configPath = module_path($this->getModuleName(), config('modules.paths.generator.config.path', 'config'));

        if (is_dir($configPath)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($configPath));

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $config = str_replace($configPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $config_key = str_replace([DIRECTORY_SEPARATOR, '.php'], ['.', ''], $config);
                    $segments = explode('.', $this->getModuleNameLower() . '.' . $config_key);

                    // Remove duplicated adjacent segments
                    $normalized = [];
                    foreach ($segments as $segment) {
                        if (end($normalized) !== $segment) {
                            $normalized[] = $segment;
                        }
                    }

                    $key = ($config === 'config.php') ? $this->getModuleNameLower() : implode('.', $normalized);

                    $this->publishes([$file->getPathname() => config_path($config)], 'config');
                    $this->merge_config_from($file->getPathname(), $key);
                }
            }
        }
    }

    /**
     * Merge config from the given path recursively.
     */
    protected function merge_config_from(string $path, string $key): void
    {
        $existing = config($key, []);
        $module_config = require $path;

        config([$key => array_replace_recursive($existing, $module_config)]);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/' . $this->getModuleNameLower());
        $sourcePath = module_path($this->getModuleName(), 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->getModuleNameLower() . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->getModuleNameLower());

        Blade::componentNamespace(config('modules.namespace', 'Modules') . '\\' . $this->getModuleName() . '\\View\\Components', $this->getModuleNameLower());
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths', []) as $path) {
            if (is_dir($path . '/modules/' . $this->getModuleNameLower())) {
                $paths[] = $path . '/modules/' . $this->getModuleNameLower();
            }
        }

        return $paths;
    }
}
