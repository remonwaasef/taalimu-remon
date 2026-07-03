<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;

class AdminNotificationService
{
    /**
     * Notify tenant admins about an event.
     *
     * @param  string  $action  The action type (e.g., 'student_registered')
     * @param  string  $message  The notification message
     * @param  string  $url  The URL to redirect to
     * @param  string  $icon  The FontAwesome icon class
     * @param  string  $causer  The name of the user who performed the action
     * @param  int|null  $tenantId  Optional tenant ID, defaults to current tenant
     */
    public function notifyAdmins(
        string $action,
        string $message,
        string $url,
        string $icon,
        string $causer,
        ?int $tenantId = null
    ): void {
        $tenantId = $tenantId ?? \Modules\Tenancy\Services\TenantResolver::get()->id;

        $admins = User::where('tenant_id', $tenantId)
            ->whereIn('role', ['admin', 'center_admin'])
            ->get();

        if ($admins->isEmpty()) {
            return;
        }

        Notification::send($admins, new GeneralNotification(
            $action,
            $message,
            $url,
            $icon,
            $causer
        ));
    }
}
