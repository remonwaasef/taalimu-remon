<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Builder;

class CourseQuery extends BaseQuery
{
    /**
     * Apply filters to the course query.
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function apply(Builder $query, array $filters): Builder
    {
        if (isset($filters['search']) && $filters['search'] != '') {
            $search = $this->escapeLike($filters['search']);
            $query->where('title', 'like', '%' . $search . '%');
        }

        if (isset($filters['status']) && $filters['status'] != '') {
            $query->where('status', $filters['status']);
        }

        return $query;
    }
}
