<?php

namespace App\Jobs;

use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOtpNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $fullPhone;

    public $otpCode;

    public $email;

    public $whatsappMessage;

    /**
     * Create a new job instance.
     */
    public function __construct(string $fullPhone, string $otpCode, ?string $email, string $whatsappMessage)
    {
        $this->fullPhone = $fullPhone;
        $this->otpCode = $otpCode;
        $this->email = $email;
        $this->whatsappMessage = $whatsappMessage;

        // High priority queue for OTPs
        $this->onQueue('high');
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppService $whatsapp): void
    {
        // 1. Send via WhatsApp
        try {
            $whatsapp->sendSystemMessage($this->fullPhone, $this->whatsappMessage);
        } catch (\Exception $e) {
            Log::warning('Job Phone OTP WhatsApp send failed: '.$e->getMessage());
        }

        // 2. Send via Email
        if ($this->email) {
            try {
                $otpCode = $this->otpCode;
                Mail::html('
                    <div style="font-family: Arial, sans-serif; direction: rtl; text-align: right; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px;">
                        <h2 style="color: #4f46e5; margin-bottom: 16px;">مرحباً بك في منصة تعليمي! 🎓</h2>
                        <p style="font-size: 16px; color: #334155; line-height: 1.6;">لقد قمت بطلب رمز التحقق لتأكيد رقم هاتفك وتفعيل حسابك.</p>
                        <div style="background-color: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 6px; padding: 16px; text-align: center; margin: 24px 0;">
                            <span style="font-size: 28px; font-weight: bold; letter-spacing: 4px; color: #0f172a;">'.$otpCode.'</span>
                        </div>
                        <p style="font-size: 14px; color: #64748b; margin-bottom: 24px;">يرجى عدم مشاركة هذا الرمز مع أي شخص. هذا الرمز صالح لمدة 10 دقائق فقط.</p>
                        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin-bottom: 16px;">
                        <p style="font-size: 12px; color: #94a3b8; text-align: center;">هذه الرسالة مرسلة تلقائياً، يرجى عدم الرد عليها.</p>
                    </div>
                ', function ($message) {
                    $message->to($this->email)
                        ->subject('كود تفعيل حسابك - منصة تعليمي 🎓');
                });
                Log::info('Job Phone OTP Email sent successfully to: '.$this->email);
            } catch (\Exception $mailEx) {
                Log::warning('Job Phone OTP Email send failed: '.$mailEx->getMessage());
            }
        }
    }
}
