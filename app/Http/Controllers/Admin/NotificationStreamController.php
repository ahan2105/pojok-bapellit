<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NotificationStreamController extends Controller
{
    /**
     * SSE Stream — kirim notif realtime ke browser
     */
    public function stream(Request $request): StreamedResponse
    {
        $user   = $request->user();
        $lastId = (int) $request->header('Last-Event-ID', 0);

        // Kalau client baru connect, ambil ID terakhir biar gak spam notif lama
        if ($lastId === 0) {
            $lastId = $user->customNotifications()->max('id') ?? 0;
        }

        // ⚠️ WAJIB: release session lock biar request lain gak nunggu
        session()->save();

        return response()->stream(function () use ($user, $lastId) {
            if (ob_get_level() > 0) {
                ob_end_clean();
            }

            $startTime     = time();
            $maxDuration   = 300;   // 5 menit per koneksi
            $lastHeartbeat = time();

            while (true) {
                if (connection_aborted()) {
                    break;
                }

                // Sinyal reconnect ke client sebelum timeout
                if ((time() - $startTime) > $maxDuration) {
                    echo "event: reconnect\n";
                    echo "data: {\"reason\":\"timeout\"}\n\n";
                    flush();
                    break;
                }

                // Cari notif baru
                $newNotifications = $user->customNotifications()
                    ->where('id', '>', $lastId)
                    ->orderBy('id')
                    ->get();

                foreach ($newNotifications as $notif) {
                    $lastId = $notif->id;

                    echo "id: {$notif->id}\n";
                    echo "event: notification\n";
                    echo "data: " . json_encode([
                        'id'         => $notif->id,
                        'type'       => $notif->type,
                        'title'      => $notif->title,
                        'message'    => $notif->message,
                        'data'       => $notif->data,
                        'url'        => $notif->url,
                        'is_read'    => $notif->read_at !== null,
                        'created_at' => $notif->created_at?->toIso8601String(),
                    ]) . "\n\n";
                }

                // Heartbeat tiap 15 detik
                if ((time() - $lastHeartbeat) >= 15) {
                    echo ": heartbeat " . time() . "\n\n";
                    $lastHeartbeat = time();
                }

                flush();
                sleep(2);
            }
        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache, no-store, must-revalidate',
            'Connection'        => 'keep-alive',
            'X-Accel-Buffering' => 'no',
             'Content-Encoding'  => 'identity',
        ]);
    }
}