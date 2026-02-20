<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists
            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                // Login existing user
                Auth::login($user);
                return redirect()->intended('/dashboard');
            } else {
                // Check if user exists with same email
                $existingUser = User::where('email', $googleUser->email)->first();

                if ($existingUser) {
                    // Update existing user with google_id
                    $existingUser->update([
                        'google_id' => $googleUser->id,
                        'email_verified_at' => now(), // Trust Google verification
                    ]);
                    
                    Auth::login($existingUser);
                    return redirect()->intended('/dashboard');
                }

                // If no user exists, create one? 
                // Creating a center requires much more info (subdomain, etc).
                // For now, we might want to redirect to registration page with pre-filled data
                // OR create a student account if this is a student portal.
                
                // Assuming this is for Admin/Owner login primarily based on previous context:
                // We cannot auto-create a center. We should redirect to register with data.
                
                return redirect()->route('register', [
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id, // Pass this to link after reg
                ])->with('info', __('Please complete your center registration.'));
            }

        } catch (\Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login.portal')->withErrors(['email' => 'Unable to login with Google. Please try again.']);
        }
    }
}
