<?php

if (!function_exists('tenant_url')) {
    /**
     * Generate a URL for a tenant based on the configured tenancy mode.
     *
     * @param string $path The path to append to the tenant URL (e.g., 'login', 'dashboard')
     * @param \App\Models\Tenant|string|null $tenant The tenant object or domain string. If null, uses current tenant.
     * @param bool $secure Whether to use HTTPS
     * @return string The full tenant URL
     */
    function tenant_url(string $path = '', $tenant = null, ?bool $secure = null): string
    {
        // Get tenant
        if (is_null($tenant)) {
            $tenant = app('tenant');
        }
        
        $tenantDomain = is_object($tenant) ? $tenant->domain : $tenant;
        
        if (empty($tenantDomain)) {
            throw new \RuntimeException('Cannot generate tenant URL: no tenant specified');
        }
        
        // Determine protocol
        if (is_null($secure)) {
            $secure = request()->isSecure();
        }
        $protocol = $secure ? 'https://' : 'http://';
        
        $mode = config('app.tenancy_mode', 'subdomain');
        $baseDomain = config('app.tenant_domain', parse_url(config('app.url'), PHP_URL_HOST));
        $port = request()->getPort();
        $portSuffix = ($port && !in_array($port, [80, 443])) ? ':' . $port : '';
        
        // Clean path
        $path = ltrim($path, '/');
        
        if ($mode === 'path') {
            // Path-based: domain.com/c/{tenant}/{path}
            return $protocol . $baseDomain . $portSuffix . '/c/' . $tenantDomain . ($path ? '/' . $path : '');
        } else {
            // Subdomain-based: {tenant}.domain.com/{path}
            return $protocol . $tenantDomain . '.' . $baseDomain . $portSuffix . ($path ? '/' . $path : '');
        }
    }
}

if (!function_exists('tenant_route')) {
    /**
     * Generate a named route URL for a tenant.
     *
     * @param string $name Route name
     * @param array $parameters Route parameters
     * @param \App\Models\Tenant|string|null $tenant The tenant object or domain string. If null, uses current tenant.
     * @param bool $absolute Whether to generate an absolute URL
     * @return string
     */
    function tenant_route(string $name, array $parameters = [], $tenant = null, bool $absolute = true): string
    {
        // Get tenant
        if (is_null($tenant)) {
            $tenant = app('tenant');
        }
        
        $tenantDomain = is_object($tenant) ? $tenant->domain : $tenant;
        
        $mode = config('app.tenancy_mode', 'subdomain');
        
        if ($mode === 'path') {
            // In path mode, add tenant parameter
            $parameters['tenant'] = $tenantDomain;
        }
        
        return route($name, $parameters, $absolute);
    }
}

if (!function_exists('current_tenant')) {
    /**
     * Get the current tenant instance.
     *
     * @return \App\Models\Tenant|null
     */
    function current_tenant(): ?\App\Models\Tenant
    {
        return app()->has('tenant') ? app('tenant') : null;
    }
}

if (!function_exists('tenant_asset')) {
    /**
     * Generate an asset URL for a tenant.
     *
     * @param string $path
     * @param \App\Models\Tenant|string|null $tenant
     * @return string
     */
    function tenant_asset(string $path, $tenant = null): string
    {
        return tenant_url('assets/' . ltrim($path, '/'), $tenant);
    }
}

if (!function_exists('get_currency_symbol')) {
    /**
     * Get the currency symbol for the current tenant.
     *
     * @return string
     */
    function get_currency_symbol(): string
    {
        $tenant = current_tenant();
        $currency = $tenant->settings['financial']['currency'] ?? 'EGP';
        
        $symbols = [
            'EGP' => 'EGP',
            'SAR' => 'SAR',
            'USD' => '$',
            'EUR' => '€',
        ];

        // Locale specific Arabic symbols
        if (app()->getLocale() == 'ar') {
            $symbols['EGP'] = 'ج.م';
            $symbols['SAR'] = 'ر.س';
        }

        return $symbols[$currency] ?? $currency;
    }
}

if (!function_exists('format_price')) {
    /**
     * Format price with currency symbol.
     *
     * @param float|int $amount
     * @param bool $withSymbol
     * @return string
     */
    function format_price($amount, bool $withSymbol = true): string
    {
        $formatted = number_format($amount, 2);
        
        if (!$withSymbol) {
            return $formatted;
        }

        $symbol = get_currency_symbol();
        
        // Right-to-left or Left-to-right placement logic could be added here if needed
        return $formatted . ' ' . $symbol;
    }
}
