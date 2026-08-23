<?php

namespace App\Policies;

use App\Models\ClassRecording;
use App\Models\User;

class ClassRecordingPolicy
{
    /**
     * Watch permission is the core security boundary: the recording's class
     * must belong to this tenant and the user must have explicit access.
     */
    public function view(User $user, ClassRecording $recording): bool
    {
        if ((int) $user->tenant_id !== (int) $recording->tenant_id) {
            return false;
        }

        if ($recording->status !== ClassRecording::STATUS_READY) {
            return false;
        }

        $class = $recording->onlineClass;

        if (! $class) {
            return false;
        }

        // Owning instructor + center admins can always preview their material.
        if ($user->instructor && (int) $user->instructor->id === (int) $class->instructor_id) {
            return true;
        }

        if ($user->hasAnyRole(['center_admin', 'admin'])) {
            return true;
        }

        return $class->hasAccess($user);
    }

    public function viewAnalytics(User $user, ClassRecording $recording): bool
    {
        if ((int) $user->tenant_id !== (int) $recording->tenant_id) {
            return false;
        }

        if ($user->hasAnyRole(['center_admin', 'admin'])) {
            return true;
        }

        $class = $recording->onlineClass;

        return $class && $user->instructor
            && (int) $user->instructor->id === (int) $class->instructor_id;
    }
}
