<?php

namespace App\Jobs;

use App\Models\BugReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ProcessBugReportNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $report;

    public $debugError;

    public $tries = 3;

    public $backoff = [60, 120, 300];

    public function __construct(BugReport $report, ?string $debugError = null)
    {
        $this->report = $report;
        $this->debugError = $debugError;
    }

    public function handle()
    {
        if ($this->report->screenshot) {
            try {
                $this->copyToPublicStorage($this->report->screenshot);
            } catch (\Exception $e) {
                Log::warning('Failed to copy screenshot to public storage: '.$e->getMessage());
            }
        }

        $this->sendTelegramNotification($this->report, $this->debugError);
        $this->sendEmailNotification($this->report);
    }

    private function copyToPublicStorage(string $fileName): void
    {
        $sourcePath = storage_path('app/public/'.$fileName);
        $destPath = public_path('storage/'.$fileName);
        $destDir = dirname($destPath);

        if (! file_exists($destDir)) {
            mkdir($destDir, 0755, true);
        }

        if (file_exists($sourcePath)) {
            if (realpath($sourcePath) !== realpath($destPath)) {
                copy($sourcePath, $destPath);
            }
        }
    }

    private function sendTelegramNotification(BugReport $report, ?string $debugError = null): void
    {
        $botToken = config('services.telegram.bot_token');
        $chatId = config('services.telegram.admin_chat_id');

        if (! $botToken || ! $chatId) {
            return;
        }

        $tenant = $report->tenant;
        $user = $report->user;

        $priorityEmoji = match ($report->priority) {
            'critical' => '🔴',
            'high' => '🟠',
            'medium' => '🟡',
            'low' => '🟢',
            default => '⚪',
        };

        $categoryEmoji = match ($report->category) {
            'bug' => '🐛',
            'suggestion' => '💡',
            'ui_issue' => '🎨',
            'performance' => '⚡',
            default => '📝',
        };

        $message = "━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "🚨 *بلاغ جديد - تعليمُه*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "{$categoryEmoji} *النوع:* {$report->category}\n";
        $message .= "{$priorityEmoji} *الأولوية:* {$report->priority}\n\n";
        $message .= "📌 *العنوان:* {$report->title}\n\n";
        $message .= "📝 *الوصف:*\n{$report->description}\n\n";

        if ($debugError) {
            $message .= "⚠️ *خطأ في الصورة:* `{$debugError}`\n\n";
        } else {
            $fullRealPath = $report->screenshot ? (Storage::disk('public')->path($report->screenshot)) : null;
            if ($fullRealPath && file_exists($fullRealPath)) {
                $message .= "📸 *تم إرفاق لقطة الشاشة أدناه*\n\n";
            }
        }

        $message .= "━━━━━━━━━━━━━━━━━━━━\n";
        $message .= '🏢 *المركز:* '.($tenant?->name ?? 'N/A')."\n";
        $message .= '👤 *المستخدم:* '.($user?->name ?? 'N/A')."\n";
        $message .= '📧 *الإيميل:* '.($user?->email ?? 'N/A')."\n";
        $message .= "🔗 *الصفحة:* {$report->page_url}\n";
        $message .= '📱 *المتصفح:* '.($report->browser_info['browser'] ?? 'N/A')."\n";
        $message .= "🆔 *رقم البلاغ:* #{$report->id}\n";
        $message .= '━━━━━━━━━━━━━━━━━━━━';

        $fullRealPath = $report->screenshot ? (Storage::disk('public')->path($report->screenshot)) : null;

        if ($fullRealPath && file_exists($fullRealPath)) {
            Http::timeout(10)->attach(
                'photo', fopen($fullRealPath, 'r'), basename($fullRealPath)
            )->post("https://api.telegram.org/bot{$botToken}/sendPhoto", [
                'chat_id' => $chatId,
                'caption' => $message,
                'parse_mode' => 'Markdown',
            ]);
        } else {
            Http::timeout(10)->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);
        }
    }

    private function sendEmailNotification(BugReport $report): void
    {
        $developerEmail = config('mail.bug_report_address', config('mail.from.address'));

        if (! $developerEmail) {
            return;
        }

        $tenant = $report->tenant;
        $user = $report->user;

        $subject = "[Taalimu Bug #{$report->id}] [{$report->priority}] {$report->title}";

        $body = "<div style='font-family: Cairo, Arial, sans-serif; direction: rtl; padding: 20px;'>";
        $body .= "<div style='background: linear-gradient(135deg, #059669, #047857); color: white; padding: 20px; border-radius: 12px; margin-bottom: 20px;'>";
        $body .= "<h2 style='margin: 0;'>🚨 بلاغ جديد - تعليمُه</h2>";
        $body .= "<p style='margin: 5px 0 0; opacity: 0.9;'>#{$report->id} | {$report->priority} | {$report->category}</p>";
        $body .= '</div>';

        $body .= "<div style='background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0;'>";
        $body .= "<h3 style='color: #1e293b;'>📌 {$report->title}</h3>";
        $body .= "<p style='color: #475569; line-height: 1.8;'>{$report->description}</p>";
        $body .= "<hr style='border: none; border-top: 1px solid #e2e8f0;'>";
        $body .= "<table style='width: 100%; color: #64748b; font-size: 14px;'>";
        $body .= '<tr><td><strong>🏢 المركز:</strong></td><td>'.($tenant?->name ?? 'N/A').'</td></tr>';
        $body .= '<tr><td><strong>👤 المستخدم:</strong></td><td>'.($user?->name ?? 'N/A').' ('.($user?->email ?? 'N/A').')</td></tr>';
        $body .= "<tr><td><strong>🔗 الصفحة:</strong></td><td><a href='{$report->page_url}'>{$report->page_url}</a></td></tr>";
        $body .= '<tr><td><strong>📱 المتصفح:</strong></td><td>'.($report->browser_info['browser'] ?? 'N/A').' / '.($report->browser_info['os'] ?? 'N/A').'</td></tr>';
        $body .= "<tr><td><strong>🕐 الوقت:</strong></td><td>{$report->created_at}</td></tr>";
        $body .= '</table>';
        $body .= '</div></div>';

        Mail::html($body, function ($mail) use ($developerEmail, $subject) {
            $mail->to($developerEmail)
                ->subject($subject);
        });
    }
}
