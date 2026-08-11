<?php

namespace App\Services;

use App\Helpers\QueryHelper;
use App\Models\Course;
use App\Models\Student;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

/**
 * SearchService — unified search entry point.
 *
 * Uses the active Laravel Scout engine (Meilisearch in production,
 * database driver locally). When the full-text engine is unreachable
 * it gracefully degrades to indexed-safe LIKE queries, keeping every
 * search tenant-isolated through an explicit tenant_id filter.
 */
class SearchService
{
    /**
     * Hard cap on the number of matched ids pulled from the search engine.
     */
    private const MAX_IDS = 1000;

    /**
     * Fields matched by the fallback LIKE search per model.
     */
    private const STUDENT_FIELDS = ['name', 'phone', 'parent_phone', 'code', 'email'];

    private const COURSE_FIELDS = ['title', 'description'];

    /**
     * Search students ranked by relevance, scoped to a tenant.
     *
     * @return Collection<int, Student>
     */
    public function searchStudents(Tenant $tenant, string $term, int $limit = 20, ?int $excludeCourseId = null): Collection
    {
        if ($limit < 1) {
            throw new \InvalidArgumentException('Search limit must be at least 1.');
        }

        $ids = $this->studentIds($tenant, $term, $limit);

        if (empty($ids)) {
            return new Collection;
        }

        $query = Student::query()
            ->select('id', 'name', 'phone')
            ->where('tenant_id', $tenant->id)
            ->whereIn('id', $ids)
            ->orderByRaw('FIELD(id,'.implode(',', $ids).')')
            ->limit($limit);

        if ($excludeCourseId) {
            $query->whereDoesntHave(
                'user.enrollments',
                fn (Builder $q) => $q->where('course_id', $excludeCourseId)
            );
        }

        return $query->get();
    }

    /**
     * Search courses ranked by relevance, scoped to a tenant.
     *
     * @return Collection<int, Course>
     */
    public function searchCourses(Tenant $tenant, string $term, int $limit = 20): Collection
    {
        if ($limit < 1) {
            throw new \InvalidArgumentException('Search limit must be at least 1.');
        }

        $ids = $this->courseIds($tenant, $term, $limit);

        if (empty($ids)) {
            return new Collection;
        }

        return Course::query()
            ->select('id', 'title')
            ->where('tenant_id', $tenant->id)
            ->whereIn('id', $ids)
            ->orderByRaw('FIELD(id,'.implode(',', $ids).')')
            ->limit($limit)
            ->get();
    }

    /**
     * Get ranked student ids matching the term within a tenant.
     *
     * @return array<int, int>
     */
    public function studentIds(Tenant $tenant, string $term, int $limit = self::MAX_IDS): array
    {
        return $this->engineIds(Student::class, $tenant, $term, $limit, self::STUDENT_FIELDS);
    }

    /**
     * Get ranked course ids matching the term within a tenant.
     *
     * @return array<int, int>
     */
    public function courseIds(Tenant $tenant, string $term, int $limit = self::MAX_IDS): array
    {
        return $this->engineIds(Course::class, $tenant, $term, $limit, self::COURSE_FIELDS);
    }

    /**
     * Resolve matching ids through the active search engine, falling back
     * to safe LIKE queries when the engine is unreachable.
     *
     * @param  class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  array<int, string>  $likeFields
     * @return array<int, int>
     */
    private function engineIds(string $model, Tenant $tenant, string $term, int $limit, array $likeFields): array
    {
        $term = trim($term);

        if ($term === '') {
            return [];
        }

        try {
            $ids = $model::search($term)
                ->where('tenant_id', (int) $tenant->id)
                ->take($limit)
                ->get()
                ->pluck('id')
                ->all();
        } catch (\Throwable $e) {
            $this->logEngineFailure($model, $e);

            $ids = [];
        }

        if (! empty($ids)) {
            return array_map('intval', $ids);
        }

        return $this->likeIds($model::query(), $term, $limit, $likeFields);
    }

    /**
     * Index-safe LIKE fallback used when the search engine is unavailable.
     *
     * @param  array<int, string>  $fields
     * @return array<int, int>
     */
    private function likeIds(Builder $query, string $term, int $limit, array $fields): array
    {
        $search = QueryHelper::escapeLike($term);

        return array_map('intval', $query
            ->where(function (Builder $q) use ($search, $fields) {
                foreach ($fields as $field) {
                    $q->orWhere($field, 'like', '%'.$search.'%');
                }
            })
            ->limit($limit)
            ->pluck('id')
            ->all());
    }

    /**
     * Log search engine failures without breaking the request.
     *
     * @param  class-string<\Illuminate\Database\Eloquent\Model>  $model
     */
    private function logEngineFailure(string $model, \Throwable $e): void
    {
        Log::warning('Full-text search engine unavailable; falling back to LIKE search.', [
            'model' => $model,
            'error' => $e->getMessage(),
        ]);
    }
}
