<?php

namespace App\Traits;

use App\Models\Notification;
use App\Models\User;
use App\Events\NotificationSent;

trait HasNotifications
{
    /**
     * Relasi ke notif CUSTOM (tabel notifications)
     */
    public function customNotifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    /**
     * Relasi notif custom yang belum dibaca
     */
    public function customUnreadNotifications()
    {
        return $this->hasMany(Notification::class)->whereNull('read_at')->latest();
    }

    /**
     * Kirim notif custom ke user + broadcast realtime
     */
    public function sendNotification(
        string $type,
        string $title,
        string $message,
        array $data = [],
        ?string $url = null
    ): Notification {
        $notif = $this->customNotifications()->create([
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'data'    => $data,
            'url'     => $url,
        ]);

        broadcast(new NotificationSent($notif));

        return $notif;
    }

    /**
     * Kirim notif ke semua admin
     */
    public static function sendNotificationToAdmins(
        string $type,
        string $title,
        string $message,
        array $data = [],
        ?string $url = null
    ): void {
        $admins = User::where('role', 'admin')
            ->orWhere('is_admin', true)
            ->get();

        foreach ($admins as $admin) {
            $admin->sendNotification($type, $title, $message, $data, $url);
        }
    }
}