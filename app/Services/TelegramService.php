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
        $this->token = config('services.telegram.bot_token');
        $this->chatId = config('services.telegram.admin_chat_id');
    }

    /**
     * Send a notification to the admin via Telegram (queued).
     */
    public function sendAdminNotification($message)
    {
        if (app()->runningInConsole()) {
            return $this->sendAdminNotificationDirectly($message);
        }

        try {
            \App\Jobs\SendTelegramNotification::dispatch($message)->onQueue('notifications');
            return true;
        } catch (\Throwable $e) {
            Log::error("Failed to queue Telegram notification: " . $e->getMessage());
            return $this->sendAdminNotificationDirectly($message);
        }
    }

    /**
     * Send a notification directly (synchronously).
     */
    public function sendAdminNotificationDirectly($message)
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
        
        // Fetch subscription info
        $sub = \App\Models\Subscription::where('tenant_id', $tenant->id)->latest()->first();
        $packageName = $sub ? $sub->type_label : 'غير محدد';
        $subStatus = $sub ? ($sub->onTrial() ? 'فترة تجريبية' : 'مفعل') : 'N/A';
        $endsAt = ($sub && $sub->ends_at) ? $sub->ends_at->format('Y-m-d') : 'N/A';
        $amount = ($sub && $sub->total_amount) ? $sub->total_amount . ' EGP' : 'مجاني';

        $userName = $user ? $user->name : 'غير معروف';
        $userEmail = $user ? $user->email : 'غير متاح';
        $userPhone = $user ? $user->phone : 'غير متاح';

        $message = "<b>🚀 تسجيل جديد / اشتراك في المنصة!</b>\n\n";
        $message .= "<b>🏢 اسم المركز:</b> {$tenant->name}\n";
        $message .= "<b>👤 اسم المدير:</b> {$userName}\n";
        $message .= "<b>📧 البريد الإلكتروني:</b> <code>{$userEmail}</code>\n";
        $message .= "<b>📱 رقم الهاتف:</b> <code>{$userPhone}</code>\n";
        $message .= "<b>🌐 رابط المركز:</b> {$url}\n";
        $message .= "<b>🔑 كلمة المرور:</b> <i>(تم التشفير لأسباب أمنية)</i>\n";
        $message .= "---------------------------\n";
        $message .= "<b>📦 الباقة الحالية:</b> {$packageName}\n";
        $message .= "<b>💳 حالة الاشتراك:</b> {$subStatus}\n";
        $message .= "<b>💰 قيمة الدفع:</b> {$amount}\n";
        $message .= "<b>⏳ تاريخ الانتهاء:</b> {$endsAt}\n\n";
        $message .= "#NewRegistration";

        return $this->sendAdminNotification($message);
    }

    /**
     * Send an alert when a center creates or replies to a ticket.
     */
    public function sendTicketAlert($tenant, $ticket, $isNew = true)
    {
        $action = $isNew ? 'تذكرة دعم جديدة' : 'رد على تذكرة دعم';
        $message = "<b>🎫 {$action}</b>\n\n";
        $message .= "<b>🏢 المركز:</b> {$tenant->name}\n";
        $message .= "<b>📝 العنوان:</b> {$ticket->subject}\n";
        $message .= "<b>🔢 رقم التذكرة:</b> #{$ticket->id}\n";
        $message .= "<b>⚠️ الأولوية:</b> " . ($ticket->priority ?? 'عادية') . "\n\n";
        $message .= "#SupportTicket";

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
        $message .= "<b>❌ نوع الخطأ:</b> <code>" . class_basename($e) . "</code>\n";
        $message .= "يرجى مراجعة سجلات الخادم (Logs) لمعرفة التفاصيل.\n\n";
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
        $message .= "<b>📉 قيمة الخصم:</b> {$discount} " . \App\Models\SiteSetting::get('currency', 'EGP') . "\n";

        return $this->sendAdminNotification($message);
    }

    /**
     * Send an alert for failed payments or renewals.
     */
    public function sendFailedPaymentAlert($tenant, $reason = null)
    {
        $reason = $reason ?? 'غير محدد';
        $message = "<b>❌ فشل في عملية الدفع / التجديد!</b>\n\n";
        $message .= "<b>🏢 المركز:</b> {$tenant->name}\n";
        $message .= "<b>📧 البريد:</b> {$tenant->email}\n";
        $message .= "<b>⚠️ السبب:</b> <code>{$reason}</code>\n\n";
        $message .= "#PaymentFailed";

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

    /**
     * Send an alert when a center reaches resource limits.
     */
    public function sendResourceLimitWarning($tenant, $resource, $usage, $limit)
    {
        $percent = ($limit > 0) ? round(($usage / $limit) * 100, 1) : 0;
        $message = "<b>⚠️ تنبيه: اقتراب استهلاك كامل الموارد ({$percent}%)</b>\n\n";
        $message .= "<b>🏢 المركز:</b> {$tenant->name}\n";
        $message .= "<b>📊 المورد:</b> <code>{$resource}</code>\n";
        $message .= "<b>📈 الاستهلاك:</b> {$usage} / {$limit}\n\n";
        $message .= "#ResourceWarning";

        return $this->sendAdminNotification($message);
    }

    /**
     * Send churning/inactivity alerts.
     */
    public function sendChurnWarning($tenant, $reason)
    {
        $message = "<b>📉 تنبيه: ركود / احتمال مغادرة مركز</b>\n\n";
        $message .= "<b>🏢 المركز:</b> {$tenant->name}\n";
        $message .= "<b>📧 البريد:</b> {$tenant->email}\n";
        $message .= "<b>🔔 السبب:</b> <code>{$reason}</code>\n\n";
        $message .= "#ChurnWarning";

        return $this->sendAdminNotification($message);
    }

    /**
     * Send alert for sensitive setting changes.
     */
    public function sendSettingChangeAlert($user, $key, $oldValue, $newValue)
    {
        $message = "<b>⚙️ تنبيه: تغيير في إعدادات النظام الحساسة</b>\n\n";
        $message .= "<b>👤 بواسطة:</b> {$user->name}\n";
        $message .= "<b>🔑 الإعداد:</b> <code>{$key}</code>\n";
        $message .= "<b>⬅️ القيمة السابقة:</b> <code>" . (is_array($oldValue) ? json_encode($oldValue) : $oldValue) . "</code>\n";
        $message .= "<b>➡️ القيمة الجديدة:</b> <code>" . (is_array($newValue) ? json_encode($newValue) : $newValue) . "</code>\n\n";
        $message .= "#SecurityAudit";

        return $this->sendAdminNotification($message);
    }

    /**
     * Send alert when a new coupon is created.
     */
    public function sendCouponCreatedAlert($user, $coupon)
    {
        $type = $coupon->type === 'percentage' ? 'نسبة مئوية' : 'مبلغ ثابت';
        $value = $coupon->reward;
        $package = $coupon->package ? $coupon->package->name : 'جميع الباقات';
        
        $message = "<b>🎫 إنشاء كوبون خصم جديد!</b>\n\n";
        $message .= "<b>👤 بواسطة:</b> {$user->name}\n";
        $message .= "<b>🎫️ كود الكوبون:</b> <code>{$coupon->code}</code>\n";
        $message .= "<b>📎 الاسم:</b> {$coupon->name}\n";
        $message .= "<b>📉 النوع:</b> {$type}\n";
        $message .= "<b>💰 القيمة:</b> {$value}\n";
        $message .= "<b>📦 المخطط المستهدف:</b> {$package}\n";
        $message .= "<b>📅 ينتهي في:</b> " . ($coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : 'غير محدد') . "\n\n";
        $message .= "#SalesAudit";

        return $this->sendAdminNotification($message);
    }
}
