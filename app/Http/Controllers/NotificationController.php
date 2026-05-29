<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = Notification::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $notifications->where('seen', false)->count(),
        ]);
    }

    public function markRead(Request $request, Notification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === $request->user()->id, 403);

        $notification->update(['seen' => true]);

        return back()->with('status', 'Notification marked as read.');
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        Notification::query()
            ->where('user_id', $request->user()->id)
            ->where('seen', false)
            ->update(['seen' => true]);

        return back()->with('status', 'All notifications marked as read.');
    }

    public function destroy(Request $request, Notification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === $request->user()->id, 403);

        $notification->delete();

        return back()->with('status', 'Notification deleted.');
    }
}
