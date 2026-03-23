<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class DailyIssueDigestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Collection $issues
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route('admin.operation-issues.index');
        $criticalCount = $this->issues->where('severity', 'critical')->count();
        $totalCount = $this->issues->count();

        $message = (new MailMessage)
            ->subject('📅 Daily Operational Issues Digest')
            ->line("There are {$totalCount} unresolved issues from the last 24 hours.")
            ->line("**Critical Issues:** {$criticalCount}");

        if ($criticalCount > 0) {
            $message->error();
        }

        $message->line('Summary of recent issues:')
            ->action('View All Issues', $url);

        foreach ($this->issues->take(5) as $issue) {
            $message->line("- [{$issue->severity}] " . ($issue->tenant->name ?? 'System') . ": " . ($issue->title ?: substr($issue->message, 0, 100)));
        }

        if ($totalCount > 5) {
            $message->line("... and " . ($totalCount - 5) . " more.");
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'total_issues' => $this->issues->count(),
            'critical_issues' => $this->issues->where('severity', 'critical')->count(),
            'issue_ids' => $this->issues->pluck('id')->toArray(),
            'message' => 'Daily operational issues digest for ' . now()->toDateString(),
        ];
    }
}
