<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $resetUrl = route('password.reset', ['token' => $this->token, 'email' => $notifiable->email]);

        return (new MailMessage)
            ->subject('إعادة تعيين كلمة المرور')
            ->greeting('مرحباً '.$notifiable->name)
            ->line('لقد تلقينا طلباً لإعادة تعيين كلمة المرور الخاصة بحسابك.')
            ->action('إعادة تعيين كلمة المرور', $resetUrl)
            ->line('رابط إعادة التعيين صالح لمدة 24 ساعة.')
            ->line('إذا لم تطلب إعادة تعيين كلمة المرور، يمكنك تجاهل هذا البريد الإلكتروني.')
            ->salutation('مع تحيات فريق '.config('app.name'));
    }
}
