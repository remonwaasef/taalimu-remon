<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

        $email = $validated['email'];
        $tenantId = $this->resolveTenantIdForEmail($email);

        // SEC-A4: generic response for unknown/ambiguous accounts — never
        // reveal whether an email exists (account-enumeration oracle).
        if (! $tenantId) {
            return back()->with('status', 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.');
        }

        $user = User::where('tenant_id', $tenantId)->where('email', $email)->first();

        if (! $user) {
            return back()->with('status', 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.');
        }

        $token = Str::random(64);

        // SEC-04: never persist the raw token — store a hash so a DB read can
        // never be replayed as a working reset link.
        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('tenant_id', $tenantId)
            ->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'tenant_id' => $tenantId,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        try {
            $user->notify(new PasswordResetNotification($token));
        } catch (\Exception $e) {
            Log::error("Password reset email failed for {$email}: ".$e->getMessage());

            return back()->withErrors(['email' => 'حدث خطأ أثناء إرسال البريد الإلكتروني. يرجى المحاولة لاحقاً.']);
        }

        return back()->with('status', 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.');
    }

    /**
     * Determine which tenant owns the account for this email.
     *
     * On a tenant subdomain the current tenant wins. On the central domain,
     * the email must resolve to exactly one account — with per-tenant unique
     * emails there can legitimately be several; an ambiguous email gets a
     * generic (deny) response instead of resetting a random account.
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
