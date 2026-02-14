<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected $token;
    protected $chatId;

    public function __construct()
    {
        $this->token = config('services.telegram.bot_token') ?? env('TELEGRAM_BOT_TOKEN');
        $this->chatId = config('services.telegram.admin_chat_id') ?? env('TELEGRAM_ADMIN_CHAT_ID');
    }

    /**
     * Send a notification to the admin via Telegram.
     */
    public function sendAdminNotification($message)
    {
        if (!$this->token || !$this->chatId) {
            Log::warning("Telegram credentials missing. Set TELEGRAM_BOT_TOKEN and TELEGRAM_ADMIN_CHAT_ID in .env");
            return false;
        }

        try {
            $response = Http::post("https://api.telegram.org/bot{$this->token}/sendMessage", [
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

            if ($response->successful()) {
                Log::info("Telegram admin notification sent successfully.");
                return true;
            }

            Log::error("Telegram API Error: " . $response->body());
        } catch (\Exception $e) {
            Log::error("Telegram Exception: " . $e->getMessage());
        }

        return false;
    }

    /**
     * Send a welcome message with credentials to the admin for manual sharing.
     */
    public function sendRegistrationAlert($tenant, $user, $password)
    {
        $domain = $tenant->domain . '.' . config('app.domain', 'taalimu.com');
        $url = "https://{$domain}";

        $message = "<b>🚀 تسجيل جديد في المنصة!</b>\n\n";
        $message .= "<b>اسم المركز:</b> {$tenant->name}\n";
        $message .= "<b>اسم المدير:</b> {$user->name}\n";
        $message .= "<b>البريد الإلكتروني:</b> <code>{$user->email}</code>\n";
        $message .= "<b>رقم الهاتف:</b> <code>{$user->phone}</code>\n";
        $message .= "<b>رابط المركز:</b> {$url}\n";
        $message .= "<b>كلمة المرور:</b> <code>{$password}</code>\n\n";
        $message .= "يرجى نسخ هذه البيانات وإرسالها للمستخدم.";

        return $this->sendAdminNotification($message);
    }
}
