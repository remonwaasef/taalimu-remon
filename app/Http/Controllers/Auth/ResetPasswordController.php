<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token)
    {
        return view('auth.passwords.reset', ['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $validated['email'])
            ->where('token', $validated['token'])
            ->first();

        if (! $record) {
            return back()->withErrors(['email' => 'رابط إعادة تعيين كلمة المرور غير صالح أو منتهي الصلاحية.']);
        }

        if ($record->created_at < now()->subHours(24)) {
            DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

            return back()->withErrors(['email' => 'انتهت صلاحية رابط إعادة تعيين كلمة المرور. يرجى طلب رابط جديد.']);
        }

        $user = User::where('email', $validated['email'])->first();

        if (! $user) {
            return back()->withErrors(['email' => 'لم يتم العثور على المستخدم.']);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

        return redirect()->route('login.portal')->with('status', 'تم تغيير كلمة المرور بنجاح. يمكنك الآن تسجيل الدخول.');
    }
}
