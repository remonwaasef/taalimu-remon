<?php

namespace Modules\Center\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(20);

        return view('center::notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $url = $notification->data['url'] ?? null;
        if ($url && Str::startsWith($url, ['http://', 'https://'])) {
            $parsed = parse_url($url);
            $allowedHosts = [parse_url(config('app.url'), PHP_URL_HOST), config('app.tenant_domain')];
            if (! in_array($parsed['host'] ?? '', $allowedHosts)) {
                $url = '/';
            }
        }

        return redirect($url ?? '/');
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', __('center::messages.msg_052'));
    }
}
