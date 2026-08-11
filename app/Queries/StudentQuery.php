<?php

namespace App\Queries;

use App\Services\SearchService;
use Illuminate\Database\Eloquent\Builder;

class StudentQuery extends BaseQuery
{
    /**
     * Apply filters to the student query.
     */
    public function apply(Builder $query, array $filters): Builder
    {
        if (isset($filters['search']) && $filters['search'] != '') {
            $this->applySearch($query, $filters['search']);
        }

        if (isset($filters['grade_id']) && $filters['grade_id'] != '') {
            $query->where('grade_id', $filters['grade_id']);
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

            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%')
                    ->orWhere('parent_phone', 'like', '%'.$search.'%')
                    ->orWhere('code', 'like', '%'.$search.'%');
            });
        }

        $ids = app(SearchService::class)->studentIds($tenant, $term);

        if (empty($ids)) {
            return $query->whereRaw('1 = 0');
        }

        return $query
            ->whereIn('students.id', $ids)
            ->orderByRaw('FIELD(students.id,'.implode(',', $ids).')');
    }
}
