<?php

namespace App\Services\Student;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentPasswordService
{
    /**
     * Reset student password to a new random one.
     *
     * @return string
     */
    public function resetPassword(User $user)
    {
        $newPassword = Str::random(12);

        $user->update([
            'password' => Hash::make($newPassword),
            'must_change_password' => true,
        ]);

        return $newPassword;
    }
}
