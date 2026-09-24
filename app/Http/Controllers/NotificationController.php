<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    /**
     * Ambil list notif (untuk dropdown bell)
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // ⭐ OPTIMASI: Gunakan select spesifik untuk mengurangi transfer data
        $notifications = $user->customNotifications()
            ->select(['id', 'type', 'title', 'message', 'data', 'url', 'read_at', 'created_at'])
            ->latest()
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

        // ⭐ OPTIMASI: Cache unread count selama 30 detik untuk mencegah query COUNT berulang
        // Key unik per user agar tidak bentrok
        $cacheKey = "user_{$user->id}_unread_count";
        $unreadCount = Cache::remember($cacheKey, 30, function () use ($user) {
            return $user->unreadNotificationsCount();
        });

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $unreadCount,
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

        // ⭐ OPTIMASI: Invalidate cache unread count agar data konsisten
        Cache::forget("user_{$notification->user_id}_unread_count");

        return response()->json(['success' => true]);
    }

    /**
     * Tandai semua notif sudah dibaca
     */
    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        $user->markAllNotificationsAsRead();

        // ⭐ OPTIMASI: Set cache unread count ke 0 langsung (lebih cepat daripada invalidate lalu re-query)
        Cache::put("user_{$user->id}_unread_count", 0, 30);

        return response()->json(['success' => true]);
    }
}