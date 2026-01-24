<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Builder;

class StudentQuery extends BaseQuery
{
    /**
     * Apply filters to the student query.
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function apply(Builder $query, array $filters): Builder
    {
        if (isset($filters['search']) && $filters['search'] != '') {
            $search = $this->escapeLike($filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('parent_phone', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        if (isset($filters['grade_id']) && $filters['grade_id'] != '') {
            $query->where('grade_id', $filters['grade_id']);
        }

        return $query;
    }
}
