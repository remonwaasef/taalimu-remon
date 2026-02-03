<?php

namespace App\Notifications;

use App\Models\OperationIssue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CriticalIssueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected OperationIssue $issue
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('admin.operation-issues.show', $this->issue->uuid);

        return (new MailMessage)
            ->error()
            ->subject('🚨 Critical Issue: ' . $this->issue->title)
            ->line('A critical issue has been detected in a tenant environment.')
            ->line('**Tenant:** ' . ($this->issue->tenant->name ?? 'N/A'))
            ->line('**Action:** ' . $this->issue->action)
            ->line('**Message:** ' . $this->issue->message)
            ->action('View Issue Details', $url)
            ->line('Please investigate immediately.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'issue_id' => $this->issue->id,
            'uuid' => $this->issue->uuid,
            'title' => $this->issue->title,
            'message' => $this->issue->message,
            'severity' => $this->issue->severity,
            'tenant' => $this->issue->tenant->name ?? null,
            'action' => $this->issue->action,
        ];
    }
}
