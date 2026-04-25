<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $notifications = AppNotification::forUser($user)
            ->latest()
            ->paginate(30);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request, AppNotification $notification)
    {
        $notification->markAsRead();
        return back();
    }

    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        AppNotification::forUser($user)->unread()->update(['read_at' => now()]);
        return back()->with('success', 'Semua notifikasi dibaca.');
    }
}