<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FALaravel\Facade as Google2FA;

class TwoFactorController extends Controller
{
    /**
     * Show the 2FA Setup page (For forcing users to enable it).
     */
    public function showSetupForm()
    {
        $user = Auth::user();

        // If already enabled, redirect to dashboard or verify
        if ($user->google2fa_enabled) {
            return redirect()->route('center.dashboard');
        }

        // Keep the secret in the session until it is confirmed with a valid
        // OTP. Persisting it on GET would lock the user out (the 2FA
        // middleware gates on the stored secret) without ever confirming it.
        $secret = session('2fa_pending_secret');

        if (! $secret) {
            $secret = $user->google2fa_secret ?: Google2FA::generateSecretKey();
            session(['2fa_pending_secret' => $secret]);
        }

        // Generate the OTP Auth URL (otpauth://...)
        $otpAuthUrl = Google2FA::getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        // Generate QR Code directly using Chillerlan (Stable)
        $qrCodeUrl = (new \chillerlan\QRCode\QRCode)->render($otpAuthUrl);

        return view('auth.2fa.setup', compact('qrCodeUrl', 'user'));
    }

    /**
     * Complete the setup by verifying the first code.
     */
    public function confirmSetup(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|digits:6',
        ]);

        $user = Auth::user();

        $secret = session('2fa_pending_secret') ?: $user->google2fa_secret;

        if (! $secret) {
            return back()->withErrors(['one_time_password' => __('2FA is not configured. Please scan the QR code again.')]);
        }

        $valid = Google2FA::verifyKey($secret, $request->one_time_password);

        if ($valid) {
            $user->google2fa_secret = $secret;
            $user->google2fa_enabled = true;
            $user->save();

            // Mark session as verified
            $request->session()->forget('2fa_pending_secret');
            session(['2fa_verified' => true]);

            return redirect()->route('center.dashboard')->with('success', __('Security setup complete.'));
        }

        return back()->withErrors(['one_time_password' => __('Invalid OTP code.')]);
    }

    /**
     * Disable 2FA (requires the current password to authorize the change).
     */
    public function disable(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();

        $user->google2fa_secret = null;
        $user->google2fa_enabled = false;
        $user->save();

        $request->session()->forget(['2fa_verified', '2fa_pending_secret']);

        return redirect()->route('center.dashboard')->with('success', __('Two-factor authentication disabled.'));
    }

    /**
     * Show Verify Page (For login challenge).
     */
    public function showVerifyForm()
    {
        return view('auth.2fa.verify');
    }

    /**
     * Process verification challenge.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|digits:6',
        ]);

        $user = Auth::user();

        $valid = Google2FA::verifyKey($user->google2fa_secret, $request->one_time_password);

        if ($valid) {
            session(['2fa_verified' => true]);

            // Redirect to intended url or dashboard
            return redirect()->intended(route('center.dashboard'));
        }

        return back()->withErrors(['one_time_password' => __('Invalid OTP code.')]);
    }
}
