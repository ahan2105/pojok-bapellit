<?php

namespace App\Traits;

use App\Models\Notification;
use App\Models\User;

trait HasNotifications
{
    /**
     * Relasi ke notif custom (tabel notifications)
     */
    public function customNotifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    /**
     * Notif custom yang belum dibaca
     */
    public function customUnreadNotifications()
    {
        return $this->hasMany(Notification::class)
            ->whereNull('read_at')
            ->latest();
    }

    /**
     * Hitung notif belum dibaca
     */
    public function unreadNotificationsCount(): int
    {
        return $this->customUnreadNotifications()->count();
    }

    /**
     * Kirim notif custom.
     * Support named argument: type, title, message, data, url
     * Support positional argument: sendNotification($type, $title, $message, $data, $url)
     */
    public function sendNotification(
        string $type = 'info',
        string $title = '',
        ?string $message = null,
        array $data = [],
        ?string $url = null
    ): Notification {
        // Handle legacy: sendNotification($title, $message, $options)
        if ($message === null) {
            $message = $title;
            $title   = $type;
            $type    = 'info';
        }

        // Handle legacy: sendNotification($title, $message, $optionsArray)
        if (is_array($message)) {
            $options = $message;
            $payload = [
                'type'    => $options['type'] ?? 'info',
                'title'   => $title,
                'message' => $type,  // di legacy, $type sebenarnya berisi message
                'data'    => $options['data'] ?? [],
                'url'     => $options['url'] ?? null,
            ];
        } else {
            $payload = [
                'type'    => $type,
                'title'   => $title,
                'message' => $message,
                'data'    => $data,
                'url'     => $url,
            ];
        }

        // ⭐ Override created_at dari data['created_at'] (kalau ada)
        $customCreatedAt = $payload['data']['created_at'] ?? null;
        unset($payload['data']['created_at']);

        $notif = new Notification($payload);
        $notif->user_id = $this->id;

        if ($customCreatedAt) {
            $notif->created_at = $customCreatedAt;
            $notif->updated_at = $customCreatedAt;
        }

        $notif->save();

        return $notif;
    }

    /**
     * Tandai semua notif sebagai sudah dibaca
     */
    public function markAllNotificationsAsRead(): void
    {
        $this->customUnreadNotifications()->update(['read_at' => now()]);
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

    /**
     * Kirim notif ke semua user dengan role tertentu
     */
    public static function notifyRole(
        string $role,
        string $title,
        string $message,
        array $options = []
    ): int {
        $users = User::where('role', $role)->get();
        $count = 0;

        foreach ($users as $user) {
            if (isset($options['except_user_id']) && $user->id === $options['except_user_id']) {
                continue;
            }
            $user->sendNotification(
                $options['type'] ?? 'info',
                $title,
                $message,
                $options['data'] ?? [],
                $options['url'] ?? null
            );
            $count++;
        }

        return $count;
    }

    /**
     * Kirim notif ke banyak user sekaligus
     */
    public static function notifyMany(
        array $userIds,
        string $title,
        string $message,
        array $options = []
    ): int {
        $count = 0;
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                $user->sendNotification(
                    $options['type'] ?? 'info',
                    $title,
                    $message,
                    $options['data'] ?? [],
                    $options['url'] ?? null
                );
                $count++;
            }
        }
        return $count;
    }
}