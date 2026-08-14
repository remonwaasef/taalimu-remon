<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Pre-registration phone verification controller.
 * Handles OTP generation and verification BEFORE account creation
 * to prevent orphaned/incomplete accounts in the database.
 */
class PhoneVerificationController extends Controller
{
    /**
     * Format phone number with country code for WhatsApp delivery.
     * Strips leading zeros and non-digits, then prepends country code.
     */
    private function formatPhoneForWhatsApp(string $phone, string $countryCode): string
    {
        // Remove any non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        // Remove leading zeros from local number
        $phone = ltrim($phone, '0');
        // Ensure country code doesn't have +
        $countryCode = preg_replace('/[^0-9]/', '', $countryCode);
        // Don't double-add if phone already starts with country code
        if (! str_starts_with($phone, $countryCode)) {
            $phone = $countryCode.$phone;
        }

        return $phone;
    }

    /**
     * Send an OTP code to the provided phone number via WhatsApp.
     * Uses cache-based storage since the user doesn't exist yet.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'country_code' => 'required|string|max:5',
        ]);

        $phone = $request->input('phone');
        $countryCode = $request->input('country_code');
        $fullPhone = $this->formatPhoneForWhatsApp($phone, $countryCode);

        // Rate limit: max 3 OTP requests per phone per 5 minutes
        $rateLimitKey = 'phone_otp_'.md5($fullPhone);

        // Increase rate limit for local development/testing to prevent locking out developers.
        // Environment-based only Ã¢â‚¬â€ request IPs must never relax throttling (spoofable via XFF).
        $maxAttempts = is_relaxed_throttle_env() ? 100 : 3;

        if (RateLimiter::tooManyAttempts($rateLimitKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);

            return response()->json([
                'success' => false,
                'message' => __('messages.otp_wait_seconds', ['seconds' => $seconds]),
            ], 429);
        }

        // Check if phone is already registered (check both local and full formats)
        if (User::where('phone', $phone)->orWhere('phone', $fullPhone)->exists()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.phone_already_registered'),
            ], 422);
        }

        // Generate a 6-digit OTP
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store in cache for 10 minutes (keyed by local phone for matching with form)
        $cacheKey = 'registration_otp_'.md5($phone);
        Cache::put($cacheKey, [
            'code' => $otpCode,
            'phone' => $phone,
            'country_code' => $countryCode,
            'full_phone' => $fullPhone,
            'attempts' => 0,
            'created_at' => now()->toDateTimeString(),
        ], now()->addMinutes(10));

        // Send via WhatsApp and Email in the background
        $message = __('messages.otp_whatsapp_message', ['otp' => $otpCode]);
        $googleData = session('google_user');
        $email = $googleData['email'] ?? null;

        \App\Jobs\SendOtpNotification::dispatch($fullPhone, $otpCode, $email, $message);

        // Increment rate limiter
        RateLimiter::hit($rateLimitKey, 300); // 5 minutes

        Log::info('Registration OTP sent', [
            'phone' => substr($phone, 0, -4).'****',
            'country_code' => $countryCode,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('messages.otp_sent_success'),
            'expires_in' => 600, // 10 minutes in seconds
        ]);
    }

    /**
     * Verify an OTP code for the provided phone number.
     * On success, stores a verified token in the session.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'otp' => 'required|string|size:6',
        ]);

        $phone = $request->input('phone');
        $otp = $request->input('otp');
        $cacheKey = 'registration_otp_'.md5($phone);

        $otpData = Cache::get($cacheKey);

        if (! $otpData) {
            return response()->json([
                'success' => false,
                'message' => __('messages.otp_expired'),
            ], 422);
        }

        // Check max verification attempts (prevent brute force)
        if (($otpData['attempts'] ?? 0) >= 5) {
            Cache::forget($cacheKey);

            return response()->json([
                'success' => false,
                'message' => __('messages.otp_max_attempts'),
            ], 429);
        }

        // Increment attempt counter
        $otpData['attempts'] = ($otpData['attempts'] ?? 0) + 1;
        Cache::put($cacheKey, $otpData, now()->addMinutes(10));

        if ($otpData['code'] !== $otp) {
            return response()->json([
                'success' => false,
                'message' => __('messages.otp_incorrect'),
                'remaining_attempts' => 5 - $otpData['attempts'],
            ], 422);
        }

        // OTP verified! Store a verification token in session
        $verificationToken = hash('sha256', $phone.config('app.key').now()->timestamp);
        session([
            'phone_verified' => true,
            'phone_verified_number' => $phone,
            'phone_verified_country_code' => $otpData['country_code'] ?? '20',
            'phone_verification_token' => $verificationToken,
            'phone_verified_at' => now()->toDateTimeString(),
        ]);
        session()->save();

        // Clean up the OTP from cache
        Cache::forget($cacheKey);

        Log::info('Phone verified for registration', ['phone' => substr($phone, 0, -4).'****']);

        return response()->json([
            'success' => true,
            'message' => __('messages.otp_verified_success'),
            'token' => $verificationToken,
        ]);
    }
}
