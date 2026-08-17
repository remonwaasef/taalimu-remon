<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

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
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ]);

        $email = $validated['email'];
        $tenantId = $this->resolveTenantIdForEmail($email);

        $record = $tenantId
            ? DB::table('password_reset_tokens')->where('email', $email)->where('tenant_id', $tenantId)->first()
            : null;

        if (! $record || ! Hash::check($validated['token'], $record->token)) {
            return back()->withErrors(['email' => 'رابط إعادة تعيين كلمة المرور غير صالح أو منتهي الصلاحية.']);
        }

        // SEC-04: expiry unified with the auth config (auth.passwords.users.expire).
        $expiryMinutes = (int) config('auth.passwords.users.expire', 15);
        if ($record->created_at < now()->subMinutes($expiryMinutes)) {
            DB::table('password_reset_tokens')->where('email', $email)->where('tenant_id', $tenantId)->delete();

            return back()->withErrors(['email' => 'انتهت صلاحية رابط إعادة تعيين كلمة المرور. يرجى طلب رابط جديد.']);
        }

        $user = User::where('tenant_id', $tenantId)->where('email', $email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'رابط إعادة تعيين كلمة المرور غير صالح أو منتهي الصلاحية.']);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $email)->where('tenant_id', $tenantId)->delete();

        return redirect()->route('login.portal')->with('status', 'تم تغيير كلمة المرور بنجاح. يمكنك الآن تسجيل الدخول.');
    }

    /**
     * Same tenant resolution as ForgotPasswordController — a reset must only
     * ever target the account that owns the token.
     */
    protected function resolveTenantIdForEmail(string $email): ?string
    {
        if (app()->bound('tenant') && app('tenant')) {
            return (string) app('tenant')->id;
        }

        $matches = User::query()->where('email', $email)->get(['id', 'tenant_id']);

        if ($matches->count() !== 1) {
            return null;
        }

        return (string) ($matches->first()->tenant_id ?? 'global');
    }
}
