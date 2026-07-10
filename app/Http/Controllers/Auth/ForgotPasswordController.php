<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLink(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user) {
            return back()->withErrors(['email' => 'لا يوجد حساب مرتبط بهذا البريد الإلكتروني.']);
        }

        $token = Str::random(64);
        $used = DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->where('created_at', '>', now()->subHour())
            ->exists();

        if ($used) {
            return back()->withErrors(['email' => 'تم إرسال رابط إعادة تعيين كلمة المرور مسبقاً. يرجى التحقق من بريدك الإلكتروني.']);
        }

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => $token, 'created_at' => now()]
        );

        try {
            $user->notify(new PasswordResetNotification($token));
        } catch (\Exception $e) {
            Log::error("Password reset email failed for {$user->email}: ".$e->getMessage());

            return back()->withErrors(['email' => 'حدث خطأ أثناء إرسال البريد الإلكتروني. يرجى المحاولة لاحقاً.']);
        }

        return back()->with('status', 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.');
    }
}
