<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;

class PhoneVerificationController extends Controller
{
    protected $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    public function show(Request $request)
    {
        return $request->user()->hasVerifiedPhone()
            ? redirect()->intended('/dashboard')
            : view('auth.verify-phone');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = $request->user();

        if ($user->phone_verification_code === $request->code &&
            now()->lessThanOrEqualTo($user->phone_verification_expires_at)) {
            
            $user->markPhoneAsVerified();

            return redirect()->intended('/dashboard')
                ->with('status', app()->getLocale() == 'ar' ? 'تم تفعيل الحساب بنجاح!' : 'Phone verified successfully!');
        }

        return back()->withErrors(['code' => app()->getLocale() == 'ar' ? 'الكود غير صحيح أو انتهت صلاحيته.' : 'The code is invalid or has expired.']);
    }

    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->phone_verification_expires_at && now()->diffInSeconds($user->phone_verification_expires_at) > 14 * 60) {
            return back()->with('error', app()->getLocale() == 'ar' ? 'يرجى الانتظار دقيقة قبل طلب كود جديد.' : 'Please wait a minute before requesting a new code.');
        }

        $code = $user->generatePhoneVerificationCode();
        
        $message = app()->getLocale() == 'ar' 
            ? "كود تفعيل حسابك في منصة تعليمي هو: {$code}"
            : "Your Taalimu verification code is: {$code}";

        $this->whatsapp->sendSystemMessage($user->phone, $message);

        return back()->with('status', app()->getLocale() == 'ar' ? 'تم إرسال كود جديد!' : 'Verification code resent!');
    }
}
