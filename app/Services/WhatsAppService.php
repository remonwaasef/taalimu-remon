<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Traits\HasLocaleResolution;

class WhatsAppService
{
    use HasLocaleResolution;
    /**
     * Send a WhatsApp message.
     * For now, this is a mock/placeholder for UltraMsg or similar APIs.
     * In a real scenario, we would use tenant-specific credentials.
     */
    /**
     * Send a WhatsApp message using Meta Cloud API.
     */
    public function sendMessageByTenant($tenant, $to, $message)
    {
        $settings = $tenant->settings['whatsapp'] ?? [];
        
        if (!($settings['enabled'] ?? false)) {
            return false;
        }

        $accessToken = $settings['access_token'] ?? null;
        $phoneNumberId = $settings['phone_number_id'] ?? null;
        $apiVersion = $settings['api_version'] ?? 'v21.0';
        $countryCode = $settings['country_code'] ?? '20';

        if (!$accessToken || !$phoneNumberId) {
            Log::warning("Official WhatsApp credentials missing for tenant: " . $tenant->id);
            return false;
        }

        // Format phone number: remove any non-digit characters and ensure country code
        $to = preg_replace('/[^0-9]/', '', $to);
        if ($countryCode && !str_starts_with($to, $countryCode)) {
            $to = $countryCode . ltrim($to, '0');
        }

        try {
            $response = Http::withToken($accessToken)
                ->post("https://graph.facebook.com/{$apiVersion}/{$phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => $to,
                    'type' => 'text',
                    'text' => [
                        'preview_url' => false,
                        'body' => $message,
                    ],
                ]);

            if ($response->successful()) {
                Log::info("Official WhatsApp message sent to {$to} for tenant {$tenant->id}");
                return true;
            }

            Log::error("Official WhatsApp failed for tenant {$tenant->id}: " . $response->body());
        } catch (\Exception $e) {
            Log::error("Official WhatsApp exception for tenant {$tenant->id}: " . $e->getMessage());
        }

        return false;
    }


    /**
     * Send student attendance notification.
     */
    public function sendAttendanceNotification($tenant, $student, $course)
    {
        $to = $student->parent_phone ?: $student->phone;
        if (!$to) return false;

        $locale = $this->getTargetLocale($tenant, $student);
        $settings = $tenant->settings['whatsapp'] ?? [];
        $template = $settings["attendance_template_{$locale}"] ?? $settings['attendance_template'] ?? null;

        if ($template) {
            $message = strtr($template, [
                ':student_name' => $student->name,
                ':course_name' => $course->title,
                ':tenant_name' => $tenant->name
            ]);
        } else {
            $message = __('center::messages.whatsapp_attendance_notify', [
                'student_name' => $student->name,
                'course_name' => $course->title,
                'tenant_name' => $tenant->name
            ], $locale);
        }
        
        return $this->sendMessageByTenant($tenant, $to, $message);
    }

    /**
     * Send payment notification.
     */
    public function sendPaymentNotification($tenant, $student, $amount, $remaining)
    {
        $to = $student->parent_phone ?: $student->phone;
        if (!$to) return false;

        $locale = $this->getTargetLocale($tenant, $student);
        $settings = $tenant->settings['whatsapp'] ?? [];
        $template = $settings["payment_template_{$locale}"] ?? $settings['payment_template'] ?? null;

        if ($template) {
            $message = strtr($template, [
                ':amount' => $amount,
                ':currency' => get_currency_symbol(),
                ':student_name' => $student->name,
                ':remaining' => $remaining,
                ':tenant_name' => $tenant->name
            ]);
        } else {
            $message = __('center::messages.whatsapp_payment_notify', [
                'amount' => $amount,
                'currency' => get_currency_symbol(),
                'student_name' => $student->name,
                'remaining' => $remaining,
                'tenant_name' => $tenant->name
            ], $locale);
        }
        
        return $this->sendMessageByTenant($tenant, $to, $message);
    }

    /**
     * Send debt reminder notification.
     */
    public function sendDebtReminder($tenant, $student, $amount)
    {
        $to = $student->parent_phone ?: $student->phone;
        if (!$to) return false;

        $locale = $this->getTargetLocale($tenant, $student);
        $settings = $tenant->settings['whatsapp'] ?? [];
        $template = $settings["debt_template_{$locale}"] ?? $settings['debt_template'] ?? null;

        if ($template) {
            $message = strtr($template, [
                ':amount' => $amount,
                ':currency' => get_currency_symbol(),
                ':student_name' => $student->name,
                ':tenant_name' => $tenant->name
            ]);
        } else {
            $message = __('center::messages.whatsapp_debt_reminder', [
                'amount' => $amount,
                'currency' => get_currency_symbol(),
                'student_name' => $student->name,
                'tenant_name' => $tenant->name
            ], $locale);
        }
        
        return $this->sendMessageByTenant($tenant, $to, $message);
    }

    /**
     * Send a system-wide WhatsApp message (e.g., for registration OTP).
     * Uses global credentials from .env.
     */
    public function sendSystemMessage($to, $message)
    {
        $accessToken = config('services.whatsapp.system_token');
        $phoneNumberId = config('services.whatsapp.system_phone_id');
        $apiVersion = config('services.whatsapp.system_version', 'v21.0');
        $countryCode = config('services.whatsapp.system_country_code', '20');

        if (!$accessToken || !$phoneNumberId) {
            // Fallback: Log the message instead of sending if keys are missing
            Log::info("WhatsApp System Message (SIMULATED): To: {$to}, Message: {$message}");
            return true; 
        }

        // Format phone number
        $to = preg_replace('/[^0-9]/', '', $to);
        if ($countryCode && !str_starts_with($to, $countryCode)) {
            $to = $countryCode . ltrim($to, '0');
        }

        try {
            $response = Http::withToken($accessToken)
                ->post("https://graph.facebook.com/{$apiVersion}/{$phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => $to,
                    'type' => 'text',
                    'text' => [
                        'preview_url' => false,
                        'body' => $message,
                    ],
                ]);

            if ($response->successful()) {
                Log::info("Official WhatsApp System Message sent to {$to}");
                return true;
            }

            Log::error("Official WhatsApp System Message failed: " . $response->body());
        } catch (\Exception $e) {
            Log::error("Official WhatsApp System Message exception: " . $e->getMessage());
        }

        return false;
    }

}
