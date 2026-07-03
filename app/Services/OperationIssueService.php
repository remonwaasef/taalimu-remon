<?php

namespace App\Services;

use App\Models\IssueTimeline;
use App\Models\OperationIssue;
use App\Models\User;

class OperationIssueService
{
    /**
     * Acknowledge an issue.
     */
    public function acknowledge(OperationIssue $issue, User $user): void
    {
        $oldStatus = $issue->status;

        $issue->update([
            'status' => 'acknowledged',
            'acknowledged_at' => now(),
            'first_response_at' => $issue->first_response_at ?? now(),
        ]);

        $this->recordTimeline($issue, 'status_changed', $user, [
            'old_value' => ['status' => $oldStatus],
            'new_value' => ['status' => 'acknowledged'],
        ]);
    }

    /**
     * Assign an issue to an assignee.
     */
    public function assignTo(OperationIssue $issue, User $assignee, User $assignedBy): void
    {
        $oldAssignee = $issue->assigned_to;

        $issue->update([
            'assigned_to' => $assignee->id,
            'status' => $issue->status === 'new' ? 'acknowledged' : $issue->status,
            'first_response_at' => $issue->first_response_at ?? now(),
        ]);

        $this->recordTimeline($issue, 'assigned', $assignedBy, [
            'old_value' => ['assigned_to' => $oldAssignee],
            'new_value' => ['assigned_to' => $assignee->id, 'name' => $assignee->name],
        ]);
    }

    /**
     * Update the status of an issue.
     */
    public function updateStatus(OperationIssue $issue, string $status, User $user, ?string $comment = null): void
    {
        $oldStatus = $issue->status;

        $updateData = ['status' => $status];

        if ($status === 'in_progress' && ! $issue->first_response_at) {
            $updateData['first_response_at'] = now();
        }

        $issue->update($updateData);

        $this->recordTimeline($issue, 'status_changed', $user, [
            'old_value' => ['status' => $oldStatus],
            'new_value' => ['status' => $status],
            'comment' => $comment,
        ]);
    }

    /**
     * Resolve an issue.
     */
    public function resolve(OperationIssue $issue, string $type, string $notes, User $user): void
    {
        $oldStatus = $issue->status;
        $resolvedAt = now();
        $resolutionMinutes = $issue->created_at->diffInMinutes($resolvedAt);

        $issue->update([
            'status' => 'resolved',
            'resolution_type' => $type,
            'resolution_notes' => $notes,
            'resolved_by' => $user->id,
            'resolved_at' => $resolvedAt,
            'resolution_time_minutes' => $resolutionMinutes,
        ]);

        $this->recordTimeline($issue, 'resolved', $user, [
            'old_value' => ['status' => $oldStatus],
            'new_value' => [
                'status' => 'resolved',
                'resolution_type' => $type,
                'resolution_time_minutes' => $resolutionMinutes,
            ],
            'comment' => $notes,
        ]);
    }

    /**
     * Add a comment to an issue.
     */
    public function addComment(OperationIssue $issue, string $comment, User $user): IssueTimeline
    {
        return $this->recordTimeline($issue, 'commented', $user, [
            'comment' => $comment,
        ]);
    }

    /**
     * Mute notifications for an issue.
     */
    public function mute(OperationIssue $issue): void
    {
        $issue->update(['is_muted' => true]);

        if (auth()->check()) {
            $this->recordTimeline($issue, 'muted', auth()->user());
        }
    }

    /**
     * Unmute notifications for an issue.
     */
    public function unmute(OperationIssue $issue): void
    {
        $issue->update(['is_muted' => false]);

        if (auth()->check()) {
            $this->recordTimeline($issue, 'unmuted', auth()->user());
        }
    }

    /**
     * Merge this issue into a target issue.
     */
    public function merge(OperationIssue $source, OperationIssue $target): void
    {
        // Move timeline entries to target
        $source->timeline()->update(['issue_id' => $target->id]);

        // Increment occurrence count
        $target->increment('occurrence_count', $source->occurrence_count);
        $target->update([
            'last_occurrence_at' => max($source->last_occurrence_at ?? $source->created_at, $target->last_occurrence_at ?? $target->created_at),
            'is_recurring' => true,
        ]);

        // Soft delete the source issue
        $source->delete();
    }

    /**
     * Increment the occurrence count of an issue.
     */
    public function incrementOccurrence(OperationIssue $issue): void
    {
        $issue->increment('occurrence_count');
        $issue->update([
            'last_occurrence_at' => now(),
            'is_recurring' => $issue->occurrence_count > 1,
        ]);
    }

    /**
     * Record a timeline entry.
     */
    protected function recordTimeline(OperationIssue $issue, string $type, ?User $user, array $data = []): IssueTimeline
    {
        return $issue->timeline()->create([
            'user_id' => $user?->id,
            'type' => $type,
            'old_value' => $data['old_value'] ?? null,
            'new_value' => $data['new_value'] ?? null,
            'comment' => $data['comment'] ?? null,
        ]);
    }
}
