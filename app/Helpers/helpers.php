<?php

if (! function_exists('tenant_url')) {
    /**
     * Generate a URL for a tenant based on the configured tenancy mode.
     *
     * @param  string  $path  The path to append to the tenant URL (e.g., 'login', 'dashboard')
     * @param  \App\Models\Tenant|string|null  $tenant  The tenant object or domain string. If null, uses current tenant.
     * @param  bool  $secure  Whether to use HTTPS
     * @return string The full tenant URL
     */
    function tenant_url(string $path = '', $tenant = null, ?bool $secure = null): string
    {
        // Get tenant
        if (is_null($tenant)) {
            $tenant = app()->bound('tenant') ? app('tenant') : null;
        }

        $tenantDomain = is_object($tenant) ? $tenant->domain : $tenant;

        if (empty($tenantDomain)) {
            return url($path);
        }

        // Determine protocol
        if (is_null($secure)) {
            $secure = request()->isSecure();
        }
        $protocol = $secure ? 'https://' : 'http://';

        $mode = config('app.tenancy_mode', 'subdomain');
        $currentHost = request()->getHost();
        $baseDomain = config('app.tenant_domain');
        if (empty($baseDomain) || $baseDomain === 'localhost') {
            if (str_ends_with($currentHost, 'taalimu.com')) {
                $baseDomain = 'taalimu.com';
            } else {
                $baseDomain = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
            }
        }
        $port = request()->getPort();
        $portSuffix = ($port && ! in_array($port, [80, 443])) ? ':'.$port : '';

        // Clean path
        $path = ltrim($path, '/');

        if ($mode === 'path') {
            // Path-based: domain.com/c/{tenant}/{path}
            return $protocol.$baseDomain.$portSuffix.'/c/'.$tenantDomain.($path ? '/'.$path : '');
        } else {
            // Subdomain-based: {tenant}.domain.com/{path}
            return $protocol.$tenantDomain.'.'.$baseDomain.$portSuffix.($path ? '/'.$path : '');
        }
    }
}

if (! function_exists('tenant_route')) {
    /**
     * Generate a named route URL for a tenant.
     *
     * @param  string  $name  Route name
     * @param  array  $parameters  Route parameters
     * @param  \App\Models\Tenant|string|null  $tenant  The tenant object or domain string. If null, uses current tenant.
     * @param  bool  $absolute  Whether to generate an absolute URL
     */
    function tenant_route(string $name, array $parameters = [], $tenant = null, bool $absolute = true): string
    {
        // Get tenant
        if (is_null($tenant)) {
            $tenant = app()->bound('tenant') ? app('tenant') : null;
        }

        $tenantDomain = is_object($tenant) ? $tenant->domain : $tenant;

        $mode = config('app.tenancy_mode', 'subdomain');

        if (! isset($parameters['tenant']) && $tenantDomain) {
            $parameters['tenant'] = $tenantDomain;
        }

        return route($name, $parameters, $absolute);
    }
}

if (! function_exists('current_tenant')) {
    /**
     * Get the current tenant instance.
     */
    function current_tenant(): ?\App\Models\Tenant
    {
        return app()->bound('tenant') ? app('tenant') : null;
    }
}

if (! function_exists('tenant_asset')) {
    /**
     * Generate an asset URL for a tenant.
     *
     * @param  \App\Models\Tenant|string|null  $tenant
     */
    function tenant_asset(string $path, $tenant = null): string
    {
        return tenant_url('assets/'.ltrim($path, '/'), $tenant);
    }
}

if (! function_exists('get_currency_symbol')) {
    /**
     * Get the currency symbol for the current tenant.
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

if (! function_exists('format_price')) {
    /**
     * Format price with currency symbol.
     *
     * @param  float|int  $amount
     */
    function format_price($amount, bool $withSymbol = true): string
    {
        $formatted = number_format($amount, 2);

        if (! $withSymbol) {
            return $formatted;
        }

        $symbol = get_currency_symbol();

        // Right-to-left or Left-to-right placement logic could be added here if needed
        return $formatted.' '.$symbol;
    }
}

if (! function_exists('sanitizePhoneForWhatsApp')) {
    /**
     * Sanitize a phone number for WhatsApp links.
     *
     * @param  string|null  $phone
     * @return string
     */
    function sanitizePhoneForWhatsApp($phone)
    {
        if (! $phone) {
            return '';
        }
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $countryCode = app('tenant')->settings['default_country_code'] ?? '20';
            $phone = $countryCode.substr($phone, 1);
        }

        return $phone;
    }
}

if (! function_exists('is_relaxed_throttle_env')) {
    /**
     * Whether rate limits may be relaxed for development/testing.
     *
     * SECURITY: this must rely on the application environment ONLY.
     * Never trust the request IP for this decision — behind a proxy the
     * client IP is derived from X-Forwarded-For and a private-looking
     * address (192.168.x.x) can be attacker-controlled, which would allow
     * brute-force protections to be bypassed in production.
     */
    function is_relaxed_throttle_env(): bool
    {
        return app()->environment(['local', 'testing']);
    }
}
