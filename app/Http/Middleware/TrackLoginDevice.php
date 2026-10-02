<?php

namespace App\Http\Middleware;

use App\Models\BannedLoginSession;
use App\Models\LoginHistory;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackLoginDevice
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user login tapi sesi/IP+UA dibanned → logout + redirect
        if ($request->user()) {
            $user = $request->user();
            $ua = $request->userAgent() ?? '';
            $ip = $request->ip();

            $isBanned = BannedLoginSession::where('user_id', $user->id)
                ->where(function ($q) use ($ip, $ua) {
                    $q->where('ip_address', $ip)
                        ->where('user_agent', $ua);
                    // fallback: jika UA kosong di banned record, cek IP saja
                    $q->orWhere(function ($q2) use ($ip) {
                        $q2->where('ip_address', $ip)->whereNull('user_agent');
                    });
                })
                ->exists();

            // Cek juga banned by IP saja untuk user tersebut (tanpa UA)
            if (! $isBanned) {
                $isBanned = BannedLoginSession::where('user_id', $user->id)
                    ->where('ip_address', $ip)
                    ->whereNull('user_agent')
                    ->exists();
            }

            if ($isBanned) {
                // Kirim notifikasi ke user sebelum logout
                try {
                    $banned = BannedLoginSession::where('user_id', $user->id)->latest()->first();
                    DB::table('notifications')->insert([
                        'user_id' => $user->id,
                        'type' => 'session_banned',
                        'title' => 'Sesi diblokir admin',
                        'message' => 'Perangkat/IP '.$ip.' diblokir. Alasan: '.($banned->reason ?? '-'),
                        'data' => json_encode(['ip' => $ip, 'reason' => $banned->reason ?? null]),
                        'url' => null,
                        'read_at' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } catch (\Throwable $e) {
                }

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Sesi Anda diblokir oleh admin.'], 403);
                }

                return redirect()->route('login')->withErrors(['login' => 'Sesi/perangkat Anda diblokir oleh admin. Hubungi admin untuk membuka blokir.']);
            }
        }

        $response = $next($request);

        if ($request->user()) {
            $user = $request->user();
            $ua = $request->userAgent() ?? '';
            $ip = $request->ip();

            // parse UA sederhana tanpa dependency
            $device = $this->detectDevice($ua);
            $platform = $this->detectPlatform($ua);
            $browser = $this->detectBrowser($ua);

            // cari record yang sama (user + ip + ua) dalam 1 jam terakhir, update last_active_at
            $history = LoginHistory::where('user_id', $user->id)
                ->where('ip_address', $ip)
                ->where('user_agent', $ua)
                ->latest('last_active_at')
                ->first();

            if ($history && $history->last_active_at?->gt(now()->subHour())) {
                $history->update(['last_active_at' => now()]);
            } else {
                LoginHistory::create([
                    'user_id' => $user->id,
                    'ip_address' => $ip,
                    'user_agent' => $ua,
                    'device' => $device,
                    'platform' => $platform,
                    'browser' => $browser,
                    'logged_in_at' => now(),
                    'last_active_at' => now(),
                ]);
            }
        }

        return $response;
    }

    private function detectDevice(string $ua): string
    {
        $ua = strtolower($ua);
        if (str_contains($ua, 'mobile') || str_contains($ua, 'android') && str_contains($ua, 'mobile')) {
            return 'Mobile';
        }
        if (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) {
            return 'Tablet';
        }

        return 'Desktop';
    }

    private function detectPlatform(string $ua): string
    {
        if (str_contains($ua, 'Windows')) {
            return 'Windows';
        }
        if (str_contains($ua, 'Mac OS') || str_contains($ua, 'Macintosh')) {
            return 'macOS';
        }
        if (str_contains($ua, 'Android')) {
            return 'Android';
        }
        if (str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')) {
            return 'iOS';
        }
        if (str_contains($ua, 'Linux')) {
            return 'Linux';
        }

        return 'Unknown';
    }

    private function detectBrowser(string $ua): string
    {
        if (str_contains($ua, 'Edg/')) {
            return 'Edge';
        }
        if (str_contains($ua, 'OPR/') || str_contains($ua, 'Opera')) {
            return 'Opera';
        }
        if (str_contains($ua, 'Chrome/')) {
            return 'Chrome';
        }
        if (str_contains($ua, 'Firefox/')) {
            return 'Firefox';
        }
        if (str_contains($ua, 'Safari/') && ! str_contains($ua, 'Chrome')) {
            return 'Safari';
        }

        return 'Unknown';
    }
}
