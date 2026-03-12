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
        $countryCode = $settings['country_code'] ?? '20';

        if (!$token || !$instanceId) {
            Log::warning("WhatsApp credentials missing for tenant: " . $tenant->id);
            return false;
        }

        // Format phone number: remove any non-digit characters and ensure country code
        $to = preg_replace('/[^0-9]/', '', $to);
        if ($countryCode && !str_starts_with($to, $countryCode)) {
            $to = $countryCode . ltrim($to, '0');
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
        // Prioritize parent phone for notifications
        $to = $student->parent_phone ?: $student->phone;
        if (!$to) return false;

        $message = __('center::messages.whatsapp_attendance_notify', [
            'student_name' => $student->name,
            'course_name' => $course->title,
            'tenant_name' => $tenant->name
        ]);
        
        return $this->sendMessageByTenant($tenant, $to, $message);
    }

    /**
     * Send payment notification.
     */
    public function sendPaymentNotification($tenant, $student, $amount, $remaining)
    {
        // Prioritize parent phone for notifications
        $to = $student->parent_phone ?: $student->phone;
        if (!$to) return false;

        $message = __('center::messages.whatsapp_payment_notify', [
            'amount' => $amount,
            'currency' => get_currency_symbol(),
            'student_name' => $student->name,
            'remaining' => $remaining,
            'tenant_name' => $tenant->name
        ]);
        
        return $this->sendMessageByTenant($tenant, $to, $message);
    }

    /**
     * Send debt reminder notification.
     */
    public function sendDebtReminder($tenant, $student, $amount)
    {
        // Prioritize parent phone for notifications
        $to = $student->parent_phone ?: $student->phone;
        if (!$to) return false;

        $message = __('center::messages.whatsapp_debt_reminder', [
            'amount' => $amount,
            'currency' => get_currency_symbol(),
            'student_name' => $student->name,
            'tenant_name' => $tenant->name
        ]);
        
        return $this->sendMessageByTenant($tenant, $to, $message);
    }
}
