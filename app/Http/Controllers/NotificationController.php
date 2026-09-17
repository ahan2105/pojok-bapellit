<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * List notif user (JSON) — buat dropdown bell
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $notifs = $user->customNotifications()->take(20)->get();

        return response()->json([
            'unread_count'  => $user->customUnreadNotifications()->count(),
            'notifications' => $notifs->map(fn($n) => [
                'id'         => $n->id,
                'type'       => $n->type,
                'title'      => $n->title,
                'message'    => $n->message,
                'url'        => $n->url,
                'read'       => $n->isRead(),
                'created_at' => $n->created_at->diffForHumans(),
            ]),
        ]);
    }

    /**
     * Tandai 1 notif sudah dibaca
     */
    public function markAsRead(int $id)
    {
        /** @var User $user */
        $user = Auth::user();

        $notif = $user->customNotifications()->findOrFail($id);
        $notif->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Tandai semua sudah dibaca
     */
    public function markAllAsRead()
    {
        /** @var User $user */
        $user = Auth::user();

        $user->customUnreadNotifications()->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}