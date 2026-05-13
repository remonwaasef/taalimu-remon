<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * إشعار ترحيبي يُرسل تلقائياً عند إضافة عضو فريق جديد.
 * يحتوي على بيانات الدخول (البريد + كلمة المرور المؤقتة).
 */
class TeamMemberWelcome extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $plainPassword;
    protected string $tenantName;
    protected string $loginUrl;
    protected string $roleName;

    public function __construct(string $plainPassword, string $tenantName, string $loginUrl, string $roleName)
    {
        $this->plainPassword = $plainPassword;
        $this->tenantName = $tenantName;
        $this->loginUrl = $loginUrl;
        $this->roleName = $roleName;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $locale = $notifiable->locale ?? app()->getLocale();

        if ($locale === 'ar') {
            return $this->buildArabicMail($notifiable);
        }

        return $this->buildEnglishMail($notifiable);
    }

    protected function buildArabicMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("مرحباً بك في فريق {$this->tenantName}")
            ->greeting("مرحباً {$notifiable->name} 👋")
            ->line("تم إضافتك كعضو في فريق **{$this->tenantName}** بدور **{$this->roleName}**.")
            ->line('بيانات تسجيل الدخول الخاصة بك:')
            ->line("**البريد الإلكتروني:** {$notifiable->email}")
            ->line("**كلمة المرور المؤقتة:** {$this->plainPassword}")
            ->action('تسجيل الدخول الآن', $this->loginUrl)
            ->line('⚠️ يرجى تغيير كلمة المرور فور تسجيل الدخول.')
            ->salutation('فريق ' . $this->tenantName);
    }

    protected function buildEnglishMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Welcome to {$this->tenantName} Team")
            ->greeting("Hello {$notifiable->name} 👋")
            ->line("You've been added to **{$this->tenantName}** team as **{$this->roleName}**.")
            ->line('Your login credentials:')
            ->line("**Email:** {$notifiable->email}")
            ->line("**Temporary Password:** {$this->plainPassword}")
            ->action('Login Now', $this->loginUrl)
            ->line('⚠️ Please change your password immediately after login.')
            ->salutation($this->tenantName . ' Team');
    }
}
