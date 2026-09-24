<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NotificationStreamController extends Controller
{
    /**
     * SSE stream untuk notifikasi realtime.
     * 
     * OPTIMASI:
     * - Session lock dilepas sebelum streaming dimulai
     * - Query hanya mengambil kolom yang diperlukan + LIMIT
     * - Heartbeat setiap 15 detik untuk menjaga koneksi
     * - Max duration 300 detik (5 menit) lalu reconnect otomatis
     */
    public function stream(Request $request): StreamedResponse
    {
        $user   = $request->user();
        $lastId = (int) $request->header('Last-Event-ID', 0);

        // Ambil last ID dari database jika tidak ada header
        if ($lastId === 0) {
            $lastId = \App\Models\Notification::where('user_id', $user->id)->max('id') ?? 0;
        }

        // ⚠️ WAJIB: release session lock agar request lain tidak menunggu
        session()->save();

        // ⭐ Set timeout unlimited untuk long-running connection
        set_time_limit(0);
        ignore_user_abort(true);

        return response()->stream(function () use ($user, $lastId) {
            // Bersihkan output buffer jika ada
            if (ob_get_level() > 0) {
                ob_end_clean();
            }

            $startTime     = time();
            $maxDuration   = 300; // 5 menit
            $lastHeartbeat = time();
            $currentLastId = $lastId;

            while (true) {
                // Cek apakah client masih terhubung
                if (connection_aborted()) {
                    break;
                }

                // Timeout setelah maxDuration untuk mencegah koneksi terlalu lama
                if ((time() - $startTime) > $maxDuration) {
                    echo "event: reconnect\n";
                    echo "data: {\"reason\":\"timeout\"}\n\n";
                    flush();
                    break;
                }

                // ⭐ OPTIMASI: Query dengan select spesifik + limit untuk efisiensi
                $newNotifications = \App\Models\Notification::query()
                    ->select(['id', 'user_id', 'type', 'title', 'message', 'data', 'url', 'read_at', 'created_at'])
                    ->where('user_id', $user->id)
                    ->where('id', '>', $currentLastId)
                    ->orderBy('id', 'asc')
                    ->limit(100) // Batasi jumlah data per polling
                    ->get();

                foreach ($newNotifications as $notif) {
                    $currentLastId = $notif->id;

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
                    
                    flush();
                }

                // Heartbeat setiap 15 detik untuk menjaga koneksi tetap hidup
                if ((time() - $lastHeartbeat) >= 15) {
                    echo ": heartbeat " . time() . "\n\n";
                    $lastHeartbeat = time();
                    flush();
                }

                // Sleep 2 detik antara polling (balance antara realtime dan beban server)
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