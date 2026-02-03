<?php

namespace App\Services;

use App\Models\OperationIssue;
use App\Models\User;
use App\Notifications\CriticalIssueNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class IssueNotifier
{
    /**
     * Severities that trigger immediate notification
     */
    private array $criticalSeverities = ['critical', 'high'];

    /**
     * Notify administrators if the issue meets notification criteria
     */
    public function notifyIfNeeded(OperationIssue $issue): void
    {
        // Skip if issue is muted
        if ($issue->is_muted) {
            return;
        }

        // Skip if notifications are disabled
        if (!config('issues.notifications.email', true)) {
            return;
        }

        // Only notify for critical severities
        if (!in_array($issue->severity, $this->criticalSeverities)) {
            return;
        }

        // Only notify immediately for critical issues
        if ($issue->severity === 'critical' || config('issues.notifications.critical_immediate', true)) {
            $this->notifyCritical($issue);
        }
    }

    /**
     * Send immediate notification for critical issues
     */
    protected function notifyCritical(OperationIssue $issue): void
    {
        try {
            // Find super admins to notify
            $admins = $this->getAdminsToNotify();

            if ($admins->isEmpty()) {
                Log::warning('No admins found to notify about critical issue', [
                    'issue_id' => $issue->id,
                ]);
                return;
            }

            // Send notification
            Notification::send($admins, new CriticalIssueNotification($issue));

            Log::info('Critical issue notification sent', [
                'issue_id' => $issue->id,
                'admins_notified' => $admins->pluck('id')->toArray(),
            ]);
        } catch (\Throwable $e) {
            // Don't let notification failure break the flow
            Log::error('Failed to send critical issue notification', [
                'issue_id' => $issue->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get list of administrators to notify
     */
    protected function getAdminsToNotify(): \Illuminate\Database\Eloquent\Collection
    {
        return User::where('role', 'super_admin')
            ->orWhereHas('roles', function ($query) {
                $query->where('name', 'super_admin');
            })
            ->get();
    }

    /**
     * Send daily digest of unresolved issues
     */
    public function sendDailyDigest(): void
    {
        $issues = OperationIssue::unresolved()
            ->notMuted()
            ->where('created_at', '>=', now()->subDay())
            ->orderBy('severity')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($issues->isEmpty()) {
            return;
        }

        // TODO: Implement digest notification
        Log::info('Daily issue digest would be sent', [
            'issue_count' => $issues->count(),
            'critical_count' => $issues->where('severity', 'critical')->count(),
        ]);
    }
}
