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
    /**
     * Send a welcome message with credentials to the admin for manual sharing.
     */
    public function sendRegistrationAlert($tenant, $user, $password)
    {
        $domain = $tenant->domain . '.' . config('app.domain', 'taalimu.com');
        $url = "https://{$domain}";
        
        // Fetch subscription info
        $sub = \App\Models\Subscription::where('tenant_id', $tenant->id)->latest()->first();
        $packageName = $sub ? $sub->type_label : 'غير محدد';
        $subStatus = $sub ? ($sub->onTrial() ? 'تجربة مجانية' : 'اشتراك مفعل') : 'غير محدد';
        $endsAt = ($sub && $sub->ends_at) ? $sub->ends_at->format('Y-m-d') : 'غير محدد';
        $amount = ($sub && $sub->total_amount) ? $sub->total_amount . ' ' . \App\Models\SiteSetting::get('currency_symbol', 'جنيه') : 'مجاني/غير محدد';

        $message = "<b>🚀 تسجيل جديد / اشتراك في المنصة!</b>\n\n";
        $message .= "<b>🏢 اسم المركز:</b> {$tenant->name}\n";
        $message .= "<b>👤 اسم المدير:</b> {$user->name}\n";
        $message .= "<b>📧 البريد الإلكتروني:</b> <code>{$user->email}</code>\n";
        $message .= "<b>📱 رقم الهاتف:</b> <code>{$user->phone}</code>\n";
        $message .= "<b>🌐 رابط المركز:</b> {$url}\n";
        $message .= "<b>🔑 كلمة المرور:</b> <code>{$password}</code>\n";
        $message .= "---------------------------\n";
        $message .= "<b>📦 الباقة الحالية:</b> {$packageName}\n";
        $message .= "<b>💳 حالة الاشتراك:</b> {$subStatus}\n";
        $message .= "<b>💰 قيمة الدفع:</b> {$amount}\n";
        $message .= "<b>⏳ تاريخ الانتهاء:</b> {$endsAt}\n\n";
        $message .= "يرجى مراجعة لوحة تحكم الإدارة لمزيد من التفاصيل.";

        return $this->sendAdminNotification($message);
    }

    /**
     * Send an alert when a center creates or replies to a ticket.
     */
    public function sendTicketAlert($tenant, $ticket, $isNew = true)
    {
        $action = $isNew ? "تذكرة جديدة" : "رد جديد على تذكرة";
        $message = "<b>🎫 {$action}</b>\n\n";
        $message .= "<b>🏢 المركز:</b> {$tenant->name}\n";
        $message .= "<b>📝 العنوان:</b> {$ticket->subject}\n";
        $message .= "<b>🔢 رقم التذكرة:</b> #{$ticket->id}\n";
        $message .= "<b>⚠️ الأولوية:</b> " . ($ticket->priority ?? 'عادية') . "\n\n";
        $message .= "يرجى الرد من لوحة التحكم.";

        return $this->sendAdminNotification($message);
    }

    /**
     * Send an alert when a 500 server error occurs.
     */
    public function sendExceptionAlert(\Throwable $e, $url, $user = null)
    {
        $message = "<b>⚠️ خطأ برمجي في النظام (Error 500)</b>\n\n";
        $message .= "<b>🔗 الرابط:</b> {$url}\n";
        $message .= "<b>👤 المستخدم:</b> " . ($user ? $user->email : 'زائر') . "\n";
        $message .= "<b>❌ الرسالة:</b> <code>" . substr($e->getMessage(), 0, 200) . "</code>\n";
        $message .= "<b>📂 الملف:</b> <code>" . basename($e->getFile()) . "</code> في سطر <code>" . $e->getLine() . "</code>\n\n";
        $message .= "#ErrorAlert";

        return $this->sendAdminNotification($message);
    }

    /**
     * Send an alert for super admin login.
     */
    public function sendLoginAlert($user, $ip)
    {
        $message = "<b>🔐 تسجيل دخول مديـر النظام</b>\n\n";
        $message .= "<b>👤 المسؤول:</b> {$user->name}\n";
        $message .= "<b>📧 البريد:</b> {$user->email}\n";
        $message .= "<b>🌐 IP:</b> <code>{$ip}</code>\n";
        $message .= "<b>⏰ الوقت:</b> " . now()->toDateTimeString() . "\n";

        return $this->sendAdminNotification($message);
    }

    /**
     * Send an alert for coupon usage.
     */
    public function sendCouponAlert($tenant, $coupon, $discount)
    {
        $message = "<b>🎟️ استخدام كوبون خصم</b>\n\n";
        $message .= "<b>🏢 المركز:</b> {$tenant->name}\n";
        $message .= "<b>🏷️ الكوبون:</b> <code>{$coupon->code}</code>\n";
        $message .= "<b>📉 قيمة الخصم:</b> {$discount} " . \App\Models\SiteSetting::get('currency_symbol', 'جنيه') . "\n";

        return $this->sendAdminNotification($message);
    }

    /**
     * Send an alert for failed payments or renewals.
     */
    public function sendFailedPaymentAlert($tenant, $reason = 'فشل عملية الدفع')
    {
        $message = "<b>❌ فشل في عملية الدفع / التجديد!</b>\n\n";
        $message .= "<b>🏢 المركز:</b> {$tenant->name}\n";
        $message .= "<b>📧 البريد:</b> {$tenant->email}\n";
        $message .= "<b>⚠️ السبب:</b> <code>{$reason}</code>\n\n";
        $message .= "يرجى مراجعة حالة سجل الدفع للتأكد.";

        return $this->sendAdminNotification($message);
    }

    /**
     * Send summary report alerts.
     */
    public function sendSummaryReport($title, $data)
    {
        $message = "<b>📊 {$title}</b>\n\n";
        foreach ($data as $key => $value) {
            $message .= "▫️ <b>{$key}:</b> {$value}\n";
        }
        $message .= "\n#SummaryReport";

        return $this->sendAdminNotification($message);
    }
}
