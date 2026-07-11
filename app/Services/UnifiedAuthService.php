<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UnifiedAuthService
{
    /**
     * Authenticate a user by email or phone.
     */
    public function authenticate(string $emailOrPhone, string $password): ?User
    {
        $isEmail = filter_var($emailOrPhone, FILTER_VALIDATE_EMAIL);

        if ($isEmail) {
            $query = User::where('email', $emailOrPhone);
            if (app()->bound('tenant')) {
                $query->where(function ($q) {
                    $q->where('tenant_id', app('tenant')->id)
                        ->orWhereNull('tenant_id'); // Allow super admins
                });
            }
            $potentialUser = $query->first();

            if ($potentialUser && Hash::check($password, $potentialUser->password)) {
                if (Auth::attempt(['email' => $emailOrPhone, 'password' => $password])) {
                    return Auth::user();
                }
            }
        } else {
            // Phone-based login logic
            $cleanPhone = preg_replace('/[^0-9]/', '', $emailOrPhone);

            $phoneVariations = array_values(array_filter(array_unique([
                $emailOrPhone,
                $cleanPhone,
                '0'.$cleanPhone,
                substr($cleanPhone, 1),
            ])));

            if (! empty($phoneVariations)) {
                // 1. Single Query for matching User by phone variations
                $query = User::whereIn('phone', $phoneVariations);
                if (app()->bound('tenant')) {
                    $query->where('tenant_id', app('tenant')->id);
                }

                $potentialUsers = $query->get();
                foreach ($potentialUsers as $potentialUser) {
                    if (Hash::check($password, $potentialUser->password)) {
                        if (Auth::attempt(['email' => $potentialUser->email, 'password' => $password])) {
                            return Auth::user();
                        }
                    }
                }

                // 2. Fallback: Single Query for Student with Eager Loaded User
                $studentQuery = Student::with('user')->whereIn('phone', $phoneVariations);
                if (app()->bound('tenant')) {
                    $studentQuery->where('tenant_id', app('tenant')->id);
                }

                $student = $studentQuery->first();
                if ($student && $student->user && Hash::check($password, $student->user->password)) {
                    if (Auth::attempt(['email' => $student->user->email, 'password' => $password])) {
                        return Auth::user();
                    }
                }
            }
        }

        return null;
    }
}
