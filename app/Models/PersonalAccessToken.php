<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    /**
     * Find the token instance for the given token.
     * High-Scale: Add caching layer to prevent DB hit on every API request.
     * Handles both 'plain' and 'id|plain' formats.
     *
     * @param  string  $token
     * @return static|null
     */
    public static function findToken($token)
    {
        $plainToken = str_contains($token, '|') ? explode('|', $token, 2)[1] : $token;
        $hashedToken = hash('sha256', $plainToken);
        $cacheKey = 'sanctum_token_'.$hashedToken;

        return Cache::remember($cacheKey, 300, function () use ($token) {
            return parent::findToken($token);
        });
    }

    /**
     * High-Scale Override: Prevent excessive writes to last_used_at.
     * Only save if the token is new or last_used_at is old enough (e.g., 30+ mins).
     */
    public function save(array $options = [])
    {
        $dirty = $this->getDirty();

        if (count($dirty) === 1 && isset($dirty['last_used_at'])) {
            $lastUsed = $this->getOriginal('last_used_at');

            // If it was updated less than 30 minutes ago, skip saving to DB
            if ($lastUsed && \Illuminate\Support\Carbon::parse($lastUsed)->diffInMinutes(now()) < 30) {
                return true;
            }
        }

        return parent::save($options);
    }

    /**
     * Boot the model to handle cache invalidation.
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($token) {
            Cache::forget('sanctum_token_'.$token->token);
        });

        static::deleted(function ($token) {
            Cache::forget('sanctum_token_'.$token->token);
        });
    }
}
