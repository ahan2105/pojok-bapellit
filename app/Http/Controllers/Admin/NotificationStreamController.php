<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NotificationStreamController extends Controller
{
    public function stream(Request $request): StreamedResponse
    {
        $user   = $request->user();
        $lastId = (int) $request->header('Last-Event-ID', 0);

        if ($lastId === 0) {
            $lastId = $user->customNotifications()->max('id') ?? 0;
        }

        // ⚠️ WAJIB: release session lock biar request lain gak nunggu
        session()->save();

        // ⭐ WAJIB: biar SSE gak mati di detik 30 (max_execution_time)
        set_time_limit(0);
        ignore_user_abort(true);

        return response()->stream(function () use ($user, $lastId) {
            if (ob_get_level() > 0) {
                ob_end_clean();
            }

            $startTime     = time();
            $maxDuration   = 300;
            $lastHeartbeat = time();

            while (true) {
                if (connection_aborted()) {
                    break;
                }

                if ((time() - $startTime) > $maxDuration) {
                    echo "event: reconnect\n";
                    echo "data: {\"reason\":\"timeout\"}\n\n";
                    flush();
                    break;
                }

                // ⭐ Query langsung ke model (lebih ringan dari relasi)
                // + hanya ambil kolom yang perlu
                $newNotifications = \App\Models\Notification::query()
                    ->where('user_id', $user->id)
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