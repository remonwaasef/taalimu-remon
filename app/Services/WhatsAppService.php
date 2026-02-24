<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp message.
     * For now, this is a mock/placeholder for UltraMsg or similar APIs.
     * In a real scenario, we would use tenant-specific credentials.
     */
    public function sendMessageByTenant($tenant, $to, $message)
    {
        $settings = $tenant->settings['whatsapp'] ?? [];
        
        if (!($settings['enabled'] ?? false)) {
            return false;
        }

        $token = $settings['token'] ?? null;
        $instanceId = $settings['instance_id'] ?? null;

        if (!$token || !$instanceId) {
            Log::warning("WhatsApp credentials missing for tenant: " . $tenant->id);
            return false;
        }

        // Example for UltraMsg API
        try {
            $response = Http::post("https://api.ultramsg.com/{$instanceId}/messages/chat", [
                'token' => $token,
                'to' => $to,
                'body' => $message,
            ]);

            if ($response->successful()) {
                Log::info("WhatsApp message sent to {$to} for tenant {$tenant->id}");
                return true;
            }

            Log::error("WhatsApp failed for tenant {$tenant->id}: " . $response->body());
        } catch (\Exception $e) {
            Log::error("WhatsApp exception for tenant {$tenant->id}: " . $e->getMessage());
        }

        return false;
    }

    /**
     * Send student attendance notification.
     */
    public function sendAttendanceNotification($tenant, $student, $course)
    {
        $to = $student->phone; // Assuming student phone is the parent's contact
        if (!$to) return false;

        $message = "تحرك من المركز: الطالب {$student->name} حضر الآن حصة {$course->title} في مركز {$tenant->name}.";
        
        return $this->sendMessageByTenant($tenant, $to, $message);
    }

    /**
     * Send payment notification.
     */
    public function sendPaymentNotification($tenant, $student, $amount, $remaining)
    {
        $to = $student->phone;
        if (!$to) return false;

        $message = "تم استلام دفعة مالية بقيمة {$amount} ج.م من الطالب {$student->name}. المتبقي في الحساب: {$remaining} ج.م. شكراً لكم، مركز {$tenant->name}.";
        
        return $this->sendMessageByTenant($tenant, $to, $message);
    }

    /**
     * Send debt reminder notification.
     */
    public function sendDebtReminder($tenant, $student, $amount)
    {
        $to = $student->phone;
        if (!$to) return false;

        $message = "تذكير ودي: يرجى العلم أنه يوجد رصيد مستحق بقيمة {$amount} ".get_currency_symbol()." في حساب الطالب {$student->name}. نرجو منكم المبادرة بالسداد عبر مركزنا أو عبر بوابة الدفع الإلكتروني. شكراً لكم، مركز {$tenant->name}.";
        
        return $this->sendMessageByTenant($tenant, $to, $message);
    }
}
