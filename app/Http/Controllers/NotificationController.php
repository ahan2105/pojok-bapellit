<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Ambil list notif (untuk dropdown bell)
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $notifications = $user->customNotifications()
            ->limit(20)
            ->get()
            ->map(function ($n) {
                return [
                    'id'         => $n->id,
                    'type'       => $n->type,
                    'title'      => $n->title,
                    'message'    => $n->message,
                    'data'       => $n->data,
                    'url'        => $n->url,
                    'is_read'    => $n->read_at !== null,
                    'created_at' => $n->created_at?->toIso8601String(),
                ];
            });

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $user->unreadNotificationsCount(),
        ]);
    }

    /**
     * Tandai 1 notif sudah dibaca
     */
public function markAsRead(Request $request, int $id)
    {
        $notification = Notification::findOrFail($id);

        abort_if($notification->user_id !== $request->user()->id, 403);

        $notification->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Tandai semua notif sudah dibaca
     */
    public function markAllAsRead(Request $request)
    {
        $request->user()->markAllNotificationsAsRead();

        return response()->json(['success' => true]);
    }
}