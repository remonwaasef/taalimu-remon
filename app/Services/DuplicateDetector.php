<?php

namespace App\Services;

use App\Models\OperationIssue;

class DuplicateDetector
{
    /**
     * Time window for considering issues as duplicates (in hours)
     */
    private int $timeWindowHours = 24;

    /**
     * Find an existing issue with the same fingerprint
     */
    public function find(string $fingerprint): ?OperationIssue
    {
        return OperationIssue::where('fingerprint', $fingerprint)
            ->where('created_at', '>=', now()->subHours($this->timeWindowHours))
            ->whereNotIn('status', ['resolved', 'closed'])
            ->first();
    }

    /**
     * Find similar issues for potential merging
     */
    public function findSimilar(OperationIssue $issue, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return OperationIssue::where('id', '!=', $issue->id)
            ->where(function ($query) use ($issue) {
                $query->where('fingerprint', $issue->fingerprint)
                    ->orWhere(function ($q) use ($issue) {
                        $q->where('exception_class', $issue->exception_class)
                            ->where('file_path', $issue->file_path)
                            ->where('line_number', $issue->line_number);
                    });
            })
            ->whereNotIn('status', ['closed'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Check if two issues should be considered duplicates
     */
    public function areDuplicates(OperationIssue $issue1, OperationIssue $issue2): bool
    {
        // Same fingerprint = definitely duplicates
        if ($issue1->fingerprint === $issue2->fingerprint) {
            return true;
        }

        // Same exception at same location
        if (
            $issue1->exception_class === $issue2->exception_class &&
            $issue1->file_path === $issue2->file_path &&
            $issue1->line_number === $issue2->line_number
        ) {
            return true;
        }

        return false;
    }

    /**
     * Set the time window for duplicate detection
     */
    public function setTimeWindow(int $hours): self
    {
        $this->timeWindowHours = $hours;

        return $this;
    }
}
