<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Google2FALaravel\Facade as Google2FA;
use Illuminate\Support\Facades\Auth;

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

        // Generate secret key if not exists
        if (!$user->google2fa_secret) {
            $user->google2fa_secret = Google2FA::generateSecretKey();
            $user->save();
        }

        // Generate the OTP Auth URL (otpauth://...)
        $otpAuthUrl = Google2FA::getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->google2fa_secret
        );

        // Generate QR Code directly using Chillerlan (Stable)
        $qrCodeUrl = (new \chillerlan\QRCode\QRCode())->render($otpAuthUrl);

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
        
        $valid = Google2FA::verifyKey($user->google2fa_secret, $request->one_time_password);

        if ($valid) {
            $user->google2fa_enabled = true;
            $user->save();
            
            // Mark session as verified
            session(['2fa_verified' => true]);

            return redirect()->route('center.dashboard')->with('success', __('Security setup complete.'));
        }

        return back()->withErrors(['one_time_password' => __('Invalid OTP code.')]);
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
