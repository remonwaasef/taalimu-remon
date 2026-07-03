<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class TenantCache
{
    /**
     * Get the cache prefix for the current context.
     */
    protected static function getPrefix(): string
    {
        return app()->bound('tenant')
            ? 'tenant_'.app('tenant')->id.'_'
            : 'global_';
    }

    /**
     * Get an item from the cache, or execute the given Closure and store the result.
     *
     * @param  string  $key
     * @param  \DateTimeInterface|\DateInterval|int|null  $ttl
     * @return mixed
     */
    public static function remember($key, $ttl, \Closure $callback)
    {
        return Cache::remember(self::getPrefix().$key, $ttl, $callback);
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string  $key
     */
    public static function forget($key): bool
    {
        return Cache::forget(self::getPrefix().$key);
    }

    /**
     * Store an item in the cache.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @param  \DateTimeInterface|\DateInterval|int|null  $ttl
     */
    public static function put($key, $value, $ttl = null): bool
    {
        return Cache::put(self::getPrefix().$key, $value, $ttl);
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return Cache::get(self::getPrefix().$key, $default);
    }
}
