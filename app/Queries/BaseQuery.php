<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Builder;

abstract class BaseQuery
{
    /**
     * Apply filters to the query.
     */
    abstract public function apply(Builder $query, array $filters): Builder;

    /**
     * Helper to escape LIKE queries.
     */
    protected function escapeLike(?string $value): string
    {
        return \App\Helpers\QueryHelper::escapeLike($value);
    }
}
