<?php

namespace App\Queries;

use App\Services\SearchService;
use Illuminate\Database\Eloquent\Builder;

class CourseQuery extends BaseQuery
{
    /**
     * Apply filters to the course query.
     */
    public function apply(Builder $query, array $filters): Builder
    {
        if (isset($filters['search']) && $filters['search'] != '') {
            $this->applySearch($query, $filters['search']);
        }

        if (isset($filters['status']) && $filters['status'] != '') {
            $query->where('status', $filters['status']);
        }

        return $query;
    }

    /**
     * Filter by the full-text engine when a tenant is bound, falling back
     * to safe LIKE matching otherwise.
     */
    protected function applySearch(Builder $query, string $term): Builder
    {
        $tenant = current_tenant();

        if (! $tenant) {
            $search = $this->escapeLike($term);

            return $query->where('title', 'like', '%'.$search.'%');
        }

        $ids = app(SearchService::class)->courseIds($tenant, $term);

        if (empty($ids)) {
            return $query->whereRaw('1 = 0');
        }

        return $query
            ->whereIn('courses.id', $ids)
            ->orderByRaw('FIELD(courses.id,'.implode(',', $ids).')');
    }
}
