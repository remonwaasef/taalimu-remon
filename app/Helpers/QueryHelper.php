<?php

namespace App\Helpers;

class QueryHelper
{
    /**
     * Escape characters that have a special meaning in a LIKE clause.
     *
     * @param string|null $value
     * @param string $char
     * @return string
     */
    public static function escapeLike(?string $value, string $char = '\\'): string
    {
        if (!$value) {
            return '';
        }

        return str_replace(
            [$char, '%', '_'],
            [$char . $char, $char . '%', $char . '_'],
            $value
        );
    }
}
