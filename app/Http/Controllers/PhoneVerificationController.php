<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\User;

/**
 * Pre-registration phone verification controller.
 * Handles OTP generation and verification BEFORE account creation
 * to prevent orphaned/incomplete accounts in the database.
 */
class PhoneVerificationController extends Controller
{
    /**
     * Send an OTP code to the provided phone number via WhatsApp.
     * Uses cache-based storage since the user doesn't exist yet.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        $phone = $request->input('phone');

        // Rate limit: max 3 OTP requests per phone per 5 minutes
        $rateLimitKey = 'phone_otp_' . md5($phone);
        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return response()->json([
                'success' => false,
                'message' => app()->getLocale() == 'ar'
                    ? "تم إرسال الكود بالفعل. يرجى الانتظار {$seconds} ثانية قبل المحاولة مرة أخرى."
                    : "OTP already sent. Please wait {$seconds} seconds before trying again.",
            ], 429);
        }

        // Check if phone is already registered
        if (User::where('phone', $phone)->exists()) {
            return response()->json([
                'success' => false,
                'message' => app()->getLocale() == 'ar'
                    ? 'رقم الهاتف هذا مسجل بالفعل. يرجى تسجيل الدخول بدلاً من ذلك.'
                    : 'This phone number is already registered. Please login instead.',
            ], 422);
        }

        // Generate a 6-digit OTP
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store in cache for 10 minutes
        $cacheKey = 'registration_otp_' . md5($phone);
        Cache::put($cacheKey, [
            'code' => $otpCode,
            'phone' => $phone,
            'attempts' => 0,
            'created_at' => now()->toDateTimeString(),
        ], now()->addMinutes(10));

        // Send via WhatsApp
        try {
            $whatsapp = app(\App\Services\WhatsAppService::class);
            $message = app()->getLocale() == 'ar'
                ? "مرحباً بك في منصة تعليمي! 🎓\nكود التحقق الخاص بك هو: {$otpCode}\nيرجى عدم مشاركة هذا الكود مع أي شخص."
                : "Welcome to Taalimu! 🎓\nYour verification code is: {$otpCode}\nPlease do not share this code with anyone.";

            $whatsapp->sendSystemMessage($phone, $message);
        } catch (\Exception $e) {
            Log::warning('Phone OTP WhatsApp send failed: ' . $e->getMessage());
            // Don't fail the request - the OTP is still in cache for testing
        }

        // Increment rate limiter
        RateLimiter::hit($rateLimitKey, 300); // 5 minutes

        Log::info('Registration OTP sent', ['phone' => substr($phone, 0, -4) . '****']);

        return response()->json([
            'success' => true,
            'message' => app()->getLocale() == 'ar'
                ? 'تم إرسال كود التحقق عبر واتساب. يرجى إدخال الكود المكون من 6 أرقام.'
                : 'Verification code sent via WhatsApp. Please enter the 6-digit code.',
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
        $cacheKey = 'registration_otp_' . md5($phone);

        $otpData = Cache::get($cacheKey);

        if (!$otpData) {
            return response()->json([
                'success' => false,
                'message' => app()->getLocale() == 'ar'
                    ? 'انتهت صلاحية كود التحقق. يرجى طلب كود جديد.'
                    : 'Verification code expired. Please request a new code.',
            ], 422);
        }

        // Check max verification attempts (prevent brute force)
        if (($otpData['attempts'] ?? 0) >= 5) {
            Cache::forget($cacheKey);
            return response()->json([
                'success' => false,
                'message' => app()->getLocale() == 'ar'
                    ? 'تم تجاوز الحد الأقصى لمحاولات التحقق. يرجى طلب كود جديد.'
                    : 'Maximum verification attempts exceeded. Please request a new code.',
            ], 429);
        }

        // Increment attempt counter
        $otpData['attempts'] = ($otpData['attempts'] ?? 0) + 1;
        Cache::put($cacheKey, $otpData, now()->addMinutes(10));

        if ($otpData['code'] !== $otp) {
            return response()->json([
                'success' => false,
                'message' => app()->getLocale() == 'ar'
                    ? 'كود التحقق غير صحيح. يرجى المحاولة مرة أخرى.'
                    : 'Incorrect verification code. Please try again.',
                'remaining_attempts' => 5 - $otpData['attempts'],
            ], 422);
        }

        // OTP verified! Store a verification token in session
        $verificationToken = hash('sha256', $phone . config('app.key') . now()->timestamp);
        session([
            'phone_verified' => true,
            'phone_verified_number' => $phone,
            'phone_verification_token' => $verificationToken,
            'phone_verified_at' => now()->toDateTimeString(),
        ]);
        session()->save();

        // Clean up the OTP from cache
        Cache::forget($cacheKey);

        Log::info('Phone verified for registration', ['phone' => substr($phone, 0, -4) . '****']);

        return response()->json([
            'success' => true,
            'message' => app()->getLocale() == 'ar'
                ? 'تم التحقق من رقم الهاتف بنجاح! ✅'
                : 'Phone number verified successfully! ✅',
            'token' => $verificationToken,
        ]);
    }
}
